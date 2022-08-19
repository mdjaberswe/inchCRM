<?php

namespace App\Models;

use App\Models\BaseModel;
use Venturecraft\Revisionable\RevisionableTrait;

class Setting extends BaseModel
{
	use RevisionableTrait;

	protected $table = 'settings';
	protected $fillable = ['key', 'name', 'value'];
	protected $revisionCreationsEnabled = false;	
	protected static $paymentGateway = ['paypal', 'stripe'];

	public static function getPaymentGateway()
	{
		return self::$paymentGateway;
	}

	public static function generalSettingValidate($data)
	{	
		$rules = ['app_name'	=> 'required|max:200',
				  'logo'		=> 'image|mimetypes:image/png|max:3000',
				  'favicon'		=> 'image|mimetypes:image/png|max:1000',
				  'timezone'	=> 'required|timezone',
				  'date_format'	=> 'required|in:Y-m-d,d-m-Y,m-d-Y,Y.m.d,d.m.Y,m.d.Y,Y/m/d,d/m/Y,m/d/Y',
				  'time_format'	=> 'required|in:12,12_am,24',
				  'pagination_limit'	=> 'required|integer|min:10|max:100',
				  'allowed_files'		=> 'required|max:65535',
				  'purchase_code'		=> 'required'];

		$error_messages = ['mimetypes' => ' The image must be a file of type: png. '];		  

		return \Validator::make($data, $rules, $error_messages);
	}

	public static function companySettingValidate($data)
	{	
		$rules = ['company_name'		=> 'required|max:200',
				  'company_logo'		=> 'image|mimetypes:image/png|max:3000',
				  'company_phone'		=> 'max:200',
				  'company_fax'			=> 'max:200',
				  'company_website'		=> 'max:200',
				  'company_vat_no'		=> 'max:200',
				  'company_description'	=> 'max:65535',
				  'company_street'		=> 'max:200',
				  'company_city'		=> 'max:200',
				  'company_state'		=> 'max:200',
				  'company_zip'			=> 'max:200',
				  'company_country'		=> 'exists:countries,code',
				  'company_info_format'	=> 'max:65535'];

		$error_messages = ['mimetypes' => ' The image must be a file of type: png. '];		  

		return \Validator::make($data, $rules, $error_messages);
	}

	public static function emailSettingValidate($data)
	{	
		$required = $data['mail_driver'] != 'mail' ? 'required|' : '';
		$mailgun_required = $data['mail_driver'] == 'mailgun' ? 'required|' : '';
		$mandrill_required = $data['mail_driver'] == 'mandrill' ? 'required|' : '';
		$ses_required = $data['mail_driver'] == 'ses' ? 'required|' : '';

		$rules = ['mail_driver'		=> 'required|in:mail,smtp,mailgun,mandrill,ses',
				  'mail_host'		=> $required . 'max:200',
				  'mail_username'	=> $required . 'max:200',
				  'mail_password'	=> $required . 'max:200',
				  'mail_port'		=> $required . 'max:200',
				  'mail_encryption'	=> $required . 'in:tls,ssl',
				  'mailgun_domain'	=> $mailgun_required . 'max:200',
				  'mailgun_secret'	=> $mailgun_required . 'max:200',
				  'mandrill_secret'	=> $mandrill_required . 'max:200',
				  'ses_key'			=> $ses_required . 'max:200',
				  'ses_secret'		=> $ses_required . 'max:200',
				  'ses_region'		=> $ses_required . 'max:200'];			  

		return \Validator::make($data, $rules);
	}

	public static function smsSettingValidate($data)
	{
		$clickatell_required = $data['sms_service'] == 'clickatell' ? 'required|' : '';
		$twilio_required = $data['sms_service'] == 'twilio' ? 'required|' : '';

		$rules = ['sms_service'			=> 'required|in:disabled,clickatell,twilio',
				  'clickatell_username'	=> $clickatell_required . 'max:200',
				  'clickatell_password'	=> $clickatell_required . 'max:200',
				  'clickatell_api_id'	=> $clickatell_required . 'max:200',
				  'twilio_account_sid'	=> $twilio_required . 'max:200',
				  'twilio_auth_token'	=> $twilio_required . 'max:200',
				  'twilio_phone_no'		=> $twilio_required . 'max:200'];  

		return \Validator::make($data, $rules);
	}

	public static function pusherSettingValidate($data)
	{
		$rules = ['pusher_app_id'		=> 'required|max:200',
				  'pusher_app_key'		=> 'required|max:200',
				  'pusher_app_secret'	=> 'required|max:200',
				  'pusher_cluster'		=> 'max:200',
				  'realtime_notification'	=> 'required|in:0,1',
				  'desktop_notification'	=> 'required|in:0,1'];

		return \Validator::make($data, $rules);
	}

