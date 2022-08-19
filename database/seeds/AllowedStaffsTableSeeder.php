<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\AllowedStaff;

class AllowedStaffsTableSeeder extends Seeder
{
	public function run()
	{
		AllowedStaff::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$allowed_staffs = [
			['staff_id' => 1, 'linked_id' => 9, 'linked_type' => 'lead', 'created_at' => $save_date, 'updated_at' => $save_date],
			['staff_id' => 1, 'linked_id' => 8, 'linked_type' => 'lead', 'created_at' => $save_date, 'updated_at' => $save_date],
			['staff_id' => 2, 'linked_id' => 7, 'linked_type' => 'lead', 'created_at' => $save_date, 'updated_at' => $save_date],
			['staff_id' => 2, 'linked_id' => 6, 'linked_type' => 'lead', 'created_at' => $save_date, 'updated_at' => $save_date],
			['staff_id' => 3, 'linked_id' => 1, 'linked_type' => 'lead', 'created_at' => $save_date, 'updated_at' => $save_date],
			['staff_id' => 3, 'linked_id' => 2, 'linked_type' => 'lead', 'created_at' => $save_date, 'updated_at' => $save_date],
			['staff_id' => 4, 'linked_id' => 3, 'linked_type' => 'lead', 'created_at' => $save_date, 'updated_at' => $save_date],
			['staff_id' => 4, 'linked_id' => 4, 'linked_type' => 'lead', 'created_at' => $save_date, 'updated_at' => $save_date],
			['staff_id' => 5, 'linked_id' => 5, 'linked_type' => 'lead', 'created_at' => $save_date, 'updated_at' => $save_date],
			['staff_id' => 5, 'linked_id' => 6, 'linked_type' => 'lead', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		AllowedStaff::insert($allowed_staffs);
	}
}