<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Role;
use App\Models\Source;
use App\Models\Import;
use App\Models\Contact;
use App\Models\Account;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Revision;
use App\Models\ContactType;
use App\Jobs\Job;

class ImportContactData extends Job
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

        $created_data['heading'] = ['NAME', 'ACCOUNT', 'EMAIL', 'PHONE', 'CONTACT TYPE', 'SOURCE', 'CONTACT OWNER', 'WARNINGS'];
        $updated_data['heading'] = ['NAME', 'ACCOUNT', 'EMAIL', 'PHONE', 'CONTACT TYPE', 'SOURCE', 'CONTACT OWNER', 'WARNINGS'];
        $skipped_data['heading'] = ['NAME', 'ACCOUNT', 'EMAIL', 'PHONE', 'CONTACT TYPE', 'SOURCE', 'CONTACT OWNER', 'ERRORS/WARNINGS'];
        
        unset($map['import']);
        $exchange_key = array_filter($map);
        $remove_key = array_diff_assoc($map, $exchange_key);
        $remove_key = count($remove_key) ? array_keys($remove_key) : false;
        $default_role = Role::getClientDefaultIds();

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

            if(array_key_exists('account_id', $row_format)) :
                $account_exists = Account::where('account_name', $row_format['account_id'])->get();
                $row_format['account_id'] = $account_exists->count() ? $account_exists->first()->id : 0;
            else :
                $row_format['account_id'] = null;
            endif;

            if(array_key_exists('parent_id', $row_format)) :
                $parent_contact = User::onlyContact()->whereEmail($row_format['parent_id'])->get();
                $row_format['parent_id'] = null;
                if($parent_contact->count()) :
                    $parent_contact = Contact::whereId($parent_contact->first()->linked_id)->where('account_id', $row_format['account_id'])->get();
                    $row_format['parent_id'] = $parent_contact->count() ? $parent_contact->first()->id : null;
                endif;    
            endif;

            if(array_key_exists('currency_id', $row_format)) :
                $currency_exists = Currency::where('code', $row_format['currency_id'])->get();
                $row_format['currency_id'] = $currency_exists->count() ? $currency_exists->first()->id : Currency::getBase()->id;
            else :
                $row_format['currency_id'] = Currency::getBase()->id;
            endif;  

            if(array_key_exists('contact_owner', $row_format)) :
                $owner_exists = User::where('email', $row_format['contact_owner'])->onlyStaff()->get();
                $row_format['contact_owner'] = $owner_exists->count() ? $owner_exists->first()->linked_id : auth_staff()->id;
            else :
                $row_format['contact_owner'] = auth_staff()->id;
            endif;

            if(array_key_exists('contact_type_id', $row_format)) :
                $contact_type_exists = ContactType::where('name', $row_format['contact_type_id'])->get();
                $row_format['contact_type_id'] = $contact_type_exists->count() ? $contact_type_exists->first()->id : null;
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

            $password = str_random(8);
            $password_status = null;
            if(array_key_exists('password', $row_format)) :
                if(not_null_empty($row_format['password'])) :
                    $password_status = 1;
                    $password = $row_format['password'];
                else :
                    $password_status = 0;
                endif;    
            endif;

            if(array_key_exists('status', $row_format)) :
                $status = 1;

                if(!is_bool($row_format['status'])) :
                    $str_status = (string)$row_format['status'];

                    switch($str_status) :
                        case '1':
                        case 'true':
                        case 'True':
                        case 'TRUE':
                            $row_format['status'] = 1;
                        break;  

                        case '0':
                        case 'false':
                        case 'False':
                        case 'FALSE':
                            $row_format['status'] = 0;
                        break;    

                        default: 
                            $status = 0;
                            $row_format['status'] = 1; 
                    endswitch;    
                endif;
            else :
                $status = null;
                $row_format['status'] = 1;    
            endif;

            if(isset($row_format['email']) && $row_format['email'] != '') :
                $email_exists = User::where('email', $row_format['email'])->get();
                if($email_exists->count()) :
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
            $validation_data['change_owner'] = isset($row_format['contact_owner']);
            $row_validation = Contact::singleValidate($validation_data);

            if($row_validation->fails()) :
                $error_msg = $row_validation->getMessageBag()->toArray();
                $numeric_field = ['annual_revenue'];

                if(array_key_exists('last_name', $error_msg)) :
                    $skip = true;
                    $warning[] = $error_msg['last_name'][0];                                
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
                        if($error_field != 'email') :
                            $row_format[$error_field] = null;
                        endif;    
                    endif;  
                endforeach;
            endif;

            if(!$skip) :
                $row_data = replace_null_if_empty($row_format);                
                $row_keys = array_keys($row_data);
                $user_field_keys = ['email', 'password', 'status'];
                $social_media = ['facebook', 'twitter', 'skype'];            
                $non_contacted_field = array_merge($user_field_keys, $social_media);
                $contact_field = array_diff($row_keys, $non_contacted_field);   
                $contact_data = array_except($row_data, $non_contacted_field);
                $user_fields = array_intersect($row_keys, $user_field_keys);             
                $media_field = array_intersect($row_keys, $social_media);

                if($create) :
                    $contact = new Contact;
                    $contact->save();
                    $contact->update($contact_data);   

                    $user = new User;               
                    $user->email = $row_data['email'];             
                    $user->password = bcrypt($password);
                    $user->status = $row_data['status'];
                    $user->linked_id = $contact->id;
                    $user->linked_type = 'contact';
                    $user->save();  

                    $user->roles()->attach($default_role);

                    Revision::whereRevisionable_type('contact')->whereRevisionable_id($contact->id)->where('key', '!=', 'created_at')->forceDelete();       
                    Revision::whereRevisionable_type('user')->whereRevisionable_id($user->id)->where('key', '!=', 'created_at')->forceDelete();                              
                endif;

                if($update || $overwrite) :
                    $contact = $email_exists->first()->linked;
                    $update_parent_status = $update ? !not_null_empty($contact->parent_id) : true;

                    if(array_key_exists('parent_id', $row_data) && not_null_empty($row_data['parent_id']) && $update_parent_status) :
                        $supervisor = Contact::find($row_data['parent_id']);
                        if(non_property_checker($supervisor, 'parent_id') == $contact->id) :
                            $supervisor->update(['parent_id' => $contact->parent_id]);
                        endif;
                    endif;
                endif;    
                
                if($update) :
                    foreach($contact_field as $field) :
                        if($field != 'email' && !not_null_empty($contact->$field)) :
                            $contact->$field = $row_data[$field];
                        endif;
                    endforeach;

                    $contact->update();
                endif;
                
                if($overwrite) :
                    $contact->update($contact_data);

                    $user = $contact->user;
                    foreach($user_fields as $user_field) :
                        if($user_field == 'status' && $status == 1) :
                            $user->status = $row_data['status'];
                        endif;

                        if($user_field == 'password' && $password_status == 1) :
                            $user->password = bcrypt($password);
                        endif;
                    endforeach;
                    $user->update();
                endif;

                foreach($media_field as $media) :
                    $create_media = true;

                    if($update) :
                        $socialmedia_exists = $contact->socialmedia()->whereMedia($media)->get();                        
                        if($socialmedia_exists->count()) :
                            $socialmedia_data = json_decode($socialmedia_exists->first()->data, true);                         
                            $socialmedia_data = array_filter($socialmedia_data);
                            $create_media = !count($socialmedia_data);
                        endif;
                    endif;  

                    if($create_media) :
                        $contact->socialmedia()->whereMedia($media)->forceDelete();
                        $contact->socialmedia()->create(['media' => $media, 'data' => json_encode(['link' => $row_data[$media]])]);
                    endif;
                endforeach;

                $report_data = [
                                $contact->name,
                                $contact->account->name,
                                $contact->email,
                                $contact->phone,
                                non_property_checker($contact->type, 'name'), 
                                non_property_checker($contact->source, 'name'),
                                non_property_checker($contact->contactowner, 'name'),
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
                $skip_row_company = isset($skip_row['account_id']) ? $skip_row['account_id'] : null;
                $skip_row_email = isset($skip_row['email']) ? $skip_row['email'] : null;
                $skip_row_phone = isset($skip_row['phone']) ? $skip_row['phone'] : null;
                $skip_row_stage = isset($skip_row['contact_type_id']) ? $skip_row['contact_type_id'] : null;
                $skip_row_source = isset($skip_row['source_id']) ? $skip_row['source_id'] : null;
                $skip_row_owner = isset($skip_row['contact_owner']) ? $skip_row['contact_owner'] : null;
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
