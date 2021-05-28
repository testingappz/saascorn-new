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
use Redirect;
use App\Http\Traits\PushDataToBucket;
use App\Http\Traits\AddDataToFundAmerica;

class ProfileController extends Controller {

  public function __construct() {
    $this->middleware('auth');
  }

  use PushDataToBucket;
  use AddDataToFundAmerica;
  //function to render view file for create profile
  public function createProfile() {

  	//get id of logged in user
    $userId = Auth::id();
    $userDetails = User::with('userdetails')->where('id',$userId)->first();
    $profileStatus = $userDetails->profile_updated;
    if($profileStatus==1)
    {
      return redirect('investor/view_investor_profile');
    }
    else
    {
      //get all countries
      $countries = Countries::all();
    
      return view('investor.create_profile', ['countries' => $countries,'userDetails'=>$userDetails]);
    }
  }

  //function to upload profile picture
  public function createProfilePicture(Request $request) {

    try
    {
      //get id of logged in user
      $userId = Auth::id();
      $validation = Validator::make($request->all(), [
        'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2000'
       ]);
      
      if($validation->passes())
      {
        //upload in folder
        $image = $request->file('profile_picture');
        $imageName = rand() . '.' . $image->getClientOriginalExtension();
        
        $pathToSave =  'images/'.$imageName;
        
        //upload images 
        $res = $this->AddFileToBucket($pathToSave,$request->file('profile_picture'));

        if($res)
        {
          //get url of uploaded images
          $url = $this->GetFileUrlFromBucket($pathToSave);

          //update in user table
          $user = User::find($userId);

          $user->profile_image = $imageName;

          $user->save();
          //update in user table
          return response()->json([
           'message'   => 'Image Uploaded Successfully',
           'uploaded_image' => $url,
           'status'  => 1,
           'class_name'  => 'alert-success'
          ]);
        }
        else
        {
          return response()->json([
           'message'   => $validation->errors()->all(),
           'uploaded_image' => '',
           'status'  => 0,
           'class_name'  => 'alert-danger'
          ]);
        }
      }
      else
      {
        return response()->json([
         'message'   => $validation->errors()->all(),
         'uploaded_image' => '',
         'status'  => 0,
         'class_name'  => 'alert-danger'
        ]);
      }
    }
    catch(Exception $e)
    {
      return response()->json([
         'message'   => $e->getMessage(),
         'uploaded_image' => '',
         'status'  => 0,
         'class_name'  => 'alert-danger'
        ]);
    }
    
  }

  //function to remove profile picture
  public function removeProfilePicture(Request $request) {

    try
    {
      //get id of logged in user
      $userId = Auth::id();
      $user = User::find($userId);
      $path = '/images/'.$user->profile_image;
      //remove image from bucket
      $res = $this->RemoveFileFromBucket($path);
      if($res)
      { 

        //remove image from db
        $user->profile_image='';
        $user->save();
        
        return response()->json([
         'message'   => 'Profile Picture Removed Successfully',
         'uploaded_image' => "/images/image_placeholder.svg",
         'status'  => 1,
         'class_name'  => 'alert-success'
        ]);

      }
      else
      {
        return response()->json([
         'message'   => 'Request Failed.Please Try Again After Some Time',
         'uploaded_image' => '',
         'status'  => 0,
         'class_name'  => 'alert-danger'
        ]);
      }
     
    }
    catch(Exception $e)
    {
      return response()->json([
         'message'   => $e->getMessage(),
         'uploaded_image' => '',
         'status'  => 0,
         'class_name'  => 'alert-danger'
        ]);
    }
    
  }

