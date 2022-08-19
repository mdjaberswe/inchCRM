<?php

namespace App\Http\Controllers\Admin;

use App\Models\Call;
use App\Models\Activity;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminCallController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:task.view', ['only' => ['connectedCallData']]);
		$this->middleware('admin:task.create', ['only' => ['store']]);
		$this->middleware('admin:task.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:task.delete', ['only' => ['destroy']]);
	}



	public function callData(Request $request, $module_name, $module_id)
	{
		if($request->ajax()) :
			$module = morph_to_model($module_name)::find($module_id);
			if(isset($module)) :
				$calls = $module->calls()->latest('id')->get();
				return DatatablesManager::callData($calls, $request);
			endif;
			
			return null;	
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = Call::validate($data);

			if($validation->passes()) :
				$call = new Call;
				$call->subject = $request->subject;				
				$call->client_id = $request->client_id;	
				$call->client_type = $request->client_type;	
				$call->call_time = ampm_to_sql_datetime($request->call_time);
				$call->description = null_if_empty($request->description);
				$call->type = $request->type;

				if($request->related_type !== '') :
					$call->related_id = $request->related_id;
					$call->related_type = $request->related_type;
				endif;	

				$call->save();

				Activity::create(['linked_id' => $call->id, 'linked_type' => 'call']);
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function show(Request $request, Call $call)
	{
		
	}



	public function edit(Request $request, Call $call)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;
			$html = null;

			if(isset($call) && isset($request->id) && $call->id == $request->id) :
				$info = $call->toArray();

				$info['show'] = [];

				$client_field = $info['client_type'] . '_id';						
				$info[$client_field] = $info['client_id'];	
				$info['show'][] = $client_field;
			
				if(!is_null($info['related_type'])) :
					$related_field = $info['related_type'] . '_id';						
					$info[$related_field] = $info['related_id'];	
					$info['show'][] = $related_field;
				endif;

				$info = (object)$info; 

				if(isset($request->html)) :
					$html = view('admin.call.partials.form', ['form' => 'edit'])->render();
				endif;	
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info, 'html' => $html]);
		endif;
	}



	public function update(Request $request, Call $call)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($call) && isset($request->id) && $call->id == $request->id) :
				$validation = Call::validate($data);
				if($validation->passes()) :
					$call->subject = $request->subject;
					$call->client_id = $request->client_id;	
					$call->client_type = $request->client_type;	
					$call->call_time = ampm_to_sql_datetime($request->call_time);
					$call->description = null_if_empty($request->description);
					$call->type = $request->type;

					if($request->related_type == '') :
						$call->related_id = null;
						$call->related_type = null;
					else :							
						$call->related_id = $request->related_id;
						$call->related_type = $request->related_type;
					endif;	

					$call->update();
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



	public function destroy(Request $request, Call $call)
	{
		if($request->ajax()) :
			$status = true;

			if($call->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$call->activity->delete();
				$call->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;
	}
}