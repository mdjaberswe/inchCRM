<?php

namespace App\Jobs;

use App\Models\FilterView;
use App\Models\TaskStatus;
use App\Jobs\Job;

class SyncTaskFilterFixedView extends Job
{
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $completed_status = TaskStatus::whereCategory('closed')->pluck('id')->toArray();

        $owner_me = ['task_owner' => ['condition' => 'equal', 'value' => [0]]];
    
        $open_tasks = ['completion_percentage' => ['condition' => 'less', 'value' => 100], 'task_status_id' => ['condition' => 'not_equal', 'value' => $completed_status]];
        $my_open_tasks = $owner_me + $open_tasks;
        FilterView::where('is_fixed', 1)->where('view_name', 'Open Tasks')->update(['filter_params' => json_encode($open_tasks)]);
        FilterView::where('is_fixed', 1)->where('view_name', 'My Open Tasks')->update(['filter_params' => json_encode($my_open_tasks)]);

        $overdue_tasks = ['completion_percentage' => ['condition' => 'less', 'value' => 100], 'task_status_id' => ['condition' => 'not_equal', 'value' => $completed_status], 'due_date' => ['condition' => 'last', 'value' => 90]];
        $my_overdue_tasks = $owner_me + $overdue_tasks;
        FilterView::where('is_fixed', 1)->where('view_name', 'Overdue Tasks')->update(['filter_params' => json_encode($overdue_tasks)]);
        FilterView::where('is_fixed', 1)->where('view_name', 'My Overdue Tasks')->update(['filter_params' => json_encode($my_overdue_tasks)]);

        $closed_tasks = ['completion_percentage' => ['condition' => 'equal', 'value' => 100], 'task_status_id' => ['condition' => 'equal', 'value' => $completed_status]];
        $my_closed_tasks = $owner_me + $closed_tasks;
        FilterView::where('is_fixed', 1)->where('view_name', 'Closed Tasks')->update(['filter_params' => json_encode($closed_tasks)]);
        FilterView::where('is_fixed', 1)->where('view_name', 'My Closed Tasks')->update(['filter_params' => json_encode($my_closed_tasks)]);
    }
}
