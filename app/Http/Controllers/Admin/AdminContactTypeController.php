<?php

namespace App\Http\Controllers\Admin;

use App\Models\ContactType;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminContactTypeController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:custom_dropdowns.contact_type.view', ['only' => ['index', 'contactTypeData']]);
		$this->middleware('admin:custom_dropdowns.contact_type.create', ['only' => ['store']]);
		$this->middleware('admin:custom_dropdowns.contact_type.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:custom_dropdowns.contact_type.delete', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Contact Type List', 'item' => 'Contact Type', 'field' => 'contact_types', 'view' => 'admin.contacttype', 'route' => 'admin.administration-dropdown-contacttype', 'plain_route' => 'admin.contacttype', 'permission' => 'custom_dropdowns.contact_type', 'subnav' => 'custom-dropdown', 'modal_bulk_delete' => false, 'modal_size' => 'medium', 'save_and_new' => false];
		$table = ['thead' => [['NAME', 'style' => 'min-width: 200px'], 'DESCRIPTION'], 'action' => ContactType::allowAction()];
		$table['json_columns'] = table_json_columns(['sequence' => ['className' => 'reorder'], 'name', 'description', 'action'], ContactType::hideColumns());
		$reset_position = ContactType::resetPosition();

		return view('admin.contacttype.index', compact('page', 'table', 'reset_position'));
	}



	public function contactTypeData(Request $request)
	{
		if($request->ajax()) :
			$contact_types = ContactType::orderBy('position')->get(['id', 'position', 'name', 'description']);
			return DatatablesManager::contactTypeData($contact_types, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = ContactType::validate($data);
			$picked_position_id = $request->position;

			if($validation->passes()) :				
				$position_val = ContactType::getTargetPositionVal($picked_position_id);

				$contact_type = new ContactType;
				$contact_type->name = $request->name;
				$contact_type->description = null_if_empty($request->description);
				$contact_type->position = $position_val;
				$contact_type->save();				
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, ContactType $contact_type)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($contact_type) && isset($request->id)) :
				if($contact_type->id == $request->id) :
					$info = $contact_type->toArray();
					$info['position'] = $contact_type->prev_position_id;
					$info = (object)$info;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.administration-dropdown-contacttype.index');
	}



	public function update(Request $request, ContactType $contact_type)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($contact_type) && isset($request->id) && $contact_type->id == $request->id) :
				$validation = ContactType::validate($data);
				$picked_position_id = $request->position;

				if($validation->passes()) :					
					$position_val = ContactType::getTargetPositionVal($picked_position_id, $contact_type->id);

					$contact_type->name = $request->name;
					$contact_type->description = null_if_empty($request->description);
					$contact_type->position = $position_val;
					$contact_type->save();
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



	public function destroy(Request $request, ContactType $contact_type)
	{
		if($request->ajax()) :
			$status = true;

			if($contact_type->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$contact_type->contacts()->update(['contact_type_id' => null]);
				$contact_type->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}
}