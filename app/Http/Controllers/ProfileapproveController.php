<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;

class ProfileapproveController extends Controller
{
  public function approve($user_id)
  {
    $user = User::findOrFail($user_id);
    if($user->email_verified_at !== NULL)
    {
      return redirect('/login');
    }
    else
    {
      $user->update(['email_verified_at' => now()]);
      return redirect('/login')->with('verifymessage', 'Thank you,your email is verified successfully.Please login to continue.');
    }
    

  }
}
