<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\EventAttendee;

class EventAttendeesTableSeeder extends Seeder
{
	public function run()
	{
		EventAttendee::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$attendes = [
			['event_id' => 1, 'linked_id' => 1, 'linked_type' => 'contact', 'status' => 'going', 'created_at' => $save_date, 'updated_at' => $save_date],
			['event_id' => 1, 'linked_id' => 2, 'linked_type' => 'contact', 'status' => 'may_be', 'created_at' => $save_date, 'updated_at' => $save_date],
			['event_id' => 2, 'linked_id' => 1, 'linked_type' => 'contact', 'status' => 'may_be', 'created_at' => $save_date, 'updated_at' => $save_date],
			['event_id' => 2, 'linked_id' => 5, 'linked_type' => 'contact', 'status' => 'decline', 'created_at' => $save_date, 'updated_at' => $save_date],
			['event_id' => 3, 'linked_id' => 1, 'linked_type' => 'contact', 'status' => 'going', 'created_at' => $save_date, 'updated_at' => $save_date],
			['event_id' => 3, 'linked_id' => 3, 'linked_type' => 'contact', 'status' => 'may_be', 'created_at' => $save_date, 'updated_at' => $save_date],
			['event_id' => 4, 'linked_id' => 3, 'linked_type' => 'contact', 'status' => 'pending', 'created_at' => $save_date, 'updated_at' => $save_date],
			['event_id' => 4, 'linked_id' => 5, 'linked_type' => 'contact', 'status' => 'may_be', 'created_at' => $save_date, 'updated_at' => $save_date],
			['event_id' => 5, 'linked_id' => 2, 'linked_type' => 'contact', 'status' => 'going', 'created_at' => $save_date, 'updated_at' => $save_date],
			['event_id' => 5, 'linked_id' => 4, 'linked_type' => 'contact', 'status' => 'may_be', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		EventAttendee::insert($attendes);
	}
}