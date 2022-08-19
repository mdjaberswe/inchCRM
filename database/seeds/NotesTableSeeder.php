<?php

use Illuminate\Database\Seeder;
use App\Models\Note;

class NotesTableSeeder extends Seeder
{
	public function run()
	{
		Note::truncate();

		$save_date = date('Y-m-d H:i:s');

		$notes = [
			['note_info_id' => 1, 'linked_id' => 1, 'linked_type' => 'lead', 'created_at' => $save_date, 'updated_at' => $save_date],
			['note_info_id' => 2, 'linked_id' => 1, 'linked_type' => 'contact', 'created_at' => $save_date, 'updated_at' => $save_date],
			['note_info_id' => 3, 'linked_id' => 1, 'linked_type' => 'account', 'created_at' => $save_date, 'updated_at' => $save_date],
			['note_info_id' => 4, 'linked_id' => 1, 'linked_type' => 'project', 'created_at' => $save_date, 'updated_at' => $save_date],
			['note_info_id' => 5, 'linked_id' => 1, 'linked_type' => 'task', 'created_at' => $save_date, 'updated_at' => $save_date],
			['note_info_id' => 6, 'linked_id' => 1, 'linked_type' => 'campaign', 'created_at' => $save_date, 'updated_at' => $save_date],
			['note_info_id' => 7, 'linked_id' => 1, 'linked_type' => 'deal', 'created_at' => $save_date, 'updated_at' => $save_date],
			['note_info_id' => 8, 'linked_id' => 1, 'linked_type' => 'estimate', 'created_at' => $save_date, 'updated_at' => $save_date],
			['note_info_id' => 9, 'linked_id' => 1, 'linked_type' => 'invoice', 'created_at' => $save_date, 'updated_at' => $save_date],
			['note_info_id' =>10, 'linked_id' => 1, 'linked_type' => 'event', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Note::insert($notes);
	}
}