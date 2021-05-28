<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Admin;
use App\Investment;
use App\InvestmentDocs;
use App\MakeInvestment;
use DataTables;
use Redirect;
use App\Http\Traits\AddDataToFundAmerica;

class ProjectController extends Controller {


	public function __construct()
	{
		if (!Auth::guard('admin')->check()) 
		{
			return redirect('admin_login');//if admin is not logged in then redirect to login page
			
		} 
	}

	use AddDataToFundAmerica;

	public function projectList(Request $request)
	{
		//get all projects with documents
		$list =  Investment::with('user')->with('investmentDocs')->orderBy('id' ,'DESC')->get();
	
		return view('admin.projects.projectlist' , compact('list'));
		
	}

	public function showProjectList(Request $request,$id)
	{
		//get documents of particular project
		$list = InvestmentDocs::where('investment_id', '=', $id)->get();
		return view('admin.projects.showprojectlist' , compact('list'));
	}

	public function changeProjectStatus(Request $request)
    {
    	//get id of project
        $id = $request->id;
		$active_deactive = $request->active_deactive;
		if ($request->isMethod('post'))
		{
			//update status of project in db
			if($id != '')
			{
				$investment = Investment::where('id', '=', $request->id)->update( array( "status" => $active_deactive));
				return 1;
			}
			else
			{
				return 0;
			}
			
		}
		return redirect()->route('admin.project.projectlist');
        
    }
	
    public function investmentGetList(Request $request)
    {
    	return view('admin.investments.investmentlist');
    }


	public function investmentList(Request $request)
	{
		try
        {
        	//columns to render
            $columns = array(0 =>'id',1 =>'Investment Title',2=> 'Investor Name',3=> 'Amount',4=> 'Status',6=> 'Action');

            //get count of total rows
            $totalData = MakeInvestment::count();

            $totalFiltered = $totalData; 

            //$limit = $request->input('length');//get limit
            $limit = $request->input('length');//get limit
            $start = $request->input('start');//get offset
            $order = $columns[$request->input('order.0.column')];//get order
            $dir = $request->input('order.0.dir');
            $search = $request->input('search.value'); //serach string

            if($order=="Investment Title")
            {
                $order ='investments.investment_title';
            }

            if($order=="Investor Name")
            {
                $order ='users.first_name';
            }


            if(empty($search))//if not search applied
            {            
                $data =  MakeInvestment::
                select('make_investment.*', 'investments.investment_title', 'users.first_name','users.last_name')
                ->join('investments', 'investments.id', '=', 'make_investment.project_id')
                ->join('users', 'users.id', '=', 'make_investment.investor_id')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get()->toArray();


            }
            else //if search applied
            {

                $data =  MakeInvestment::
                select('make_investment.*', 'investments.investment_title', 'users.first_name','users.last_name')
                ->join('investments', 'investments.id', '=', 'make_investment.project_id')
                ->join('users', 'users.id', '=', 'make_investment.investor_id')
                ->where('amount',$search)
                ->orWhere('first_name', 'LIKE','%' . $search . '%')
                ->orWhere('last_name', 'LIKE','%' . $search . '%')
                ->orWhere('investment_title', 'LIKE','%' . $search . '%')
                ->offset($start)
                ->limit($limit)
                ->orderBy($order,$dir)
                ->get()->toArray();


                $totalFiltered =  MakeInvestment::
                select('make_investment.*', 'investments.investment_title', 'users.first_name','users.last_name')
                ->join('investments', 'investments.id', '=', 'make_investment.project_id')
                ->join('users', 'users.id', '=', 'make_investment.investor_id')
                ->where('amount',$search)
                ->orWhere('first_name', 'LIKE','%' . $search . '%')
                ->orWhere('last_name', 'LIKE','%' . $search . '%')
                ->orWhere('investment_title', 'LIKE','%' . $search . '%')
                ->count();

            	

            }
            //prepare json to show in table
            $final =  array();
          
            foreach($data as $key => $value)
            {
                
                $valuesin['Sno'] ='';
                $valuesin['Investment Title'] = $value['investment_title'];
                $valuesin['Investor Name'] = $value['first_name'].' '.$value['last_name'];
                $valuesin['Amount'] = $value['amount'];
                $valuesin['Status'] = $value['status'];
                
                $reviewDetails =  route('investmentlist_show',$value['id']);

                $valuesin['Action'] ='<a class="btn btn-xs btn-primary" target="_blank" href="'.$reviewDetails.'">View Details</a>';
				$final[] = $valuesin;
                
            }


            //send json data in response
            $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $final
            );
            
            echo json_encode($json_data); 


        }
        catch(\Exception $e)
        {
            return response()->json(['success' => '0','data'=>$e->getMessage(),'line'=>$e->getLine()]);
        }

	}

	public function showInvetmentDetails(Request $request,$id)
	{
		try
		{
			$alldata = array();
			$list =  MakeInvestment::where('id',$id)->with('investorDetail','investmentDetail')->orderBy('id','DESC')->get()->toArray();

			foreach($list as $listData)
			{
				$listData['updates'] = $this->InvestmentDetails($listData['investment_id']);
			}
			
			return view('admin.investments.investmentdetail' ,['list'=>$listData]);
		}
		catch(Exception $e)
		{
			abort(403, 'Unauthorized action.');
		}
		
	}
	
}
 