<?php

namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Countries;
use App\States;
use App\Cities;

class DashboardController extends Controller {

  public function __construct() {
    $this->middleware('auth');
  }

	//function to show dashboard
  public function index() {
    return view('owner.dashboard');
  }




}
