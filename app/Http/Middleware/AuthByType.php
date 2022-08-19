<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AuthByType
{
	public function handle($request, Closure $next, $linked_type = null)
	{
		if(Auth::guest() || Auth::user()->linked_type != $linked_type || !Auth::user()->status) :
		    if ($request->ajax() || $request->wantsJson()) :
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

		return $next($request);
	}
}