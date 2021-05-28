<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [

        'first_name','last_name','user_type', 'email', 'password','email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [

        'email_verified_at' => 'datetime',
    ];


    public function userdetails()
    {
        return $this->hasOne('App\UserDetail');
    }

    public function projects()
    {
        return $this->hasMany('App\Investment');
    }

    public function investments()
    {
        return $this->hasMany('App\MakeInvestment');
    }


}
