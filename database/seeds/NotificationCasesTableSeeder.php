<?php

use Illuminate\Database\Seeder;
use App\Models\NotificationCase;

class NotificationCasesTableSeeder extends Seeder
{
	public function run()
	{
		NotificationCase::truncate();

		$save_date = date('Y-m-d H:i:s');

		$notification_cases = [
			[
				'case_name' => 'lead_created',
				'case_display_name' => 'Lead created',
				'message_format' => '[executor] created a lead [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'lead_updated',
				'case_display_name' => 'Lead updated',
				'message_format' => '[executor] updated a lead [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'lead_deleted',
				'case_display_name' => 'Lead deleted',
				'message_format' => '[executor] deleted a lead [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'contact_created',
				'case_display_name' => 'Contact created',
				'message_format' => '[executor] created a contact [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'contact_updated',
				'case_display_name' => 'Contact updated',
				'message_format' => '[executor] updated a contact [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'contact_deleted',
				'case_display_name' => 'Contact deleted',
				'message_format' => '[executor] deleted a contact [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'account_created',
				'case_display_name' => 'Account created',
				'message_format' => '[executor] created a account [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'account_updated',
				'case_display_name' => 'Account updated',
				'message_format' => '[executor] updated a account [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'account_deleted',
				'case_display_name' => 'Account deleted',
				'message_format' => '[executor] deleted a account [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'project_created',
				'case_display_name' => 'Project created',
				'message_format' => '[executor] created a project [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'project_updated',
				'case_display_name' => 'Project updated',
				'message_format' => '[executor] updated a project [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'project_deleted',
				'case_display_name' => 'Project deleted',
				'message_format' => '[executor] deleted a project [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'project_member_added',
				'case_display_name' => 'Project member added',
				'message_format' => '[executor] added [name] in a project',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'project_member_deleted',
				'case_display_name' => 'Project member deleted',
				'message_format' => '[executor] deleted [name] from a project',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'task_created',
				'case_display_name' => 'Task created',
				'message_format' => '[executor] assigned a task to [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'task_updated',
				'case_display_name' => 'Task updated',
				'message_format' => '[executor] updated a task [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'task_deleted',
				'case_display_name' => 'Task deleted',
				'message_format' => '[executor] deleted a task [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'campaign_created',
				'case_display_name' => 'Campaign created',
				'message_format' => '[executor] created a campaign [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'campaign_updated',
				'case_display_name' => 'Campaign updated',
				'message_format' => '[executor] updated a campaign [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'campaign_deleted',
				'case_display_name' => 'Campaign deleted',
				'message_format' => '[executor] deleted a campaign [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'deal_created',
				'case_display_name' => 'Deal created',
				'message_format' => '[executor] created a deal [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'deal_updated',
				'case_display_name' => 'Deal updated',
				'message_format' => '[executor] updated a deal [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'deal_deleted',
				'case_display_name' => 'Deal deleted',
				'message_format' => '[executor] deleted a deal [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'estimate_created',
				'case_display_name' => 'Estimate created',
				'message_format' => '[executor] created an estimate [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'estimate_updated',
				'case_display_name' => 'Estimate updated',
				'message_format' => '[executor] updated an estimate [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'estimate_deleted',
				'case_display_name' => 'Estimate deleted',
				'message_format' => '[executor] deleted an estimate [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'invoice_created',
				'case_display_name' => 'Invoice created',
				'message_format' => '[executor] created an invoice [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'invoice_updated',
				'case_display_name' => 'Invoice updated',
				'message_format' => '[executor] updated an invoice [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'invoice_deleted',
				'case_display_name' => 'Invoice deleted',
				'message_format' => '[executor] deleted an invoice [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'item_created',
				'case_display_name' => 'Item created',
				'message_format' => '[executor] created an item [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'item_updated',
				'case_display_name' => 'Item updated',
				'message_format' => '[executor] updated an item [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'item_deleted',
				'case_display_name' => 'Item deleted',
				'message_format' => '[executor] deleted an item [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'expense_created',
				'case_display_name' => 'Expense created',
				'message_format' => '[executor] created an expense [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'expense_updated',
				'case_display_name' => 'Expense updated',
				'message_format' => '[executor] updated an expense [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'expense_deleted',
				'case_display_name' => 'Expense deleted',
				'message_format' => '[executor] deleted an expense [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'goal_created',
				'case_display_name' => 'Goal created',
				'message_format' => '[executor] created a goal [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'goal_updated',
				'case_display_name' => 'Goal updated',
				'message_format' => '[executor] updated a goal [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'goal_deleted',
				'case_display_name' => 'Goal deleted',
				'message_format' => '[executor] deleted a goal [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'event_created',
				'case_display_name' => 'Event created',
				'message_format' => '[executor] created an event [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'event_updated',
				'case_display_name' => 'Event updated',
				'message_format' => '[executor] updated an event [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'event_deleted',
				'case_display_name' => 'Event deleted',
				'message_format' => '[executor] deleted an event [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'user_created',
				'case_display_name' => 'User created',
				'message_format' => '[executor] created an user [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'user_updated',
				'case_display_name' => 'User updated',
				'message_format' => '[executor] updated an user [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'user_deleted',
				'case_display_name' => 'User deleted',
				'message_format' => '[executor] deleted an user [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'role_created',
				'case_display_name' => 'Role created',
				'message_format' => '[executor] created a role [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'role_updated',
				'case_display_name' => 'Role updated',
				'message_format' => '[executor] updated a role [name] <br> [details]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			],
			[
				'case_name' => 'role_deleted',
				'case_display_name' => 'Role deleted',
				'message_format' => '[executor] deleted a role [name]',
				'created_at' => $save_date,
				'updated_at' => $save_date
			]
		];

		NotificationCase::insert($notification_cases);		 
	}
}		