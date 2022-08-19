<?php

namespace App\Jobs;

use App\Jobs\Job;

class CleanRemovedFile extends Job
{
    protected $removed_files;
    protected $directory;
    protected $location;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($removed_files, $directory, $location)
    {
        $this->removed_files = $removed_files;
        $this->directory = $directory;
        $this->location = $location;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(isset($this->removed_files)) :
            $removed_files = is_array($this->removed_files) ? $this->removed_files : [$this->removed_files];
            foreach($removed_files as $rmv_file) :
                $removed_file_path = $this->location . $rmv_file;
                unlink_file($removed_file_path, $this->directory['public']);
            endforeach; 
        endif;
    }
}
