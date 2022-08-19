<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;
use App\Http\Controllers\HomeController;

class AccountBaseController extends HomeController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('auth.type:contact');
	}
}