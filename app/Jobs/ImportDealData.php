<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Deal;
use App\Models\Source;
use App\Models\Import;
use App\Models\Account;
use App\Models\Contact;
use App\Models\Campaign;
use App\Models\Currency;
use App\Models\Revision;
use App\Models\DealType;
use App\Models\DealStage;
use App\Models\DealPipeline;
use App\Jobs\Job;

class ImportDealData extends Job
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

        $created_data['heading'] = ['DEAL NAME', 'AMOUNT', 'CLOSING DATE', 'PIPELINE', 'STAGE', 'PROBABILITY (%)', 'WARNINGS'];
        $updated_data['heading'] = ['DEAL NAME', 'AMOUNT', 'CLOSING DATE', 'PIPELINE', 'STAGE', 'PROBABILITY (%)', 'WARNINGS'];
        $skipped_data['heading'] = ['DEAL NAME', 'AMOUNT', 'CLOSING DATE', 'PIPELINE', 'STAGE', 'PROBABILITY (%)', 'ERRORS/WARNINGS'];
        
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

            $currency = Currency::getBase();
            if(array_key_exists('currency_id', $row_format)) :
                $currency_exists = Currency::where('code', $row_format['currency_id'])->get();
                $currency = $currency_exists->count() ? $currency_exists->first() : $currency; 
            endif; 
            $row_format['currency_id'] = $currency->id;

            $owner = auth_staff();
            if(array_key_exists('deal_owner', $row_format)) :
                $owner_exists = User::where('email', $row_format['deal_owner'])->onlyStaff()->get();
                $owner = $owner_exists->count() ? $owner_exists->first()->linked : $owner;      
            endif;
            $row_format['deal_owner'] = $owner->id;

            $pipeline = DealPipeline::default()->first();
            if(array_key_exists('deal_pipeline_id', $row_format)) :
                $pipeline_exists = DealPipeline::where('name', $row_format['deal_pipeline_id'])->get();
                $pipeline = $pipeline_exists->count() ? $pipeline_exists->first() : $pipeline;              
            endif;
            $row_format['deal_pipeline_id'] = $pipeline->id;

            $stage = $pipeline->stages->first();
            if(array_key_exists('deal_stage_id', $row_format)) :
                $stage_exists = $pipeline->stages()->where('name', $row_format['deal_stage_id'])->get();
                $stage = $stage_exists->count() ? $stage_exists->first() : $stage;              
            endif;
            $row_format['deal_stage_id'] = $stage->id;

            $probability = $stage->probability;
            if(array_key_exists('probability', $row_format)) :
                $probability = is_numeric($row_format['probability']) ? $row_format['probability'] : $probability;   
            endif;
            $row_format['probability'] = $probability;

            $closing_date = date("Y-m-d", strtotime("+$pipeline->period days"));
            if(array_key_exists('closing_date', $row_format)) :
                $timestamp = strtotime($row_format['closing_date']);
                
                if($timestamp) :
                    $closing_date = date('Y-m-d', $timestamp);
                endif;
            endif;
            $row_format['closing_date'] = $closing_date;

            if(array_key_exists('account_id', $row_format)) :
                $account_exists = Account::where('account_name', $row_format['account_id'])->get();
                $row_format['account_id'] = $account_exists->count() ? $account_exists->first()->id : 0;
            else :
                $row_format['account_id'] = null;
            endif;

            if(array_key_exists('contact_id', $row_format)) :
                $user = User::onlyContact()->whereEmail($row_format['contact_id'])->get();
                if($user->count()) :
                    $contact = $user->first()->linked->where('account_id', $row_format['account_id'])->get();
                    $row_format['contact_id'] = $contact->count() ? $contact->first()->id : null;
                endif;    
            endif;

            if(array_key_exists('deal_type_id', $row_format)) :
                $type_exists = DealType::where('name', $row_format['deal_type_id'])->get();
                $row_format['deal_type_id'] = $type_exists->count() ? $type_exists->first()->id : null;
            endif;

            if(array_key_exists('source_id', $row_format)) :
                $source_exists = Source::where('name', $row_format['source_id'])->get();
                $row_format['source_id'] = $source_exists->count() ? $source_exists->first()->id : null;
            endif;  

            if(array_key_exists('campaign_id', $row_format)) :
                $campaign_exists = Campaign::where('name', $row_format['campaign_id'])->get();
                $row_format['campaign_id'] = $campaign_exists->count() ? $campaign_exists->first()->id : null;
            endif;  

            if(array_key_exists('access', $row_format)) :
                $row_format['access'] = strtolower($row_format['access']);
            else :
                $row_format['access'] = 'public';    
            endif;

            if(isset($row_format['name']) && $row_format['name'] != '') :
                $deal_exists = Deal::where('name', $row_format['name'])->get();
                if($deal_exists->count()) :
                    if($import->import_type == 'new') :
                        $create = true;
                        $warning[] = 'The deal name field has duplicate records.';
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
            $validation_data['id'] = $deal_exists->count() ? $deal_exists->first()->id : null;
            $validation_data['change_owner'] = isset($row_format['deal_owner']);
            $row_validation = Deal::singleValidate($validation_data);

            if($row_validation->fails()) :
                $error_msg = $row_validation->getMessageBag()->toArray();
                $numeric_field = ['amount', 'probability'];

                if(array_key_exists('name', $error_msg)) :
                    $skip = true;
                    $warning[] = $error_msg['name'][0];                                
                endif;

                if(array_key_exists('account_id', $error_msg)) :
                    $skip = true;
                    $warning[] = $error_msg['account_id'][0];                                
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
                $deal_field = array_keys($row_data);
                $position = Deal::getTargetPositionVal(-1);

                if($create) :
                    $deal = new Deal;
                    $deal->position = $position;
                    $deal->save();
                    $deal->update($row_data);
                    Revision::whereRevisionable_type('deal')->whereRevisionable_id($deal->id)->where('key', '!=', 'created_at')->forceDelete();                                    
                endif;

                if($update || $overwrite) :
                    $deal = $deal_exists->first();
                    $row_data['name'] = $deal->name;
                endif;   
                
                if($update) : 
                    if(array_key_exists('deal_stage_id', $row_data) && (is_null($deal->deal_stage_id) || empty($deal->deal_stage_id))) :
                        $deal->position = $position;
                    endif;    

                    foreach($deal_field as $field) :
                        if($field != 'name' && (is_null($deal->$field) || empty($deal->$field))) :
                            $deal->$field = $row_data[$field];
                        endif;
                    endforeach; 

                    $deal->update();
                endif;
                
                if($overwrite) :
                    if(array_key_exists('deal_stage_id', $row_data) && $row_data['deal_stage_id'] != $deal->deal_stage_id) :
                        $row_data['position'] = $position;
                    endif;

                    $deal->update($row_data);
                endif;

                $report_data = [
                                $deal->name,
                                $deal->amount,
                                $deal->readableDateHtml('closing_date'),
                                $deal->pipeline->name, 
                                $deal->stage->name,
                                $deal->probability,
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
                $skip_row_amount = isset($skip_row['amount']) ? $skip_row['amount'] : null;
                $skip_row_closing_date = isset($skip_row['closing_date']) ? $skip_row['closing_date'] : null;
                $skip_row_pipeline = isset($skip_row['deal_pipeline_id']) ? $skip_row['deal_pipeline_id'] : null;
                $skip_row_stage = isset($skip_row['deal_stage_id']) ? $skip_row['deal_stage_id'] : null;
                $skip_row_probability = isset($skip_row['probability']) ? $skip_row['probability'] : null;
                $skipped_data[] = [$skip_row_name, $skip_row_amount, $skip_row_closing_date, $skip_row_pipeline, $skip_row_stage, $skip_row_probability, $warning];  
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
