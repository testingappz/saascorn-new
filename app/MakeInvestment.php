<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MakeInvestment extends Model
{
    //
    protected $table = 'make_investment';

    public function investmentDetail()
    {
        return $this->belongsTo('App\Investment','project_id');
    }

    public function investorDetail()
    {
        return $this->belongsTo('App\User','investor_id');
    }
}
