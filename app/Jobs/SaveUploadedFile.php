<?php

namespace App\Jobs;

use App\Models\AttachFile;
use App\Jobs\Job;

class SaveUploadedFile extends Job
{
    protected $uploaded_files;
    protected $linked_type;
    protected $linked_id;
    protected $directory;
    protected $location;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($uploaded_files, $linked_type, $linked_id, $directory, $location)
    {
        $this->uploaded_files = $uploaded_files;
        $this->linked_type = $linked_type;
        $this->linked_id = $linked_id;
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
        if(isset($this->uploaded_files) && count($this->uploaded_files)) :
            foreach($this->uploaded_files as $uploaded_file_name) :
                $file_path = $this->location . $uploaded_file_name;   
                $file_path = $this->directory['public'] ? public_path($file_path) : storage_path($file_path);         

                if(file_exists($file_path)) :
                    $path_info = pathinfo($file_path);
                    $file_size = filesize_kb($file_path);
                    $file_name = uploaded_filename_original($uploaded_file_name);

                    $file = new AttachFile;
                    $file->name = $file_name;
                    $file->format = $path_info['extension'];
                    $file->size = $file_size;
                    $file->location = $uploaded_file_name;
                    $file->linked_id = $this->linked_id;
                    $file->linked_type = $this->linked_type;
                    $file->save();
                endif;  
            endforeach;
        endif;
    }
}
