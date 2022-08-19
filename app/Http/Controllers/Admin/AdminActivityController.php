<?php

namespace App\Http\Controllers\Admin;

use App\Models\Activity;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminActivityController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();
		$this->middleware('admin:sale.item.view', ['only' => ['index', 'data']]);
	}



	public function index()
	{
		$page = ['title' => 'Activities List', 'item' => 'Activity', 'item_plural' => 'Activities', 'breadcrumb' => Activity::getBreadcrumb(), 'field' => 'activities', 'view' => 'admin.activity', 'route' => 'admin.activity', 'permission' => 'activity', 'modal_create' => false, 'modal_edit' => false, 'mass_del_permit' => true];
		$table = Activity::getTableFormat();
		return view('admin.activity.index', compact('page', 'table'));
	}



	public function data(Request $request)
	{
		if($request->ajax()) :
			return Activity::getTableData($request);
		endif;
	}



	public function bulkDestroy(Request $request)
	{

	}
}	