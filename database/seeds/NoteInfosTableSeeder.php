<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\NoteInfo;

class NoteInfosTableSeeder extends Seeder
{
	public function run()
	{
		NoteInfo::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$note_infos = [
			['description' => $faker->sentence(10), 'linked_id' => 1, 'linked_type' => 'lead', 'created_at' => $save_date, 'updated_at' => $save_date],
			['description' => $faker->sentence(10), 'linked_id' => 1, 'linked_type' => 'contact', 'created_at' => $save_date, 'updated_at' => $save_date],
			['description' => $faker->sentence(10), 'linked_id' => 1, 'linked_type' => 'account', 'created_at' => $save_date, 'updated_at' => $save_date],
			['description' => $faker->sentence(10), 'linked_id' => 1, 'linked_type' => 'project', 'created_at' => $save_date, 'updated_at' => $save_date],
			['description' => $faker->sentence(10), 'linked_id' => 1, 'linked_type' => 'task', 'created_at' => $save_date, 'updated_at' => $save_date],
			['description' => $faker->sentence(10), 'linked_id' => 1, 'linked_type' => 'campaign', 'created_at' => $save_date, 'updated_at' => $save_date],
			['description' => $faker->sentence(10), 'linked_id' => 1, 'linked_type' => 'deal', 'created_at' => $save_date, 'updated_at' => $save_date],
			['description' => $faker->sentence(10), 'linked_id' => 1, 'linked_type' => 'estimate', 'created_at' => $save_date, 'updated_at' => $save_date],
			['description' => $faker->sentence(10), 'linked_id' => 1, 'linked_type' => 'invoice', 'created_at' => $save_date, 'updated_at' => $save_date],
			['description' => $faker->sentence(10), 'linked_id' => 1, 'linked_type' => 'event', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		NoteInfo::insert($note_infos);
	}
}