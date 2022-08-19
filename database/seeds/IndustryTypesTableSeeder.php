<?php

use Illuminate\Database\Seeder;
use App\Models\IndustryType;

class IndustryTypesTableSeeder extends Seeder
{
	public function run()
	{
		IndustryType::truncate();
		$save_date = date('Y-m-d H:i:s');

		$types = [
			['name' => 'Accounting', 'position' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Advertising', 'position' => 2, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Agriculture', 'position' => 3, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Aircraft', 'position' => 4, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Airline', 'position' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Automotive', 'position' => 6, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Banking', 'position' => 7, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Biotechnology', 'position' => 8, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Broadcasting', 'position' => 9, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Brokerage', 'position' => 10, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Call Centers', 'position' => 11, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Chemicals', 'position' => 12, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Computer', 'position' => 13, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Construction', 'position' => 14, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Consulting', 'position' => 15, 'created_at' => $save_date, 'updated_at' => $save_date],		
			['name' => 'Cosmetics', 'position' => 16, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Defence', 'position' => 17, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Department Stores', 'position' => 18, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Ecommerce', 'position' => 19, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Education', 'position' => 20, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Electronics', 'position' => 21, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Energy', 'position' => 22, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Engineering', 'position' => 23, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Entertainment', 'position' => 24, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Financial Services', 'position' => 25, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Grocery', 'position' => 26, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Health Care', 'position' => 27, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Insurance', 'position' => 28, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Manufacturing', 'position' => 29, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Other', 'position' => 30, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		IndustryType::insert($types);
	}
}