<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
	public function __construct()
	{
		parent::__construct();		
		$this->middleware('auth');
	}



	public function index()
	{
		if(auth()->user()->linked_type == 'staff') :
			$initial_route = auth_staff()->initial_route;
		
			if(isset($initial_route)) :
				return redirect()->route($initial_route);
			else :
				Auth::logout();
				return redirect()->route('auth.signin');
			endif;	
		endif;
	}



	public function setSidenavStatus(Request $request)
	{
		if($request->ajax()) :
			if(isset($request->is_compress)) :
				Session::put('is_compress', $request->is_compress);
			endif;
		endif;

		return redirect()->route('home');
	}
}