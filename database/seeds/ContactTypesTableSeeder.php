<?php

use Illuminate\Database\Seeder;
use App\Models\ContactType;

class ContactTypesTableSeeder extends Seeder
{
	public function run()
	{
		ContactType::truncate();
		$save_date = date('Y-m-d H:i:s');

		$types = [
			['name' => 'Client', 'position' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Supplier', 'position' => 2, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Partner', 'position' => 3, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Competitor', 'position' => 4, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Contractor', 'position' => 5, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		ContactType::insert($types);
	}
}