<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\SocialMedia;

class SocialMediaTableSeeder extends Seeder
{
	public function run()
	{
		SocialMedia::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$social_media = [
			['linked_id' => 1, 'linked_type' => 'staff', 'media' => 'facebook', 'data' => json_encode(['link' => 'james.gardner']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'account', 'media' => 'facebook', 'data' => json_encode(['link' => 'ask.inc']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'contact', 'media' => 'facebook', 'data' => json_encode(['link' => 'ellen.hings']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'contact', 'media' => 'twitter', 'data' => json_encode(['link' => 'ellen23.hings']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'contact', 'media' => 'skype', 'data' => json_encode(['link' => 'ellen1.hings']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'lead', 'media' => 'facebook', 'data' => json_encode(['link' => 'john.doe']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'lead', 'media' => 'twitter', 'data' => json_encode(['link' => 'john-doe']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'lead', 'media' => 'linkedin', 'data' => json_encode(['link' => 'john.doe55']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'lead', 'media' => 'googleplus', 'data' => json_encode(['link' => 'john.doe.1987']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'lead', 'media' => 'github', 'data' => json_encode(['link' => 'john.doe.cse']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'lead', 'media' => 'tumblr', 'data' => json_encode(['link' => 'john.doe']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'lead', 'media' => 'skype', 'data' => json_encode(['link' => 'john-doe.us']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'lead', 'media' => 'snapchat', 'data' => json_encode(['link' => 'john.doe.happy']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'lead', 'media' => 'youtube', 'data' => json_encode(['link' => 'john.doe.live']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'lead', 'media' => 'instagram', 'data' => json_encode(['link' => 'john.doe.world']), 'created_at' => $save_date, 'updated_at' => $save_date],
			['linked_id' => 1, 'linked_type' => 'lead', 'media' => 'pinterest', 'data' => json_encode(['link' => 'john.doe90']), 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		SocialMedia::insert($social_media);
	}
}