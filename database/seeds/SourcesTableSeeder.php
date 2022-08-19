<?php

use Illuminate\Database\Seeder;
use App\Models\Source;

class SourcesTableSeeder extends Seeder
{
	public function run()
	{
		Source::truncate();

		$save_date = date('Y-m-d H:i:s');

		$sources = [
			['name' => 'Web', 'position' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Email', 'position' => 2, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Cold Call', 'position' => 3, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Chat', 'position' => 4, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Referral', 'position' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Social Media', 'position' => 6, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Display Ads', 'position' => 7, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Events', 'position' => 8, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Advertisement', 'position' => 9, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Trade Show', 'position' => 10, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Online Store', 'position' => 11, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Source::insert($sources);
	}
}