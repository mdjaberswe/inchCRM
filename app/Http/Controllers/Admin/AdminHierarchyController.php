<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminHierarchyController extends AdminBaseController
{
	protected $hierarchy_modules;

	public function __construct()
	{
		parent::__construct();
		$this->hierarchy_modules = ['contact', 'account'];
	}



	public function addChild(Request $request, $module_name = null, $module_id = null)
	{
		if($request->ajax() && !is_null($module_name) && in_array($module_name, $this->hierarchy_modules) && !is_null($module_id)) :
			$model = morph_to_model($module_name);
			$module = $model::find($module_id);

			if(isset($module)) :
				$childs = $model::whereNotIn('id', $module->invalid_select_child)->orderBy('id')->get();
				return DatatablesManager::hierarchyAddChildData($childs, $request);
			endif;	

			return null;		
		endif;
	}



	public function storeChild(Request $request, $module_name, $module_id)
	{
		if($request->ajax()) :
			$status = false;
			$errors = null;
			$chart_refresh = false;
			$data = $request->all();

			if($module_id == $request->module_id && $module_name == $request->module_name && in_array($module_name, $this->hierarchy_modules)) :
				$model = morph_to_model($module_name);
				$module = $model::find($module_id);
				$validation = $model::addChildValidate($data, $module);

				if(isset($module) && $validation->passes()) :
					$status = true;
					$childs = $module_name . 's';

					if(count($request->$childs)) :
						if($request->add_method == 'all') :
							$model::whereIn('id', $request->$childs)->update(['parent_id' => $module->id]);
						else :
							foreach($request->$childs as $child_id) :
								$child = $model::find($child_id);

								if($child->directChilds->count()) :
									$child->directChilds()->update(['parent_id' => $child->closest_parent_id]);
								endif;	

								$child->update(['parent_id' => $module->id]);
							endforeach;	
						endif;

						$chart_refresh = true;
					endif;	
				else :
					$errors = $validation->getMessageBag()->toArray();				
				endif;	
			else :
				$errors['module_name'] = 'Invalid hierarchy module';	
			endif;	

			return response()->json(['status' => $status, 'errors' => $errors, 'orgChartRefresh' => $chart_refresh, 'module' => $module_name]);
		endif;
	}


	public function removeChild(Request $request)
	{
		if($request->ajax()) :
			$data = $request->all();
			$status = false;
			$remove_all = true;
			$orgchart_ui_id = null;
			$total_node = null;
			$errors = null;

			if(isset($request->id) && isset($request->orgchart) && in_array($request->module, $this->hierarchy_modules)) :
				$model = morph_to_model($request->module);
				$validation = $model::nodeParentRemoveValidate($data);

				if($validation->passes()) :
					$node = $model::find($request->id);
					$orgchart = $model::find($request->orgchart);

					// children pyramid hierarchy
					if(is_array($node->root_parent_hierarchy) && in_array($orgchart->id, $node->root_parent_hierarchy)) :
						if($request->confirmation != 'all' && !is_null($node->closest_parent_id) && $node->directChilds->count()) :
							$remove_all = false;
							$node->directChilds()->update(['parent_id' => $node->closest_parent_id]);
						endif;						
					// root parent hierarchy
					elseif(is_array($orgchart->root_parent_hierarchy) && in_array($node->id, $orgchart->root_parent_hierarchy)) :
						$root_parent_key = array_search($node->id, $orgchart->root_parent_hierarchy);
						$next_root_id = $root_parent_key == 0 ? $orgchart->id : $orgchart->root_parent_hierarchy[$root_parent_key - 1];
						$model::whereId($next_root_id)->update(['parent_id' => $node->closest_parent_id]);
						$remove_all = false;
					endif;	

					$node->update(['parent_id' => null]);
					$total_node = $orgchart->total_node;
					$orgchart_ui_id = $request->module . '-hierarchy-' . $orgchart->id;
					$status = true;
				else :
					$errors = $validation->getMessageBag()->toArray();
				endif;	
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'removeAll' => $remove_all, 'orgchartId' => $orgchart_ui_id, 'totalNode' => $total_node, 'id' => $request->id]);
		endif;
	}



	public function editParent(Request $request, $module_name, $module_id)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;
			$html = null;

			if($request->ajax() && !is_null($module_name) && in_array($module_name, $this->hierarchy_modules) && !is_null($module_id)) :
				$model = morph_to_model($module_name);
				$module = $model::find($module_id);

				if(isset($module)) :
					$info = $module->toArray();
					$default_data = get_data_from_attribute($request->default);
					$info['module_id'] = $module_id;
					$info['module_name'] = $module_name;
					$info['selectlist'] = [];
					$info['selectlist']['parent_id'] = $model::where('id', '!=', $module->id)->get()->pluck('name', 'id')->toArray();					
					$info = array_merge($info, $default_data);
					$info = (object)$info;
					$html = view('partials.modals.common-parent-node', ['module_name' => $module_name, 'have_child' => $module->directChilds->count()])->render();
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'info' => $info, 'html' => $html]);
		endif;
	}



	public function updateParent(Request $request, $module_name, $module_id)
	{
		if($request->ajax()) :
			$status = false;
			$errors = null;
			$parent = null;
			$msg = [];
			$chart_refresh = false;
			$data = $request->all();

			if($module_id == $request->module_id && $module_name == $request->module_name && in_array($module_name, $this->hierarchy_modules)) :
				$model = morph_to_model($module_name);
				$module = $model::find($module_id);
				$validation = $model::nodeParentUpdateValidate($data, $module);

				if(isset($module) && $validation->passes()) :
					if(not_null_empty($request->parent_id)) :
						$parent = $model::find($request->parent_id);
						if($parent->parent_id == $module->id) :
							$parent->update(['parent_id' => $module->parent_id]);
						endif;	
					endif;		

					if(!isset($request->confirmation) && $module->directChilds->count()) :
						$module->directChilds()->update(['parent_id' => $module->closest_parent_id]);
					endif;

					$module->update(['parent_id' => $request->parent_id]);

					$hierarchy = $model::find($request->hierarchy_id);
					if(!in_array($request->parent_id, $hierarchy->hierarchy_zone_ids))
					{						
						$moved_msg = "The node has moved ";
						$moved_msg .= isset($parent) ? "to <a href='$parent->show_route/orgchart'>$parent->name</a> hierarchy." : "away from the hierarchy.";
						$msg[] = $moved_msg;
					}

					$chart_refresh = true;
					$status = true;
				else :
					$errors = $validation->getMessageBag()->toArray();				
				endif;	
			else :
				$errors['module_name'] = 'Invalid hierarchy module';	
			endif;	

			return response()->json(['status' => $status, 'errors' => $errors, 'orgChartRefresh' => $chart_refresh, 'module' => $module_name, 'notifyMsgs' => $msg]);
		endif;
	}
}	