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
use App\Countries;
use App\States;
use App\Cities;
use DataTables;
use Redirect;
use Carbon\Carbon;
use App\Http\Traits\AddDataToFundAmerica;

class DisbursementController extends Controller {


	public function __construct()
	{
		if (!Auth::guard('admin')->check())
		{
			return redirect('admin_login');//if admin is not logged in then redirect to login page

		}
	}

	use AddDataToFundAmerica;

    public function disbursementGetList(Request $request)
    {
      return view('admin.disbursements.disbursements');
    }

    public function disbursementList(Request $request)
    {
      try
      {
        //columns to render
        $columns = array(0 =>'created_at',1 =>'offering',2=> 'amount',3=>'contact_name',4=>'Via',6=> 'Status',7=>'Details');

        $limit = $request->input('length')?$request->input('length'):10;//get limit
        $start = $request->input('start')?$request->input('start'):0;//get offset

        if($start==0)
        {
          $start = 1;
        }
        else
        {
          $start = ($start/10)+1;
        }

        //get data from fund america api as per pagination
        $data = $this->GetAlldisbursements($start,$limit);

        if(isset($data->resources) && !empty($data->resources))
        {
          $totalFiltered = $data->total_resources;

          //prepare json to show in table
          $final =  array();

          foreach($data->resources as $key => $resource)
          {
            //get offering id from url
            $getOfferId = explode("/", $resource->offering_url);
            $offerId = end($getOfferId);
            //get name of offering
            $getOfferName = Investment::where('offering_id',$offerId)->first();

            if(!empty($getOfferName))
            {
                $offerId = $getOfferName->investment_title;
            }


            //convert date to format as per used
            $valuesin['Created At'] = Carbon::parse($resource->created_at)->format('Y-m-d H:m:s');

            $valuesin['Offering'] = $offerId;
            $valuesin['Amount'] = '$'.$resource->amount;
            $valuesin['Contact Name'] = $resource->contact_name;
            $valuesin['Via'] = $resource->payment_method;
            $valuesin['Status'] = $resource->status;

            $reviewDetails =  route('disbursementDetail',$resource->id);

            $valuesin['Details'] ='<a class="btn btn-xs btn-primary" target="_blank" href="'.$reviewDetails.'">View Details</a>';
            $final[] = $valuesin;

          }


          //send json data in response
          $json_data = array(
          "draw"            => intval($request->input('draw')),
          "recordsTotal"    => intval($totalFiltered),
          "recordsFiltered" => intval($totalFiltered),
          "data"            => $final
          );

          echo json_encode($json_data);
          die();
        }


      }
      catch(Exception $e)
      {
        abort(403, 'Unauthorized action.');
      }
    }


    public function disbursementDetail(Request $request,$id)
    {
      try
      {
        //get detail of single disbursement from fund america
        $data = $this->GetSingleDisbursement($id);

        return view('admin.disbursements.disbursementdetail' ,['list'=>$data]);
      }
      catch(Exception $e)
      {
        abort(403, 'Unauthorized action.');
      }

    }

    //function to get projects which are ready for disbursements
    public function renderNewDisbursement(Request $request)
    {
      try
      {
        $countries = Countries::all();
        $data = MakeInvestment::with('investmentDetail')->where('status','invested')->groupBy('project_id')->get()->toArray();

        return view('admin.disbursements.renderdisbursements' ,['list'=>$data,'countries'=>$countries]);
      }
      catch(Exception $e)
      {
        abort(403, 'Unauthorized action.');
      }
    }

     //get state country city
    public function getCountryData(Request $request)
    {
      try
      {
        $dataToAppend='';
        $type = $request->type;
        $typeId = $request->typeId;

        //get states of specifc country
        if($type==1)
        {
          $data = States::where('country_id',$typeId)->get()->toArray();
        }
        else if($type==2)//get cities of specific state
        {
          $data =  Cities::where('state_id',$typeId)->get()->toArray();
        }

        //create option for satte,city dropdown
        if(!empty($data))
        {
          foreach($data as $singleRow)
          {
            $dataToAppend.='<option value='.$singleRow['id'].'>'.ucfirst($singleRow["name"]).'</option>';
          }
        }

        return response()->json([
           'message'   => 'Success',
           'data' => $dataToAppend,
           'status'  => 1,
           'class_name'  => 'alert-success'
          ]);
      }
      catch(Exception $e)
      {
        return response()->json([
           'message'   => $e->getMessage(),
           'data' => '',
           'status'  => 0,
           'class_name'  => 'alert-danger'
        ]);
      }
    }

  //function to transfer amount
  public function managePayment(Request $request)
  {

    try{

        $data = $request->all();

        //create entity
        $res = $this->CreateEntityForDisbursement($data);

        if($res['status']==1)
        {
          $entityId = $res['data'];

          $data['entity_id']=$entityId;

          //create payment method
          if($data['payment_method']=="ach")
          {
            //create ach payment method
            $resPaymentMethod = $this->CreatePaymentMethodAch($data);
          }
          else if($data['payment_method']=="wire")
          {
            //create wire payment method
            $resPaymentMethod = $this->CreatePaymentMethodWire($data);
          }

          if($resPaymentMethod['status']==1)
          {

            $paymentMethodId = $resPaymentMethod['data'];

            $data['bank_transfer_method_id']=$paymentMethodId;

            $offerData = Investment::where('id',$data['offer_name'])->first();

            $data['offering_id']=$offerData->offering_id;

            //send request fro disbursement
            $resDisbursement = $this->CreateDisbursementUsingAch($data);

            if($resDisbursement['status']==1)
            {
							$email = $request->email;
							$name = $request->cname;
							$amount = $request->amount;
							$acount_no = $request->acount_no;
							$account_type = $request->account_type;
							$payment_method = $request->payment_method;



							$url = $_SERVER['HTTP_HOST'].'/images/templateimages/emailbg.png';

							$details = [
							'name' => $name,
							'amount' => $amount,
							'acount_no' => $acount_no,
							'account_type' => $account_type,
							'payment_method' => $payment_method,
							'url' => $url
							];
							\Mail::to($email)->send(new \App\Mail\Disbursement($details));

               return response()->json([
                 'message'   => 'Disbursement Request Sent Successfully.',
                 'data' => '',
                 'status'  => 1,
                 'class_name'  => 'alert-success'
              ]);
            }
            else
            {

                return response()->json([
                 'message'   => $resDisbursement['message'],
                 'data' => '',
                 'status'  => 0,
                 'class_name'  => 'alert-danger'
              ]);

            }

          }
          else
          {
              return response()->json([
               'message'   => $resPaymentMethod['message'],
               'data' => '',
               'status'  => 0,
               'class_name'  => 'alert-danger'
            ]);
          }

        }
        else
        {
          return response()->json([
             'message'   => $res['message'],
             'data' => '',
             'status'  => 0,
             'class_name'  => 'alert-danger'
          ]);
        }

    }
    catch(Exeption $e)
    {
      return response()->json([
             'message'   => $e->getMessage(),
             'data' => '',
             'status'  => 0,
             'class_name'  => 'alert-danger'
          ]);
    }
  }

}
