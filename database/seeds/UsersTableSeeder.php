<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
	public function run()
	{
		User::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		// Super Admin
		User::create(['email' => 'admin@demo.com', 'linked_id' => 1, 'linked_type' => 'staff', 'password' => bcrypt('123456'), 'last_login' => date('Y-m-d H:i:s')]);

		sleep(1);

		$users = [
			['email' => 'staff_11@demo.com', 'linked_id' => 2, 'linked_type' => 'staff', 'password' => bcrypt('secret'), 'last_login' => date('Y-m-d H:i:s'), 'created_at' => $save_date, 'updated_at' => $save_date],
			['email' => 'staff_13@demo.com', 'linked_id' => 3, 'linked_type' => 'staff', 'password' => bcrypt('secret'), 'last_login' => date('Y-m-d H:i:s'), 'created_at' => $save_date, 'updated_at' => $save_date],
			['email' => 'staff_15@demo.com', 'linked_id' => 4, 'linked_type' => 'staff', 'password' => bcrypt('secret'), 'last_login' => date('Y-m-d H:i:s'), 'created_at' => $save_date, 'updated_at' => $save_date],
			['email' => 'staff_17@demo.com', 'linked_id' => 5, 'linked_type' => 'staff', 'password' => bcrypt('secret'), 'last_login' => date('Y-m-d H:i:s'), 'created_at' => $save_date, 'updated_at' => $save_date],
			['email' => 'contact_11@demo.com', 'linked_id' => 1, 'linked_type' => 'contact', 'password' => bcrypt('secret'), 'last_login' => date('Y-m-d H:i:s'), 'created_at' => $save_date, 'updated_at' => $save_date],
			['email' => 'contact_12@demo.com', 'linked_id' => 2, 'linked_type' => 'contact', 'password' => bcrypt('secret'), 'last_login' => date('Y-m-d H:i:s'), 'created_at' => $save_date, 'updated_at' => $save_date],
			['email' => 'contact_13@demo.com', 'linked_id' => 3, 'linked_type' => 'contact', 'password' => bcrypt('secret'), 'last_login' => date('Y-m-d H:i:s'), 'created_at' => $save_date, 'updated_at' => $save_date],
			['email' => 'contact_14@demo.com', 'linked_id' => 4, 'linked_type' => 'contact', 'password' => bcrypt('secret'), 'last_login' => date('Y-m-d H:i:s'), 'created_at' => $save_date, 'updated_at' => $save_date],
			['email' => 'contact_15@demo.com', 'linked_id' => 5, 'linked_type' => 'contact', 'password' => bcrypt('secret'), 'last_login' => date('Y-m-d H:i:s'), 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		User::insert($users);
	}
}
