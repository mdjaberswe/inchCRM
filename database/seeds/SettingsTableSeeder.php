<?php

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsTableSeeder extends Seeder
{
	public function run()
	{
		Setting::truncate();

		$settings = ['app_name' => ['App name', 'CrispyCRM'],
					 'logo' => ['Logo', 'img/default-logo.png'],
					 'favicon' => ['Favicon', 'img/default-favicon.png'],
					 'timezone' => ['Timezone', config('app.timezone')],
					 'date_format' => ['Date format', 'Y-m-d'],
					 'time_format' => ['Time format', '12'],			 
					 'allowed_files' => ['Allowed file types', 'png,jpg,pdf,doc,docx,xls,xlsx,zip,rar,txt'],
					 'pagination_limit' => ['Default pagination limit', 10],

					 'company_name' => ['Company name', null],					 
					 'company_phone' => ['Company phone', null],
					 'company_fax' => ['Company fax', null],
					 'company_website' => ['Company website', null],
					 'company_vat_no' => ['Company vat number', null],
					 'company_description' => ['Company description', null],
					 'company_street' => ['Company street', null],
					 'company_city' => ['Company city', null],
					 'company_state' => ['Company state', null],
					 'company_zip' => ['Company zip code', null],
					 'company_country' => ['Company country', null],
					 'company_logo' => ['Company logo', null],
					 'company_info_format' => ['Company information format', '[company_name]<br>[street]<br>[city] [state]<br>[country] [zip_code]'],

					 'mail_driver' => ['Mail driver', 'mail'],

					 'mail_host' => ['Mail host', null],
					 'mail_port' => ['Mail port', null],
					 'mail_username' => ['Mail username', null],
					 'mail_password' => ['Mail password', null],
					 'mail_encryption' => ['Mail encryption', null],

					 'mailgun_domain' => ['Mailgun domain', null],
					 'mailgun_secret' => ['Mailgun secret', null],

					 'mandrill_secret' => ['Mandrill secret', null],

					 'ses_key' => ['SES key', null],
					 'ses_secret' => ['SES secret', null],
					 'ses_region' => ['SES region', null],

					 'sms_service' => ['Default SMS Service', 'disabled'],

					 'clickatell_username' => ['Clickatell username', null],
					 'clickatell_password' => ['Clickatell password', null],
					 'clickatell_api_id' => ['Clickatell api id', null],

					 'twilio_account_sid' => ['Twilio account sid', null],
					 'twilio_auth_token' => ['Twilio authentication token', null],
					 'twilio_phone_no' => ['Twilio phone number', null],

					 'paypal_status' => ['Paypal status', 0],
					 'paypal_email' => ['Paypal email', null],
					 'paypal_mode' => ['Paypal mode', 'sandbox'], // live, sandbox
					 'paypal_sandbox_client_id'	=> ['Paypal sandbox client id', null],
					 'paypal_sandbox_secret' => ['Paypal sandbox secret', null],
					 'paypal_live_client_id' => ['Paypal live client id', null],
					 'paypal_live_secret' => ['Paypal live secret', null],

					 'stripe_status' => ['Stripe status', 0],
					 'stripe_mode' => ['Stripe mode', 'test'], // live, test
					 'stripe_test_secret_key'	=> ['Stripe test secret key', null],
					 'stripe_test_public_key' => ['Stripe test public key', null],
					 'stripe_live_secret_key' => ['Stripe live secret key', null],
					 'stripe_live_public_key' => ['Stripe live public key', null],
					 
					 'pusher_app_id' => ['Pusher app id', null],
					 'pusher_app_key' => ['Pusher app key', null],
					 'pusher_app_secret' => ['Pusher app secret', null],
					 'pusher_cluster' => ['Pusher cluster', null],

					 'realtime_notification' => ['Enable real time notification', 0],
					 'desktop_notification' => ['Enable desktop notification', 0],

					 'cold_lead_label' => ['Cold lead Label', 'Cold'],
					 'warm_lead_label' => ['Warm lead Label', 'Warm'],
					 'hot_lead_label' => ['Hot lead Label', 'Hot'],
					 'cold_lead_low' => ['Cold lead lower limit', 0],
					 'cold_lead_up' => ['Cold lead upper limit', 30],
					 'warm_lead_low' => ['Warm lead lower limit', 31],
					 'warm_lead_up' => ['Warm lead upper limit', 70],					 
					 'hot_lead_low' => ['Hot lead lower limit', 71],
					 'hot_lead_up' => ['Hot lead upper limit', 99]];

		foreach($settings as $key => $value) :
			$setting = new Setting;
			$setting->key = $key;
			$setting->name = $value[0];
			$setting->value = $value[1];
			$setting->save();
		endforeach;				 
	}
}		