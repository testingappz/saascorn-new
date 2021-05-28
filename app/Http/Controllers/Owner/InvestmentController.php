<?php
namespace App\Http\Controllers\Owner;
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
use Redirect;
use Carbon\Carbon;
use App\Http\Traits\PushDataToBucket;
use App\Http\Traits\AddDataToFundAmerica;

class InvestmentController extends Controller {

	public function __construct() {
	    $this->middleware('auth');
	}

	use PushDataToBucket;
	use AddDataToFundAmerica;

   //function to show form for add new project

	public function showInvestmentForm()
	{
		//get id of logged in user
		try
		{
			//get user id from auth
			$userId = Auth::id();
			//get investment related data from trait,all count and sum
			$countInvData = $this->GetOwnerDetails($userId);
			//send data to view
	    	return view('owner.add_investment',['countInvData'=>$countInvData]);
		}
	    catch(Exception $e){
		  	abort(403, $e->getMessage());
		}
	}

  	//add new investment
  	public function addNewInvestment(Request $request)
  	{
	  	try
	  	{
	  		//get id of logged in user
		  	$userId = Auth::id();
	  		$doclist = $request->doclist;

	  		$vidlist = $request->vidlisthide;

		  	$validation = Validator::make($request->all(), [
	        'title' => 'required|string|max:50:unique:investments',
	        'description' => 'required|string',
	        'budget' => 'required|numeric|max:10000000000',
	        'offering_end_date' => 'required|date',
	        'minimum_investment' => 'required|numeric|max:10000000000',
	        'benefit_return' => 'required|numeric',
	        'project_image' => 'mimes:jpeg,jpg,png',
	        'documents.*' => 'mimes:jpeg,jpg,png,doc,pdf,docx',
	        'videos.*' => 'mimes:mp4,ogx,oga,ogv,ogg,webm',
	       ]);

		  	if($validation->passes())
		  	{
		  		$docToSave = explode(',',$doclist[0]);
		  		$vidToSave = explode(',',$vidlist[0]);

		  		$projectImageName='';

				//create offering in fundamerica
				$response = $this->CreateOffering($userId,$investmentId='',$request->all());

				if($response['status']==0)
				{
					return Redirect::back()->withErrors(['invalid Request', $response['message']]);
				}
				else if($response['status']==2)
				{
					return Redirect::back()->withErrors(['invalid Request', $response['message'][0]]);
				}
				else if($response['status']==1)
				{
					//upload if project's image is given
			  		if($request->hasFile('project_image'))
			  		{
			  			$image = $request->file('project_image');
				        $projectImageName = rand() . '.' . $image->getClientOriginalExtension();
				        $pathToSave =  'projectImages/'.$projectImageName;
				        //upload images
	        			$res = $this->AddFileToBucket($pathToSave,$request->file('project_image'));

			  		}


		  			//save data in investment table to create new project
					$investment = new Investment;
					$investment->user_id = $userId;
					$investment->investment_title = $request->title;
					$investment->investment_description = $request->description;
					$investment->budget = $request->budget;
					$investment->min_investment = $request->minimum_investment;
					$investment->benifit_return = $request->benefit_return;
					$investment->investment_image = $projectImageName;
					$investment->offering_end_date = $request->offering_end_date;
					$investment->offering_id = $response['data'];
					$investment->agreement_id = $response['agreement_id'];
					$investment->tech_agreement_id = $response['tech_agreement_id'];
					$investment->status = 1	;
					$investment->save();
					$id = $investment->id;


					// echo "<pre>";print_r();
					$email = Auth::user()->email;
					$name = Auth::user()->first_name;
					$title = $investment->investment_title;
					$budget = $investment->budget;
					$min_investment = $investment->min_investment;
					 $offering_end_date = $investment->offering_end_date;


					// $url = $_SERVER['HTTP_ORIGIN'].'/images/templateimages/bg1.png';
					$url = $_SERVER['HTTP_ORIGIN'].'/images/templateimages/emailbg.png';


					$details = [
					'name' => $name,
					'title' => $title,
					'budget' => $budget,
					'min_investment' => $min_investment,
					'offering_end_date' => $offering_end_date,
					'url' => $url
					];



					\Mail::to($email)->send(new \App\Mail\CreateProject($details));

					//upload documents

					if($request->hasFile('documents'))
					{
						$files = $request->file('documents');
						//upload docs
						$this->uploadDocs($files,$docToSave,$type=1,$investment->id);
					}

					//upload videos
					if($request->hasFile('videos'))
					{
						$files = $request->file('videos');
					    //upload videos
						$this->uploadDocs($files,$vidToSave,$type=2,$investment->id);
					}
				}

				return redirect('owner/investment_detail/id/'.$id)->with('success', 'Investment Added Successfully.Please Check Your Email For Agreements.');
		  	}
		  	else
		  	{
		  		return Redirect::back()->withErrors($validation)->withInput();
		  	}
		}
		catch(Exception $e){
		  	abort(403, $e->getMessage());
		}
  	}

