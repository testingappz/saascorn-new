<?php
namespace App\Http\Controllers\Investor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Countries;
use App\States;
use App\Cities;
use Validator;
use App\User;
use App\UserDetail;
use App\Investment;
use App\InvestmentDocs;
use App\MakeInvestment;

use Redirect;
use App\Http\Traits\PushDataToBucket;
use App\Http\Traits\AddDataToFundAmerica;

class MakeInvestmentController extends Controller {

	public function __construct() {
	    $this->middleware('auth');
	}

	use PushDataToBucket;
  	use AddDataToFundAmerica;

  	//get details of single project/investment
	public function investmentDetail(Request $request){
	  	try
	  	{
	  		//get investment/project id from url
	  		$investmentId = $request->id;
				$invDetail=array();

	  		//get id of logged in user
	  		$userId = Auth::id();

		    if(Auth::Check())
		    {
					$userId = Auth::id();

					$chkIndExist = Investment::where('status',1)->where('accept_investment',1)->where('id',$investmentId)->first();

						//$chkIndExist = Investment::where('id',$investmentId)->first();
					$getInvId = MakeInvestment::where('investor_id',$userId)->where('project_id',$investmentId)->first();
					//fetch investment details from fund america api
					if(!empty($getInvId))
					{

						$invDetail = $this->InvestmentDetails($getInvId->investment_id);
					}

					if(isset($investmentId) && !empty($investmentId) && !empty($chkIndExist))
					{
						//get specific investment with all docs,videos
						$investmentData = Investment::with('investmentDocs','ownerData.userdetails')->where('id',$investmentId)->first();

						return view('investor.investment_detail',['data'=>$investmentData,'invDetail'=>$invDetail]);
					}
					else
					{
						abort(403, 'Unauthorized action.');
					}

		    }
	  	}
	  	catch(Exception $e)
	  	{
	  		abort(403, $e->getMessage());
	  	}

	}

  	//function to view investments
	public function investmentListing(Request $request){
	  	try
	  	{
		    if(Auth::Check())
		    {
		    	//get id of logged in user
	  		   	$userId = Auth::id();
		      	if(isset($userId) && !empty($userId))
		      	{
		      		$lastId = $request->lastId;
	                $searchText = $request->search;
	                $minAmount = $request->minAmount;

	                 //get investment related data

      				$countInvData = $this->GetInvestorDetails($userId);

			      	if($request->ajax())
			      	{
	                	//get investments

				      	/*$investmentData = Investment::with('ownerData.userdetails')
				      	->where('user_id',$userId)->where('status',1);*/
				      	$investmentData = Investment::with('ownerData.userdetails')->where('status',1)->where('accept_investment',1);

				      	//when serach string is sent in akax request then below condition will work
				      	if(!empty($minAmount))
				      	{
				      		$investmentData->where('min_investment',$minAmount);
				      	}

				      	if(!empty($searchText))
				      	{
				      		$investmentData->where('investment_title','like','%' . $searchText . '%');
				      	}

				      	if(!empty($lastId))
				      	{
				      		$investmentData->where('id','<',$lastId);
				      	}

				      	$investmentData = $investmentData->take(6)->orderBy('investments.id', 'DESC')->get();

				      	$totaldata = Investment::where('status',1)->where('accept_investment',1)->count();

				        return view('investor.inner_investment',['data'=>$investmentData,'countInvData'=>$countInvData,'totaldata'=>$totaldata]);
	            	}
	            	else
	            	{

	            		//get investments
				      	/*$investmentData = Investment::with('ownerData.userdetails')
				      	->where('user_id',$userId)->where('status',1);*/

				      	$investmentData = Investment::with('ownerData.userdetails')->where('status',1)->where('accept_investment',1);

				      	$totaldata = Investment::where('status',1)->where('accept_investment',1)->count();

				      	//when search string is sent in akax request then below condition will work

				      	if(!empty($searchText))
				      	{
				      		$investmentData->where('investment_title','like','%' . $searchText . '%');
				      	}
				      	if(!empty($minAmount))
				      	{
				      		$investmentData->where('min_investment',$minAmount);
				      	}

				      	$investmentData = $investmentData->take(6)->orderBy('investments.id', 'DESC')->get();



				        return view('investor.investments',['data'=>$investmentData,'countInvData'=>$countInvData,'totaldata'=>$totaldata]);
	            	}

		      	}
				else
				{
					abort(403, 'Unauthorized action.');
				}
		    }
	  	}
	  	catch(Exception $e)
	  	{
	  		abort(403, $e->getMessage());
	  	}

	}

