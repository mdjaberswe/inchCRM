<?php

namespace App\Jobs;

use DB;
use App\Models\User;
use App\Models\Task;
use App\Models\Import;
use App\Models\Revision;
use App\Models\TaskStatus;
use App\Jobs\Job;

class ImportTaskData extends Job
{
    protected $import;
    protected $map;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Import $import, $data)
    {
        $this->import = $import;
        $this->map = $data['map'];
        $this->data = $data['import_data'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $import = $this->import;  
        $map = $this->map;  
        $import_data = $this->data;

        $created_data = [];                             
        $updated_data = [];
        $skipped_data = [];

        $created_data['heading'] = ['TASK NAME', 'DUE DATE', 'STATUS', 'PRIORITY', 'RELATED TO', 'TASK OWNER', 'WARNINGS'];
        $updated_data['heading'] = ['TASK NAME', 'DUE DATE', 'STATUS', 'PRIORITY', 'RELATED TO', 'TASK OWNER', 'WARNINGS'];
        $skipped_data['heading'] = ['TASK NAME', 'DUE DATE', 'STATUS', 'PRIORITY', 'RELATED TO', 'TASK OWNER', 'ERRORS/WARNINGS'];
        
        unset($map['import']);
        $exchange_key = array_filter($map);
        $remove_key = array_diff_assoc($map, $exchange_key);
        $remove_key = count($remove_key) ? array_keys($remove_key) : false;

        foreach($import_data as $row) :
            $row_original = $row;

            if($remove_key) :
                array_forget($row, $remove_key);
            endif;

            $row_format = rename_array_key($row, $exchange_key);
            $create = false;
            $update = false;
            $overwrite = false;
            $skip = false;
            $warning = [];

            $task_status = TaskStatus::orderBy('position')->get()->first();
            if(array_key_exists('task_status_id', $row_format)) :
                $task_status_exists = TaskStatus::where('name', $row_format['task_status_id'])->get();
                $task_status = $task_status_exists->count() ? $task_status_exists->first() : $task_status; 
            endif; 
            $row_format['task_status_id'] = $task_status->id;

            $owner = null;
            if(array_key_exists('task_owner', $row_format)) :
                $owner_exists = User::where('email', $row_format['task_owner'])->onlyStaff()->get();
                $owner = $owner_exists->count() ? $owner_exists->first()->linked->id : $owner;      
            endif;
            $row_format['task_owner'] = $owner;

            $priority = null;
            if(array_key_exists('priority', $row_format)) :
                $data_priority = strtolower($row_format['priority']);
                if(in_array($data_priority, Task::prioritylist())) :
                    $priority = $data_priority;
                endif;
            endif;
            $row_format['priority'] = $priority;

            $completion_percentage = 0;
            if(array_key_exists('completion_percentage', $row_format)) :
                $completion_percentage = is_numeric($row_format['completion_percentage']) ? $row_format['completion_percentage'] : $completion_percentage;   
                $completion_percentage = $task_status->category == 'closed' ? 100 : $completion_percentage;
            endif;
            $row_format['completion_percentage'] = $completion_percentage;

            $start_date = null;
            if(array_key_exists('start_date', $row_format)) :
                $start_timestamp = strtotime($row_format['start_date']);
                
                if($start_timestamp) :
                    $start_date = date('Y-m-d', $start_timestamp);
                endif;
            endif;
            $row_format['start_date'] = $start_date;

            $due_date = null;
            if(array_key_exists('due_date', $row_format)) :
                $due_timestamp = strtotime($row_format['due_date']);
                
                if($due_timestamp) :
                    $due_date = date('Y-m-d', $due_timestamp);
                endif;

                if(isset($start_timestamp) && $start_timestamp > $due_timestamp) :
                    $row_format['start_date'] = null;
                endif;    
            endif;
            $row_format['due_date'] = $due_date;

            $linked_type = null;
            $linked_id = null;
            if(array_key_exists('linked_type', $row_format) && array_key_exists('linked_id', $row_format)) :
                $data_linked_type = strtolower($row_format['linked_type']);
                if(in_array($data_linked_type, Task::relatedTypes())) :
                    $linked_model = morph_to_model($data_linked_type);
                    $linked_id_exists = $linked_model::readableIdentifier($row_format['linked_id'])->get();
                    if(($data_linked_type == 'lead' || $data_linked_type == 'contact') && $linked_id_exists->count()) :
                        $linked_id_exists = $linked_id_exists->where('name', $row_format['linked_id']);
                    endif;    
  
                    if($linked_id_exists->count()) :
                        $linked_type = $data_linked_type;
                        $linked_id = $linked_id_exists->first()->id;
                    endif;    
                endif;
            endif;
            $row_format['linked_type'] = $linked_type;
            $row_format['linked_id'] = $linked_id;

            if(array_key_exists('access', $row_format)) :
                $row_format['access'] = strtolower($row_format['access']);
            else :
                $row_format['access'] = 'public';    
            endif;

            if(isset($row_format['name']) && $row_format['name'] != '') :
                $task_exists = Task::where('name', $row_format['name'])->get();
                if($task_exists->count()) :
                    if($import->import_type == 'new') :
                        $create = true;
                        $warning[] = 'The task name field has duplicate records.';
                    elseif($import->import_type == 'update') :
                        $update = true;
                    else :
                        $overwrite = true;
                    endif;  
                else :
                    $create = true;
                endif;  
            else :
                $create = true;     
            endif;  

            $validation_data = $row_format;
            $validation_data['id'] = $task_exists->count() ? $task_exists->first()->id : null;
            $validation_data['change_owner'] = isset($row_format['task_owner']);
            $row_validation = Task::singleValidate($validation_data);

            if($row_validation->fails()) :
                $error_msg = $row_validation->getMessageBag()->toArray();
                $numeric_field = ['completion_percentage'];

                if(array_key_exists('name', $error_msg)) :
                    $skip = true;
                    $warning[] = $error_msg['name'][0];                                
                endif;

                foreach($error_msg as $error_field => $msg) :
                    if(in_array($error_field, $numeric_field)) :
                        $row_format[$error_field] = 0;
                    elseif($error_field == 'access') :
                        $row_format[$error_field] = 'public';
                    else :
                        $row_format[$error_field] = null;
                    endif;  
                endforeach;
            endif;
            
            if(!$skip) :
                $row_data = replace_null_if_empty($row_format);
                $task_field = array_keys($row_data);
                $position = Task::getTargetPositionVal(-1);

                if($create) :
                    $task = new Task;
                    $task->position = $position;
                    $task->save();
                    $task->update($row_data);
                    Revision::whereRevisionable_type('task')->whereRevisionable_id($task->id)->where('key', '!=', 'created_at')->forceDelete();                                    
                endif;

                if($update || $overwrite) :
                    $task = $task_exists->first();
                    $row_data['name'] = $task->name;
                endif;   
                
                if($update) : 
                    if(array_key_exists('task_status_id', $row_data) && (is_null($task->task_status_id) || empty($task->task_status_id))) :
                        $task->position = $position;
                    endif;    

                    foreach($task_field as $field) :
                        if($field != 'name' && (is_null($task->$field) || empty($task->$field))) :
                            $task->$field = $row_data[$field];
                        endif;
                    endforeach; 

                    $task->update();
                endif;
                
                if($overwrite) :
                    if(array_key_exists('task_status_id', $row_data) && $row_data['task_status_id'] != $task->task_status_id) :
                        $row_data['position'] = $position;
                    endif;

                    $task->update($row_data);
                endif;

                $report_data = [
                                $task->name,
                                $task->readableDateHtml('due_date'),
                                $task->status->name, 
                                ucfirst($task->priority),
                                non_property_checker($task->linked, 'name_link_icon'),
                                non_property_checker($task->owner, 'name'),
                                $warning
                            ];

                if($create) :
                    $created_data[] = $report_data;
                else :
                    $updated_data[] = $report_data;
                endif;  
            else :
                $skip_row = rename_array_key($row_original, $exchange_key);
                $skip_row_name = isset($skip_row['name']) ? $skip_row['name'] : null;
                $skip_row_due_date = isset($skip_row['due_date']) ? $skip_row['due_date'] : null;
                $skip_row_status = isset($skip_row['task_status_id']) ? $skip_row['task_status_id'] : null;
                $skip_row_priority = isset($skip_row['priority']) ? $skip_row['priority'] : null;
                $skip_row_related = isset($skip_row['linked_type']) && isset($skip_row['linked_id']) ? $skip_row['linked_type'] . ' - ' . $skip_row['linked_id'] : null;
                $skip_row_owner = isset($skip_row['task_owner']) ? $skip_row['task_owner'] : null;
                $skipped_data[] = [$skip_row_name, $skip_row_due_date, $skip_row_status, $skip_row_priority, $skip_row_related, $skip_row_owner, $warning];  
            endif;
        endforeach;

        $import->is_imported = 1;
        $import->created_data = json_encode($created_data);
        $import->updated_data = json_encode($updated_data);
        $import->skipped_data = json_encode($skipped_data);
        $import->initial_data = null;
        $import->update();
    }
}
