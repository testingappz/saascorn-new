<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use App\UserDetail;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    protected $redirectTo = '/login';


    /*public function redirectTo() {
      $role = Auth::user()->user_type;
      switch ($role) {
          case 'investor':
            return 'investor/create_investor_profile';
            break;
          case 'owner':
            return 'owner/create_owner_profile';
            break;

          default:
            return '/login';
          break;
        }
    }*/

    // public function redirectTo() {
    //
    //
    //   $role = Auth::user()->user_type;
    //
    //   switch ($role) {
    //       case 'investor':
    //         return 'investor/create_investor_profile';
    //         break;
    //       case 'owner':
    //         return 'owner/create_owner_profile';
    //         break;
    //
    //       default:
    //         return '/login';
    //       break;
    //     }
    // }


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        if($data['user_type']=="owner")
        {
            return Validator::make($data, [
                'first_name' => ['required', 'string', 'max:100'],
                'last_name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8','regex:/[A-Z]/','regex:/[@$!%*#?&]/'],
                'user_type' => ['required', 'string', 'max:50'],
                'terms' => ['accepted'],
            ],[
              'password.regex'=>'Password must have one capital letter and one special symbol.',
          ]);
        }
        else
        {
            $rules = array(
                'accredited_type' => ['required'],
                'first_name' => ['required', 'string', 'max:100'],
                'last_name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8','regex:/[A-Z]/','regex:/[@$!%*#?&]/'],
                'user_type' => ['required', 'string', 'max:50'],

            );
            $messsages = array(
                'accredited_type.required'=>'Please specify whether you are accredited investor or not',
                'password.regex'=>'Password must have one capital letter and one special symbol.',
            );

            //if yes
            if(isset($data['accredited_type']) && $data['accredited_type']==1)
            {
                $rules['qualify'] = ['required'];
                $rules['terms'] = ['accepted'];
                $messsages['qualify.required'] = 'Please specify how you qualify as an accredited';
            }
            else if(isset($data['accredited_type']) && $data['accredited_type']==2)//if no
            {
                $rules['annual_income'] = ['required'];
                $rules['networth'] = ['required'];
                $rules['last_offering'] = ['required'];
                $rules['terms'] = ['accepted'];
                $messsages['annual_income.required'] = 'Please specify annual income.';
                $messsages['networth.required'] = 'Please specify networth.';
                $messsages['last_offering.required'] = 'Please specify last offering.';
            }
            else
            {
              $rules['accredited_type'] = ['required'];
              $rules['terms'] = ['accepted'];
              $messsages['accredited_type.required'] = 'Please specify whether you are accredited investor or not.';
            }

            return Validator::make($data,$rules,$messsages);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // Create the User and store the object
        $newUser = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type' => $data['user_type'],
        ]);
  // For investor Add user information data
        if($data['user_type']=="investor" && isset($newUser->id) && !empty($newUser->id))
        {
          $type = isset($data['qualify'])?$data['qualify']:0;
          $maxInvestment = isset($data['max_investment'])?$data['max_investment']:0;
          $annualIncome = isset($data['annual_income'])?$data['annual_income']:0;
          $networth = isset($data['networth'])?$data['networth']:0;
          $lastInvestment = isset($data['last_offering'])?$data['last_offering']:0;
          // Add user information data
          $userInfo = UserDetail::create([
            'user_id' => $newUser->id,
            'accredited_investor_type' => $data['accredited_type'],
            'accredited_type' => $type,
            'max_investment' => $maxInvestment,
            'annual_income' => $annualIncome,
            'networth' => $networth,
            'last_investment' => $lastInvestment,
          ]);
        }

        return $newUser;
    }


          public function register(Request $request)
          {

              $this->validator($request->all())->validate();
              event(new Registered($user = $this->create($request->all())));

              //EAMIL SENDING AFTER REGISETER REGISTER
              $url = $_SERVER['HTTP_ORIGIN'].'/images/templateimages/emailbg.png';
                $linkurl =   $_SERVER['HTTP_ORIGIN'].'/users/approve/'.$user->id;
                $email = $request->email;
                $details = [
                'name' => $request->first_name,
                'lastname' => $request->last_name,
                'url' => $url,
                'linkurl' => $linkurl
                ];
                \Mail::to($email)->send(new \App\Mail\RegisterEmail($details));

                // dd("Email is Sent.");

              return redirect($this->redirectPath())->with('verifymessage', 'Verification link has sent on your email plese check !');
            }

}
