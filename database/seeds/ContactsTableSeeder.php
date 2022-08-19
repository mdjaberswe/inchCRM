<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Contact;

class ContactsTableSeeder extends Seeder
{
	public function run()
	{
		Contact::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$contacts = [
			['contact_owner' => 1, 'account_id' => 1, 'first_name' => 'Taylor', 'last_name' => 'Otwell', 'title' => $faker->jobTitle, 'phone' => $faker->phoneNumber, 'fax' => rand(31937729, 51937729), 'website' => 'www.'.$faker->domainName, 'date_of_birth' => $faker->dateTimeThisCentury->format('Y-m-d'), 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'country_code' => $faker->countryCode, 'timezone' => config('app.timezone'), 'currency_id' => 1, 'annual_revenue' => rand(1000000, 9999999), 'contact_type_id' => rand(1, 5), 'source_id' => rand(1, 10), 'description' => $faker->text(200), 'created_at' => $save_date, 'updated_at' => $save_date],
			['contact_owner' => 2, 'account_id' => 2, 'first_name' => $faker->firstName, 'last_name' => $faker->lastName, 'title' => $faker->jobTitle, 'phone' => $faker->phoneNumber, 'fax' => rand(31937729, 51937729), 'website' => 'www.'.$faker->domainName, 'date_of_birth' => $faker->dateTimeThisCentury->format('Y-m-d'), 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'country_code' => $faker->countryCode, 'timezone' => config('app.timezone'), 'currency_id' => 1, 'annual_revenue' => rand(1000000, 9999999), 'contact_type_id' => rand(1, 5), 'source_id' => rand(1, 10), 'description' => $faker->text(200), 'created_at' => $save_date, 'updated_at' => $save_date],
			['contact_owner' => 3, 'account_id' => 3, 'first_name' => $faker->firstName, 'last_name' => $faker->lastName, 'title' => $faker->jobTitle, 'phone' => $faker->phoneNumber, 'fax' => rand(31937729, 51937729), 'website' => 'www.'.$faker->domainName, 'date_of_birth' => $faker->dateTimeThisCentury->format('Y-m-d'), 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'country_code' => $faker->countryCode, 'timezone' => config('app.timezone'), 'currency_id' => 1, 'annual_revenue' => rand(1000000, 9999999), 'contact_type_id' => rand(1, 5), 'source_id' => rand(1, 10), 'description' => $faker->text(200), 'created_at' => $save_date, 'updated_at' => $save_date],
			['contact_owner' => 4, 'account_id' => 4, 'first_name' => $faker->firstName, 'last_name' => $faker->lastName, 'title' => $faker->jobTitle, 'phone' => $faker->phoneNumber, 'fax' => rand(31937729, 51937729), 'website' => 'www.'.$faker->domainName, 'date_of_birth' => $faker->dateTimeThisCentury->format('Y-m-d'), 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'country_code' => $faker->countryCode, 'timezone' => config('app.timezone'), 'currency_id' => 1, 'annual_revenue' => rand(1000000, 9999999), 'contact_type_id' => rand(1, 5), 'source_id' => rand(1, 10), 'description' => $faker->text(200), 'created_at' => $save_date, 'updated_at' => $save_date],
			['contact_owner' => 5, 'account_id' => 5, 'first_name' => $faker->firstName, 'last_name' => $faker->lastName, 'title' => $faker->jobTitle, 'phone' => $faker->phoneNumber, 'fax' => rand(31937729, 51937729), 'website' => 'www.'.$faker->domainName, 'date_of_birth' => $faker->dateTimeThisCentury->format('Y-m-d'), 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'country_code' => $faker->countryCode, 'timezone' => config('app.timezone'), 'currency_id' => 1, 'annual_revenue' => rand(1000000, 9999999), 'contact_type_id' => rand(1, 5), 'source_id' => rand(1, 10), 'description' => $faker->text(200), 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Contact::insert($contacts);
	}
}