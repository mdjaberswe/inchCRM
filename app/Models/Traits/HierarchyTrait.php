<?php

namespace App\Models\Traits;

trait HierarchyTrait
{
	public function directParent()
	{
		return $this->belongsTo(self::class, 'parent_id');
	}

	public function directChilds()
	{
		return $this->hasMany(self::class, 'parent_id');
	}

	public function getChildCallNameAttribute()
	{
		return ucfirst($this->hierarchy_child);
	}

	public function getHasHierarchyAttribute()
	{
		return (!is_null($this->parent_id) || $this->directChilds->count());
	}

	public function getClosestParentIdAttribute()
	{
		if(!is_null($this->parent_id)) :
			$closest_parent = self::withTrashed()->find($this->parent_id);
			$closest_parent_status = isset($closest_parent) ? is_null($closest_parent->deleted_at) : true;

			while(!$closest_parent_status) :
				$closest_parent_status = true;

				if(!is_null($closest_parent->parent_id)) :
					$closest_parent = self::withTrashed()->find($closest_parent->parent_id);					

					if(isset($closest_parent)) :
						$closest_parent_status = is_null($closest_parent->deleted_at);	
					endif;	
				endif;	
			endwhile;

			if(isset($closest_parent) && is_null($closest_parent->deleted_at)) :
				return $closest_parent->id;
			endif;
		endif;

		return null;
	}

	public function getRootParentHierarchyAttribute()
	{
		if(!is_null($this->parent_id)) :
			$parent_id = $this->parent_id;
			$parent_hierarchy = [];

			while(!is_null($parent_id)) :				
				$closest_parent = self::withTrashed()->find($parent_id);
				$parent_id = null;

				if(isset($closest_parent)) :
					if(is_null($closest_parent->deleted_at)) :
						array_push($parent_hierarchy, $closest_parent->id);
					endif;					
					$parent_id = $closest_parent->parent_id;				
				endif;					
			endwhile;

			if(count($parent_hierarchy)) :
				return $parent_hierarchy;
			endif;	
		endif;

		return null;
	}

	public function getRootParentIdAttribute()
	{
		if(!is_null($this->root_parent_hierarchy)) :
			return last($this->root_parent_hierarchy);
		endif;

		return null;
	}

	public function getPyramidHierarchyAttribute()
	{
		if($this->directChilds->count()) :
			$parents = [$this];
			$pyramid = [];
			$go_down = true; 

			$row = 0;
			while($go_down) :
				$row++;

				$next_parents = [];
				foreach($parents as $parent) :
					$pyramid[$row][$parent->id] = $parent->directChilds()->get();
				
					foreach($parent->directChilds as $child) :
						if($child->directChilds->count()) :
							$next_parents[] = $child;
						endif;	
					endforeach;
				endforeach;
				$parents = $next_parents;

				$go_down = count($parents) ? true : false;
			endwhile;	

			return $pyramid;
		endif;

		return null;
	}

	public function getHierarchyZoneAttribute()
	{
		$zone = !is_null($this->pyramid_hierarchy) ? array_flatten($this->pyramid_hierarchy) : [];
		array_unshift($zone, $this);

		return collect($zone);
	}

	public function getHierarchyZoneIdsAttribute()
	{
		return $this->hierarchy_zone->pluck('id')->toArray();
	}

	public function getInvalidSelectChildAttribute()
	{
		$root_parents = is_null($this->root_parent_hierarchy) ? [] : $this->root_parent_hierarchy;
		$direct_childs = $this->directChilds->pluck('id')->toArray();
		$invalid_select_child_ids = array_merge([$this->id], $root_parents, $direct_childs);

		return $invalid_select_child_ids;
	}

	public function getHierarchyInfoAttribute()
	{
		$hierarchy = [];
		$pyramid_hierarchy = [];
		$total_node = 1;

		if(!is_null($this->pyramid_hierarchy)) :
			$prev_row_data = [];
			$down_to_top = array_reverse($this->pyramid_hierarchy);

			foreach($down_to_top as $row) :
				foreach($row as $parent_id => $childs) :
					$current_row_data = [];

					foreach($childs as $child) :
						$data = ['id' => $child->id, 'image' => $child->avatar, 'template' => $child->getHierarchyTemplateAttribute($this->id)];  
						$total_node++;

						if(array_key_exists($child->id, $prev_row_data)) :
							$data = $data + ['children' => $prev_row_data[$child->id]];
						endif;

						$current_row_data[] = $data;
						$prev_row_data[$parent_id][] = $data;
					endforeach;
				endforeach;

				$pyramid_hierarchy = $current_row_data;	 
			endforeach;	
		endif;	

		$this_data = ['id' => $this->id, 'image' => $this->avatar, 'template' => $this->getHierarchyTemplateAttribute($this->id, 'active', ['create', 'add'])];  					   				 

		if(count($pyramid_hierarchy)) :
			$this_data['children'] = $pyramid_hierarchy;
		endif;			 

		if(is_null($this->root_parent_hierarchy)) :
			$hierarchy = $this_data;		 
		else :
			$prev_data = null;
			foreach($this->root_parent_hierarchy as $key => $root_parent_id) :
				$root_parent = self::find($root_parent_id);
				$next_root = $key == 0 ? $this->id : null_if_not_key(($key - 1), $this->root_parent_hierarchy);
				$data = ['id' => $root_parent->id, 'image' => $root_parent->avatar, 'template' => $root_parent->getHierarchyTemplateAttribute($this->id, null, ['remove'], $next_root)]; 					   		 
				$total_node++;

				if($prev_data == null) :
					$prev_data = $data + ['children' => [$this_data]];
				else :
					$prev_data = $data + ['children' => [$prev_data]];	
				endif;	
			endforeach;	
			$hierarchy = $prev_data;			
		endif;

		$outcome = ['chart_format' => $hierarchy, 'total_node' => $total_node];

		return $outcome;
	}

