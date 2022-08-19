<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\FilterView;

class FilterViewsTableSeeder extends Seeder
{
	public function run()
	{
		FilterView::truncate();

		$faker = Faker::create();
		$save_date = date('Y-m-d H:i:s');

		$views = [
			['module_name' => 'task', 'view_name' => 'My Open Tasks', 'filter_params' => json_encode(['task_owner' => ['condition' => 'equal', 'value' => [0]], 'completion_percentage' => ['condition' => 'less', 'value' => 100], 'task_status_id' => ['condition' => 'not_equal', 'value' => [5]]]), 'visible_type' => 'everyone', 'visible_to' => null, 'is_fixed' => 1, 'is_default' => 1, 'created_at' => $save_date, 'updated_at' => $save_date],
			['module_name' => 'task', 'view_name' => 'My Overdue Tasks', 'filter_params' => json_encode(['task_owner' => ['condition' => 'equal', 'value' => [0]], 'completion_percentage' => ['condition' => 'less', 'value' => 100], 'task_status_id' => ['condition' => 'not_equal', 'value' => [5]], 'due_date' => ['condition' => 'last', 'value' => 90]]), 'visible_type' => 'everyone', 'visible_to' => null, 'is_fixed' => 1, 'is_default' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['module_name' => 'task', 'view_name' => 'My Closed Tasks', 'filter_params' => json_encode(['task_owner' => ['condition' => 'equal', 'value' => [0]], 'completion_percentage' => ['condition' => 'equal', 'value' => 100], 'task_status_id' => ['condition' => 'equal', 'value' => [5]]]), 'visible_type' => 'everyone', 'visible_to' => null, 'is_fixed' => 1, 'is_default' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['module_name' => 'task', 'view_name' => 'All Tasks', 'filter_params' => null, 'visible_type' => 'everyone', 'visible_to' => null, 'is_fixed' => 1, 'is_default' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['module_name' => 'task', 'view_name' => 'Open Tasks', 'filter_params' => json_encode(['completion_percentage' => ['condition' => 'less', 'value' => 100], 'task_status_id' => ['condition' => 'not_equal', 'value' => [5]]]), 'visible_type' => 'everyone', 'visible_to' => null, 'is_fixed' => 1, 'is_default' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['module_name' => 'task', 'view_name' => 'Overdue Tasks', 'filter_params' => json_encode(['completion_percentage' => ['condition' => 'less', 'value' => 100], 'task_status_id' => ['condition' => 'not_equal', 'value' => [5]], 'due_date' => ['condition' => 'last', 'value' => 90]]), 'visible_type' => 'everyone', 'visible_to' => null, 'is_fixed' => 1, 'is_default' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
			['module_name' => 'task', 'view_name' => 'Closed Tasks', 'filter_params' => json_encode(['completion_percentage' => ['condition' => 'equal', 'value' => 100], 'task_status_id' => ['condition' => 'equal', 'value' => [5]]]), 'visible_type' => 'everyone', 'visible_to' => null, 'is_fixed' => 1, 'is_default' => 0, 'created_at' => $save_date, 'updated_at' => $save_date],
		];

		FilterView::insert($views);

		\DB::table('staff_view')->truncate();
		$staffs = \App\Models\Staff::all();
		foreach($staffs as $staff) :
			$staff->views()->attach(rand(1, 7));
		endforeach;
	}
}