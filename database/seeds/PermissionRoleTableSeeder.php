<?php

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class PermissionRoleTableSeeder extends Seeder
{
	public function run()
	{
		\DB::table('permission_role')->truncate();

		$roles = Role::onlyGeneral()->get();
		$permission_count = Permission::onlyGeneral()->count();
		$preserved_permissions = Permission::onlyGeneral()->whereType('preserve')->get(['id'])->pluck('id')->toArray();

		foreach($roles as $role) :
			if($role->label == 'general') :
				switch($role->name) :
					case 'administrator' :
						$role->permissions()->attach(range(1, $permission_count));
					break;

					case 'standard' :			
						$role->permissions()->attach(array_diff(range(1, $permission_count), $preserved_permissions));
					break;

					default : $role->permissions()->attach(array_diff(range(1, $permission_count), $preserved_permissions));
				endswitch;
			endif;	
		endforeach;

		$client_roles = Role::onlyClient()->get();

		foreach($client_roles as $client_role) :
			$client_permission = Permission::onlyClient()->whereName($client_role->name)->first();
			$client_role->permissions()->attach($client_permission->id);
		endforeach;
	}
}