	public function getHierarchyChartFormatAttribute()
	{
		return $this->hierarchy_info['chart_format'];
	}

	public function getTotalNodeAttribute()
	{
		return $this->hierarchy_info['total_node'];
	}

	public function getHierarchyTemplateAttribute($hierarchy_id = null, $node_class = null, $permission = null, $next_child = null)
	{
		$node_class = (!is_null($node_class) && is_null($this->parent_id)) ? 'root' : $node_class;
		$address_tooltip = '';
		$full_address = $this->getAddressAttribute(true, true);
		$short_address = str_limit($this->address, 15, '.');
		$address_tooltip_class = strlen($full_address) > 34 ? 'tooltip-lg' : '';
		if($full_address != $short_address) :
			$address_tooltip = "data-toggle='tooltip' data-placement='top' title='" . $full_address . "'";
		endif;	

		$create = "";
		if(is_null($permission) || in_array('create', $permission)) :
			$create = "<li><a class='add-new-common' data-item='$this->identifier' data-action='" . route("admin.$this->identifier.store") . "' data-content='$this->identifier.partials.form' data-default='$this->hierarchy_form_default|hierarchy_id:$hierarchy_id' save-new='false'><i class='mdi mdi-plus lg'></i> Create $this->child_call_name</a></li>";
		endif;
			
		$add = "";
		if(is_null($permission) || in_array('add', $permission)) :
			$add = "<li><a class='add-multiple' modal-title='Add $this->child_call_name to Parent $this->identifier_call_name' modal-sub-title='$this->name' modal-datatable='true' datatable-url='hierarchy-add-child/$this->identifier/$this->id' data-action='" . route('admin.hierarchy.store.child', [$this->identifier, $this->id]) . "' data-content='partials.modals.common-add-node' data-default='module_name:$this->identifier|module_id:$this->id|add_method:all' save-new='false'><i class='fa fa-sitemap'></i> Add $this->child_call_name</a></li>";
		endif;

		$change_parent = "";
		if(is_null($permission) || in_array('change_parent', $permission)) :
			$change_parent = "<li><a class='common-edit-btn' editid='$this->id' data-item='$this->identifier' data-default='hierarchy_id:$hierarchy_id' data-url='" . route('admin.hierarchy.edit.parent', [$this->identifier, $this->id]) . "' data-posturl='" . route('admin.hierarchy.update.parent', [$this->identifier, $this->id]) . "' modal-title='Change Parent' modal-sub-title='$this->name' modal-small='true'><i class='mdi mdi-call-split lg'></i> Change Parent</a></li>";
		endif;

		$remove = "";
		if(is_null($permission) || in_array('remove', $permission)) :
			$remove = "<li><a class='remove' data-nextchild='$next_child'><i class='mdi mdi-content-cut'></i> Remove</a></li>";
		endif;	

		$action_btns = $create . $add . $change_parent . $remove;

		$template = "<div class='node-content $node_class dropdown dark' data-hierarchy='$hierarchy_id'>
						<div class='node-img'>
							<img src='" . $this->getAvatarAttribute(true) . "'>
						</div>

						<div class='node-info $address_tooltip_class'>
							<h3 data-name='$this->name'><a href='" . $this->getShowRouteAttribute([$this->id, 'orgchart']) . "'>" . str_limit($this->name, 15, '.') . "</a></h3>
							<h5>" . str_limit(empty_property_checker($this->type, 'name'), 15, '.') . "</h5>
							<p><span $address_tooltip class='fa fa-map-marker'></span>" . $short_address . "</p>
						</div>

						<div class='node-btn'>
							<a class='dropdown-toggle' animation='fadeIn|fadeOut' data-toggle='dropdown' aria-expanded='false'><i class='mdi mdi-dots-horizontal pe-va'></i></a>
							<ul class='dropdown-menu up-caret near'>
								" . $action_btns . "								
							</ul>
						</div>
					</div>"; 

		return $template;			
	}

	public static function addChildValidate($data, $module)
	{
		$table = $data['module_name'] . 's';
		$rules = ["module_name"	=> "required|in:account,contact",
				  "module_id"	=> "required|exists:$table,id,deleted_at,NULL",
				  "add_method"	=> "required|in:one,all"];

		if(isset($module)) :
			$invalid_ids = implode(',', $module->invalid_select_child);
			$rules[$table] = "required|array|max:10|not_in:$invalid_ids|exists:$table,id,deleted_at,NULL";
		endif;

		return \Validator::make($data, $rules);
	}

	public static function nodeParentRemoveValidate($data)
	{
		$table = $data['module'] . 's';
		$rules = ["id" => "required|exists:$table,id,deleted_at,NULL",
				  "module" => "required|in:account,contact",
				  "orgchart" => "required|different:id|exists:$table,id,deleted_at,NULL",
				  "confirmation" => "required|in:one,all"];

		return \Validator::make($data, $rules);
	}


	public static function nodeParentUpdateValidate($data)
	{
		$table = $data['module_name'] . 's';
		$rules = ["module_id"	=> "required|exists:$table,id,deleted_at,NULL",
				  "module_name"	=> "required|in:account,contact",
				  "parent_id"	=> "different:module_id|exists:$table,id,deleted_at,NULL",
				  "hierarchy_id"=> "different:module_id|exists:$table,id,deleted_at,NULL"];

		return \Validator::make($data, $rules);
	}
}	