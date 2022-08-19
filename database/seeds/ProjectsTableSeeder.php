<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Project;

class ProjectsTableSeeder extends Seeder
{
	public function run()
	{
		Project::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$projects = [
			['account_id' => 1, 'project_owner' => 1, 'deal_id' => 1, 'name' => '2018 1st Project', 'description' => 'First project description', 'start_date' => date('Y-m-d H:i:s'), 'end_date' => $faker->dateTimeInInterval('+ 7 days', '+ 15 days', null), 'status' => 'in_progress', 'completion_percentage' => 10, 'access' => 'public', 'created_at' => $save_date, 'updated_at' => $save_date],
			['account_id' => 2, 'project_owner' => 2, 'deal_id' => 2, 'name' => '2018 2nd Project', 'description' => 'Second project description', 'start_date' => date('Y-m-d H:i:s'), 'end_date' => $faker->dateTimeInInterval('+ 7 days', '+ 15 days', null), 'status' => 'in_progress', 'completion_percentage' => 20, 'access' => 'public', 'created_at' => $save_date, 'updated_at' => $save_date],
			['account_id' => 3, 'project_owner' => 3, 'deal_id' => 3, 'name' => '2018 3rd Project', 'description' => 'Third project description', 'start_date' => date('Y-m-d H:i:s'), 'end_date' => $faker->dateTimeInInterval('+ 7 days', '+ 15 days', null), 'status' => 'cancelled', 'completion_percentage' => 30, 'access' => 'private', 'created_at' => $save_date, 'updated_at' => $save_date],
			['account_id' => 4, 'project_owner' => 4, 'deal_id' => 4, 'name' => '2018 4th Project', 'description' => 'Fourth project description', 'start_date' => date('Y-m-d H:i:s'), 'end_date' => $faker->dateTimeInInterval('+ 7 days', '+ 15 days', null), 'status' => 'completed', 'completion_percentage' => 100, 'access' => 'public', 'created_at' => $save_date, 'updated_at' => $save_date],
			['account_id' => 5, 'project_owner' => 5, 'deal_id' => 5, 'name' => '2018 5th Project', 'description' => 'Fifth project description', 'start_date' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'end_date' => $faker->dateTimeInInterval('+ 7 days', '+ 15 days', null), 'status' => 'upcoming', 'completion_percentage' => 0, 'access' => 'private', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Project::insert($projects);
	}
}
