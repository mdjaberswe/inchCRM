<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Deal;

class DealsTableSeeder extends Seeder
{
	public function run()
	{
		Deal::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$deals = [
			['position' => 1, 'account_id' => 1, 'contact_id' => 1, 'deal_owner' => 1, 'name' => 'Deal With My First Account', 'description' => $faker->sentence(10), 'currency_id' => 1, 'amount' => 10000, 'closing_date' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'deal_pipeline_id' => 1, 'deal_stage_id' => 1, 'probability' => 10, 'deal_type_id' => 1, 'source_id' => rand(1, 10), 'campaign_id' => 1, 'access' => 'public', 'created_at' => $save_date, 'updated_at' => $save_date],
			['position' => 2, 'account_id' => 2, 'contact_id' => 2, 'deal_owner' => 2, 'name' => 'Deal With My Second Account', 'description' => $faker->sentence(10), 'currency_id' => 1, 'amount' => 13000, 'closing_date' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'deal_pipeline_id' => 1, 'deal_stage_id' => 2, 'probability' => 25, 'deal_type_id' => 1, 'source_id' => rand(1, 10), 'campaign_id' => 2, 'access' => 'public', 'created_at' => $save_date, 'updated_at' => $save_date],
			['position' => 3, 'account_id' => 3, 'contact_id' => 3, 'deal_owner' => 3, 'name' => 'Deal With My Third Account', 'description' => $faker->sentence(10), 'currency_id' => 1, 'amount' => 15000, 'closing_date' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'deal_pipeline_id' => 1, 'deal_stage_id' => 3, 'probability' => 35, 'deal_type_id' => 1, 'source_id' => rand(1, 10), 'campaign_id' => 3, 'access' => 'public', 'created_at' => $save_date, 'updated_at' => $save_date],
			['position' => 4, 'account_id' => 4, 'contact_id' => 4, 'deal_owner' => 4, 'name' => 'Deal With My Fourth Account', 'description' => $faker->sentence(10), 'currency_id' => 1, 'amount' => 17000, 'closing_date' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'deal_pipeline_id' => 1, 'deal_stage_id' => 4, 'probability' => 45, 'deal_type_id' => 1, 'source_id' => rand(1, 10), 'campaign_id' => 4, 'access' => 'public', 'created_at' => $save_date, 'updated_at' => $save_date],
			['position' => 5, 'account_id' => 5, 'contact_id' => 5, 'deal_owner' => 5, 'name' => 'Deal With My Fifth Account', 'description' => $faker->sentence(10), 'currency_id' => 1, 'amount' => 20000, 'closing_date' => $faker->dateTimeInInterval('+ 2 days', '+ 5 days', null), 'deal_pipeline_id' => 1, 'deal_stage_id' => 8, 'probability' => 100, 'deal_type_id' => 1, 'source_id' => rand(1, 10), 'campaign_id' => 5, 'access' => 'public', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Deal::insert($deals);

		$participant_contacts = [
			['contact_id' => 1, 'linked_id' => 1, 'linked_type' => 'deal'],
			['contact_id' => 2, 'linked_id' => 2, 'linked_type' => 'deal'],
			['contact_id' => 3, 'linked_id' => 3, 'linked_type' => 'deal'],
			['contact_id' => 4, 'linked_id' => 4, 'linked_type' => 'deal'],
			['contact_id' => 5, 'linked_id' => 5, 'linked_type' => 'deal']
		];

		\DB::table('participant_contacts')->insert($participant_contacts);
	}
}