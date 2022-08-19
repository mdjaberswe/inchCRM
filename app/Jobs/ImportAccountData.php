<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Import;
use App\Models\Account;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Revision;
use App\Models\AccountType;
use App\Models\IndustryType;
use App\Jobs\Job;

class ImportAccountData extends Job
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

        $created_data['heading'] = ['ACCOUNT NAME', 'EMAIL', 'PHONE', 'ACCOUNT TYPE', 'INDUSTRY', 'ACCOUNT OWNER', 'WARNINGS'];
        $updated_data['heading'] = ['ACCOUNT NAME', 'EMAIL', 'PHONE', 'ACCOUNT TYPE', 'INDUSTRY', 'ACCOUNT OWNER', 'WARNINGS'];
        $skipped_data['heading'] = ['ACCOUNT NAME', 'EMAIL', 'PHONE', 'ACCOUNT TYPE', 'INDUSTRY', 'ACCOUNT OWNER', 'ERRORS/WARNINGS'];
        
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

            if(array_key_exists('account_owner', $row_format)) :
                $owner_exists = User::where('email', $row_format['account_owner'])->onlyStaff()->get();
                $row_format['account_owner'] = $owner_exists->count() ? $owner_exists->first()->linked_id : auth_staff()->id;
            else :
                $row_format['account_owner'] = auth_staff()->id;
            endif;

            if(array_key_exists('parent_id', $row_format)) :
                $parent_exists = Account::where('account_name', $row_format['parent_id'])->get();
                $row_format['parent_id'] = $parent_exists->count() ? $parent_exists->first()->id : null;
            endif;

            if(array_key_exists('account_type_id', $row_format)) :
                $type_exists = AccountType::where('name', $row_format['account_type_id'])->get();
                $row_format['account_type_id'] = $type_exists->count() ? $type_exists->first()->id : null;
            endif;  

            if(array_key_exists('industry_type_id', $row_format)) :
                $industry_exists = IndustryType::where('name', $row_format['industry_type_id'])->get();
                $row_format['industry_type_id'] = $industry_exists->count() ? $industry_exists->first()->id : null;
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

            if(isset($row_format['account_name']) && $row_format['account_name'] != '') :
                $account_exists = Account::where('account_name', $row_format['account_name'])->get();
                if($account_exists->count()) :
                    if($import->import_type == 'new') :
                        $skip = true;
                        $warning[] = 'The account name has already been taken.';
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
            $validation_data['id'] = $account_exists->count() ? $account_exists->first()->id : null;
            $validation_data['change_owner'] = isset($row_format['account_owner']);
            $row_validation = Account::singleValidate($validation_data);

            if($row_validation->fails()) :
                $error_msg = $row_validation->getMessageBag()->toArray();
                $numeric_field = ['no_of_employees', 'annual_revenue'];

                if(array_key_exists('account_name', $error_msg)) :
                    $skip = true;
                    $warning[] = $error_msg['account_name'][0];                                
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
                $social_media = ['facebook', 'twitter', 'skype'];
                $account_field = array_diff($row_keys, $social_media);
                $media_field = array_intersect($row_keys, $social_media);

                if($create) :
                    $account = new Account;
                    $account->save();
                    $account->update($row_data);   
                    Revision::whereRevisionable_type('account')->whereRevisionable_id($account->id)->where('key', '!=', 'created_at')->forceDelete();                                    
                endif;

                if($update || $overwrite) :
                    $account = $account_exists->first();
                    $row_data['account_name'] = $account->account_name;
                    $update_parent_status = $update ? !not_null_empty($account->parent_id) : true;

                    if(array_key_exists('parent_id', $row_data) && not_null_empty($row_data['parent_id']) && $update_parent_status) :
                        $parent_account = Account::find($row_data['parent_id']);
                        if(non_property_checker($parent_account, 'parent_id') == $account->id) :
                            $parent_account->update(['parent_id' => $account->parent_id]);
                        endif;
                    endif;
                endif;   
                
                if($update) : 
                    foreach($account_field as $field) :
                        if($field != 'account_name' && (is_null($account->$field) || empty($account->$field))) :
                            $account->$field = $row_data[$field];
                        endif;
                    endforeach; 

                    $account->update();
                endif;
                
                if($overwrite) :
                    $account->update($row_data);
                endif;

                foreach($media_field as $media) :
                    $create_media = true;

                    if($update) :
                        $socialmedia_exists = $account->socialmedia()->whereMedia($media)->get();
                        if($socialmedia_exists->count()) :
                            $socialmedia_data = json_decode($socialmedia_exists->first()->data, true);
                            $socialmedia_data = array_filter($socialmedia_data);
                            $create_media = !count($socialmedia_data);
                        endif;
                    endif;  

                    if($create_media) :
                        $account->socialmedia()->whereMedia($media)->forceDelete();
                        $account->socialmedia()->create(['media' => $media, 'data' => json_encode(['link' => $row_data[$media]])]);
                    endif;
                endforeach;

                $report_data = [
                                $account->account_name,
                                $account->account_email,
                                $account->account_phone,
                                non_property_checker($account->type, 'name'), 
                                non_property_checker($account->industry, 'name'),
                                non_property_checker($account->owner, 'name'),
                                $warning
                            ];

                if($create) :
                    $created_data[] = $report_data;
                else :
                    $updated_data[] = $report_data;
                endif;  
            else :
                $skip_row = rename_array_key($row_original, $exchange_key);
                $skip_row_name = isset($skip_row['account_name']) ? $skip_row['account_name'] : null;
                $skip_row_email = isset($skip_row['account_email']) ? $skip_row['account_email'] : null;
                $skip_row_phone = isset($skip_row['account_phone']) ? $skip_row['account_phone'] : null;
                $skip_row_type = isset($skip_row['account_type_id']) ? $skip_row['account_type_id'] : null;
                $skip_row_industry = isset($skip_row['industry_type_id']) ? $skip_row['industry_type_id'] : null;
                $skip_row_owner = isset($skip_row['account_owner']) ? $skip_row['account_owner'] : null;
                $skipped_data[] = [$skip_row_name, $skip_row_email, $skip_row_phone, $skip_row_type, $skip_row_industry, $skip_row_owner, $warning];  
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
