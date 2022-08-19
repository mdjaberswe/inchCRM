<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Expense;

class ExpensesTableSeeder extends Seeder
{
	public function run()
	{
		Expense::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$expenses = [
			['expense_category_id' => rand(1, 10), 'account_id' => null, 'project_id' => null, 'name' => 'Taxi to airport', 'amount' => rand(100, 999), 'currency_id' => 1, 'payment_method_id' => 1, 'expense_date' => $faker->dateTimeInInterval('- 10 days', '+ 5 days', null), 'billable' => 0, 'recurring' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['expense_category_id' => rand(1, 10), 'account_id' => null, 'project_id' => null, 'name' => 'Conference registration', 'amount' => rand(100, 999), 'currency_id' => 1, 'payment_method_id' => 1, 'expense_date' => $faker->dateTimeInInterval('- 10 days', '+ 5 days', null), 'billable' => 0, 'recurring' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['expense_category_id' => rand(1, 10), 'account_id' => null, 'project_id' => null, 'name' => 'ZZZ Restaurant - business dinner', 'amount' => rand(100, 999), 'currency_id' => 1, 'payment_method_id' => 1, 'expense_date' => $faker->dateTimeInInterval('- 10 days', '+ 5 days', null), 'billable' => 0, 'recurring' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['expense_category_id' => rand(1, 10), 'account_id' => null, 'project_id' => null, 'name' => 'BBB Hotel Booking', 'amount' => rand(100, 999), 'currency_id' => 1, 'payment_method_id' => 1, 'expense_date' => $faker->dateTimeInInterval('- 10 days', '+ 5 days', null), 'billable' => 0, 'recurring' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['expense_category_id' => rand(1, 10), 'account_id' => null, 'project_id' => null, 'name' => 'XYZ rental car', 'amount' => rand(100, 999), 'currency_id' => 1, 'payment_method_id' => 1, 'expense_date' => $faker->dateTimeInInterval('- 10 days', '+ 5 days', null), 'billable' => 0, 'recurring' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['expense_category_id' => rand(1, 10), 'account_id' => 1, 'project_id' => 1, 'name' => 'Water and electricity', 'amount' => rand(100, 999), 'currency_id' => 1, 'payment_method_id' => 1, 'expense_date' => $faker->dateTimeInInterval('- 10 days', '+ 5 days', null), 'billable' => 0, 'recurring' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['expense_category_id' => rand(1, 10), 'account_id' => 2, 'project_id' => 2, 'name' => 'Airport to office', 'amount' => rand(100, 999), 'currency_id' => 1, 'payment_method_id' => 1, 'expense_date' => $faker->dateTimeInInterval('- 10 days', '+ 5 days', null), 'billable' => 0, 'recurring' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['expense_category_id' => rand(1, 10), 'account_id' => 3, 'project_id' => 3, 'name' => 'ABC Software upgradation', 'amount' => rand(100, 999), 'currency_id' => 1, 'payment_method_id' => 1, 'expense_date' => $faker->dateTimeInInterval('- 10 days', '+ 5 days', null), 'billable' => 1, 'recurring' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['expense_category_id' => rand(1, 10), 'account_id' => 4, 'project_id' => 4, 'name' => 'Monthly rent January, 2018', 'amount' => rand(100, 999), 'currency_id' => 1, 'payment_method_id' => 1, 'expense_date' => $faker->dateTimeInInterval('- 10 days', '+ 5 days', null), 'billable' => 1, 'recurring' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['expense_category_id' => rand(1, 10), 'account_id' => 5, 'project_id' => 5, 'name' => 'Daycare expense', 'amount' => rand(100, 999), 'currency_id' => 1, 'payment_method_id' => 1, 'expense_date' => $faker->dateTimeInInterval('- 10 days', '+ 5 days', null), 'billable' => 1, 'recurring' => 0, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Expense::insert($expenses);
	}
}