<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Call;

class CallsTableSeeder extends Seeder
{
	public function run()
	{
		Call::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$subjects = [
			'Did you get what you were looking for?',
			'Hoping to help',
			'I found you through John',
			'Let me help',
			'Hoping you can help',
			'Our next steps',
			'X options to get started',
			'You are not alone',
			'I thought you might like these blogs',
			'Here is that info I promised you',
			'Should I stay or should I go?',
			'Permission to close your file?',
			'Feeling blue? Like baby pandas?',
			'3 weekend ideas for you',
			'Am I assuming correctly?'
		];

		$calls = [
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'lead', 'client_id' => 1, 'related_id' => 1, 'related_type' => 'campaign', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'lead', 'client_id' => 2, 'related_id' => 1, 'related_type' => 'campaign', 'call_time' => '2018-09-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'lead', 'client_id' => 3, 'related_id' => 2, 'related_type' => 'campaign', 'call_time' => '2018-10-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'lead', 'client_id' => 4, 'related_id' => 2, 'related_type' => 'campaign', 'call_time' => '2018-11-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'lead', 'client_id' => 5, 'related_id' => 3, 'related_type' => 'campaign', 'call_time' => '2018-12-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'lead', 'client_id' => 6, 'related_id' => 3, 'related_type' => 'campaign', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'lead', 'client_id' => 7, 'related_id' => 4, 'related_type' => 'campaign', 'call_time' => '2018-09-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'lead', 'client_id' => 8, 'related_id' => 4, 'related_type' => 'campaign', 'call_time' => '2018-10-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'lead', 'client_id' => 9, 'related_id' => 5, 'related_type' => 'campaign', 'call_time' => '2018-11-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'lead', 'client_id' =>10, 'related_id' => 5, 'related_type' => 'campaign', 'call_time' => '2018-12-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
		
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 1, 'related_id' => 1, 'related_type' => 'account', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 1, 'related_id' => 1, 'related_type' => 'deal', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 1, 'related_id' => 1, 'related_type' => 'project', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 1, 'related_id' => 1, 'related_type' => 'estimate', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 1, 'related_id' => 1, 'related_type' => 'invoice', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],

			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 2, 'related_id' => 2, 'related_type' => 'account', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 2, 'related_id' => 2, 'related_type' => 'deal', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 2, 'related_id' => 2, 'related_type' => 'project', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 2, 'related_id' => 2, 'related_type' => 'estimate', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 2, 'related_id' => 2, 'related_type' => 'invoice', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],

			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 3, 'related_id' => 3, 'related_type' => 'account', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 3, 'related_id' => 3, 'related_type' => 'deal', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 3, 'related_id' => 3, 'related_type' => 'project', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 3, 'related_id' => 3, 'related_type' => 'estimate', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 3, 'related_id' => 3, 'related_type' => 'invoice', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],

			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 4, 'related_id' => 4, 'related_type' => 'account', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 4, 'related_id' => 4, 'related_type' => 'deal', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 4, 'related_id' => 4, 'related_type' => 'project', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 4, 'related_id' => 4, 'related_type' => 'estimate', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 4, 'related_id' => 4, 'related_type' => 'invoice', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],

			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 5, 'related_id' => 5, 'related_type' => 'account', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 5, 'related_id' => 5, 'related_type' => 'deal', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 5, 'related_id' => 5, 'related_type' => 'project', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 5, 'related_id' => 5, 'related_type' => 'estimate', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date],
			['subject' => $subjects[rand(0, 14)], 'client_type' => 'contact', 'client_id' => 5, 'related_id' => 5, 'related_type' => 'invoice', 'call_time' => '2018-08-01 10:00:00', 'description' => $faker->sentence(20), 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Call::insert($calls);
	}
}