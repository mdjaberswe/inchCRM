<?php

namespace App\Http\Controllers\Admin;

use Session;
use App\Models\AttachFile;
use App\Models\FilterView;
use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Controllers\HomeController;

class AdminBaseController extends HomeController
{
	protected $directory;
	protected $location;

	public function __construct()
	{
		parent::__construct();
		$this->middleware('auth.type:staff');
		$this->setUploadDirectoryLocation();
	}



	public function setUploadDirectoryLocation($type = null)
	{
		$this->directory = AttachFile::directoryRule($type);
		$this->location = str_replace('.', '/', $this->directory['location']) . '/';
	}



	public function dropdownList(Request $request)
	{
		if($request->ajax()) :
			$status = false;
			$error = null;
			$items = [];

			if(isset($request->source)) :
				$table = $request->source;
				$order_by = isset($request->orderby) ? $request->orderby : 'id';
				$items = \DB::table($table)->whereNull('deleted_at')->orderBy($order_by);
				if(\Schema::hasColumn($table, 'masked')) :
					$items = $items->whereMasked(0);
				endif;
				$items = $items->get(['id', 'name']);
				$status = true;
			endif;	
			
			return response()->json(['status' => $status, 'items' => $items, 'error' => $error]);
		endif;
		
		return redirect()->route('home');
	}



	public function dropdownAppendList(Request $request, $parent, $child)
	{
		if($request->ajax()) :
			$field = $request->field;
			$id = $request->id;
			$childs = morph_to_model($child)::where($field, $id)->get()->pluck('name', 'id');

			$status = false;
			$error = null;

			if(isset($childs)) :
				$status = true;
			else:
				$error = 'Record not found.';
			endif;
			
			return response()->json(['status' => $status, 'selectOptions' => $childs, 'error' => $error]);
		endif;
	}



	public function dropdownReorder(Request $request)
	{
		if($request->ajax()) :
			$status = false;
			$error = null;

			$position_number = implode('', $request->positions);
			if(isset($request->source) && isset($request->positions) && count($request->positions) && is_numeric($position_number)) :
				$table = $request->source;

				$ids_array = \DB::table($table)->whereNull('deleted_at');
				if(\Schema::hasColumn($table, 'masked')) :
					$ids_array = $ids_array->whereMasked(0);
				endif;
				$ids_array = $ids_array->pluck('id');
				$ids_array = array_map('strval', $ids_array);
				sort($ids_array);

				$positions = $request->positions;	
				sort($positions);

				if($ids_array == $positions) :
					$position = 1;
					foreach($request->positions as $position_id) :
						\DB::table($table)->where('id', $position_id)->update(['position' => $position]);
						$position++;
					endforeach;	

					$status = true;
				endif;
			endif;

			return response()->json(['status' => $status, 'error' => $error]);
		endif;
	}



