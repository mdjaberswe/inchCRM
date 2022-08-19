<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\CampaignType;

class CampaignTypesTableSeeder extends Seeder
{
	public function run()
	{
		CampaignType::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$types = [
			['name' => 'Webinar', 'position' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Advertisement', 'position' => 2, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Conference', 'position' => 3, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Trade Show', 'position' => 4, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Telemarketing', 'position' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Training', 'position' => 6, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Referral Program', 'position' => 7, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Email', 'position' => 8, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Social Media', 'position' => 9, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Outdoor', 'position' => 10, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		CampaignType::insert($types);
	}
}