  //get state country city
  public function getCountry(Request $request) {

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


  //function to add other profile details of investor
  public function addProfileDetailsInvestor(Request $request) {

    try
    {
      //get id of logged in user
      $userId = Auth::id();

      $rules = array(
        'phone' => 'required|numeric',
        'address_line_1' => 'required|string|max:255',
        'address_line_2' => 'required|string|max:255',
        'country' => 'required|numeric|not_in:0',
        'state' => 'required|numeric|not_in:0',
        'city' => 'required|numeric|not_in:0',
        'postal' => 'required',
        'investor_type' =>'required',
        'accredited_type' => 'required',

      );
      $messsages = array(
          'accredited_type.required'=>'Please specify whether you are accredited investor or not',
      );


      //if yes
      if($request->accredited_type==1)
      {
        $rules['qualify'] = ['required'];
        $messsages['qualify.required'] = 'Please specify how you qualify as an accredited';
      }
      else if($request->accredited_type==2)//if no
      {
        $rules['annual_income'] = ['required'];
        $rules['networth'] = ['required'];
        $rules['last_offering'] = ['required'];
        $messsages['annual_income.required'] = 'Please specify annual income.';
        $messsages['networth.required'] = 'Please specify networth.';
        $messsages['last_offering.required'] = 'Please specify last offering.';
      }

      if($request->investor_type==1)//person
      {
        $rules['date_of_birth'] = ['before:18 years ago'];
        $messsages['date_of_birth.before'] = 'Age must be greater than 18 years.';
      }

      $validation = Validator::make($request->all(),$rules,$messsages);

      if($validation->passes())
      {
        $checkExist = UserDetail::where('user_id', $userId)->first();

        if($checkExist)//update existing details
        {

          $type = isset($data['qualify'])?$data['qualify']:0;
          $maxInvestment = isset($data['max_investment'])?$data['max_investment']:0;
          $annualIncome = isset($data['annual_income'])?$data['annual_income']:0;
          $networth = isset($data['networth'])?$data['networth']:0;
          $lastInvestment = isset($data['last_offering'])?$data['last_offering']:0;

          $updateData = UserDetail::where('user_id', $userId)
          ->update([
            'accredited_investor_type' => $request->accredited_type,
            'phone' => $request->phone,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'zipcode' => $request->postal,
            'investor_type' => $request->investor_type,
            'tax_id_number' => $request->tax_number,
          ]);

          if($request->investor_type==1)//person
          {
            $updateData = UserDetail::where('user_id', $userId)
            ->update([
              'date_of_birth' => $request->date_of_birth,
            ]);
          }
          else if($request->investor_type==2)//company
          {
            $updateData = UserDetail::where('user_id', $userId)
            ->update([
              'executive_name' => $request->contact_name,
              'region_formed_in' => $request->company_region,
            ]);
          }


          if($request->accredited_type==1)//if yes
          {
            $updateData = UserDetail::where('user_id', $userId)
            ->update([
              'accredited_type' => $request->qualify,
            ]);
          }
          else if($request->accredited_type==2)//if no
          {
              $updateData = UserDetail::where('user_id', $userId)->update([
              'annual_income' => $request->annual_income,
              'networth' => $request->networth,
              'last_investment' => $request->last_investment,
              
              ]);
          }

          $res = User::where('id', $userId)
          ->update([
            'profile_updated' => 1
          ]);

        }
        else//insert new details
        {
          $updateData = new UserDetail;
          $updateData->user_id = $userId;
          $updateData->accredited_investor_type = $request->accredited_type;

          if($request->investor_type==1)//person
          {
            $updateData = UserDetail::where('user_id', $userId)
            ->update([
              'date_of_birth' => $request->date_of_birth,
            ]);
          }
          else if($request->investor_type==2)//company
          {
            $updateData = UserDetail::where('user_id', $userId)
            ->update([
              'executive_name' => $request->contact_name,
              'region_formed_in' => $request->company_region,
            ]);
          }

          if($request->accredited_type==1)//if yes
          {
            $updateData->accredited_type = $request->qualify;
          }
          else if($request->accredited_type==2)//if no
          {
            $updateData->annual_income = $request->annual_income;
            $updateData->networth = $request->networth;
            $updateData->last_investment = $request->last_investment;
          }

          $updateData->phone = $request->phone;
          $updateData->address_line_1 = $request->address_line_1;
          $updateData->address_line_2 = $request->address_line_2;
          $updateData->country = $request->country;
          $updateData->state = $request->state;
          $updateData->city = $request->city;
          $updateData->zipcode = $request->postal;
          $updateData->investor_type = $request->investor_type;
          $updateData->tax_id_number = $request->tax_number;
          $updateData->save();

          //update status of user for profile

          if($updateData)
          {
            $res = User::where('id', $userId)
              ->update([
                'profile_updated' => 1
              ]);
            
          }
        }

        /////////////////////////////
        $this->CreateEntity($userId);
        /////////////////////////////
        
        return redirect()->route('viewInvestorData');
      }
      else
      {
        return Redirect::back()->withErrors($validation)->withInput();
      }
    }
    catch(Exception $e)
    {

    }
  }

  public function viewInvestorProfile(Request $request) {

    //get id of logged in user
    $userId = Auth::id();
    $userDetails = User::with('userdetails')->where('id',$userId)->first();
    $selectedCountry = $userDetails->userdetails->country;
    $selectedState = $userDetails->userdetails->state;
    $selectedCity = $userDetails->userdetails->city;
    //get all countries
    $countries = Countries::all();
    //get states of specifc country
    $states = States::where('country_id',$selectedCountry)->get();
    //get cities
    $cities =  Cities::where('state_id',$selectedState)->get();

    //get count,sum of investments by investor
    $countInvData = $this->GetInvestorDetails($userId);
    
    //get all states of selected country
     return view('investor.update_profile', ['countries' => $countries,'states' => $states,'cities' => $cities,'userDetails'=>$userDetails,'countInvData'=>$countInvData]);
  }

  //update profile details
  public function updateProfileDetailsInvestor(Request $request) {

    try
    {
      //get id of logged in user
      $userId = Auth::id();

      $rules = array(

        'first_name' => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'phone' => 'required|numeric',
        'address_line_1' => 'required|string|max:255',
        'address_line_2' => 'required|string|max:255',
        'country' => 'required|numeric|not_in:0',
        'state' => 'required|numeric|not_in:0',
        'city' => 'required|numeric|not_in:0',
        'postal' => 'required'

      );

      if($request->investor_type==1)//person
      {
        $rules['date_of_birth'] = ['before:18 years ago'];
        $messsages['date_of_birth.before'] = 'Age must be greater than 18 years.';
      }
      $validation = Validator::make($request->all(),$rules,$messsages);

      if($validation->passes())
      {
        $checkExist = UserDetail::where('user_id', $userId)->first();

        if($checkExist)//update existing details
        {
           $updateData = UserDetail::where('user_id', $userId)
          ->update([
            'phone' => $request->phone,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'zipcode' => $request->postal,
            'tax_id_number' => $request->tax_number,
            'investor_type' => $request->investor_type,
          ]);


          if($request->investor_type==1)//person
          {
            $updateData = UserDetail::where('user_id', $userId)
            ->update([
              'date_of_birth' => $request->date_of_birth,
            ]);
          }
          else if($request->investor_type==2)//company
          {
            $updateData = UserDetail::where('user_id', $userId)
            ->update([
              'executive_name' => $request->contact_name,
              'region_formed_in' => $request->company_region,
            ]);
          }



          $updateDataUser = User::where('id', $userId)
          ->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name
          ]);
        }

        /////////////////////////////
        $this->CreateEntity($userId);
        /////////////////////////////
        
        return redirect('investor/view_investor_profile')->with('success', 'Data Updated Successfully');
      }
      else
      {
        return Redirect::back()->withErrors($validation)->withInput();
      }
    }
    catch(Exception $e)
    {
      return redirect('investor/view_investor_profile')->with('error',  $e->getMessage());
    }
  }

  public function changePasswordInvestor(Request $request){
    
    if(Auth::Check())
    {
        $requestData = $request->All();
        
        $validator = $this->validatePasswords($requestData);
        if($validator->fails())
        {
          
          return response()->json([
           'message'   => $validator->getMessageBag()->toArray(),
           'status'  => 2,
           'class_name'  => 'alert-danger'
          ]);
        }
        else
        {
            $currentPassword = Auth::User()->password;
            if(Hash::check($requestData['password'], $currentPassword))
            {
                $userId = Auth::User()->id;
                $user = User::find($userId);
                $user->password = Hash::make($requestData['new_password']);;
                $user->save();
                
                return response()->json([
                 'message'   => 'Your Password Updated Successfully.',
                 'status'  => 1,
                 'class_name'  => 'alert-success'
                ]);
            }
            else
            {
                return response()->json([
                 'message'   => 'Your Current Password Was Not Recognised. Please Try Again.',
                 'status'  => 0,
                 'class_name'  => 'alert-danger'
                ]);
            }
        }
    }
    else
    {
      // Auth check failed - redirect to domain root
      return response()->json([
       'message'   => 'Invalid User Details.',
       'status'  => 0,
       'class_name'  => 'alert-danger'
      ]);
    }
  }

  public function updateInvestmentInformation(Request $request){
    
    if(Auth::Check())
    {
        $requestData = $request->All();
        
        $validator = Validator::make($request->all(), [
        //'investor_type' => 'required|numeric',
        'current_annual_income' => 'required|numeric',
        'maximun_investment' => 'required|numeric|numeric',
       ]);
        if($validator->fails())
        {
          
          return response()->json([
           'message'   => $validator->getMessageBag()->toArray(),
           'status'  => 2,
           'class_name'  => 'alert-danger'
          ]);
        }
        else
        {
            $userId = Auth::User()->id;
           
            $updateData = UserDetail::where('user_id', $userId)
          ->update([
            //'investor_type' => $request->investor_type,
            'max_investment' => $request->maximun_investment,
            'annual_income' => $request->current_annual_income,
          ]);
            
            return response()->json([
             'message'   => 'Investment Information Updated Successfully.',
             'status'  => 1,
             'class_name'  => 'alert-success'
            ]);
            
        }
    }
    else
    {
      // Auth check failed - redirect to domain root
      return response()->json([
       'message'   => 'Invalid Details.Please Try Again.',
       'status'  => 0,
       'class_name'  => 'alert-danger'
      ]);
    }
  }

  /**
   * Validate password entry
   *
   * @param array $data
   * @return \Illuminate\Contracts\Validation\Validator
   */
  public function validatePasswords(array $data){

      $messages = [
          'password.required' => 'Please enter your current password',
          'new_password.required' => 'Please enter a new password',
          'password_confirmation.required' => 'Please enter a confirm password',
          'new_password.regex'=>'Password must have one capital letter and one special symbol.',
      ];

      $validator = Validator::make($data, [
          'password' => 'required',
          'new_password' => ['required', 'string', 'min:8','regex:/[A-Z]/','regex:/[@$!%*#?&]/'],
          'password_confirmation' => 'required|same:new_password',
      ], $messages);

      return $validator;
  }

}
