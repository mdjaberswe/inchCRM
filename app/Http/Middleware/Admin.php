<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Session;

class Admin
{
	public function handle($request, Closure $next, $permission = null)
	{
		if(Auth::user()->linked->super_admin == false || auth_staff() == false) :
			if(!Auth::user()->can($permission) || auth_staff() == false || !Auth::user()->status) :
				if($request->ajax() || $request->wantsJson()) :
					return response('Unauthorized.', 401);
				else :					
					if(Auth::check()) :		
						if(!Auth::user()->status) :
							Auth::logout();
							return redirect()->route('auth.signin');
						endif;

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
			endif;
		endif;
		
		return $next($request);
	}
}