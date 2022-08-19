<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class ChainOfCommand
{
	public function handle($request, Closure $next, $action = null)
	{		
		$staff = $request->route('user');

		$permit = $this->chainLaw($request, $staff, $action);

		if($permit == true) :
			return $next($request);
		endif;	

		if($request->ajax() || $request->wantsJson()) :
			return response('Unauthorized.', 401);
		else :
			if(Auth::check()) :
				if(Session::has('url_previous') && Session::get('url_previous') == url()->previous()) :
					Auth::logout();
					Session::forget('url_previous');
					return redirect()->route('auth.signin');
				endif;						
				Session::put('url_previous', url()->previous());

				return redirect()->to(url()->previous());
			else :						
				return redirect()->route('auth.signin');
			endif;
		endif;
	}

	protected function chainLaw($request, $staff, $action)
	{
		$conclusion = false;

		switch($action) :
			case 'edit' :
				if($staff->logged_in) :
					return true;
				else :
					$conclusion = $this->secureAdmin($request, $staff);
				endif;
			break;

			case 'delete' :
				if($staff->logged_in) :
					return false;
				else :
					$conclusion = $this->secureAdmin($request, $staff);
				endif;	
			break;

			default : return false;
		endswitch;

		return $conclusion;
	}

	protected function secureAdmin($request, $staff)
	{
		if((auth_staff()->admin == false && $staff->admin == true) || $staff->super_admin == true) :
			return false;
		endif;

		return true;
	}
}