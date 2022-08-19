<?php

namespace App\Http\Controllers\Admin;

use App\Models\Source;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminSourceController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:custom_dropdowns.source.view', ['only' => ['index', 'sourceData']]);
		$this->middleware('admin:custom_dropdowns.source.create', ['only' => ['store']]);
		$this->middleware('admin:custom_dropdowns.source.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:custom_dropdowns.source.delete', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Source List', 'item' => 'Source', 'field' => 'sources', 'view' => 'admin.source', 'route' => 'admin.administration-dropdown-source', 'plain_route' => 'admin.source', 'permission' => 'custom_dropdowns.source', 'subnav' => 'custom-dropdown', 'modal_bulk_delete' => false, 'modal_size' => 'medium', 'save_and_new' => false];
		$table = ['thead' => [['NAME', 'style' => 'min-width: 200px'], 'DESCRIPTION'], 'action' => Source::allowAction()];
		$table['json_columns'] = table_json_columns(['sequence' => ['className' => 'reorder'], 'name', 'description', 'action'], Source::hideColumns());
		$reset_position = Source::resetPosition();

		return view('admin.source.index', compact('page', 'table', 'reset_position'));
	}



	public function sourceData(Request $request)
	{
		if($request->ajax()) :
			$sources = Source::orderBy('position')->get(['id', 'position', 'name', 'description']);
			return DatatablesManager::sourceData($sources, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = Source::validate($data);
			$picked_position_id = $request->position;

			if($validation->passes()) :				
				$position_val = Source::getTargetPositionVal($picked_position_id);

				$source = new Source;
				$source->name = $request->name;
				$source->description = null_if_empty($request->description);
				$source->position = $position_val;
				$source->save();				
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, Source $source)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($source) && isset($request->id)) :
				if($source->id == $request->id) :
					$info = $source->toArray();
					$info['position'] = $source->prev_position_id;
					$info = (object)$info;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.administration-dropdown-source.index');
	}



	public function update(Request $request, Source $source)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($source) && isset($request->id) && $source->id == $request->id) :
				$validation = Source::validate($data);
				$picked_position_id = $request->position;

				if($validation->passes()) :					
					$position_val = Source::getTargetPositionVal($picked_position_id, $source->id);

					$source->name = $request->name;
					$source->description = null_if_empty($request->description);
					$source->position = $position_val;
					$source->save();
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'saveId' => $request->id]);
		endif;
	}	



	public function destroy(Request $request, Source $source)
	{
		if($request->ajax()) :
			$status = true;

			if($source->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$source->leads()->update(['source_id' => null]);
				$source->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}
}