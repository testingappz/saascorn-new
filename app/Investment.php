<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    //
    protected $table = 'investments';

   	public function user()
    {
		return $this->hasMany('App\User','id', 'user_id');


    }		
	/* public function investmentdocs()
    {
		return $this->hasMany('App\InvestmentDocs', 'investment_id', 'id');


    } */
	public function investmentDocs()
	{
		return $this->hasMany('App\InvestmentDocs');
	}

	public function ownerData()
	{
		return $this->belongsTo('App\User', 'user_id', 'id');

	}

	public function investmentData()
    {
        return $this->hasOne('App\MakeInvestment','project_id');
    }
}
