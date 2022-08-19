<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Lead;
use App\Models\Import;
use App\Models\Source;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Revision;
use App\Models\LeadStage;
use App\Jobs\Job;

class ImportLeadData extends Job
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

        $created_data['heading'] = ['NAME', 'COMPANY', 'EMAIL', 'PHONE', 'STAGE', 'SOURCE', 'LEAD OWNER', 'WARNINGS'];
        $updated_data['heading'] = ['NAME', 'COMPANY', 'EMAIL', 'PHONE', 'STAGE', 'SOURCE', 'LEAD OWNER', 'WARNINGS'];
        $skipped_data['heading'] = ['NAME', 'COMPANY', 'EMAIL', 'PHONE', 'STAGE', 'SOURCE', 'LEAD OWNER', 'ERRORS/WARNINGS'];
        
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

            if(array_key_exists('currency_id', $row_format)) :
                $currency_exists = Currency::where('code', $row_format['currency_id'])->get();
                $row_format['currency_id'] = $currency_exists->count() ? $currency_exists->first()->id : Currency::getBase()->id;
            else :
                $row_format['currency_id'] = Currency::getBase()->id;
            endif;  

            if(array_key_exists('lead_owner', $row_format)) :
                $owner_exists = User::where('email', $row_format['lead_owner'])->onlyStaff()->get();
                $row_format['lead_owner'] = $owner_exists->count() ? $owner_exists->first()->linked_id : auth_staff()->id;
            else :
                $row_format['lead_owner'] = auth_staff()->id;
            endif;

            if(array_key_exists('lead_stage_id', $row_format)) :
                $stage_exists = LeadStage::where('name', $row_format['lead_stage_id'])->get();
                $row_format['lead_stage_id'] = $stage_exists->count() ? $stage_exists->first()->id : LeadStage::orderBy('position')->get()->first()->id;
            endif;  

            if(array_key_exists('source_id', $row_format)) :
                $source_exists = Source::where('name', $row_format['source_id'])->get();
                $row_format['source_id'] = $source_exists->count() ? $source_exists->first()->id : null;
            endif;  

            if(array_key_exists('country_code', $row_format)) :
                $country_exists = Country::where('code', $row_format['country_code'])
                                         ->orWhere('iso3', $row_format['country_code'])
                                         ->orWhere('ascii_name', $row_format['country_code'])
                                         ->orWhere('name', $row_format['country_code'])
                                         ->get();
                $row_format['country_code'] = $country_exists->count() ? $country_exists->first()->code : null;
            endif;

            if(array_key_exists('access', $row_format)) :
                $row_format['access'] = strtolower($row_format['access']);
            else :
                $row_format['access'] = 'public';    
            endif;

            if(array_key_exists('date_of_birth', $row_format)) :
                $timestamp = strtotime($row_format['date_of_birth']);
                
                if($timestamp) :
                    $row_format['date_of_birth'] = date('Y-m-d H:i:s', $timestamp);
                else :
                    $row_format['date_of_birth'] = null;
                endif;
            endif;

            if(isset($row_format['email']) && $row_format['email'] != '') :
                $lead_exists = Lead::where('email', $row_format['email'])->get();
                if($lead_exists->count()) :
                    if($import->import_type == 'new') :
                        $skip = true;
                        $warning[] = 'The email has already been taken.';
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
            $validation_data['change_owner'] = isset($row_format['lead_owner']);
            $row_validation = Lead::singleValidate($validation_data);

            if($row_validation->fails()) :
                $error_msg = $row_validation->getMessageBag()->toArray();
                $numeric_field = ['no_of_employees', 'annual_revenue'];

                if(array_key_exists('last_name', $error_msg)) :
                    $skip = true;
                    $warning[] = $error_msg['last_name'][0];                                
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
                $row_keys = array_keys($row_data);
                $position = Lead::getTargetPositionVal(-1);
                $social_media = ['facebook', 'twitter', 'skype'];
                $lead_field = array_diff($row_keys, $social_media);
                $media_field = array_intersect($row_keys, $social_media);

                if($create) :
                    $lead = new Lead;
                    $lead->position = $position;
                    $lead->save();
                    $lead->update($row_data);   
                    Revision::whereRevisionable_type('lead')->whereRevisionable_id($lead->id)->where('key', '!=', 'created_at')->forceDelete();                                    
                endif;
                
                if($update) :
                    $lead = $lead_exists->first();
                    $row_data['email'] = $lead->email;

                    if(array_key_exists('lead_stage_id', $row_data) && (is_null($lead->lead_stage_id) || empty($lead->lead_stage_id))) :
                        $lead->position = $position;
                    endif;    

                    foreach($lead_field as $field) :
                        if($field != 'email' && (is_null($lead->$field) || empty($lead->$field))) :
                            $lead->$field = $row_data[$field];
                        endif;
                    endforeach; 

                    $lead->update();
                endif;
                
                if($overwrite) :
                    $lead = $lead_exists->first();
                    $row_data['email'] = $lead->email;
                    if(array_key_exists('lead_stage_id', $row_data) && $row_data['lead_stage_id'] != $lead->lead_stage_id) :
                        $row_data['position'] = $position;
                    endif;   
                    $lead->update($row_data);
                endif;

                foreach($media_field as $media) :
                    $create_media = true;

                    if($update) :
                        $socialmedia_exists = $lead->socialmedia()->whereMedia($media)->get();
                        if($socialmedia_exists->count()) :
                            $socialmedia_data = json_decode($socialmedia_exists->first()->data, true);
                            $socialmedia_data = array_filter($socialmedia_data);
                            $create_media = !count($socialmedia_data);
                        endif;
                    endif;  

                    if($create_media) :
                        $lead->socialmedia()->whereMedia($media)->forceDelete();
                        $lead->socialmedia()->create(['media' => $media, 'data' => json_encode(['link' => $row_data[$media]])]);
                    endif;
                endforeach;

                $report_data = [
                                $lead->name,
                                $lead->company,
                                $lead->email,
                                $lead->phone,
                                non_property_checker($lead->leadstage, 'name'), 
                                non_property_checker($lead->source, 'name'),
                                non_property_checker($lead->leadowner, 'name'),
                                $warning
                            ];

                if($create) :
                    $created_data[] = $report_data;
                else :
                    $updated_data[] = $report_data;
                endif;  
            else :
                $skip_row = rename_array_key($row_original, $exchange_key);
                $skip_row_name = isset($skip_row['first_name']) ? $skip_row['first_name'] . ' ' . $skip_row['last_name'] : $skip_row['last_name'];
                $skip_row_company = isset($skip_row['company']) ? $skip_row['company'] : null;
                $skip_row_email = isset($skip_row['email']) ? $skip_row['email'] : null;
                $skip_row_phone = isset($skip_row['phone']) ? $skip_row['phone'] : null;
                $skip_row_stage = isset($skip_row['lead_stage_id']) ? $skip_row['lead_stage_id'] : null;
                $skip_row_source = isset($skip_row['source_id']) ? $skip_row['source_id'] : null;
                $skip_row_owner = isset($skip_row['lead_owner']) ? $skip_row['lead_owner'] : null;
                $skipped_data[] = [$skip_row_name, $skip_row_company, $skip_row_email, $skip_row_phone, $skip_row_stage, $skip_row_source, $skip_row_owner, $warning];  
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
