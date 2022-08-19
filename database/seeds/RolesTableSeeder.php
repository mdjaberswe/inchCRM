<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
	public function run()
	{
		Role::truncate();

		$save_date = date('Y-m-d H:i:s');

		$roles = [
			['name' => 'administrator', 'display_name' => 'Administrator', 'description' => 'Manage all modules', 'fixed' => 1, 'label' => 'general', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'standard', 'display_name' => 'Standard', 'description' => 'Manage and perform basic modules', 'fixed' => 1, 'label' => 'general', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'hr_manager', 'display_name' => 'HR Manager', 'description' => 'Manage employees, announcements, calendar', 'fixed' => 0, 'label' => 'general', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'accountant', 'display_name' => 'Accountant', 'description' => 'Manage accounting', 'fixed' => 0, 'label' => 'general', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'project_reviewer', 'display_name' => 'Project Reviewer', 'description' => 'Review Projects & Tasks', 'fixed' => 0, 'label' => 'general', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'software_engineer', 'display_name' => 'Software Engineer', 'description' => 'Do projects and tasks', 'fixed' => 0, 'label' => 'general', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'business_executive', 'display_name' => 'Business Executive', 'description' => 'Manage sales & contracts', 'fixed' => 0, 'label' => 'general', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'counselor', 'display_name' => 'Counselor', 'description' => 'Manage leads, campaigns, accounts', 'fixed' => 0, 'label' => 'general', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'support_executive', 'display_name' => 'Support Executive', 'description' => 'Manage account support', 'fixed' => 0, 'label' => 'general', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'data_analyst', 'display_name' => 'Data Analyst', 'description' => 'Analyze dashboard & report', 'fixed' => 0, 'label' => 'general', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'project_manager', 'display_name' => 'Project Manager', 'description' => 'Manage Projects & Tasks', 'fixed' => 1, 'label' => 'project', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'team_member', 'display_name' => 'Project Team Member', 'description' => 'Perform project tasks', 'fixed' => 1, 'label' => 'project', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'client.deal.view', 'display_name' => 'View', 'description' => 'Client deal view own only', 'fixed' => 1, 'label' => 'client', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'client.deal.view_all', 'display_name' => 'View all', 'description' => 'Client deal view all', 'fixed' => 1, 'label' => 'client', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'client.project.view', 'display_name' => 'View', 'description' => 'Client project view own only', 'fixed' => 1, 'label' => 'client', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'client.project.view_all', 'display_name' => 'View all', 'description' => 'Client project view all', 'fixed' => 1, 'label' => 'client', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'client.estimate.view', 'display_name' => 'View', 'description' => 'Client estimate view own only', 'fixed' => 1, 'label' => 'client', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'client.estimate.view_all', 'display_name' => 'View all', 'description' => 'Client estimate view All', 'fixed' => 1, 'label' => 'client', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'client.invoice.view', 'display_name' => 'View', 'description' => 'Client invoice view own only', 'fixed' => 1, 'label' => 'client', 'created_at' => $save_date, 'updated_at' => $save_date],
			['name' => 'client.invoice.view_all', 'display_name' => 'View all', 'description' => 'Client invoice view all', 'fixed' => 1, 'label' => 'client', 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Role::insert($roles);
	}
}