	public function kanbanReorder(Request $request)
	{
		if($request->ajax()) :
			$data = $request->all();
			$kanban_morphs = ['lead', 'deal', 'task'];
			$status = false;
			$kanban_header = null;
			$kanban_count = [];
			$realtime = [];
			$errors = [];

			if(isset($request->source) && in_array($request->source, $kanban_morphs)) :
				$model = morph_to_model($request->source);
				$validation = $model::kanbanValidate($data);

				if($validation->passes()) :
					$kanban_item = $model::find($request->id);
					$field = $request->field;			
					$position = $model::getKanbanDescPosition($request->picked);

					if($request->source == 'deal') :
						if($kanban_item->deal_stage_id != $request->stage) :
							$new_stage = \App\Models\DealStage::find($request->stage);
							$kanban_item->probability = $new_stage->probability;
						endif;	
					endif;	

					if($request->source == 'task') :
						if($kanban_item->task_status_id != $request->stage) :
							$new_status = \App\Models\TaskStatus::find($request->stage);
							$kanban_item->completion_percentage = $new_status->completion_percentage;
						endif;	
					endif;	
					
					$kanban_item->position = $position;
					$kanban_item->$field = $request->stage;
					$kanban_item->update();

					if(method_exists($model, 'getKanbanStageHeaderInfo')) :
						$kanban_header = $model::getKanbanStageHeaderInfo();
					endif;

					$kanban_count = count($kanban_header) ? $kanban_header['count'] : $model::getKanbanStageCount();

					if($request->source == 'deal') :
						$total_info = \App\Models\Deal::getTotalInfo();
						$realtime['total_deal'] = $total_info['total_deal'];
						$realtime['total_amount'] = $total_info['total_amount_html'];
						$realtime['revenue_forecast'] = $total_info['total_forecast_html'];
					endif;	

					$status = true;
				else :
					$messages = $validation->getMessageBag()->toArray();
					foreach($messages as $msg) :
						$errors[] = $msg;
					endforeach;	
				endif;	
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'kanbanCount' => $kanban_count, 'kanbanHeader' => $kanban_header, 'realtime' => $realtime]);
		endif;
	}



	public function image(Request $request, $img)
	{
		try
		{
			$decrypt_img_path = decrypt($img);
			$storage_img_path = storage_path($decrypt_img_path);
			$image = \Image::make($storage_img_path);
			return $image->response();
		}
		catch (DecryptException $e)
		{
			$image = \Image::make(public_path('img/placeholder.png'));
			return $image->response();
		}
	}



	public function viewContent(Request $request)
	{
		if($request->ajax()) :
			$content = view()->exists('admin.' . $request->viewContent) ? 'admin.' . $request->viewContent : $request->viewContent;
			$status = true;
			$html = null;
			$info = [];

			if(isset($request->viewContent) && view()->exists($content)) :
				$html = view($content, ['form' => $request->viewType])->render();

				if(isset($request->default) && $request->default != '') :
					$default_data = explode('|', $request->default);
					foreach($default_data as $single_data) :
						$field_val = explode(':', $single_data);
						$field = $field_val[0];
						$value = $field_val[1];
						$info[$field] = $value;
					endforeach;	
				endif;

				$info['show'] = [];
				$info['hide'] = [];

				if(isset($request->showField) && $request->showField != '') :
					$show_field = explode('|', $request->showField);
					foreach($show_field as $single_show) :
						$info['show'][] = $single_show;
					endforeach;	
				endif;	

				if(isset($request->hideField) && $request->hideField != '') :
					$hide_field = explode('|', $request->hideField);
					foreach($hide_field as $single_hide) :
						$info['hide'][] = $single_hide;
					endforeach;	
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'html' => $html, 'info' => $info]);
		endif;	
	}



	public function tabContent(Request $request, $module_name, $module_id, $tab)
	{
		if($request->ajax()) :		
			$module = morph_to_model($module_name)::find($module_id);
			if(isset($module) && isset($module->id) && $module->id == $request->id && isset($request->type) && $tab == $request->type && array_key_exists($tab, $module::informationTypes())) :
				return view('admin.' . $module_name . '.partials.tabs.tab-' . $tab, [$module_name => $module]);
			endif;

			return null;
		endif;
	}



	public function viewToggle(Request $request, $module_name)
	{
		if($request->ajax()) :
			if(isset($request->hide_details)) :
				Session::put($module_name . '_hide_details', $request->hide_details);
				return response()->json(['status' => Session::get($module_name . '_hide_details')]);
			endif;
		endif;
	}



	public function filterFormContent(Request $request, $module_name)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;
			$html = null;

			if(in_array($module_name, FilterView::getValidModuleList())) :
				$model = morph_to_model($module_name);
				$current_filter = FilterView::getCurrentFilter($module_name);
				$html = view('admin.' . $module_name . '.partials.filter-form', ['filter_fields_list' => $model::filterFieldDropDown(), 'condition_list' => get_field_conditions_list(false), 'dropdown' => $model::getFieldValueDropdownList(), 'current_filter' => $current_filter])->render();
				$info = $current_filter->param_val_array;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info, 'html' => $html]);
		endif;
	}



	public function filterFormPost(Request $request, $module_name)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$filter_count = null;
			$view_name = null;

			if(in_array($module_name, FilterView::getValidModuleList()) && !is_null($request->fields) && is_array($request->fields) && !is_null($request->conditions) && is_array($request->conditions) && !is_null($request->values) && is_array($request->values) && count($request->fields) == count($request->conditions)) :
				$model = morph_to_model($module_name);
				$validFieldsChecker = count(array_intersect($request->fields, $model::filterFieldList())) == count($request->fields);

				if($validFieldsChecker) :
					$filter_count = count($request->fields);
					$formatted_data = FilterView::getFormattedFieldParams($request);
					$save_db_format = $formatted_data['formated_params'];
					$data = $formatted_data['data'];

					$validation = $model::filterValidate($data);

					if($validation->passes()) :
						if(!isset($request->validationOnly)) :
							$current_filter = FilterView::getCurrentFilter($module_name);
							$current_filter_param = $current_filter->param_array;
							$view_name = $current_filter->custom_view_name;
							ksort($current_filter_param);
							ksort($save_db_format);

							if($current_filter_param != $save_db_format) :
								$auth_view = $current_filter->staffs()->where('staff_id', auth_staff()->id);
								$temp_params = ['temp_params' => json_encode($save_db_format)];
								$view_name = true;
								if($auth_view->get()->count()) :
									\DB::table('staff_view')
										->where('filter_view_id', $current_filter->id)
										->where('staff_id', auth_staff()->id)
										->update($temp_params);
								else :
									$current_filter->staffs()->attach([auth_staff()->id], $temp_params);
								endif;
							endif;							
						endif;	
					else :
						$status = false;
						$errors = $validation->getMessageBag()->toArray();
					endif;	
				else :
					$status = false;
				endif;	
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'module' => $module_name, 'customViewName' => $view_name, 'filterCount' => $filter_count]);
		endif;
	}



	public function viewStore(Request $request, $module_name)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$filter_count = null;
			$view_html = null;
			$action_html = null;

			if(in_array($module_name, FilterView::getValidModuleList())) :
				$data = $request->all();
				$model = morph_to_model($module_name);
				$validation = FilterView::viewValidate($data);

				if($validation->passes()) :
					$filter_view = new FilterView;
					$filter_view->view_name = $request->view_name;
					$filter_view->module_name = $request->module;
					$filter_view->visible_type = $request->visible_to;
					$filter_view->visible_to = ($request->visible_to == 'selected_users' && count($request->selected_users)) ? json_encode($request->selected_users) : null;

					if(isset($request->has_filter_data)) :
						$filter_count = count($request->fields);
						$formatted_data = FilterView::getFormattedFieldParams($request);
						$save_db_format = $formatted_data['formated_params'];
						ksort($save_db_format);
						$filter_view->filter_params = json_encode($save_db_format);						
					else :
						$current_filter = FilterView::getCurrentFilter($request->module);
						$filter_params = is_null($current_filter->filter_temp_params) ? $current_filter->filter_params : $current_filter->filter_temp_params;
						$filter_view->filter_params = $filter_params;
						$filter_count = json_decode($filter_params, true);
						$filter_count = count($filter_count);
					endif;	

					$filter_view->save();

					$detach_views = auth_staff()->views()->where('module_name', $request->module)->pluck('filter_views.id')->toArray();
					if(count($detach_views)) :
						auth_staff()->views()->detach($detach_views);
					endif;

					auth_staff()->views()->attach($filter_view->id);

					$view_html = $filter_view->option_html;
					$action_html = $filter_view->action_btns_html;
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;	
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'module' => $module_name, 'viewHtml' => $view_html, 'viewActionHtml' => $action_html, 'filterCount' => $filter_count]);
		endif;
	}



	public function viewEdit(Request $request, FilterView $view)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;

			if(isset($view) && isset($request->id)) :
				if($view->id == $request->id) :
					$info = $view->toArray();

					$info['show'] = [];
					if(!is_null($view->visible_to)) :
						$info['selected_users[]'] = json_decode($view->visible_to, true);
						$info['show'][] = 'selected_users[]';
					endif;

					$info['visible_to'] = $view->visible_type;
					$info['module'] = $view->module_name;							

					$info = (object)$info;
					$html = view('partials.modals.common-view-form', ['form' => 'edit'])->render();
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info, 'html' => $html]);
		endif;
	}



	public function viewUpdate(Request $request, FilterView $view)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$view_name = null;
			$data = $request->all();

			if(isset($view) && isset($request->id) && $view->id == $request->id) :
				$validation = FilterView::viewValidate($data);
				if($validation->passes() && $view->auth_can_edit) :
					$view->view_name = $view_name = $request->view_name;
					$view->visible_type = $request->visible_to;
					$view->visible_to = ($request->visible_to == 'selected_users' && count($request->selected_users)) ? json_encode($request->selected_users) : null;
					$view->update();		
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'viewId' => $request->id, 'viewName' => $view_name]);
		endif;
	}



	public function viewDropdown(Request $request, $filterview_id)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$filter_count = null;
			$action_html = null;
			$view_id = null;
			$module_name = $request->module;
			$view = FilterView::find($filterview_id);

			if(isset($view) && isset($request->id) && $view->id == $request->id && $view->auth_can_view) :
				$detach_views = auth_staff()->views()->where('module_name', $view->module_name)->pluck('filter_views.id')->toArray();
				if(count($detach_views)) :
					auth_staff()->views()->detach($detach_views);
				endif;
				auth_staff()->views()->attach($view->id);

				$filter_count = json_decode($view->filter_params, true);
				$filter_count = count($filter_count);
				$action_html = $view->action_btns_html;
				$view_id = $view->id;
				$module_name = $view->module_name;
			else :
				if(in_array($module_name, FilterView::getValidModuleList())) :
					$current_filter = FilterView::getCurrentFilter($module_name);
					$view_id = $current_filter->id;
				endif;	

				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'viewId' => $view_id, 'module' => $module_name, 'viewActionHtml' => $action_html, 'filterCount' => $filter_count]);
		endif;
	}



	public function viewDestroy(Request $request, FilterView $view)
	{
		if($request->ajax()) :
			$status = true;
			$deleted_view_id = null;
			$default_view_id = null;
			$filter_count = null;

			if($view->id != $request->id || $view->is_fixed || $view->is_default || !$view->auth_can_delete) :
				$status = false;
			endif;

			if($status == true) :
				$default_view = FilterView::where('module_name', $view->module_name)->where('is_fixed', 1)->where('is_default', 1)->first();
				$default_view_id = $default_view->id;
				$deleted_view_id = $view->id;	
				$filter_count = json_decode($default_view->filter_params, true);
				$filter_count = count($filter_count);
				$view->staffs()->detach();
				$view->delete();
			endif;
			
			return response()->json(['status' => $status, 'module' => $view->module_name, 'deletedViewId' => $deleted_view_id, 'defaultViewId' => $default_view_id, 'filterCount' => $filter_count]);
		endif;
	}
}