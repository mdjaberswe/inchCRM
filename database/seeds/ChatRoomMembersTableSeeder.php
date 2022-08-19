<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\ChatRoomMember;

class ChatRoomMembersTableSeeder extends Seeder
{
	public function run()
	{
		ChatRoomMember::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$members = [
			['chat_room_id' => 1, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' => 2, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' => 3, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' => 4, 'linked_id' => 1, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
		
			['chat_room_id' => 1, 'linked_id' => 2, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' => 5, 'linked_id' => 2, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' => 6, 'linked_id' => 2, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' => 7, 'linked_id' => 2, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],

			['chat_room_id' => 2, 'linked_id' => 3, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' => 5, 'linked_id' => 3, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' => 8, 'linked_id' => 3, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' => 9, 'linked_id' => 3, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],

			['chat_room_id' => 3, 'linked_id' => 4, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' => 6, 'linked_id' => 4, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' => 8, 'linked_id' => 4, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' =>10, 'linked_id' => 4, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],

			['chat_room_id' => 4, 'linked_id' => 5, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' => 7, 'linked_id' => 5, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' => 9, 'linked_id' => 5, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_id' =>10, 'linked_id' => 5, 'linked_type' => 'staff', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		ChatRoomMember::insert($members);
	}
}