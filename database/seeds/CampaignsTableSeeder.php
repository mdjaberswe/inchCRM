<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Campaign;

class CampaignsTableSeeder extends Seeder
{
	public function run()
	{
		Campaign::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$campaigns = [
			['campaign_owner' => 1, 'campaign_type' => rand(1, 10), 'name' => 'Jan 2018 Campaign', 'description' => $faker->sentence(10), 'start_date' => '2018-01-01', 'end_date' => '2018-01-15', 'status' => 'completed', 'currency_id' => 1, 'expected_revenue' => 75000, 'budgeted_cost' => 34000, 'actual_cost' => 37000, 'numbers_sent' => 9775, 'expected_response' => 34, 'access' => 'public', 'created_at' => $save_date, 'updated_at' => $save_date],
			['campaign_owner' => 2, 'campaign_type' => rand(1, 10), 'name' => 'Feb 2018 Campaign', 'description' => $faker->sentence(10), 'start_date' => '2018-02-01', 'end_date' => '2018-02-15', 'status' => 'completed', 'currency_id' => 1, 'expected_revenue' => 80000, 'budgeted_cost' => 39000, 'actual_cost' => 41000, 'numbers_sent' => 7775, 'expected_response' => 39, 'access' => 'public', 'created_at' => $save_date, 'updated_at' => $save_date],
			['campaign_owner' => 3, 'campaign_type' => rand(1, 10), 'name' => 'Mar 2018 Campaign', 'description' => $faker->sentence(10), 'start_date' => '2018-03-01', 'end_date' => '2018-03-15', 'status' => 'completed', 'currency_id' => 1, 'expected_revenue' => 85000, 'budgeted_cost' => 45000, 'actual_cost' => 45000, 'numbers_sent' => 8775, 'expected_response' => 55, 'access' => 'public', 'created_at' => $save_date, 'updated_at' => $save_date],
			['campaign_owner' => 4, 'campaign_type' => rand(1, 10), 'name' => 'Apr 2018 Campaign', 'description' => $faker->sentence(10), 'start_date' => '2018-04-01', 'end_date' => '2018-04-15', 'status' => 'completed', 'currency_id' => 1, 'expected_revenue' => 90000, 'budgeted_cost' => 50000, 'actual_cost' => 53000, 'numbers_sent' => 5775, 'expected_response' => 77, 'access' => 'public', 'created_at' => $save_date, 'updated_at' => $save_date],
			['campaign_owner' => 5, 'campaign_type' => rand(1, 10), 'name' => 'May 2018 Campaign', 'description' => $faker->sentence(10), 'start_date' => '2018-05-01', 'end_date' => '2018-05-15', 'status' => 'completed', 'currency_id' => 1, 'expected_revenue' => 95000, 'budgeted_cost' => 55000, 'actual_cost' => 57000, 'numbers_sent' => 7775, 'expected_response' => 90, 'access' => 'public', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Campaign::insert($campaigns);
	}
}