	public static function paymentValidate($data, $payment_method)
	{
		if(in_array($payment_method, self::$paymentGateway)) :
			$rules = [];

			switch($payment_method) :
				case 'paypal' :
					$rules = ['paypal_status'	=> 'required|in:0,1',
							  'paypal_email'	=> 'required|email',
							  'paypal_mode'		=> 'required|in:sandbox,live',
							  'paypal_sandbox_client_id'=> 'required|max:200',
							  'paypal_sandbox_secret'	=> 'required|max:200',
							  'paypal_live_client_id'	=> 'required|max:200',
							  'paypal_live_secret'		=> 'required|max:200'];
				break;

				case 'stripe' :
					$rules = ['stripe_status'	=> 'required|in:0,1',
							  'stripe_mode'		=> 'required|in:test,live',
							  'stripe_test_secret_key'	=> 'required|max:200',
							  'stripe_test_public_key'	=> 'required|max:200',
							  'stripe_live_secret_key'	=> 'required|max:200',
							  'stripe_live_public_key'	=> 'required|max:200'];
				break;

				default : $rules = [];
			endswitch;

			return \Validator::make($data, $rules);
		endif;
		
		return null;	
	}

	public static function mergeSave($data)
	{
		foreach($data as $key => $value) :
			self::set($key, $value);
		endforeach;	
	}

	public static function set($key, $value)
	{
		$set_key = self::whereKey($key)->first();

		if(isset($set_key) & is_object($set_key)) :
			$set_key->value = $value;
		else :
			$set_key = new self;
			$set_key->key = $key;
			$set_key->name = snake_to_ucwords($key);
			$set_key->value = $value;
		endif;
		$set_key->save();

		return $set_key;
	}

	public static function updateEnv($data, $non_encrypted_keys = [])
	{
		if(is_file_writable(base_path('.env'))) :
			foreach($data as $key => $value) :			
				$value = in_array($key, $non_encrypted_keys) ? $value : check_before_decrypt($value);
				self::setEnv(strtoupper($key), $value);
			endforeach;

			return true;
		endif;
		
		return false;
	}

	public static function setEnv($key, $value)
	{		
		$env_path = base_path('.env');
		$env_data = file($env_path);
		$env_data = array_map(function($single_env_data) use ($key, $value) 
					{
						$single_env_key = explode('=', $single_env_data)[0];
					    return $single_env_key == $key ? "$key=$value\n" : $single_env_data;
					}, 
					$env_data);

		$env_file = fopen($env_path, 'w');
		fwrite($env_file, implode($env_data, ''));
		fclose($env_file);
	}

	public static function envLastSaved()
	{
		return file_modified_at(base_path('.env'));
	}

	public static function paymentMethodSave($data, $method)
	{
		if(in_array($method, self::$paymentGateway)) :
			$save = [];
			$non_encrypted_keys = [];

			if($method == 'paypal') :
				$save = ['paypal_status'=> $data['paypal_status'],
						'paypal_email'	=> encrypt_if_has_value($data['paypal_email']),
						'paypal_mode'	=> $data['paypal_mode'],
						'paypal_sandbox_client_id'	=> encrypt_if_has_value($data['paypal_sandbox_client_id']),
						'paypal_sandbox_secret'		=> encrypt_if_has_value($data['paypal_sandbox_secret']),
						'paypal_live_client_id'		=> encrypt_if_has_value($data['paypal_live_client_id']),
						'paypal_live_secret'		=> encrypt_if_has_value($data['paypal_live_secret'])];

				$status = $data['paypal_status'];			
				$non_encrypted_keys = ['paypal_status', 'paypal_mode'];
			endif;	

			if($method == 'stripe') :
				$save = ['stripe_status'=> $data['stripe_status'],
						'stripe_mode'	=> $data['stripe_mode'],
						'stripe_test_secret_key'=> encrypt_if_has_value($data['stripe_test_secret_key']),
						'stripe_test_public_key'=> encrypt_if_has_value($data['stripe_test_public_key']),
						'stripe_live_secret_key'=> encrypt_if_has_value($data['stripe_live_secret_key']),
						'stripe_live_public_key'=> encrypt_if_has_value($data['stripe_live_public_key'])];

				$status = $data['stripe_status'];		
				$non_encrypted_keys = ['stripe_status', 'stripe_mode'];
			endif;

			self::mergeSave($save);
			self::updateEnv($save, $non_encrypted_keys);
			PaymentMethod::whereName(ucfirst($method))->whereMasked(1)->first()->update(['status' => $status]);
		endif;	
	}

	public static function realtimeData()
	{
		$outcome = ['logo'			=> ['tag' => 'img', 'value' => asset(setting('logo', 'img/default-logo.png'))],
					'favicon'		=> ['tag' => 'img', 'value' => asset(setting('favicon', 'img/default-favicon.png'))],
					'company_logo'	=> ['tag' => 'img', 'value' => asset(setting('company_logo'))]];

		return $outcome;			
	}
}	