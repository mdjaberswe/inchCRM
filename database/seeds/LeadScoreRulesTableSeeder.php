<?php

use Illuminate\Database\Seeder;
use App\Models\LeadScoreRule;

class LeadScoreRulesTableSeeder extends Seeder
{
	public function run()
	{
		LeadScoreRule::truncate();

		$save_date = date('Y-m-d H:i:s');

		$rules = [
			['lead_score_id' => 1, 'related_to' => 'lead_property', 'attribute' => 'email', 'condition' => 'empty', 'value' => null, 'description' => "The lead property <span class='highlight'>Email</span> is empty.", 'created_at' => $save_date, 'updated_at' => $save_date],
			['lead_score_id' => 1, 'related_to' => 'lead_property', 'attribute' => 'phone', 'condition' => 'empty', 'value' => null, 'description' => "The lead property <span class='highlight'>Phone</span> is empty.", 'created_at' => $save_date, 'updated_at' => $save_date],
			['lead_score_id' => 2, 'related_to' => 'lead_property', 'attribute' => 'company', 'condition' => 'not_empty', 'value' => null, 'description' => "The lead property <span class='highlight'>Company</span> is not empty.", 'created_at' => $save_date, 'updated_at' => $save_date],
			['lead_score_id' => 3, 'related_to' => 'lead_property', 'attribute' => 'annual_revenue', 'condition' => 'greater', 'value' => 50000, 'description' => "The lead property <span class='highlight'>Annual Revenue</span> is greater than <span class='highlight'>50,000</span>", 'created_at' => $save_date, 'updated_at' => $save_date],
			['lead_score_id' => 4, 'related_to' => 'lead_property', 'attribute' => 'title', 'condition' => 'equal', 'value' => json_encode(['CEO', 'Manager']), 'description' => "The lead property <span class='highlight'>Job Title</span> is equal to any of <span class='highlight'>CEO, <i>or</i> Manager</span>.", 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		LeadScoreRule::insert($rules);
	}
}