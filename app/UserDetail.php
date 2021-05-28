<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = ['user_id', 'accredited_type','accredited_investor_type', 'max_investment', 'annual_income','networth','last_investment'];
}