  	//get details of single project/investment
	public function investmentDetail(Request $request)
	{
	  	try
	  	{
	  		//get investment/project id from url
	  		$investmentId = $request->id;
	  		$invDetails = array();

		    if(Auth::Check())
		    {
				$userId = Auth::id();

				$chkIndExist = Investment::where('status',1)->where('id',$investmentId)->where('user_id',$userId)->first();
				//$chkIndExist = Investment::where('id',$investmentId)->where('user_id',$userId)->first();

				if(isset($investmentId) && !empty($investmentId) && !empty($chkIndExist))
				{
					//get specific investment with all docs,videos
					$investmentData = Investment::with('investmentDocs','ownerData.userdetails')->where('user_id',$userId)->where('id',$investmentId)->first();

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
			        $allinvestments = $this->GetAllInvestments($start,$limit,$chkIndExist->offering_id);

			        $count = $allinvestments->total_resources;
			        if(!empty($allinvestments) && isset($allinvestments->resources) && !empty($allinvestments->resources))
			        {

			        	foreach($allinvestments->resources as $resource)
			        	{

			        		//get offering id from url
				            $getEntityId = explode("/", $resource->entity_url);
				            $entityId = end($getEntityId);
				            //get name of offering
				            $getEntityDet = User::where('entity_id',$entityId)->first();

				            if(!empty($getEntityDet))
				            {
				                $name = $getEntityDet->first_name.' '.$getEntityDet->last_name;
				            }


			        		$invDetail['name'] = $name;
			        		$invDetail['id'] = $resource->id;
			        		$invDetail['email'] = $getEntityDet->email;
			        		$invDetail['invested'] = Carbon::parse($resource->invested_at)->format('Y-m-d H:m:s');
			        		$invDetail['amount'] = $resource->amount;
			        		$invDetail['received'] = $resource->amount_received;
			        		$invDetail['reference'] = $resource->payment_reference;
			        		$invDetail['paymentMethod'] = $resource->payment_method;
			        		$invDetail['status'] = $resource->status;
			        		$invDetails[] = $invDetail;
			        	}
			        }

					$agreementData=$agreementTechData='';
					//get signurl of agreement 1
					if($investmentData->agreement_id!='' && $investmentData->agreement_status==0)
					{
						$agreementData = $this->GetEscrowAgreement($investmentData->agreement_id,$investmentData->offering_id);
					}
					else if($investmentData->agreement_id=='')
					{
						$response = $this->CreateEscrowAgreement($investmentData->offering_id);

						if($response!='')
						{
							Investment::where('id',$investmentId)->update(['agreement_id' => $response]);

							$agreementData = $this->GetEscrowAgreement($investmentData->agreement_id,$investmentData->offering_id);
						}


					}

					//get signurl of agreement 2
					if($investmentData->tech_agreement_id!='' && $investmentData->tech_agreement_status==0)
					{
						$agreementTechData = $this->GetTechEscrowAgreement($investmentData->agreement_id,$investmentData->offering_id);


					}
					else if($investmentData->tech_agreement_id=='')
					{
						$response = $this->CreateTechEscrowAgreement($investmentData->offering_id);

						if($response!='')
						{
							Investment::where('id',$investmentId)->update(['tech_agreement_id' => $response]);

							$agreementTechData = $this->GetTechEscrowAgreement($investmentData->agreement_id,$investmentData->offering_id);
						}


					}


					return view('owner.investment_detail',['data'=>$investmentData,'agreementTechData'=>$agreementTechData,'agreementData'=>$agreementData,'allinvestments'=>$invDetails,'count'=>$count]);
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

	//function to update project description
  	public function updateInvestmentDesc(Request $request)
  	{
		try
		{
		  	$validation = Validator::make($request->all(), [
	        'description' => 'required|string',
	       ]);

		  	$userId = Auth::id();
		  	if($request->investId)
		  	{
		  		if($validation->passes())
			  	{
			  		//update description
			  		$investment = Investment::find($request->investId);

					$investment->investment_description = $request->description;

					$investment->save();

					//update offering description
					$this->UpdateOffering($userId,$request->investId,$request->all());
					//update offering description

					return response()->json([
			         'message'   => 'Description Updated Successfully',
			         'status'  => 1,
			         'data'=>$investment->investment_description,
			         'class_name'  => 'alert-success'
		        	]);
			  	}
			  	else
			  	{
			  		return response()->json([
			         'message'   => $validation->errors()->all(),
			         'status'  => 0,
			         'class_name'  => 'alert-danger'
			        ]);
			  	}

		  	}
		  	else
		  	{
		  		return response()->json([
		         'message'   => 'Invalid Request.Please Try Again.',
		         'status'  => 0,
		         'class_name'  => 'alert-danger'
		        ]);
		  	}

	  	}
		catch(Exception $e){
		  	return response()->json([
		     'message'   => $e->getMessage,
		     'status'  => 0,
		     'class_name'  => 'alert-danger'
		    ]);
	  	}
  	}

  	//function to add,update videos,docs in exiting project
  	public function updateInvestmentDoc(Request $request)
  	{
	  	try
	  	{
	  		if($request->doctype==1)
	  		{
	  			$doclist = $request->doclist;
	  		}
	  		else
	  		{
	  			$doclist = $request->vidlisthide;
	  		}

	  		$pId = $request->pId;

	  		if($request->doctype==1)//validate documents
	  		{
	  			$validation = Validator::make($request->all(), [
		        'documents.*' => 'required|mimes:jpeg,jpg,png,doc,pdf,docx',
		       ]);
	  		}
	  		else if($request->doctype==2)//validate videos
	  		{
	  			$validation = Validator::make($request->all(), [
		        'videos.*' => 'required|mimes:mp4,ogx,oga,ogv,ogg,webm',
	       		]);
	  		}


		  	if($validation->passes())
		  	{
		  		$docToSave = explode(',',$doclist[0]);

		  		//get id of logged in user
	  			$userId = Auth::id();

				//upload documents

				if($request->hasFile('documents'))
				{
					$files = $request->file('documents');
					//upload docs
					$this->uploadDocs($files,$docToSave,$request->doctype,$pId);
				}

				//upload videos
				if($request->hasFile('videos'))
				{
					$files = $request->file('videos');
				    //upload videos
					$this->uploadDocs($files,$docToSave,$request->doctype,$pId);
				}

				return response()->json([
		         'message'   => 'Updations Done Successfully',
		         'status'  => 1,
		         'class_name'  => 'alert-success'
	        	]);
		  	}
		  	else
		  	{
		  		return response()->json([
		         'message'   => $validation->errors()->all(),
		         'status'  => 0,
		         'class_name'  => 'alert-danger'
	        	]);
		  	}
		}
		catch(Exception $e){

			return response()->json([
	         'message'   => $e->getMessage(),
	         'status'  => 0,
	         'class_name'  => 'alert-danger'
        	]);

		}
  	}

  	//upload videos or docs for project
  	public function uploadDocs($files,$docToSave,$type,$investmentId)
  	{

	  	try
	  	{
	  		if(!empty($files))
		  	{

		  		if($type==1)
		  		{
		  			$folderName = 'docs/';
		  		}
		  		else if($type==2)
		  		{
		  			$folderName = 'videos/';
		  		}

		  		foreach ($files as $doc)
			    {

				    $docName = rand() . '.' . $doc->getClientOriginalExtension();
				    $originalName = $doc->getClientOriginalName();

				    if(in_array($originalName, $docToSave))
				    {
				  		$pathToSave =  $folderName.$docName;
				  		$res = $this->AddFileToBucket($pathToSave,$doc);
				  		if($res)
				  		{
				  			$investmentDocs = new InvestmentDocs;
							$investmentDocs->investment_id = $investmentId;
							$investmentDocs->type = $type;
							$investmentDocs->doc_name = $docName;
							$investmentDocs->save();
				  		}

				    }

			    }
		  	}

		  	return 1;
	  	}
	  	catch(Exception $e)
	  	{
	  		return 0;
	  	}
  	}

  	//function to remove doc or video of project
  	public function deleteInvestmentDoc(Request $request)
  	{
  		try
  		{
  			$type = $request->type;
  			$docId = $request->docId;

  			if(!empty($type) && !empty($docId))
  			{
  				$getname = InvestmentDocs::where('id',$docId)->first();

  				if($type==1)
		  		{
		  			$path = 'docs/'.$getname->doc_name;
		  		}
		  		else if($type==2)
		  		{
		  			$path = 'videos/'.$getname->doc_name;
		  		}

  				$res = $this->RemoveFileFromBucket($path);

  				if($res)
  				{
  					InvestmentDocs::where('id',$docId)->delete();

	  				return response()->json([
			         'message'   => 'Document Removed Successfully.',
			         'status'  => 1,
		        	]);
  				}
  				else
	  			{
	  				return response()->json([
			         'message'   => 'Invalid Request.Please Try Again After Some Time.',
			         'status'  => 0,
		        	]);
	  			}

  			}
  			else
  			{
  				return response()->json([
		         'message'   => 'Invalid Request.Please Try Again After Some Time.',
		         'status'  => 0,
	        	]);
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


  	//function to update project investment details
  	public function updateInvestInfo(Request $request)
  	{
		try
		{
		  	$validation = Validator::make($request->all(), [
	        'offering_end_date' => 'required|date',
	        'minimum_investment' => 'required|numeric',
	       ]);

		  	$userId = Auth::id();

		  	if($request->investId)
		  	{
		  		if($validation->passes())
			  	{
			  		//update description
			  		$investment = Investment::find($request->investId);

					$investment->min_investment = $request->minimum_investment;
					$investment->offering_end_date = $request->offering_end_date;



					//update offering details
					$res = $this->UpdateOffering($userId,$request->investId,$request->all());

					if($res['status']==1)
					{
						$investment->save();

						return response()->json([
				         'message'   => 'Investment Information Updated Successfully',
				         'status'  => 1,
				         'min'=>$investment->min_investment,
				         'offer_date'=>$investment->offering_end_date,
				         'class_name'  => 'alert-success'
			        	]);
					}
					else if($res['status']==2)
					{
						return response()->json([
				         'message'   => $res['message'],
				         'status'  => 2,
				         'class_name'  => 'alert-danger'
				        ]);
					}
					else
					{
						return response()->json([
				         'message'   => $res['message'],
				         'status'  => 0,
				         'class_name'  => 'alert-danger'
				        ]);
					}

			  	}
			  	else
			  	{
			  		return response()->json([
			         'message'   => $validation->errors()->all(),
			         'status'  => 0,
			         'class_name'  => 'alert-danger'
			        ]);
			  	}

		  	}
		  	else
		  	{
		  		return response()->json([
		         'message'   => 'Invalid Request.Please Try Again.',
		         'status'  => 0,
		         'class_name'  => 'alert-danger'
		        ]);
		  	}

	  	}
		catch(Exception $e){
		  	return response()->json([
		     'message'   => $e->getMessage,
		     'status'  => 0,
		     'class_name'  => 'alert-danger'
		    ]);
	  	}
  	}

  	//function to view investments
	public function investmentListing(Request $request)
	{
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

					$countInvData = $this->GetOwnerDetails($userId);

			      	if($request->ajax())
			      	{
	                	//get investments

				      	$investmentData = Investment::with('ownerData.userdetails')
				      	->where('user_id',$userId)->where('status',1);
				      	/*$investmentData = Investment::with('ownerData.userdetails')
				      	->where('user_id',$userId);*/

				      	//when serach string is sent in akax request then below condition will work
				      	if(!empty($searchText))
				      	{
				      		$investmentData->where('investment_title','like','%' . $searchText . '%');
				      	}

				      	if(!empty($lastId))
				      	{
				      		$investmentData->where('id','<',$lastId);
				      	}

				      	$investmentData = $investmentData->take(6)->orderBy('investments.id', 'DESC')->get();

				        return view('owner.inner_investment',['data'=>$investmentData,'countInvData'=>$countInvData]);
	            	}
	            	else
	            	{
	            		//get user info
	            		$userData = User::with('userdetails')->where('id',$userId)->first();
	            		//get investments
				      	$investmentData = Investment::with('ownerData.userdetails')
				      	->where('user_id',$userId)->where('status',1);
				      	/*$investmentData = Investment::with('ownerData.userdetails')
				      	->where('user_id',$userId);*/
				      	//when serach string is sent in akax request then below condition will work
				      	if(!empty($searchText))
				      	{
				      		$investmentData->where('investment_title','like','%' . $searchText . '%');
				      	}

				      	$investmentData = $investmentData->take(6)->orderBy('investments.id', 'DESC')->get();


				        return view('owner.investments',['data'=>$investmentData,'userData'=>$userData,'countInvData'=>$countInvData]);
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

	//function to update project  details
  	public function updateProjectData(Request $request)
  	{
		try
		{
		  	$validation = Validator::make($request->all(), [
	        'title' => 'required|string|max:50',
	        'budget' => 'required|numeric',
	        'project_image' => 'mimes:jpeg,jpg,png,gif',
	       ]);
		  	//get logged user id
		  	$userId = Auth::id();

		  	if($request->investId)
		  	{
		  		if($validation->passes())
			  	{
			  		//update description
			  		$investment = Investment::find($request->investId);
			  		$projectImageName='';
			  		//upload if project's image is given
			  		if($request->hasFile('project_image'))
			  		{
			  			$image = $request->file('project_image');
				        $projectImageName = rand() . '.' . $image->getClientOriginalExtension();
				        $pathToSave =  'projectImages/'.$projectImageName;
				        //upload images
	        			$res = $this->AddFileToBucket($pathToSave,$request->file('project_image'));
			  		}

			  		if(!empty($projectImageName))
			  		{
			  			$investment->investment_image = $projectImageName;
			  		}


					$investment->investment_title = $request->title;
					$investment->budget = $request->budget;



					//update offering details to fund america
					$res = $this->UpdateOffering($userId,$request->investId,$request->all());


					if($res['status']==1)
					{
						$investment->save();
						return response()->json([
				         'message'   => 'Data Updated Successfully',
				         'status'  => 1,
				         'class_name'  => 'alert-success'
			        	]);
					}
					else
					{
						return response()->json([
				         'message'   => $res['message'],
				         'status'  => 0,
				         'class_name'  => 'alert-danger'
				        ]);
					}
					//update offering details to fund america


			  	}
			  	else
			  	{
			  		return response()->json([
			         'message'   => $validation->errors()->all(),
			         'status'  => 0,
			         'class_name'  => 'alert-danger'
			        ]);
			  	}

		  	}
		  	else
		  	{
		  		return response()->json([
		         'message'   => 'Invalid Request.Please Try Again.',
		         'status'  => 0,
		         'class_name'  => 'alert-danger'
		        ]);
		  	}

	  	}
		catch(Exception $e){
		  	return response()->json([
		     'message'   => $e->getMessage,
		     'status'  => 0,
		     'class_name'  => 'alert-danger'
		    ]);
	  	}
  	}

  	//function to sign escrow agreement link by issuer

  	public function SignAgreementLink(Request $request)
  	{
  		try
  		{
  			//get logged user id
  			$userId = Auth::id();

  			$validation = Validator::make($request->all(), [
	        'signature' => 'required|string',
	        'email' => 'required|email',
	        'title' => 'required',
	        'company' => 'required',
	       ]);

  			$projectId = $request->projectId;
  			$signature = $request->signature;
  			$email = $request->email;
  			$title = $request->title;
  			$companyName = $request->company;
  			$signlink = $request->signlink;
  			$useragent = $request->header('User-Agent');

  			if($validation->passes())
  			{
  				if($userId!='' && $projectId!='' && $signlink!='')
	  			{
	  				$investmentData = Investment::where('id',$projectId)->first();
	  				//get sign link

	  				//send request for sign process and update response in db

	  				$getResponse = $this->SignAgreementLinkFA($companyName,$email,$signature,$title,$signlink,$investmentData->agreement_id,$investmentData->offering_id,$useragent);

	  				if($getResponse['status']==0)
					{
						return response()->json([
					     'message'   => $getResponse['message'],
					     'status'  => 0,
					    ]);
					}
					else if($getResponse['status']==2)
					{
						return response()->json([
					     'message'   => $getResponse['message'][0],
					     'status'  => 0,
					    ]);
					}
					else if($getResponse['status']==1)
					{
						return response()->json([
					     'message'   => $getResponse['message'],
					     'status'  => 1,
					    ]);
					}
	  			}
	  			else
	  			{
	  				return response()->json([
				     'message'   => 'Invalid Request.Please Try Again',
				     'status'  => 0,
				    ]);
	  			}
  			}
  			else
		  	{
		  		return response()->json([
		         'message'   => $validation->errors()->all(),
		         'status'  => 0
		        ]);
		  	}


  		}
  		catch(Exception $e)
  		{
  			return response()->json([
		     'message'   => $e->getMessage,
		     'status'  => 0,
		    ]);
  		}
  	}

  	//function to sign tech service  agreement link by issuer
  	public function SignTechServiceAgreementLink(Request $request)
  	{
  		try
  		{
  			//get logged user id
  			$userId = Auth::id();

  			$validation = Validator::make($request->all(), [
	        'signature' => 'required|string',
	        'email' => 'required|email',
	        'title' => 'required',
	        'company' => 'required',
	       ]);

  			$projectId = $request->projectId;
  			$signature = $request->signature;
  			$email = $request->email;
  			$title = $request->title;
  			$companyName = $request->company;
  			$signlink = $request->signlink;
  			$useragent = $request->header('User-Agent');

  			if($validation->passes())
  			{
  				if($userId!='' && $projectId!='' && $signlink!='')
	  			{
	  				$investmentData = Investment::where('id',$projectId)->first();
	  				//get sign link

	  				//send request for sign process and update response in db

	  				$getResponse = $this->SignTechAgreementLinkFA($companyName,$email,$signature,$title,$signlink,$investmentData->tech_agreement_id,$investmentData->offering_id,$useragent);

	  				if($getResponse['status']==0)
					{
						return response()->json([
					     'message'   => $getResponse['message'],
					     'status'  => 0,
					    ]);
					}
					else if($getResponse['status']==2)
					{
						return response()->json([
					     'message'   => $getResponse['message'][0],
					     'status'  => 0,
					    ]);
					}
					else if($getResponse['status']==1)
					{
						return response()->json([
					     'message'   => $getResponse['message'],
					     'status'  => 1,
					    ]);
					}
	  			}
	  			else
	  			{
	  				return response()->json([
				     'message'   => 'Invalid Request.Please Try Again',
				     'status'  => 0,
				    ]);
	  			}
  			}
  			else
		  	{
		  		return response()->json([
		         'message'   => $validation->errors()->all(),
		         'status'  => 0
		        ]);
		  	}


  		}
  		catch(Exception $e)
  		{
  			return response()->json([
		     'message'   => $e->getMessage,
		     'status'  => 0,
		    ]);
  		}
  	}

  	//get detail of investment from fundamerica dashboard

  	public function investmentProjectDetails(Request $request)
  	{
  		//get investment id from fundamerica
  		$getInvId = $request->id;
  		//get offering id
  		$offerId = $request->pid;
  		//get user id
  		$userId = Auth::id();

  		try{

  			//get all details of offering and user
  			$investmentData = Investment::with('investmentDocs','ownerData.userdetails')->where('user_id',$userId)->where('id',$offerId)->first();

  			//fetch investment details from fund america api
  			$invDetail = $this->InvestmentDetails($getInvId);

  			return view('owner.view_investment_detail',['data'=>$investmentData,'invDetail'=>$invDetail]);
  		}
  		catch(Exception $e){

  			abort(403, $e->getMessage());
  		}
  	}

  	//function that will be used to fecth more investments of a project from fundamerica
  	public function loadMoreInvestments(Request $request)
  	{
  		try
  		{
  			$invDetails = array();
  			$limit = 10;//get limit
	        $start = $request->start?$request->start:0;//get offset
	        $pid = $request->pid;//get offset

	        if($start==0)
	        {
	          $start = 1;
	        }
	        else
	        {
	          $start = floor(($start/10)+1);
	        }


	        $chkIndExist = Investment::where('id',$pid)->first();
	        //get data from fund america api as per pagination
	        $allinvestments = $this->GetAllInvestments($start,$limit,$chkIndExist->offering_id);

	        $count = $allinvestments->total_resources;

	        if(!empty($allinvestments) && isset($allinvestments->resources) && !empty($allinvestments->resources))
	        {

	        	foreach($allinvestments->resources as $resource)
	        	{

	        		//get offering id from url
		            $getEntityId = explode("/", $resource->entity_url);
		            $entityId = end($getEntityId);
		            //get name of offering
		            $getEntityDet = User::where('entity_id',$entityId)->first();

		            if(!empty($getEntityDet))
		            {
		                $name = $getEntityDet->first_name.' '.$getEntityDet->last_name;
		            }


	        		$invDetail['name'] = $name;
	        		$invDetail['id'] = $resource->id;
	        		$invDetail['email'] = $getEntityDet->email;
	        		$invDetail['invested'] = Carbon::parse($resource->invested_at)->format('Y-m-d H:m:s');
	        		$invDetail['amount'] = $resource->amount;
	        		$invDetail['received'] = $resource->amount_received;
	        		$invDetail['reference'] = $resource->payment_reference;
	        		$invDetail['paymentMethod'] = $resource->payment_method;
	        		$invDetail['status'] = $resource->status;
	        		$invDetails[] = $invDetail;
	        	}
	        }

	        return view('owner.loadmore_investment',['allinvestments'=>$invDetails,'pid'=>$pid,'count'=>$count]);
  		}
  		catch(Exception $e){


  			abort(403, $e->getMessage());
  		}
  	}


}
