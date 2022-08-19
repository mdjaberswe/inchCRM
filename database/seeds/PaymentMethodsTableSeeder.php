<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\PaymentMethod;

class PaymentMethodsTableSeeder extends Seeder
{
	public function run()
	{
		PaymentMethod::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$methods = [
			['name' => 'Bank', 'position' => 1, 'status' => 1, 'masked' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Paypal', 'position' => 1001, 'status' => 0, 'masked' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Stripe', 'position' => 1002, 'status' => 0, 'masked' => 1, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		PaymentMethod::insert($methods);
	}
}