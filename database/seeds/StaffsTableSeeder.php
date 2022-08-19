<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Staff;

class StaffsTableSeeder extends Seeder
{
	public function run()
	{
		Staff::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$staffs = [
			['first_name' => $faker->firstName, 'last_name' => $faker->lastName, 'title' => $faker->jobTitle, 'phone' => $faker->phoneNumber, 'birthdate' => $faker->date('Y-m-d', '-30 years'), 'date_of_hire' => $faker->date('Y-m-d', '-5 years'), 'fax' => rand(71937729, 91937729), 'website' => 'www.'.$faker->domainName, 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'timezone' => config('app.timezone'), 'country_code' => $faker->countryCode, 'created_at' => $save_date, 'updated_at' => $save_date],
			['first_name' => $faker->firstName, 'last_name' => $faker->lastName, 'title' => $faker->jobTitle, 'phone' => $faker->phoneNumber, 'birthdate' => $faker->date('Y-m-d', '-30 years'), 'date_of_hire' => $faker->date('Y-m-d', '-5 years'), 'fax' => rand(71937729, 91937729), 'website' => 'www.'.$faker->domainName, 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'timezone' => config('app.timezone'), 'country_code' => $faker->countryCode, 'created_at' => $save_date, 'updated_at' => $save_date],
			['first_name' => $faker->firstName, 'last_name' => $faker->lastName, 'title' => $faker->jobTitle, 'phone' => $faker->phoneNumber, 'birthdate' => $faker->date('Y-m-d', '-30 years'), 'date_of_hire' => $faker->date('Y-m-d', '-5 years'), 'fax' => rand(71937729, 91937729), 'website' => 'www.'.$faker->domainName, 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'timezone' => config('app.timezone'), 'country_code' => $faker->countryCode, 'created_at' => $save_date, 'updated_at' => $save_date],
			['first_name' => $faker->firstName, 'last_name' => $faker->lastName, 'title' => $faker->jobTitle, 'phone' => $faker->phoneNumber, 'birthdate' => $faker->date('Y-m-d', '-30 years'), 'date_of_hire' => $faker->date('Y-m-d', '-5 years'), 'fax' => rand(71937729, 91937729), 'website' => 'www.'.$faker->domainName, 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'timezone' => config('app.timezone'), 'country_code' => $faker->countryCode, 'created_at' => $save_date, 'updated_at' => $save_date],
			['first_name' => $faker->firstName, 'last_name' => $faker->lastName, 'title' => $faker->jobTitle, 'phone' => $faker->phoneNumber, 'birthdate' => $faker->date('Y-m-d', '-30 years'), 'date_of_hire' => $faker->date('Y-m-d', '-5 years'), 'fax' => rand(71937729, 91937729), 'website' => 'www.'.$faker->domainName, 'street' => $faker->streetAddress, 'city' => $faker->city, 'state' => $faker->state, 'zip' => $faker->postcode, 'timezone' => config('app.timezone'), 'country_code' => $faker->countryCode, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Staff::insert($staffs);
	}
}