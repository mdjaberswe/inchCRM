<?php

namespace App\Models;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
	public $timestamps = false;

	protected static $initial_modules_permissions = ['module.dashboard'	=> ['module.dashboard'],
													 'module.report'	=> ['report.campaign', 'report.lead', 'report.account', 'report.project', 'report.sale', 'report.expense', 'report.expense_vs_income'],											
													 'module.lead'		=> ['lead.view'],						
													 'module.account'	=> ['account.view'],	
													 'module.project'	=> ['project.view'],
													 'module.task' 		=> ['task.view'],
													 'module.campaign'	=> ['campaign.view'],
													 'module.deal'		=> ['deal.view'],
													 'module.sale'		=> ['sale.estimate.view', 'sale.invoice.view', 'sale.item.view', 'sale.sales_funnel'],
													 'module.finance'=> ['finance.payment.view', 'finance.expense.view'],
													 'module.advanced'	=> ['advanced.goal.view', 'advanced.calendar.view', 'advanced.activity_log.view'],
													 'module.settings'	=> ['settings.system', 'settings.email', 'settings.SMTP', 'settings.payment'],
													 'module.custom_dropdowns'=> ['custom_dropdowns.lead_stage.view', 'custom_dropdowns.source.view', 'custom_dropdowns.account_type.view', 'custom_dropdowns.campaign_type.view', 'custom_dropdowns.deal_stage.view', 'custom_dropdowns.deal_type.view', 'custom_dropdowns.expense_category.view'],
													 'module.administration'=> ['administration.manage_media', 'administration.database_backup'],
													 'module.user'		=> ['user.view'],
													 'module.role'		=> ['role.view']];

	protected static $initial_permissions_route = ['module.dashboard'	=> 'admin.user.index',												   										
												   'lead.view'			=> 'admin.lead.index',						
												   'account.view' 		=> 'admin.account.index',	
												   'project.view'		=> 'admin.project.index',
												   'task.view'			=> 'admin.task.index',
												   'campaign.view'		=> 'admin.campaign.index',
												   'deal.view'			=> 'admin.deal.index',
												   'sale.estimate.view' => 'admin.sale-estimate.index',
												   'sale.invoice.view'	=> 'admin.sale-invoice.index',
												   'sale.item.view'		=> 'admin.sale-item.index',
												   'sale.sales_funnel'	=> 'admin.sale-item.index',
												   'user.view'			=> 'admin.user.index',
												   'role.view'			=> 'admin.role.index',
												   'settings.system'	=> 'admin.user.index',
												   'settings.email'		=> 'admin.user.index',
												   'settings.SMTP'		=> 'admin.user.index',
												   'settings.payment'	=> 'admin.user.index',
												   'report.campaign'	=> 'admin.user.index',
												   'report.lead'		=> 'admin.user.index',
												   'report.account'		=> 'admin.user.index',
												   'report.project'		=> 'admin.user.index',
												   'report.sale'		=> 'admin.user.index',
												   'report.expense'		=> 'admin.user.index',
												   'report.expense_vs_income'	=> 'admin.user.index',	
												   'finance.payment.view'	=> 'admin.finance-payment.index',
												   'finance.expense.view'	=> 'admin.finance-expense.index',
												   'advanced.goal.view'			=> 'admin.advanced-goal.index',
												   'advanced.activity_log.view'	=> 'admin.advanced-activity-log.index',
												   'administration.manage_media'	=> 'admin.user.index',
												   'administration.database_backup'	=> 'admin.user.index',
												   'custom_dropdowns.lead_stage.view'		=> 'admin.administration-dropdown-leadstage.index',
												   'custom_dropdowns.source.view'		=> 'admin.administration-dropdown-source.index',
												   'custom_dropdowns.account_type.view'	=> 'admin.administration-dropdown-accounttype.index',
												   'custom_dropdowns.campaign_type.view'	=> 'admin.administration-dropdown-campaigntype.index',
												   'custom_dropdowns.deal_stage.view'		=> 'admin.administration-dropdown-dealstage.index',
												   'custom_dropdowns.deal_type.view'		=> 'admin.administration-dropdown-dealtype.index',
												   'custom_dropdowns.expense_category.view' => 'admin.administration-dropdown-expensecategory.index']; 
		
	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeModule($query)
	{
		$query->where('name', 'LIKE', '%module%');
	}

	public function scopeNonModule($query)
	{
		$query->where('name', 'NOT LIKE', '%module%');
	}

	public function scopeIdentifier($query, $identifier)
	{
		$query->where('name', 'LIKE', $identifier . '%');
	}

	public function scopeOnlyGeneral($query)
	{
		return $query->whereLabel('general');
	}

	public function scopeOnlyClient($query)
	{
		return $query->whereLabel('client');
	}

	public function scopeNotPreserve($query)
	{
		return $query->where('type', '!=', 'preserve');
	}

	/*
	|--------------------------------------------------------------------------
	| FUNCTIONS
	|--------------------------------------------------------------------------
	*/
	public static function getPermissionsGroups($role = null, $preserve = true)
	{
		$permissions_groups = [];

		$groups = self::groupBy('group')->onlyGeneral()->pluck('group');
		
		foreach($groups as $group) :
			$permissions_groups[$group]['name'] = $group;
			$permissions_groups[$group]['display_name'] = snake_to_ucwords($group);

			if($preserve == false) :
				$modules = self::whereGroup($group)->module()->onlyGeneral()->select(['id', 'name', 'display_name'])->orderBy('id')->get();
			else :
				$modules = self::whereGroup($group)->module()->onlyGeneral()->notPreserve()->select(['id', 'name', 'display_name'])->orderBy('id')->get();
			endif;	

			$module_permissions = self::modulesPermissionsMap($modules, $role, $preserve);
			$all_checked = false;
			$has_all_permissions = array_unique($module_permissions['has_permission']);
			if(count($has_all_permissions) == 1 && end($has_all_permissions) == true) :
				$all_checked = true;
			endif;

			$permissions_groups[$group]['modules'] = $modules;
			$permissions_groups[$group]['module_permissions'] = $module_permissions;
			$permissions_groups[$group]['all_checked'] = $all_checked;			
		endforeach;	

		return $permissions_groups;
	}

	public static function modulesPermissionsMap($modules, $role = null, $preserve = true)
	{
		$modules_permissions_map = [];

		foreach($modules as $module) :
			$identifier = module_identifier($module->name);
			if($preserve == false) :
				$permissions = self::identifier($identifier)->nonModule()->onlyGeneral()->select('id', 'name', 'display_name', 'type')->orderBy('id')->get();
			else :
				$permissions = self::identifier($identifier)->nonModule()->onlyGeneral()->notPreserve()->select('id', 'name', 'display_name', 'type')->orderBy('id')->get();
			endif;			
			$modules_permissions_map[$module->name] = [];
			$modules_permissions_map['has_permission'][$module->name] = self::hasPermission($role, $module->id);
			foreach($permissions as $permission) :
				$map_key = permissions_map_key($permission->name);
				$modules_permissions_map[$module->name][$map_key][] = ['id' => $permission->id, 'name' => $permission->name, 'display_name' => $permission->display_name, 'type' => $permission->type, 'has_permission' => self::hasPermission($role, $permission->id)];
			endforeach;
		endforeach;

		return $modules_permissions_map;
	}

	public static function hasPermission($role = null, $permission_id)
	{
		$outcome = false;

		if($role != null) :
			$has_permission = $role->permissions()->wherePermission_id($permission_id)->count();
			if($has_permission > 0) :
				$outcome = true;
			endif;
		endif;

		return $outcome;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsToMany
	public function roles()
	{
		return $this->belongsToMany(Role::class)->withTimestamps();
	}
}