<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Admin;
use App\User;
use Redirect;
use App\UserDetail;
class AdminController extends Controller {


	public function __construct()
	{
	
		 if (!Auth::guard('admin')->check()) {
			return redirect('admin_login');
			
		} 
			
	}
	public function adminLogin(Request $request)
	{
		if(!empty($_POST))
		{
			
			$admin = Admin::where('email', $request->email)->first();

			if (!$admin) 
			{
				\Session::put('message','Please enter correct credentials.');
				return redirect('admin_login');
			}
			if (Hash::check($request->password, $admin->password)) 
			{
				Auth::guard('admin')->login($admin);
				return redirect('/admin_dashboard');
			}
			else
			{
					
				\Session::put('message','Please enter correct credentials.');
				return redirect('admin_login');

				
			}

			
		
		}
		else
		{
			 if (Auth::guard('admin')->check()) {
				return redirect('/admin_dashboard');
			
			} 
			return view('admin.login');
		}
	   
	}

	public function dashboard(Request $request)
	{
		if (Auth::guard('admin')->check()) {
			
			//print_r(Auth::guard('admin')->check());
			//exit;
           return view('admin.dashboard');
        }
	
		
	}
	public function logout()
    {
        Auth::guard('admin')->logout();
		// \Session::flush();
        return redirect('/admin_login');
    }
	public function ownerList(Request $request)
	{
		
		$list =  User::where('user_type' , 'owner')->orderBy('id' ,'DESC')->get();
		return view('admin.ownerlist' , compact('list'));
					
		
	}
	public function showOwnerList(Request $request,$userID)
	{
		$list = User::with('userdetails')->where('id', '=', $userID)->get();
	
		return view('admin.showownerlist' , compact('list'));
					
		
	}
	public function showInvestorList(Request $request,$userID)
	{
		$list = User::with('userdetails')->where('id', '=', $userID)->get();
		return view('admin.showinvestorlist' , compact('list'));	
		
	}
	public function investorList(Request $request)
	{
		$list =  User::where('user_type' , 'investor')->orderBy('id' ,'DESC')->get();
        return view('admin.investorlist' , compact('list'));	
		
	}
	public function changeUserStatus(Request $request)
    {
        $id=$request->id;
		$active_deactive =$request->active_deactive;
		if ($request->isMethod('post'))
		{
			if($id != ''  )
			{
				$user = User::where('id', '=', $request->id)->update( 
				   array( 
						 "status" => $active_deactive,
					   
						 )
				   );
				return 1;
			}
			else
			{
				return 0;
			}
			
		}
		if($request->type=='owner')
		{
			return redirect()->route('admin.ownerlist');
		}
		else
		{
			return redirect()->route('admin.investorlist');
		}
        
    }
	


}
 