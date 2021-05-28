<?php

namespace App\Http\Controllers\Investor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller {
  public function __construct() {
    $this->middleware('auth');
  }
  public function index() {
    return view('investor.dashboard');
  }
  public function createProfile() {
    return view('investor.create_profile');
  }
}
