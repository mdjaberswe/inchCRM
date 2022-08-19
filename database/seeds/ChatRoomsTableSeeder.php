<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\ChatRoom;

class ChatRoomsTableSeeder extends Seeder
{
	public function run()
	{
		ChatRoom::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$rooms = [
			['name' => 'Staff#1 and Staff#2', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Staff#1 and Staff#3', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Staff#1 and Staff#4', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Staff#1 and Staff#5', 'created_at' => $save_date, 'updated_at' => $save_date],
		
			['name' => 'Staff#2 and Staff#3', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Staff#2 and Staff#4', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Staff#2 and Staff#5', 'created_at' => $save_date, 'updated_at' => $save_date],

			['name' => 'Staff#3 and Staff#4', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Staff#3 and Staff#5', 'created_at' => $save_date, 'updated_at' => $save_date],

			['name' => 'Staff#4 and Staff#5', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		ChatRoom::insert($rooms);
	}
}