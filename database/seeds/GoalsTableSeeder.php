<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Goal;

class GoalsTableSeeder extends Seeder
{
	public function run()
	{
		Goal::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$goals = [
			['name' => '2018 Primary Goal', 'goal_owner' => 1, 'start_date' => '2018-01-01', 'end_date' => '2018-12-29', 'leads_count' => 25, 'accounts_count' => 15, 'deals_count' => 10, 'sales_amount' => 20000, 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => '2018 Promotional Goal', 'goal_owner' => 2, 'start_date' => '2018-01-01', 'end_date' => '2018-12-28', 'leads_count' => 40, 'accounts_count' => 30, 'deals_count' => 20, 'sales_amount' => 37500, 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => '2018 Super Star Goal', 'goal_owner' => 3, 'start_date' => '2018-01-01', 'end_date' => '2018-12-29', 'leads_count' => 50, 'accounts_count' => 40, 'deals_count' => 30, 'sales_amount' => 45000, 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => '2018 Best Performer Goal', 'goal_owner' => 4, 'start_date' => '2018-01-01', 'end_date' => '2018-12-29', 'leads_count' => 75, 'accounts_count' => 55, 'deals_count' => 40, 'sales_amount' => 70500, 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => '2018 I AM THE BEST', 'goal_owner' => 5, 'start_date' => '2018-01-01', 'end_date' => '2018-12-29', 'leads_count' => 90, 'accounts_count' => 70, 'deals_count' => 50, 'sales_amount' => 87500, 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Goal::insert($goals);
	}
}