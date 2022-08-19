<?php

use Illuminate\Database\Seeder;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
	public function run()
	{
		Item::truncate();

		$save_date = date('Y-m-d H:i:s');

		$items = [
			['name' => 'Dell Vostro 5568', 'price' => 25700, 'currency_id' => 1, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Canon PowerShots', 'price' => 33000, 'currency_id' => 1, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'ASUS S510UA Lapt', 'price' => 21700, 'currency_id' => 1, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'CREATIVE SBS E28', 'price' => 17700, 'currency_id' => 1, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Lenovo IP320 7th', 'price' => 19700, 'currency_id' => 1, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Item::insert($items);
	}
}