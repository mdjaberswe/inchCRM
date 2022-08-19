<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Reminder;

class RemindersTableSeeder extends Seeder
{
	public function run()
	{
		Reminder::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$reminders = [
			['reminder_to' => 1, 'description' => 'Event: Jan 2018 Event will start at 2018-01-01 10:00:00 AM', 'reminder_before' => 15, 'reminder_before_type' => 'minute', 'reminder_date' => '2018-01-01 09:45:00', 'is_notified' => rand(0, 1), 'linked_id' => 1, 'linked_type' => 'event', 'created_at' => $save_date, 'updated_at' => $save_date],
			['reminder_to' => 2, 'description' => 'Event: Feb 2018 Event will start at 2018-02-01 10:00:00 AM', 'reminder_before' => 15, 'reminder_before_type' => 'minute', 'reminder_date' => '2018-02-01 09:45:00', 'is_notified' => rand(0, 1), 'linked_id' => 2, 'linked_type' => 'event', 'created_at' => $save_date, 'updated_at' => $save_date],
			['reminder_to' => 3, 'description' => 'Event: Mar 2018 Event will start at 2018-03-01 10:00:00 AM', 'reminder_before' => 15, 'reminder_before_type' => 'minute', 'reminder_date' => '2018-03-01 09:45:00', 'is_notified' => rand(0, 1), 'linked_id' => 3, 'linked_type' => 'event', 'created_at' => $save_date, 'updated_at' => $save_date],
			['reminder_to' => 4, 'description' => 'Event: Apr 2018 Event will start at 2018-04-01 10:00:00 AM', 'reminder_before' => 15, 'reminder_before_type' => 'minute', 'reminder_date' => '2018-04-01 09:45:00', 'is_notified' => rand(0, 1), 'linked_id' => 4, 'linked_type' => 'event', 'created_at' => $save_date, 'updated_at' => $save_date],
			['reminder_to' => 5, 'description' => 'Event: May 2018 Event will start at 2018-05-01 10:00:00 AM', 'reminder_before' => 15, 'reminder_before_type' => 'minute', 'reminder_date' => '2018-05-01 09:45:00', 'is_notified' => rand(0, 1), 'linked_id' => 5, 'linked_type' => 'event', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Reminder::insert($reminders);
	}
}