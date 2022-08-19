<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Payment;

class PaymentsTableSeeder extends Seeder
{
	public function run()
	{
		Payment::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$payments = [
			['invoice_id' => 1, 'amount' => 15003.30, 'payment_method_id' => 1, 'payment_date' => $faker->dateTimeInInterval('- 10 days', '-2 days', null), 'note' => 'Invoice payment', 'transaction_id' => strtoupper(str_random(17)), 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['invoice_id' => 1, 'amount' => 10000.00, 'payment_method_id' => 1, 'payment_date' => $faker->dateTimeInInterval('- 10 days', '-2 days', null), 'note' => 'Invoice payment', 'transaction_id' => strtoupper(str_random(17)), 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['invoice_id' => 2, 'amount' => 12077.00, 'payment_method_id' => 1, 'payment_date' => $faker->dateTimeInInterval('- 10 days', '-2 days', null), 'note' => 'Invoice payment', 'transaction_id' => strtoupper(str_random(17)), 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['invoice_id' => 2, 'amount' => 20000.00, 'payment_method_id' => 1, 'payment_date' => $faker->dateTimeInInterval('- 10 days', '-2 days', null), 'note' => 'Invoice payment', 'transaction_id' => strtoupper(str_random(17)), 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['invoice_id' => 3, 'amount' => 10127.30, 'payment_method_id' => 1, 'payment_date' => $faker->dateTimeInInterval('- 10 days', '-2 days', null), 'note' => 'Invoice payment', 'transaction_id' => strtoupper(str_random(17)), 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['invoice_id' => 3, 'amount' => 11000.00, 'payment_method_id' => 1, 'payment_date' => $faker->dateTimeInInterval('- 10 days', '-2 days', null), 'note' => 'Invoice payment', 'transaction_id' => strtoupper(str_random(17)), 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['invoice_id' => 4, 'amount' => 10251.30, 'payment_method_id' => 1, 'payment_date' => $faker->dateTimeInInterval('- 10 days', '-2 days', null), 'note' => 'Invoice payment', 'transaction_id' => strtoupper(str_random(17)), 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['invoice_id' => 4, 'amount' => 7000.00, 'payment_method_id' => 1, 'payment_date' => $faker->dateTimeInInterval('- 10 days', '-2 days', null), 'note' => 'Invoice payment', 'transaction_id' => strtoupper(str_random(17)), 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['invoice_id' => 5, 'amount' => 10189.30, 'payment_method_id' => 1, 'payment_date' => $faker->dateTimeInInterval('- 10 days', '-2 days', null), 'note' => 'Invoice payment', 'transaction_id' => strtoupper(str_random(17)), 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['invoice_id' => 5, 'amount' => 9000.00, 'payment_method_id' => 1, 'payment_date' => $faker->dateTimeInInterval('- 10 days', '-2 days', null), 'note' => 'Invoice payment', 'transaction_id' => strtoupper(str_random(17)), 'currency_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Payment::insert($payments);
	}
}