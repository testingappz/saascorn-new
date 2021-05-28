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

    try
    {
      //get id of logged in user
      $userId = Auth::id();
      $userDetails = User::with('userdetails')->where('id',$userId)->first();
      $profileStatus = $userDetails->profile_updated;

      if($profileStatus==1)
      {
        return redirect('owner/view_owner_profile');
      }
      else
      {
        //get all countries
        $countries = Countries::all();
      
        return view('owner.create_profile', ['countries' => $countries,'userDetails'=>$userDetails]);
      }
    }
    catch(Exception $e){
      abort(403, $e->getMessage());
    }
    
  }

  //function to upload profile picture
  public function createProfilePicture(Request $request) {

    try
    {
      //get id of logged in user
      $userId = Auth::id();
      $validation = Validator::make($request->all(), [
        'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif'
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
           'message'   => 'image upload failed.Please try after some time.',
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

  //get state country city
  public function getCountryData(Request $request) {

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

  //function to add other profile details of owner
  public function addProfileDetails(Request $request) {

    try
    {
      //get id of logged in user
      $userId = Auth::id();

      $messages = [
          'country.required' => 'Please select country',
          'state.required' => 'Please select state',
          'city.required' => 'Please select city',
          'country.numeric' => 'Please select country',
          'state.numeric' => 'Please select state',
          'city.numeric' => 'Please select city',
          'country.min' => 'Please select country',
          'state.min' => 'Please select state',
          'city.min' => 'Please select city',
      ];


      $validation = Validator::make($request->all(), [
        'company_name' => 'required|string|max:50',
        'phone' => 'required|numeric',
        'address_line_1' => 'required|string|max:255',
        'address_line_2' => 'required|string|max:255',
        'country' => 'required|min:1|numeric',
        'state' => 'required|min:1|numeric',
        'city' => 'required|min:1|numeric',
        'contact_name' => 'required|string',
        'company_region' => 'required',
        'tax_number' => 'required|min:9',
       ], $messages);

      if($validation->passes())
      {
        $checkExist = UserDetail::where('user_id', $userId)->first();

        if($checkExist)//update existing details
        {
           $updateData = UserDetail::where('user_id', $userId)
          ->update([
            'company_name' => $request->company_name,
            'phone' => $request->phone,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'zipcode' => $request->postal,
            'executive_name' => $request->contact_name,
            'region_formed_in' => $request->company_region,
            'tax_id_number' => $request->tax_number,
          ]);
        }
        else//insert new details
        {
          $updateData = new UserDetail;
          $updateData->user_id = $userId;
          $updateData->company_name = $request->company_name;
          $updateData->phone = $request->phone;
          $updateData->address_line_1 = $request->address_line_1;
          $updateData->address_line_2 = $request->address_line_2;
          $updateData->country = $request->country;
          $updateData->state = $request->state;
          $updateData->city = $request->city;
          $updateData->zipcode = $request->postal;
          $updateData->executive_name = $request->contact_name;
          $updateData->region_formed_in = $request->company_region;
          $updateData->tax_id_number = $request->tax_number;
          $updateData->save();
        }

        //updaate status of user for profile
        if($updateData)
        {
          $res = User::where('id', $userId)
            ->update([
              'profile_updated' => 1
            ]);
          
        }

        //create entity in fund america dashboard and update data in user table
        $this->CreateEntity($userId);
        //create entity in fund america dashboard and update data in user table

          return redirect()->route('viewOwnerData');
      }
      else
      {
        return Redirect::back()->withErrors($validation)->withInput();
      }
    }
    catch(Exception $e)
    {
      abort(403, $e->getMessage());
    }
  }


  //view owner profile data
  public function viewOwnerProfile(){

    try
    {

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

      //get investment related data

      $countInvData = $this->GetOwnerDetails($userId);
      
      //get all states of selected country
       return view('owner.update_profile', ['countries' => $countries,'states' => $states,'cities' => $cities,'userDetails'=>$userDetails,'countInvData'=>$countInvData]);
    }
    catch(Exception $e)
    {
      abort(403, $e->getMessage());
    }
    
  }

  //update owner profile deails

  public function updateProfileDetails(Request $request) {

    try
    {
      //get id of logged in user
      $userId = Auth::id();

        $messages = [
          'country.required' => 'Please select country',
          'state.required' => 'Please select state',
          'city.required' => 'Please select city',
          'country.numeric' => 'Please select country',
          'state.numeric' => 'Please select state',
          'city.numeric' => 'Please select city',
          'country.min' => 'Please select country',
          'state.min' => 'Please select state',
          'city.min' => 'Please select city',
          'phone.min' => 'The phone must be at least 10 digits',
      ];

      $validation = Validator::make($request->all(), [
        'first_name' => 'required|string|max:100',
        'last_name' => 'required|string|max:100',
        'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'address_line_1' => 'required|string|max:255',
        'address_line_2' => 'required|string|max:255',
        'country' => 'required|min:1|numeric',
        'state' => 'required|min:1|numeric',
        'city' => 'required|min:1|numeric',
        'postal' => 'required'
        
      ],$messages);

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
            'zipcode' => $request->postal
          ]);

          $updateDataUser = User::where('id', $userId)
          ->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name
          ]);
        }
          
        $this->CreateEntity($userId);
        
        return redirect('owner/view_owner_profile')->with('success', 'Data Updated Successfully');
      }
      else
      {
        return Redirect::back()->withErrors($validation);
        //return Redirect::back()->withErrors($validation)->withInput();
      }
    }
    catch(Exception $e)
    {
      return redirect('owner/view_owner_profile')->with('error',  $e->getMessage());
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
         'message'   => 'Request Failed.Please Try Again After Sometime.',
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

  public function changePasswordOwner(Request $request){
    
    try
    {
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
    catch(Exception $e)
    {
      return response()->json([
         'message'   => $e->getMessage(),
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
  public function validatePasswords(array $data)
  {
      $messages = [
          'password.required' => 'Please enter your current password',
          'new_password.required' => 'Please enter a new password',
          'password_confirmation.required' => 'Please enter a confirm password',
          'new_password.regex'=>'Password must have one capital letter and one special symbol.',
      ];

      $validator = Validator::make($data, [
          'password' => 'required',
          'new_password' => ['required', 'min:8'],
          'password_confirmation' => 'required|same:new_password',
      ], $messages);

      return $validator;
  }
  
}
