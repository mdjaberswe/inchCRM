<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Event;

class EventsTableSeeder extends Seeder
{
	public function run()
	{
		Event::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$events = [
			['name' => 'Aug 2018 Event', 'event_owner' => 1, 'location' => 'Blue Hill, New York', 'linked_id' => 1, 'linked_type' => 'lead', 'start_date' => '2018-08-01 10:00:00', 'end_date' => '2018-08-01 11:00:00', 'priority' => 'high', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Sep 2018 Event', 'event_owner' => 2, 'location' => 'Blue Hill, New York', 'linked_id' => 1, 'linked_type' => 'contact', 'start_date' => '2018-09-01 10:00:00', 'end_date' => '2018-09-01 11:00:00', 'priority' => 'highest', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Oct 2018 Event', 'event_owner' => 3, 'location' => 'Blue Hill, New York', 'linked_id' => 1, 'linked_type' => 'account', 'start_date' => '2018-10-01 10:00:00', 'end_date' => '2018-10-01 11:00:00', 'priority' => 'low', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Nov 2018 Event', 'event_owner' => 4, 'location' => 'Blue Hill, New York', 'linked_id' => 1, 'linked_type' => 'project', 'start_date' => '2018-11-01 10:00:00', 'end_date' => '2018-11-01 11:00:00', 'priority' => 'lowest', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Dec 2018 Event', 'event_owner' => 5, 'location' => 'Blue Hill, New York', 'linked_id' => 1, 'linked_type' => 'campaign', 'start_date' => '2018-12-01 10:00:00', 'end_date' => '2018-12-01 11:00:00', 'priority' => 'normal', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Event::insert($events);
	}
}