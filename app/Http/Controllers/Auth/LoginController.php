<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
     // public function redirectTo()
     // {
     //   $role = Auth::user()->user_type;
     //   $profileSetup = Auth::user()->profile_updated;
     //
     //
     //   //if profile details are not added
     //   if($profileSetup==0)
     //   {
     //     switch ($role) {
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
     //   }
     //   else
     //   {
     //
     //     switch ($role) {
     //       case 'investor':
     //         return 'investor/view_investor_profile';
     //         break;
     //       case 'owner':
     //         return 'owner/view_owner_profile';
     //         break;
     //       default:
     //         return '/login';
     //       break;
     //     }
     //   }
     //
     //
     // }

    public function login(Request $request)
    {
      // echo "<pre>";print_r($request->all());die;
        $input = $request->all();
        $hashedPass = Hash::make($input['password']);

        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();
        if(!empty($user))
        {
            if(!$user->email_verified_at)
            {
                // $message = "Please verify your account. Click here for send email again";
                $message = 'Please verify your account.';
                // echo $request->email;die;
                return redirect('/login')->with('verifyalert', $message)->with('veryfyemail', $request->email);

            }

        }


        // $fieldType = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        if(auth()->attempt(array('email' => $input['email'], 'password' => $input['password'])))
        {
            $role = Auth::user()->user_type;
            $profileSetup = Auth::user()->profile_updated;
            if($profileSetup==0)
            {
                if($role == 'investor'){
                  return redirect('/investor/create_investor_profile');

                }elseif($role == 'owner'){
                  return redirect('/owner/create_owner_profile');

                }else{
                  return redirect('/login');
                }

            }
            else
            {
                if($role == 'investor'){
                  return redirect('/investor/create_investor_profile');

                }elseif($role == 'owner'){
                  return redirect('/owner/create_owner_profile');

                }else{
                  return redirect('/login');
                }

            }

        }
        else if(empty($user))
        {
            return redirect()->route('login')
                ->with('loginerror','Email is not registered with us.');
        }
        else if($hashedPass!=$user->password)
        {
            return redirect()->route('login')
                ->with('loginerror','Please provide valid password.');
        }

    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