	//function to make investment on a project by investor
	public function makeInvestment(Request $request){
		try
		{
			if(Auth::Check())
		    {
		    	//get id of logged in user
	  		   	$userId = Auth::id();
		      	if(isset($userId) && !empty($userId))
		      	{
		      		$amount = $request->amount;
	                $projectId = $request->project_id;

	                $investmentAlreadyMade = MakeInvestment::where('investor_id',$userId)->where('project_id',$projectId)->first();

	                if(!empty($investmentAlreadyMade))
	                {
	                	return response()->json([
				         'message'   => "Investment Request Already Sent.",
				         'status'  => 0,
				        ]);
	                }

	                if($amount>0 && $projectId!='')
	                {
	                	$projectInfo = Investment::where('id',$projectId)->first();

	                	$minInvestment = $projectInfo->min_investment;
	                	$offeringId = $projectInfo->offering_id;
	                	$acceptStatus = $projectInfo->accept_investment;
	                	//check if project is created  on fund america and open for investments
	                	if($offeringId=='' || $acceptStatus==0)
	                	{
	                		return response()->json([
					         'message'   => "Project is not available for investments.",
					         'status'  => 0,
					        ]);
	                	}

	                	if($amount<$minInvestment)//check if user given fund is less than min amount set by issuer
	                	{
	                		return response()->json([
					         'message'   => "Minium Investment amount is ".$minInvestment,
					         'status'  => 0,
					        ]);
	                	}

	             		//create investment on fund america

	                	if($request->payment_method==1)//check
	                	{
	                		$resPayment = $this->MakeInvestmentUsingCheck($userId,$request->all());
	                	}
	                	if($request->payment_method==2)//ach
	                	{
	                		$resPayment = $this->MakeInvestmentUsingACH($userId,$request->all());
	                	}
	                	else if($request->payment_method==3)//wire
	                	{
	                		$resPayment = $this->MakeInvestmentUsingWire($userId,$request->all());
	                	}

	                	if(!empty($resPayment))
	                	{
	                		if($resPayment['status']==0 || $resPayment['status']==2)
                			{
		                				return response()->json([
								         'message'   => $resPayment['message'],
								         'status'  => 0,
								        ]);
                			}
                			else if($resPayment['status']==1)
                			{
												$projectId = $request->project_id;
												$paymeanType = $request->payment_method;
												if($paymeanType == 1)
												{
													$type = "cheque";
												}elseif($paymeanType == 2)
												{
													$type = 'ach';
												}elseif($paymeanType == 3){
													$type = 'wire';
												}

												$amount = $request->amount;
												$name = Auth::user()->first_name;

												$projectdetail = Investment::where('id',$projectId)->first();
												$title = $projectdetail->investment_title;

												$url = $_SERVER['HTTP_HOST'].'/images/templateimages/emailbg.png';
												$emai = Auth::user()->email;
												$details = [
													'name' => $name,
													'title' => $title,
													'budget' => $amount,
													'type' => $type,
													'url' => $url
												];
												\Mail::to($emai)->send(new \App\Mail\Investment($details));

												return response()->json([
								         'message'   => 'Investment Request Initiated Successfully',
								         'status'  => 1,
								        ]);
                			}

	                	}


	                }
	     			else
	     			{
	     				return response()->json([
				         'message'   => "Please Provide Valid Data",
				         'status'  => 0,
				        ]);
	     			}
		      	}
				else
				{
					return response()->json([
			         'message'   => 'Unauthorized action.',
			         'status'  => 0,
			        ]);
				}
		    }
		}
		catch(Exception $e)
		{
			return response()->json([
	         'message'   => $e->getMessage(),
	         'status'  => 0,
	        ]);
		}
	}

}
