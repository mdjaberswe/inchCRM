<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Activity;

class ActivitiesTableSeeder extends Seeder
{
	public function run()
	{
		Activity::truncate();

		$save_date = date('Y-m-d H:i:s');

		$activities = [
			['linked_id' => 1, 'linked_type' => 'task', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 2, 'linked_type' => 'task', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 3, 'linked_type' => 'task', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 4, 'linked_type' => 'task', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 5, 'linked_type' => 'task', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 6, 'linked_type' => 'task', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 7, 'linked_type' => 'task', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 8, 'linked_type' => 'task', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 9, 'linked_type' => 'task', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>10, 'linked_type' => 'task', 'created_at' => $save_date, 'updated_at' => $save_date],
		
			['linked_id' => 1, 'linked_type' => 'event', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 2, 'linked_type' => 'event', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 3, 'linked_type' => 'event', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 4, 'linked_type' => 'event', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 5, 'linked_type' => 'event', 'created_at' => $save_date, 'updated_at' => $save_date],
		
			['linked_id' => 1, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 2, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 3, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 4, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 5, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 6, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 7, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 8, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 9, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>10, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>11, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>12, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>13, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>14, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>15, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>16, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>17, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>18, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>19, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>20, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>21, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>22, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>23, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>24, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>25, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>26, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>27, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>28, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>29, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>30, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>31, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>32, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>33, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>34, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' =>35, 'linked_type' => 'call', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Activity::insert($activities);
	}
}		