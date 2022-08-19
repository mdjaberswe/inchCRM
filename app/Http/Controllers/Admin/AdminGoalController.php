<?php

namespace App\Http\Controllers\Admin;

use App\Models\Goal;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminGoalController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:advanced.goal.view', ['only' => ['index', 'goalData']]);
		$this->middleware('admin:advanced.goal.create', ['only' => ['store']]);
		$this->middleware('admin:advanced.goal.edit', ['only' => ['edit', 'update']]);
		$this->middleware('admin:advanced.goal.delete', ['only' => ['destroy', 'bulkDestroy']]);
	}



	public function index()
	{
		$page = ['title' => 'Goals List', 'item' => 'Goal', 'field' => 'goals', 'view' => 'admin.goal', 'route' => 'admin.advanced-goal', 'permission' => 'advanced.goal', 'modal_size' => 'large', 'mass_update_permit' => permit('mass_update.goal'), 'mass_del_permit' => permit('mass_delete.goal')];
		$table = ['thead' => [['GOAL', 'style' => 'min-width: 160px'], ['GOAL OWNER', 'style' => 'min-width: 185px'], ['LEADS', 'data_class' => 'center'], ['ACCOUNTS', 'data_class' => 'center'], ['DEALS', 'data_class' => 'center'], ['SALES', 'data_class' => 'center', 'style' => 'min-width: 130px'], ['PROGRESS', 'style' => 'min-width: 90px'], 'DATE'], 'checkbox' => Goal::allowMassAction(), 'action' => Goal::allowAction()];
		$table['json_columns'] = table_json_columns(['checkbox', 'name', 'goal_owner', 'leads_count', 'accounts_count', 'deals_count', 'sales_amount', 'progress', 'date', 'action'], Goal::hideColumns());

		return view('admin.goal.index', compact('page', 'table'));
	}



	public function goalData(Request $request)
	{
		if($request->ajax()) :
			$goals = Goal::latest('id')->get();
			return DatatablesManager::goalData($goals, $request);
		endif;
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$notification = null;
			$data = $request->all();
			$validation = Goal::validate($data);

			if($validation->passes()) :
				$goal = new Goal;
				$goal->goal_owner = null_if_empty($request->goal_owner);
				$goal->name = $request->name;
				$goal->start_date = $request->start_date;
				$goal->end_date = $request->end_date;
				$goal->leads_count = null_if_empty($request->leads_count);
				$goal->accounts_count = null_if_empty($request->accounts_count);
				$goal->deals_count = null_if_empty($request->deals_count);
				$goal->currency_id = $request->currency_id;
				$goal->sales_amount = null_if_empty($request->sales_amount);
				$goal->description = null_if_empty($request->description);
				$goal->save();

				$notification = notification_log('goal_created', 'goal', $goal->id, 'staff', $request->goal_owner);
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'notification' => $notification]);
		endif;
	}



	public function show(Goal $goal)
	{

	}	



	public function edit(Request $request, Goal $goal)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($goal) && isset($request->id)) :
				if($goal->id == $request->id) :
					$info = $goal;
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info]);
		endif;

		return redirect()->route('admin.advanced-goal.index');
	}



	public function update(Request $request, Goal $goal)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($goal) && isset($request->id) && $goal->id == $request->id) :
				$validation = Goal::validate($data);
				if($validation->passes()) :
					$goal->goal_owner = null_if_empty($request->goal_owner);
					$goal->name = $request->name;
					$goal->start_date = $request->start_date;
					$goal->end_date = $request->end_date;
					$goal->leads_count = null_if_empty($request->leads_count);
					$goal->accounts_count = null_if_empty($request->accounts_count);
					$goal->deals_count = null_if_empty($request->deals_count);
					$goal->currency_id = $request->currency_id;
					$goal->sales_amount = null_if_empty($request->sales_amount);
					$goal->description = null_if_empty($request->description);
					$goal->save();
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



	public function destroy(Request $request, Goal $goal)
	{
		if($request->ajax()) :
			$status = true;

			if($goal->id != $request->id) :
				$status = false;
			endif;

			if($status == true) :
				$goal->delete();
			endif;	
			
			return response()->json(['status' => $status]);
		endif;	
	}



	public function bulkDestroy(Request $request)
	{
		if($request->ajax()) :
			$goals = $request->goals;

			$status = true;

			if(isset($goals) && count($goals) > 0) :
				Goal::whereIn('id', $goals)->delete();
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status]);
		endif;
	}
}