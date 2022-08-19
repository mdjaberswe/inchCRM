<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Account;

class AccountsTableSeeder extends Seeder
{
	public function run()
	{
		Account::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$accounts = [
			['account_owner' => 1, 'parent_id' => NULL, 'account_name' => 'Ask Inc', 'account_email' => $faker->companyEmail, 'account_phone' => $faker->phoneNumber, 'account_type_id' => rand(1, 5), 'industry_type_id' => rand(1, 23), 'fax' => rand(31937729, 51937729), 'website' => 'www.'.$faker->domainName, 'no_of_employees' => rand(50, 1000), 'currency_id' => 1, 'annual_revenue' => rand(1000000, 9999999), 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'country_code' => $faker->countryCode, 'timezone' => config('app.timezone'), 'description' => $faker->text(200), 'created_at' => $save_date, 'updated_at' => $save_date],
			['account_owner' => 2, 'parent_id' => NULL, 'account_name' => 'Elixir Food', 'account_email' => $faker->companyEmail, 'account_phone' => $faker->phoneNumber, 'account_type_id' => rand(1, 5), 'industry_type_id' => rand(1, 23), 'fax' => rand(31937729, 51937729), 'website' => 'www.'.$faker->domainName, 'no_of_employees' => rand(50, 1000), 'currency_id' => 1, 'annual_revenue' => rand(1000000, 9999999), 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'country_code' => $faker->countryCode, 'timezone' => config('app.timezone'), 'description' => $faker->text(200), 'created_at' => $save_date, 'updated_at' => $save_date],
			['account_owner' => 3, 'parent_id' => 1, 'account_name' => 'BlueDot Soft', 'account_email' => $faker->companyEmail, 'account_phone' => $faker->phoneNumber, 'account_type_id' => rand(1, 5), 'industry_type_id' => rand(1, 23), 'fax' => rand(31937729, 51937729), 'website' => 'www.'.$faker->domainName, 'no_of_employees' => rand(50, 1000), 'currency_id' => 1, 'annual_revenue' => rand(1000000, 9999999), 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'country_code' => $faker->countryCode, 'timezone' => config('app.timezone'), 'description' => $faker->text(200), 'created_at' => $save_date, 'updated_at' => $save_date],
			['account_owner' => 4, 'parent_id' => 1, 'account_name' => 'HD Green', 'account_email' => $faker->companyEmail, 'account_phone' => $faker->phoneNumber, 'account_type_id' => rand(1, 5), 'industry_type_id' => rand(1, 23), 'fax' => rand(31937729, 51937729), 'website' => 'www.'.$faker->domainName, 'no_of_employees' => rand(50, 1000), 'currency_id' => 1, 'annual_revenue' => rand(1000000, 9999999), 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'country_code' => $faker->countryCode, 'timezone' => config('app.timezone'), 'description' => $faker->text(200), 'created_at' => $save_date, 'updated_at' => $save_date],
			['account_owner' => 5, 'parent_id' => 2, 'account_name' => 'Sage Crispy', 'account_email' => $faker->companyEmail, 'account_phone' => $faker->phoneNumber, 'account_type_id' => rand(1, 5), 'industry_type_id' => rand(1, 23), 'fax' => rand(31937729, 51937729), 'website' => 'www.'.$faker->domainName, 'no_of_employees' => rand(50, 1000), 'currency_id' => 1, 'annual_revenue' => rand(1000000, 9999999), 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'country_code' => $faker->countryCode, 'timezone' => config('app.timezone'), 'description' => $faker->text(200), 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Account::insert($accounts);
	}
}