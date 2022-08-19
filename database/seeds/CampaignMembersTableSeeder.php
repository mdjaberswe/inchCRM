<?php

use Illuminate\Database\Seeder;

class CampaignMembersTableSeeder extends Seeder
{
	public function run()
	{
		\DB::table('campaign_members')->truncate();

		$status = ['planned' => 1, 'invited' => 2, 'sent' => 3, 'received' => 4, 'opened' => 5, 'responded' => 6, 'bounced' => 7, 'opted_out' => 8];

		$campaign_members = [
			['campaign_id' => 1, 'member_id' => 1, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 1, 'member_id' => 2, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 1, 'member_id' => 3, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 1, 'member_id' => 4, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 1, 'member_id' => 5, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 1, 'member_id' => 6, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 1, 'member_id' => 7, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 1, 'member_id' => 8, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 1, 'member_id' => 9, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 1, 'member_id' =>10, 'member_type' => 'lead', 'status' => array_rand($status)],

			['campaign_id' => 2, 'member_id' => 1, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 2, 'member_id' => 2, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 2, 'member_id' => 3, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 2, 'member_id' => 4, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 2, 'member_id' => 5, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 2, 'member_id' => 6, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 2, 'member_id' => 7, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 2, 'member_id' => 8, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 2, 'member_id' => 9, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 2, 'member_id' =>10, 'member_type' => 'lead', 'status' => array_rand($status)],

			['campaign_id' => 3, 'member_id' => 1, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 3, 'member_id' => 2, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 3, 'member_id' => 3, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 3, 'member_id' => 4, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 3, 'member_id' => 5, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 3, 'member_id' => 6, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 3, 'member_id' => 7, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 3, 'member_id' => 8, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 3, 'member_id' => 9, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 3, 'member_id' =>10, 'member_type' => 'lead', 'status' => array_rand($status)],

			['campaign_id' => 4, 'member_id' => 1, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 4, 'member_id' => 2, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 4, 'member_id' => 3, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 4, 'member_id' => 4, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 4, 'member_id' => 5, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 4, 'member_id' => 6, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 4, 'member_id' => 7, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 4, 'member_id' => 8, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 4, 'member_id' => 9, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 4, 'member_id' =>10, 'member_type' => 'lead', 'status' => array_rand($status)],

			['campaign_id' => 5, 'member_id' => 1, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 5, 'member_id' => 2, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 5, 'member_id' => 3, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 5, 'member_id' => 4, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 5, 'member_id' => 5, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 5, 'member_id' => 6, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 5, 'member_id' => 7, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 5, 'member_id' => 8, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 5, 'member_id' => 9, 'member_type' => 'lead', 'status' => array_rand($status)],
			['campaign_id' => 5, 'member_id' =>10, 'member_type' => 'lead', 'status' => array_rand($status)],

			['campaign_id' => 1, 'member_id' => 1, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 1, 'member_id' => 2, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 1, 'member_id' => 3, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 1, 'member_id' => 4, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 1, 'member_id' => 5, 'member_type' => 'contact', 'status' => array_rand($status)],

			['campaign_id' => 2, 'member_id' => 1, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 2, 'member_id' => 2, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 2, 'member_id' => 3, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 2, 'member_id' => 4, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 2, 'member_id' => 5, 'member_type' => 'contact', 'status' => array_rand($status)],

			['campaign_id' => 3, 'member_id' => 1, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 3, 'member_id' => 2, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 3, 'member_id' => 3, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 3, 'member_id' => 4, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 3, 'member_id' => 5, 'member_type' => 'contact', 'status' => array_rand($status)],

			['campaign_id' => 4, 'member_id' => 1, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 4, 'member_id' => 2, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 4, 'member_id' => 3, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 4, 'member_id' => 4, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 4, 'member_id' => 5, 'member_type' => 'contact', 'status' => array_rand($status)],

			['campaign_id' => 5, 'member_id' => 1, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 5, 'member_id' => 2, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 5, 'member_id' => 3, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 5, 'member_id' => 4, 'member_type' => 'contact', 'status' => array_rand($status)],
			['campaign_id' => 5, 'member_id' => 5, 'member_type' => 'contact', 'status' => array_rand($status)]
		];

		\DB::table('campaign_members')->insert($campaign_members);
	}
}
