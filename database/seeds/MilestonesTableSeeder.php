<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Milestone;

class MilestonesTableSeeder extends Seeder
{
	public function run()
	{
		Milestone::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$milestones = [
			['project_id' => 1, 'name' => '1st project 1st milestone', 'description' => '1st phase', 'start_date' => date('Y-m-d H:i:s'), 'end_date' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'completion_percentage' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['project_id' => 2, 'name' => '2nd project 1st milestone', 'description' => '1st phase', 'start_date' => date('Y-m-d H:i:s'), 'end_date' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'completion_percentage' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['project_id' => 3, 'name' => '3rd project 1st milestone', 'description' => '1st phase', 'start_date' => date('Y-m-d H:i:s'), 'end_date' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'completion_percentage' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['project_id' => 4, 'name' => '4th project 1st milestone', 'description' => '1st phase', 'start_date' => date('Y-m-d H:i:s'), 'end_date' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'completion_percentage' => 100, 'created_at' => $save_date, 'updated_at' => $save_date],
			['project_id' => 5, 'name' => '5th project 1st milestone', 'description' => '1st phase', 'start_date' => date('Y-m-d H:i:s'), 'end_date' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'completion_percentage' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
		];

		Milestone::insert($milestones);
	}
}