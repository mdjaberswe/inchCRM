<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class RoleUserTableSeeder extends Seeder
{
	public function run()
	{
		\DB::table('role_user')->truncate();

		$users = User::onlyStaff()->get();

		foreach($users as $user) :
			if($user->id <= 3) :
				$user->roles()->attach([1]);
			else :
				$user->roles()->attach([rand(2, 9)]);
			endif;
		endforeach;

		$clients = User::onlyContact()->get();
		$default = Role::getClientDefaultIds();

		foreach($clients as $client) :
			$client->roles()->attach($default);
		endforeach;
	}
}
