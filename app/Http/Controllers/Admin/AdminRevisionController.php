<?php

namespace App\Http\Controllers\Admin;

use App\Models\Revision;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminRevisionController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:advanced.activity_log.view', ['only' => ['index', 'revisionData']]);
		$this->middleware('admin:advanced.activity_log.delete', ['only' => ['destroy', 'bulkDestroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Activity Logs', 'item' => 'Activity Log', 'page_length' => 25, 'field' => 'revisions', 'view' => 'admin.revision', 'route' => 'admin.advanced-activity-log', 'modal_create' => false, 'modal_edit' => false, 'modal_size' => 'medium', 'mass_del_permit' => permit('mass_delete.activity_log')];
		$table = ['thead' => [['RESPONSIBLE PERSON', 'style' => 'min-width: 180px'], 'DESCRIPTION', ['DATE', 'style' => 'min-width: 160px']], 'checkbox' => Revision::allowMassAction(), 'action' => Revision::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'user', 'description', 'date', 'action'], Revision::hideColumns());

		return view('admin.revision.index', compact('page', 'table'));
	}



	public function revisionData(Request $request)
	{
		if($request->ajax()) :
			$revisions = Revision::latest('id')->filterByType()->groupByGet();
			return DatatablesManager::revisionData($revisions, $request);
		endif;
	}



	public function destroy(Request $request, Revision $revision)
	{
		if($request->ajax()) :
			$status = true;

			if($revision->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				Revision::whereRevisionable_type($revision->revisionable_type)
						  ->whereRevisionable_id($revision->revisionable_id)
						  ->whereCreated_at($revision->created_at)
						  ->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}



	public function bulkDestroy(Request $request)
	{
		if($request->ajax()) :
			$revisions = $request->revisions;

			$status = true;

			if(isset($revisions) && count($revisions) > 0) :
				foreach($revisions as $revision_id) :
					$revision = Revision::find($revision_id);
					if(isset($revision)) :
						Revision::whereRevisionable_type($revision->revisionable_type)
								  ->whereRevisionable_id($revision->revisionable_id)
								  ->whereCreated_at($revision->created_at)
								  ->delete();
					endif;					
				endforeach;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status]);
		endif;
	}
}