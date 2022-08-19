<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\ExpenseCategory;

class ExpenseCategoriesTableSeeder extends Seeder
{
	public function run()
	{
		ExpenseCategory::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$categories = [
			['name' => 'Advertising', 'position' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Insurance', 'position' => 2, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Stationery', 'position' => 3, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Equipment', 'position' => 4, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Machinery', 'position' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Supplies', 'position' => 6, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Airfare', 'position' => 7, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Utilities', 'position' => 8, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Maintenance', 'position' => 9, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Depreciation', 'position' => 10, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		ExpenseCategory::insert($categories);
	}
}