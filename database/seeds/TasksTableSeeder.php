<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Task;

class TasksTableSeeder extends Seeder
{
	public function run()
	{
		Task::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$tasks = [
			['position' => 1, 'task_owner' => 1, 'linked_type' => 'lead', 'linked_id' => 1, 'milestone_id' => null, 'name' => 'Set collaborators to help aginee', 'description' => 'Task description', 'priority' => 'normal', 'access' => 'private', 'completion_percentage' => 30, 'task_status_id' => 3, 'start_date' => date('Y-m-d H:i:s'), 'due_date' => $faker->dateTimeInInterval('+ 2 days', '+ 15 days', null), 'created_at' => $save_date, 'updated_at' => $save_date],
			['position' => 2, 'task_owner' => 2, 'linked_type' => 'lead', 'linked_id' => 1, 'milestone_id' => null, 'name' => 'Track project on depend on me', 'description' => 'Task description', 'priority' => 'high', 'access' => 'private', 'completion_percentage' => 40, 'task_status_id' => 3, 'start_date' => date('Y-m-d H:i:s'), 'due_date' => $faker->dateTimeInInterval('+ 2 days', '+ 15 days', null), 'created_at' => $save_date, 'updated_at' => $save_date],
			['position' => 3, 'task_owner' => 3, 'linked_type' => 'contact', 'linked_id' => 1, 'milestone_id' => null, 'name' => 'Complete me before due date', 'description' => 'Task description', 'priority' => 'low', 'access' => 'private', 'completion_percentage' => 50, 'task_status_id' => 3, 'start_date' => date('Y-m-d H:i:s'), 'due_date' => $faker->dateTimeInInterval('+ 2 days', '+ 15 days', null), 'created_at' => $save_date, 'updated_at' => $save_date],
			['position' => 4, 'task_owner' => 4, 'linked_type' => 'contact', 'linked_id' => 1, 'milestone_id' => null, 'name' => 'Do more with Project', 'description' => 'Task description', 'priority' => 'highest', 'access' => 'private', 'completion_percentage' => 60, 'task_status_id' => 3, 'start_date' => date('Y-m-d H:i:s'), 'due_date' => $faker->dateTimeInInterval('+ 2 days', '+ 15 days', null), 'created_at' => $save_date, 'updated_at' => $save_date],
			['position' => 5, 'task_owner' => 5, 'linked_type' => 'account', 'linked_id' => 1, 'milestone_id' => null, 'name' => 'Start a discussion or upload a file', 'description' => 'Task description', 'priority' => 'high', 'access' => 'private', 'completion_percentage' => 70, 'task_status_id' => 4, 'start_date' => date('Y-m-d H:i:s'), 'due_date' => $faker->dateTimeInInterval('+ 2 days', '+ 15 days', null), 'created_at' => $save_date, 'updated_at' => $save_date],
			['position' => 6, 'task_owner' => 1, 'linked_type' => 'account', 'linked_id' => 1, 'milestone_id' => null, 'name' => 'Assign me to your team members', 'description' => 'Task description', 'priority' => 'lowest', 'access' => 'private', 'completion_percentage' => 20, 'task_status_id' => 3, 'start_date' => date('Y-m-d H:i:s'), 'due_date' => $faker->dateTimeInInterval('+ 2 days', '+ 15 days', null), 'created_at' => $save_date, 'updated_at' => $save_date],
			['position' => 7, 'task_owner' => 2, 'linked_type' => 'project', 'linked_id' => 1, 'milestone_id' => 1, 'name' => 'Set my status, with the traffic light button', 'description' => 'Task description', 'priority' => 'low', 'access' => 'private', 'completion_percentage' => 30, 'task_status_id' => 3, 'start_date' => date('Y-m-d H:i:s'), 'due_date' => $faker->dateTimeInInterval('+ 2 days', '+ 15 days', null), 'created_at' => $save_date, 'updated_at' => $save_date],
			['position' => 8, 'task_owner' => 3, 'linked_type' => 'project', 'linked_id' => 1, 'milestone_id' => 1, 'name' => 'Drag and drop me into priority', 'description' => 'Task description', 'priority' => 'high', 'access' => 'private', 'completion_percentage' => 40, 'task_status_id' => 3, 'start_date' => date('Y-m-d H:i:s'), 'due_date' => $faker->dateTimeInInterval('+ 2 days', '+ 15 days', null), 'created_at' => $save_date, 'updated_at' => $save_date],
			['position' => 9, 'task_owner' => 4, 'linked_type' => 'campaign', 'linked_id' => 1, 'milestone_id' => null, 'name' => 'Set a due date on me', 'description' => 'Task description', 'priority' => 'low', 'access' => 'private', 'completion_percentage' => 100, 'task_status_id' => 5, 'start_date' => date('Y-m-d H:i:s'), 'due_date' => $faker->dateTimeInInterval('+ 2 days', '+ 15 days', null), 'created_at' => $save_date, 'updated_at' => $save_date],
			['position' =>10, 'task_owner' => 5, 'linked_type' => 'campaign', 'linked_id' => 1, 'milestone_id' => null, 'name' => 'I am a task, complete me!', 'description' => 'Task description', 'priority' => 'normal', 'access' => 'public', 'completion_percentage' => 90, 'task_status_id' => 4, 'start_date' => date('Y-m-d H:i:s'), 'due_date' => $faker->dateTimeInInterval('+ 2 days', '+ 15 days', null), 'created_at' => $save_date, 'updated_at' => $save_date]
		];

		Task::insert($tasks);
	}
}