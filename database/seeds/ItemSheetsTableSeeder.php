<?php

use Illuminate\Database\Seeder;
use App\Models\ItemSheet;

class ItemSheetsTableSeeder extends Seeder
{
	public function run()
	{
		ItemSheet::truncate();

		$save_date = date('Y-m-d H:i:s');

		$item_sheets = [
			['linked_id' => 1, 'linked_type' => 'estimate', 'item' => 'Dell Vostro 5568', 'quantity' => 1, 'unit' => 'Unit', 'rate' => 25700, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 2, 'linked_type' => 'estimate', 'item' => 'Canon PowerShots', 'quantity' => 1, 'unit' => 'Unit', 'rate' => 33000, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 3, 'linked_type' => 'estimate', 'item' => 'ASUS S510UA Lapt', 'quantity' => 1, 'unit' => 'Unit', 'rate' => 21700, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 4, 'linked_type' => 'estimate', 'item' => 'CREATIVE SBS E28', 'quantity' => 1, 'unit' => 'Unit', 'rate' => 17700, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 5, 'linked_type' => 'estimate', 'item' => 'Lenovo IP320 7th', 'quantity' => 1, 'unit' => 'Unit', 'rate' => 19700, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'invoice', 'item' => 'Dell Vostro 5568', 'quantity' => 1, 'unit' => 'Unit', 'rate' => 25700, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 2, 'linked_type' => 'invoice', 'item' => 'Canon PowerShots', 'quantity' => 1, 'unit' => 'Unit', 'rate' => 33000, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 3, 'linked_type' => 'invoice', 'item' => 'ASUS S510UA Lapt', 'quantity' => 1, 'unit' => 'Unit', 'rate' => 21700, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 4, 'linked_type' => 'invoice', 'item' => 'CREATIVE SBS E28', 'quantity' => 1, 'unit' => 'Unit', 'rate' => 17700, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 5, 'linked_type' => 'invoice', 'item' => 'Lenovo IP320 7th', 'quantity' => 1, 'unit' => 'Unit', 'rate' => 19700, 'tax' => 2, 'discount' => 5, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		ItemSheet::insert($item_sheets);
	}
}