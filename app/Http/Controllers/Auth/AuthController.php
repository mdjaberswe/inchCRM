<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Session;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'signout']);
    }



    public function signin()
    {
        $page['title'] = 'inchCRM - Signin';
        return view('auth.signin')->withPage($page);
    }



    public function postSignin(Request $request)
    {
        $rules = ['email' => 'required|email', 'password' => 'required|min:6'];
        $this->validate($request, $rules);

        $data = ['email' => $request->email, 'password' => $request->password, 'status' => 1];
        $remember = isset($request->remember_me) ? 1 : 0;

        if(Auth::attempt($data, $remember)) :
            auth()->user()->update(['last_login' => date('Y-m-d H:i:s')]);
            return redirect()->route('home');
        else :
            $danger_message = 'Authentication failed!';
            return redirect()->back()->withInput()->withDanger_message($danger_message);
        endif;
    }



    public function signout()
    {
        Auth::logout();
        Session::flush();
        return redirect()->route('auth.signin');
    }
}
