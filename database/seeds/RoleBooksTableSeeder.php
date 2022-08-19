<?php

use Illuminate\Database\Seeder;
use App\Models\RoleBook;

class RoleBooksTableSeeder extends Seeder
{
	public function run()
	{
		RoleBook::truncate();

		$save_date = date('Y-m-d H:i:s');

		$role_books = [
			['staff_id' => 1, 'role_id' => 11, 'linked_id' => 1, 'linked_type' => 'project', 'created_at' => $save_date, 'updated_at' => $save_date],
			['staff_id' => 2, 'role_id' => 11, 'linked_id' => 2, 'linked_type' => 'project', 'created_at' => $save_date, 'updated_at' => $save_date],
			['staff_id' => 3, 'role_id' => 11, 'linked_id' => 3, 'linked_type' => 'project', 'created_at' => $save_date, 'updated_at' => $save_date],
			['staff_id' => 4, 'role_id' => 11, 'linked_id' => 4, 'linked_type' => 'project', 'created_at' => $save_date, 'updated_at' => $save_date],
			['staff_id' => 5, 'role_id' => 11, 'linked_id' => 5, 'linked_type' => 'project', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		RoleBook::insert($role_books);
	}
}