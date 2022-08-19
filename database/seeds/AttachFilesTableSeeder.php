<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\AttachFile;

class AttachFilesTableSeeder extends Seeder
{
	public function run()
	{
		AttachFile::truncate();

		$save_date = date('Y-m-d H:i:s');

		$attachments = [
			['name' => 'Customer-relationship management - Wikipedia', 'location' => 'https://en.wikipedia.org/wiki/Customer-relationship_management', 'linked_id' => 1, 'linked_type' => 'lead', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Customer-relationship management - Wikipedia', 'location' => 'https://en.wikipedia.org/wiki/Customer-relationship_management', 'linked_id' => 1, 'linked_type' => 'contact', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Customer-relationship management - Wikipedia', 'location' => 'https://en.wikipedia.org/wiki/Customer-relationship_management', 'linked_id' => 1, 'linked_type' => 'account', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Customer-relationship management - Wikipedia', 'location' => 'https://en.wikipedia.org/wiki/Customer-relationship_management', 'linked_id' => 1, 'linked_type' => 'project', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Customer-relationship management - Wikipedia', 'location' => 'https://en.wikipedia.org/wiki/Customer-relationship_management', 'linked_id' => 1, 'linked_type' => 'task', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Customer-relationship management - Wikipedia', 'location' => 'https://en.wikipedia.org/wiki/Customer-relationship_management', 'linked_id' => 1, 'linked_type' => 'campaign', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Customer-relationship management - Wikipedia', 'location' => 'https://en.wikipedia.org/wiki/Customer-relationship_management', 'linked_id' => 1, 'linked_type' => 'deal', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Customer-relationship management - Wikipedia', 'location' => 'https://en.wikipedia.org/wiki/Customer-relationship_management', 'linked_id' => 1, 'linked_type' => 'estimate', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Customer-relationship management - Wikipedia', 'location' => 'https://en.wikipedia.org/wiki/Customer-relationship_management', 'linked_id' => 1, 'linked_type' => 'invoice', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'Customer-relationship management - Wikipedia', 'location' => 'https://en.wikipedia.org/wiki/Customer-relationship_management', 'linked_id' => 1, 'linked_type' => 'event', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		AttachFile::insert($attachments);
	}
}