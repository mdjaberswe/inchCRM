<?php

use Illuminate\Database\Seeder;
use App\Models\LeadStage;

class LeadStagesTableSeeder extends Seeder
{
	public function run()
	{
		LeadStage::truncate();

		$save_date = date('Y-m-d H:i:s');

		$lead_stages = [
			['name' => 'Contacted', 'position' => 1, 'category' => 'open', 'fixed' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Interested', 'position' => 2, 'category' => 'open', 'fixed' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Under Review', 'position' => 3, 'category' => 'open', 'fixed' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Ready to Deal', 'position' => 4, 'category' => 'open', 'fixed' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Converted', 'position' => 5, 'category' => 'converted', 'fixed' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Recycled', 'position' => 6, 'category' => 'closed_lost', 'fixed' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Lost Lead', 'position' => 7, 'category' => 'closed_lost', 'fixed' => 0, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		LeadStage::insert($lead_stages);
	}
}