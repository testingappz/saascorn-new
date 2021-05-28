<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
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

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    public function redirectTo() {
      $role = Auth::user()->user_type; 
      switch ($role) {
          case 'investor':
            return '/investor_update_profile';
            break;
          case 'owner':
            return '/owner_update_profile';
            break; 

          default:
            return '/login'; 
          break;
        }
    }

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
            ],['password.regex'=>'Password must have one capital letter and one special symbol.']);
        }
        else
        {
             $messsages = array(
                'accredited_type.required'=>'Please specify whether you are accredited investor or not',
                'qualify.required'=>'Please specify how you qualify as an accredited',
                'password.regex'=>'Password must have one capital letter and one special symbol.'
            );

            return Validator::make($data, [
                'first_name' => ['required', 'string', 'max:100'],
                'last_name' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8','regex:/[A-Z]/','regex:/[@$!%*#?&]/'],
                'user_type' => ['required', 'string', 'max:50'],
                'terms' => ['accepted'],
                'accredited_type' => ['required'],
                'qualify' => ['required'],
            ],$messsages);
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
        return User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'user_type' => $data['user_type'],
        ]);
    }
}
