<?php

namespace App\Jobs;

use App\Models\AllowedStaff;
use App\Models\Staff;
use App\Jobs\Job;

class SaveAllowedStaff extends Job
{
    protected $staff;
    protected $linked_type;
    protected $linked_id;    
    protected $can_write;
    protected $can_delete;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($staff, $linked_type, $linked_id, $can_write, $can_delete)
    {
        $this->staff = $staff;
        $this->linked_type = $linked_type;
        $this->linked_id = $linked_id;
        $this->can_write = $can_write;
        $this->can_delete = $can_delete;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(isset($this->staff) && count($this->staff) > 0) :
            foreach($this->staff as $staff_id) :
                    $staff = Staff::find($staff_id);

                    if(!is_null($staff)) :
                        $allowed_staff = new AllowedStaff;
                        $allowed_staff->staff_id = $staff_id;
                        $allowed_staff->linked_id = $this->linked_id;
                        $allowed_staff->linked_type = $this->linked_type;
                        $allowed_staff->can_edit = isset($this->can_write) ? 1 : 0;
                        $allowed_staff->can_delete = isset($this->can_delete) ? 1 : 0;
                        $allowed_staff->save();
                    endif;
            endforeach; 
        endif;
    }
}
