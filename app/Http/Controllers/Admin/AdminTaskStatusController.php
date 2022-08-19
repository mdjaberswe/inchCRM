<?php

namespace App\Http\Controllers\Admin;

use App\Models\TaskStatus;
use App\Jobs\SyncTaskFilterFixedView;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminTaskStatusController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:custom_dropdowns.task_status.view', ['only' => ['index', 'taskstatusData']]);
		$this->middleware('admin:custom_dropdowns.task_status.create', ['only' => ['store']]);
		$this->middleware('admin:custom_dropdowns.task_status.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:custom_dropdowns.task_status.delete', ['only' => ['destroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Task Status List', 'item' => 'Task Status', 'field' => 'task_status', 'view' => 'admin.taskstatus', 'route' => 'admin.administration-dropdown-taskstatus', 'plain_route' => 'admin.taskstatus', 'permission' => 'custom_dropdowns.task_status', 'subnav' => 'custom-dropdown', 'modal_bulk_delete' => false, 'modal_size' => 'medium', 'save_and_new' => false];
		$table = TaskStatus::getTableFormat();
		$reset_position = TaskStatus::resetPosition();

		return view('admin.taskstatus.index', compact('page', 'table', 'reset_position'));
	}



	public function taskstatusData(Request $request)
	{
		if($request->ajax()) :
			return TaskStatus::getTableData($request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();
			$validation = TaskStatus::validate($data);
			$picked_position_id = $request->position;

			if($validation->passes()) :				
				$position_val = TaskStatus::getTargetPositionVal($picked_position_id);

				$task_status = new TaskStatus;
				$task_status->name = $request->name;
				$task_status->description = null_if_empty($request->description);
				$task_status->position = $position_val;
				$task_status->completion_percentage = ($request->category == 'open') ? $request->completion_percentage : 100;
				$task_status->category = $request->category;
				$task_status->save();

				dispatch(new SyncTaskFilterFixedView);			
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function edit(Request $request, TaskStatus $task_status)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($task_status) && isset($request->id)) :
				if($task_status->id == $request->id) :
					$info = $task_status->toArray();
					$info['position'] = $task_status->prev_position_id;

					$info['freeze'] = [];

					if($task_status->fixed) :
						$info['freeze'][] = 'category';
					endif;	

					$info = (object)$info;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.administration-dropdown-taskstatus.index');
	}



	public function update(Request $request, TaskStatus $task_status)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($task_status) && isset($request->id) && $task_status->id == $request->id) :
				$validation = TaskStatus::validate($data, $task_status);
				$picked_position_id = $request->position;

				if($validation->passes()) :					
					$position_val = TaskStatus::getTargetPositionVal($picked_position_id, $task_status->id);
					$category = $task_status->category;

					$task_status->name = $request->name;
					$task_status->description = null_if_empty($request->description);
					$task_status->position = $position_val;

					if(!$task_status->fixed) :
						$task_status->category = $request->category;
						$category = $request->category;
					endif;
						
					$task_status->completion_percentage = ($category == 'open') ? $request->completion_percentage : 100;	
					$task_status->update();

					dispatch(new SyncTaskFilterFixedView);
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



	public function destroy(Request $request, TaskStatus $task_status)
	{
		if($request->ajax()) :
			$status = true;

			if($task_status->id != $request->id || $task_status->fixed) :
				$status = false;
			endif;

			if($status == true) :
				$lower_status = TaskStatus::whereCategory($task_status->category)->where('id', '!=', $task_status->id)->where('position', '<', $task_status->position);
				
				if($lower_status->count()) :
					$replace_status_id = $lower_status->latest('position')->first()->id;
				else :
					$replace_status_id = TaskStatus::whereCategory($task_status->category)->where('id', '!=', $task_status->id)->orderBy('position')->first()->id;
				endif;

				$task_status->tasks()->update(['task_status_id' => $replace_status_id]);
				$task_status->delete();

				dispatch(new SyncTaskFilterFixedView);	
			endif;	
			
			return response()->json(['status' => $status]);
		endif;		
	}
}