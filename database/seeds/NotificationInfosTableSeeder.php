<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\NotificationInfo;

class NotificationInfosTableSeeder extends Seeder
{
	public function run()
	{
		NotificationInfo::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$notification_infos = [
			['case' => 'lead_created', 'linked_id' => 1, 'linked_type' => 'lead', 'created_at' => $save_date, 'updated_at' => $save_date],
			['case' => 'account_created', 'linked_id' => 1, 'linked_type' => 'account', 'created_at' => $save_date, 'updated_at' => $save_date],
			['case' => 'project_created', 'linked_id' => 1, 'linked_type' => 'project', 'created_at' => $save_date, 'updated_at' => $save_date],
			['case' => 'task_assigned', 'linked_id' => 1, 'linked_type' => 'task', 'created_at' => $save_date, 'updated_at' => $save_date],
			['case' => 'campaign_created', 'linked_id' => 1, 'linked_type' => 'campaign', 'created_at' => $save_date, 'updated_at' => $save_date],	
			['case' => 'deal_created', 'linked_id' => 1, 'linked_type' => 'deal', 'created_at' => $save_date, 'updated_at' => $save_date],
			
			['case' => 'estimate_created', 'linked_id' => 1, 'linked_type' => 'estimate', 'created_at' => $save_date, 'updated_at' => $save_date],
			['case' => 'estimate_sent', 'linked_id' => 1, 'linked_type' => 'estimate', 'created_at' => $save_date, 'updated_at' => $save_date],
			['case' => 'estimate_accepted', 'linked_id' => 1, 'linked_type' => 'estimate', 'created_at' => $save_date, 'updated_at' => $save_date],
			['case' => 'estimate_declined', 'linked_id' => 1, 'linked_type' => 'estimate', 'created_at' => $save_date, 'updated_at' => $save_date],	
			
			['case' => 'invoice_created', 'linked_id' => 1, 'linked_type' => 'invoice', 'created_at' => $save_date, 'updated_at' => $save_date],
			['case' => 'invoice_sent', 'linked_id' => 1, 'linked_type' => 'invoice', 'created_at' => $save_date, 'updated_at' => $save_date],
			['case' => 'invoice_paid', 'linked_id' => 1, 'linked_type' => 'invoice', 'created_at' => $save_date, 'updated_at' => $save_date],
			['case' => 'invoice_partially_paid', 'linked_id' => 1, 'linked_type' => 'invoice', 'created_at' => $save_date, 'updated_at' => $save_date],
			
			['case' => 'goal_created', 'linked_id' => 1, 'linked_type' => 'goal', 'created_at' => $save_date, 'updated_at' => $save_date],	
			['case' => 'event_created', 'linked_id' => 1, 'linked_type' => 'event', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		NotificationInfo::insert($notification_infos);
	}
}