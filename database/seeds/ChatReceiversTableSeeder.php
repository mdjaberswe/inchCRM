<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\ChatReceiver;

class ChatReceiversTableSeeder extends Seeder
{
	public function run()
	{
		ChatReceiver::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$receivers = [
			['chat_sender_id' => 1, 'chat_room_member_id' => 5, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' => 2, 'chat_room_member_id' => 9, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' => 3, 'chat_room_member_id' => 13, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' => 4, 'chat_room_member_id' => 17, 'created_at' => $save_date, 'updated_at' => $save_date],
			
			['chat_sender_id' => 5, 'chat_room_member_id' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' => 6, 'chat_room_member_id' =>10, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' => 7, 'chat_room_member_id' =>14, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' => 8, 'chat_room_member_id' =>18, 'created_at' => $save_date, 'updated_at' => $save_date],

			['chat_sender_id' => 9, 'chat_room_member_id' => 2, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' =>10, 'chat_room_member_id' => 6, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' =>11, 'chat_room_member_id' =>15, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' =>12, 'chat_room_member_id' =>19, 'created_at' => $save_date, 'updated_at' => $save_date],

			['chat_sender_id' =>13, 'chat_room_member_id' => 3, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' =>14, 'chat_room_member_id' => 7, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' =>15, 'chat_room_member_id' =>11, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' =>16, 'chat_room_member_id' =>20, 'created_at' => $save_date, 'updated_at' => $save_date],

			['chat_sender_id' =>17, 'chat_room_member_id' => 4, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' =>18, 'chat_room_member_id' => 8, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' =>19, 'chat_room_member_id' =>12, 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_sender_id' =>20, 'chat_room_member_id' =>16, 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		ChatReceiver::insert($receivers);
	}
}