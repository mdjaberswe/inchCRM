<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\DealStage;

class DealStagesTableSeeder extends Seeder
{
	public function run()
	{
		DealStage::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$stages = [
			['name' => 'Qualification', 'category' => 'open', 'probability' => 10, 'position' => 1, 'fixed' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Needs Analysis', 'category' => 'open', 'probability' => 25, 'position' => 2, 'fixed' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Value Proposition', 'category' => 'open', 'probability' => 35, 'position' => 3, 'fixed' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Identified Decision Makers', 'category' => 'open', 'probability' => 45, 'position' => 4, 'fixed' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Estimate', 'category' => 'open', 'probability' => 60, 'position' => 5, 'fixed' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Negotiation/Review', 'category' => 'open', 'probability' => 75, 'position' => 6, 'fixed' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Invoice', 'category' => 'open', 'probability' => 90, 'position' => 7, 'fixed' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Closed Won', 'category' => 'closed_won', 'probability' => 100, 'position' => 8, 'fixed' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Closed Lost', 'category' => 'closed_lost', 'probability' => 0, 'position' => 9, 'fixed' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Analyze Failure', 'category' => 'closed_lost', 'probability' => 0, 'position' => 10, 'fixed' => 0, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		DealStage::insert($stages);
	}
}