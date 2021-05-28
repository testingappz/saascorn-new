<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Investment;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        
        try
        {
            //get investments
            $investmentData = Investment::where('status',1)->get();
            return view('home',['data'=>$investmentData]);
        }
        catch(Exception $e)
        {
            abort(403, $e->getMessage());
        }
    }

   
}
