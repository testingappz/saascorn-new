<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordforgetController extends Controller
{


    public function forgetpassword(Request $request){


        $request->validate([
            'email' => 'required|email'

        ]);
        $user = User::where('email',$request->email)->first();
        if(empty($user)){
          return back()
                ->with('notfoundalert',"We can't find a user with that email address.");
        }else{

              $token = Str::random(60);
              DB::table('password_resets')->insert([
              'email' => $request->email,
              'token' => $token,
              'created_at' => Carbon::now()
            ]);
              $url = $_SERVER['HTTP_ORIGIN'];
              // $linkurl = $url.'/password/reset/' . $token . '?email=' . urlencode($request->email);
              $linkurl = $url.'/password/passwordreset/' . $token ;
              $url = $_SERVER['HTTP_ORIGIN'].'/images/templateimages/emailbg.png';

              $email = $request->email;
              $details = [
              'name' => $user->first_name,
              'lastname' => $user->last_name,
              'url' => $url,
              'linkurl' => $linkurl
              ];
              // echo "<pre>";print_r($details);die;
              \Mail::to($email)->send(new \App\Mail\PasswordReset($details));
              return back()
                    ->with('success',"Password reset email sent on your account please check");

        }


    }
    public function changepassord($token){
      $users = DB::table('password_resets')->where('token',$token)->first();

      if($users){
        $email  = $users->email;

        return view('auth.passwordreset')->with('email',$email)->with('token',$token);
      }

    }
    public function resetpassword(Request $request){
      $token = $request->token;
      $request->validate([
         'email' => 'required',
         'token' => 'required',
         'password' => 'required|string|min:8|same:password_confirmation',
         'password_confirmation' => 'required',
       ]);
       $users = DB::table('password_resets')->where('token',$token)->first();


       if($users){

         if($request->email == $users->email){
            $password = $request->password;
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($password);
            $user->update();
            return redirect('/login')->with('passwordupdate', "Your Password updated");

          }else{
           return back()
                 ->with('alertmessage',"Email Not found!");
         }


       }




    }
}
