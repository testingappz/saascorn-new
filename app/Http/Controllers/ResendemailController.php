<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ResendemailController extends Controller
{
    public function resendemail(Request $request){

      $user = User::where('email', $request->email)->first();
      $url = $_SERVER['HTTP_ORIGIN'].'/images/templateimages/bg.png';
        $linkurl =   $_SERVER['HTTP_ORIGIN'].'/users/approve/'.$user->id;

        $email = $request->email;
        $details = [
        'name' => $user->first_name,
        'lastname' => $user->last_name,
        'url' => $url,
        'linkurl' => $linkurl
        ];
        // echo "<pre>";print_r($details);die;
     \Mail::to($email)->send(new \App\Mail\RegisterEmail($details));


          return response()->json([
             'message'   => 'Please check email verification link has been sent at your registered email.',
             'status'  => 1,
             'class_name'  => 'alert-success'
            ]);





    }
}
