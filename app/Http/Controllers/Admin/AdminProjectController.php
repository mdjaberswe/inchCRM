<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminProjectController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:project.view', ['only' => ['index', 'projectData', 'show']]);
		$this->middleware('admin:project.create', ['only' => ['store']]);
		$this->middleware('admin:project.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:project.delete', ['only' => ['destroy', 'bulkDestroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Projects List', 'item' => 'Project', 'field' => 'projects', 'view' => 'admin.project', 'route' => 'admin.project', 'permission' => 'project', 'bulk' => 'update', 'mass_update_permit' => permit('mass_update.project'), 'mass_del_permit' => permit('mass_delete.project')];
		$table = ['thead' => [['PROJECT NAME', 'style' => 'min-width: 170px'], ['PROGRESS', 'data_class' => 'center narrow', 'style' => 'max-width: 67px'], ['TASKS', 'style' => 'max-width: 80px', 'data_class' => 'center'], ['MILESTONES', 'style' => 'max-width: 80px', 'data_class' => 'center'], ['ISSUES', 'style' => 'max-width: 80px', 'data_class' => 'center'], ['START&nbsp;DATE', 'style' => 'min-width: 80px'], ['END&nbsp;DATE', 'style' => 'min-width: 80px'], ['OWNER', 'style' => 'min-width: 130px']], 'checkbox' => Project::allowMassAction(), 'action' => Project::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'name', 'completion_percentage', 'tasks', 'milestones', 'issues', 'start_date', 'end_date', 'project_owner', 'action'], Project::hideColumns());

		return view('admin.project.index', compact('page', 'table'));
	}



	public function projectData(Request $request)
	{
		if($request->ajax()) :
			$projects = Project::latest('id')->get();
			return DatatablesManager::projectData($projects, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$notification = null;
			$data = $request->all();
			$validation = Project::validate($data);

			if($validation->passes()) :
				$project = new Project;
				$project->name = $request->name;
				$project->account_id = $request->account_id;
				$project->deal_id = null_if_empty($request->deal_id);
				$project->project_owner = $request->project_owner;
				$project->status = $request->status;
				$project->start_date = null_if_empty($request->start_date);
				$project->end_date = null_if_empty($request->end_date);
				$project->access = $request->access;
				$project->description = $request->description;
				$project->save();

				if(count($request->contact_id)) :
					$project->contacts()->attach($request->contact_id);
				endif;	

				$notification = notification_log('project_created', 'project', $project->id, 'staff', $request->project_owner);
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'notification' => $notification]);
		endif;
	}



	public function show(Project $project)
	{
		$page = ['title' => $project->name, 'item_title' => breadcrumbs_render('admin.project.index:Projects|' . $project->name)];
		return view('admin.project.show', compact('page', 'project'));
	}



	public function edit(Request $request, Project $project)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;
			$html = null;

			if(isset($project) && isset($request->id)) :
				if($project->id == $request->id) :
					$info = $project->toArray();
					$info['contact_id[]'] = $project->contact_id_list;
					$info = (object)$info;

					if(isset($request->html)) :
						$html = view('admin.project.partials.form', ['form' => 'edit'])->render();
					endif;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info, 'html' => $html]);
		endif;

		return redirect()->route('admin.project.index');
	}



	public function update(Request $request, Project $project)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($project) && isset($request->id) && $project->id == $request->id) :
				$validation = Project::validate($data);
				if($validation->passes()) :
					$project->name = $request->name;
					$project->account_id = $request->account_id;
					$project->deal_id = null_if_empty($request->deal_id);
					$project->project_owner = $request->project_owner;
					$project->status = $request->status;
					$project->start_date = null_if_empty($request->start_date);
					$project->end_date = null_if_empty($request->end_date);
					$project->access = $request->access;
					$project->description = $request->description;
					$project->save();

					if(count($request->contact_id)) :
						$project->contacts()->sync($request->contact_id);
					else :
						$project->contacts()->detach();	
					endif;	
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



	public function destroy(Request $request, Project $project)
	{
		if($request->ajax()) :
			$status = true;

			if($project->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$project->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;
	}



	public function bulkDestroy(Request $request)
	{
		if($request->ajax()) :
			$projects = $request->projects;

			$status = true;

			if(isset($projects) && count($projects) > 0) :
				Project::whereIn('id', $projects)->delete();
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status]);
		endif;
	}



	public function connectedProjectData(Request $request, $module_name, $module_id)
	{
		if($request->ajax()) :
			$module = morph_to_model($module_name)::find($module_id);
			if(isset($module)) :
				$deals = $module->projects()->latest('id')->get();
				return DatatablesManager::connectedProjectData($deals, $request);
			endif;
			
			return null;	
		endif;
	}
}

