<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\VerifiesEmails;

class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    


    protected function redirectTo() 
    {
      $role = Auth::user()->user_type; 
      $profileSetup = Auth::user()->profile_updated; 
      
      //if profile details are not added
      if($profileSetup==0)
      {
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
      }
      else
      {
        switch ($role) {
          case 'investor':
            return 'investor/view_investor_profile';
            break;
          case 'owner':
            return 'owner/view_owner_profile';
            break; 

          default:
            return '/login'; 
          break;
        }
      }
      
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
