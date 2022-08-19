<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\ChatSender;

class ChatSendersTableSeeder extends Seeder
{
	public function run()
	{
		ChatSender::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$senders = [
			['chat_room_member_id' => 1, 'message' => 'Hi! How are you?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' => 2, 'message' => 'Hi! How are you?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' => 3, 'message' => 'Hi! How are you?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' => 4, 'message' => 'Hi! How are you?', 'created_at' => $save_date, 'updated_at' => $save_date],
		
			['chat_room_member_id' => 5, 'message' => 'fine and you?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' => 6, 'message' => 'How is life?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' => 7, 'message' => 'How is life?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' => 8, 'message' => 'How is life?', 'created_at' => $save_date, 'updated_at' => $save_date],

			['chat_room_member_id' => 9, 'message' => 'fine and you?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' =>10, 'message' => 'Awesome! yours?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' =>11, 'message' => 'Have weekend plan?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' =>12, 'message' => 'Have weekend plan?', 'created_at' => $save_date, 'updated_at' => $save_date],

			['chat_room_member_id' =>13, 'message' => 'fine and you?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' =>14, 'message' => 'Awesome! yours?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' =>15, 'message' => 'Going to NewYork. Yours?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' =>16, 'message' => 'Have you complete the task?', 'created_at' => $save_date, 'updated_at' => $save_date],

			['chat_room_member_id' =>17, 'message' => 'fine and you?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' =>18, 'message' => 'Awesome! yours?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' =>19, 'message' => 'Going to NewYork. Yours?', 'created_at' => $save_date, 'updated_at' => $save_date],
			['chat_room_member_id' =>20, 'message' => 'Yes', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		ChatSender::insert($senders);
	}
}