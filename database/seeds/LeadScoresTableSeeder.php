<?php

use Illuminate\Database\Seeder;
use App\Models\LeadScore;

class LeadScoresTableSeeder extends Seeder
{
	public function run()
	{
		LeadScore::truncate();

		$save_date = date('Y-m-d H:i:s');

		$scores = [
			['score' =>-10, 'created_at' => $save_date, 'updated_at' => $save_date],
			['score' => 10, 'created_at' => $save_date, 'updated_at' => $save_date],
			['score' => 20, 'created_at' => $save_date, 'updated_at' => $save_date],
			['score' => 10, 'created_at' => $save_date, 'updated_at' => $save_date]			
		];

		LeadScore::insert($scores);
	}
}