<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\DealPipeline;

class DealPipelinesTableSeeder extends Seeder
{
	public function run()
	{
		DealPipeline::truncate();
		\DB::table('pipeline_stages')->truncate();

		$default = ['name' => 'Default Pipeline', 'default' => 1, 'period' => 30, 'position' => 1];
		DealPipeline::create($default);

		$pipeline_stages = [
			['deal_pipeline_id' => 1, 'deal_stage_id' => 1, 'position' => 1],
			['deal_pipeline_id' => 1, 'deal_stage_id' => 2, 'position' => 2],
			['deal_pipeline_id' => 1, 'deal_stage_id' => 3, 'position' => 3],
			['deal_pipeline_id' => 1, 'deal_stage_id' => 4, 'position' => 4],
			['deal_pipeline_id' => 1, 'deal_stage_id' => 5, 'position' => 5],
			['deal_pipeline_id' => 1, 'deal_stage_id' => 6, 'position' => 6],
			['deal_pipeline_id' => 1, 'deal_stage_id' => 7, 'position' => 7],
			['deal_pipeline_id' => 1, 'deal_stage_id' => 8, 'position' => 8],
			['deal_pipeline_id' => 1, 'deal_stage_id' => 9, 'position' => 9],
			['deal_pipeline_id' => 1, 'deal_stage_id' =>10, 'position' =>10]
		];

		\DB::table('pipeline_stages')->insert($pipeline_stages);
	}
}