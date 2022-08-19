<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Notification;

class NotificationsTableSeeder extends Seeder
{
	public function run()
	{
		Notification::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$notifications = [
			['notification_info_id' => 1, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' => 2, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' => 3, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' => 4, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' => 5, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' => 6, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' => 7, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' => 8, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' => 9, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' =>10, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' =>11, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' =>12, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' =>13, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' =>14, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' =>15, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['notification_info_id' =>16, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Notification::insert($notifications);
	}
}