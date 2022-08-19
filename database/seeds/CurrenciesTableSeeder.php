<?php

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrenciesTableSeeder extends Seeder
{
	public function run()
	{
		Currency::truncate();

		$save_date = date('Y-m-d H:i:s');

		$currencies = [
			['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$', 'exchange_rate' => 1, 'face_value' => 1, 'base' => 1, 'position' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Euro', 'code' => 'EUR', 'symbol' => '€', 'exchange_rate' => 1.175, 'face_value' => 1, 'base' => 0, 'position' => 3, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Pound sterling', 'code' => 'GBP', 'symbol' => '£', 'exchange_rate' => 1.335, 'face_value' => 1, 'base' => 0, 'position' => 4, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Bangladeshi Taka', 'code' => 'BDT', 'symbol' => '৳', 'exchange_rate' => 0.0125, 'face_value' => 1, 'base' => 0, 'position' => 5, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Currency::insert($currencies);
	}
}