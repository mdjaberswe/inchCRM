<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\DealType;

class DealTypesTableSeeder extends Seeder
{
	public function run()
	{
		DealType::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$types = [
			['name' => 'New Business', 'position' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Existing Business', 'position' => 2, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		DealType::insert($types);
	}
}