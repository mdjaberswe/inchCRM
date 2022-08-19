<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
	public function index()
	{
		$view = \App\Models\FilterView::getFilterViews('task');

		dd($view);
	}






















	// relation: belongsTo

	// relation: belongsToMany

	// relation: hasOne

	// relation: hasMany

	// relation: morphTo

	// relation: morphOne

	// relation: morphMany

	// relation: morphToMany

	// relation: morphedByMany
}