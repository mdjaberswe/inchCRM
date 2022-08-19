<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Models\Country;
use App\Models\NotificationCase;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminSettingController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->middleware('admin:settings.general', ['only' => ['index', 'postGeneral']]);
		$this->middleware('admin:settings.company', ['only' => ['company', 'postCompany']]);
		$this->middleware('admin:settings.email', ['only' => ['email', 'postEmail']]);
		$this->middleware('admin:settings.SMS', ['only' => ['sms', 'postSms']]);
		$this->middleware('admin:settings.payment_gateway', ['only' => ['payment', 'paymentMethod', 'updatePaymentMethod']]);
		$this->middleware('admin:settings.notification', ['only' => ['notification', 'notificationType', 'updatePusher', 'notificationCaseData', 'updateNotificationCase', 'bulkUpdateNotificationCase']]);
		$this->middleware('admin:settings.cron_job', ['only' => ['cronjob']]);
	}



	public function index()
	{
		$page = ['title' => 'General Settings'];
		return view('admin.setting.general', compact('page'));
	}



	public function postGeneral(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$realtime_data = [];

			$validation = Setting::generalSettingValidate($request->all());
			
			if(isset($validation) && $validation->passes()) :
				$data = ['app_name'			=> $request->app_name,						
						'timezone'			=> $request->timezone,
						'date_format'		=> $request->date_format,
						'time_format'		=> $request->time_format,
						'pagination_limit'	=> $request->pagination_limit,
						'allowed_files'		=> $request->allowed_files,
						'purchase_code'		=> encrypt($request->purchase_code)];

				$upload_directory = 'uploads/app/';		

				if(isset($request->logo) && $request->logo != '') :					
					$logo_file_name = file_public_uploads($request->file('logo'), $upload_directory);					
					$save_logo_path =  $upload_directory . $logo_file_name;					
					$data['logo'] = $save_logo_path;

					clean_public_uploads(config('setting.logo'));
				endif;	

				if(isset($request->favicon) && $request->favicon != '') :
					$favicon_file_name = file_public_uploads($request->file('favicon'), $upload_directory);					
					$save_favicon_path =  $upload_directory . $favicon_file_name;					
					$data['favicon'] = $save_favicon_path;

					clean_public_uploads(config('setting.favicon'));
				endif;

				Setting::mergeSave($data);

				$realtime_data = Setting::realtimeData();
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'realtime' => $realtime_data]);
		endif;
	}



	public function company()
	{
		$page = ['title' => 'Company Settings'];
		$countries_list = ['' => '-None-'] + Country::orderBy('ascii_name')->get(['id', 'code', 'ascii_name'])->pluck('ascii_name', 'code')->toArray();
		return view('admin.setting.company', compact('page', 'countries_list'));
	}



	public function postCompany(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$realtime_data = [];

			$validation = Setting::companySettingValidate($request->all());
			
			if(isset($validation) && $validation->passes()) :
				$data = ['company_name'			=> $request->company_name,
						'company_phone'			=> null_if_empty($request->company_phone),
						'company_fax'			=> null_if_empty($request->company_fax),
						'company_website'		=> null_if_empty($request->company_website),
						'company_vat_no'		=> null_if_empty($request->company_vat_no),
						'company_description'	=> null_if_empty($request->company_description),
						'company_street'		=> null_if_empty($request->company_street),
						'company_city'			=> null_if_empty($request->company_city),
						'company_state'			=> null_if_empty($request->company_state),
						'company_zip'			=> null_if_empty($request->company_zip),
						'company_country'		=> null_if_empty($request->company_country),
						'company_info_format'	=> null_if_empty($request->company_info_format)];		

				$upload_directory = 'uploads/app/';

				if(isset($request->company_logo) && $request->company_logo != '') :					
					$logo_file_name = file_public_uploads($request->file('company_logo'), $upload_directory);					
					$save_logo_path =  $upload_directory . $logo_file_name;					
					$data['company_logo'] = $save_logo_path;

					clean_public_uploads(config('setting.company_logo'));
				endif;	

				Setting::mergeSave($data);

				$realtime_data = Setting::realtimeData();
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'realtime' => $realtime_data]);
		endif;
	}



	public function email()
	{
		$page = ['title' => 'Email Settings'];
		$mail_driver = config('setting.mail_driver');
		$mail_driver_type = in_array($mail_driver, ['mail', 'smtp']) ? [$mail_driver] : ['smtp', $mail_driver];
		return view('admin.setting.email', compact('page', 'mail_driver', 'mail_driver_type'));
	}



	public function postEmail(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;

			$validation = Setting::emailSettingValidate($request->all());
			
			if(isset($validation) && $validation->passes()) :
				$data = ['mail_driver'		=> $request->mail_driver,
						'mail_host'			=> null_if_empty($request->mail_host),
						'mail_username'		=> encrypt_if_has_value($request->mail_username),
						'mail_password'		=> encrypt_if_has_value($request->mail_password),
						'mail_port'			=> null_if_empty($request->mail_port),
						'mail_encryption'	=> null_if_empty($request->mail_encryption),
						'mailgun_domain'	=> encrypt_if_has_value($request->mailgun_domain),
						'mailgun_secret'	=> encrypt_if_has_value($request->mailgun_secret),
						'mandrill_secret'	=> encrypt_if_has_value($request->mandrill_secret),
						'ses_key'			=> encrypt_if_has_value($request->ses_key),
						'ses_secret'		=> encrypt_if_has_value($request->ses_secret),
						'ses_region'		=> encrypt_if_has_value($request->ses_region)];

				$non_encrypted_keys = ['mail_driver', 'mail_host', 'mail_port', 'mail_encryption'];

				Setting::mergeSave($data);
				Setting::updateEnv($data, $non_encrypted_keys);
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function sms()
	{
		$page = ['title' => 'SMS Settings'];
		$sms_service = config('setting.sms_service');
		return view('admin.setting.sms', compact('page', 'sms_service'));
	}



	public function postSms(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;

			$validation = Setting::smsSettingValidate($request->all());
			
			if(isset($validation) && $validation->passes()) :
				$data = ['sms_service'			=> $request->sms_service,
						'clickatell_username'	=> encrypt_if_has_value($request->clickatell_username),
						'clickatell_password'	=> encrypt_if_has_value($request->clickatell_password),
						'clickatell_api_id'		=> encrypt_if_has_value($request->clickatell_api_id),
						'twilio_account_sid'	=> encrypt_if_has_value($request->twilio_account_sid),
						'twilio_auth_token'		=> encrypt_if_has_value($request->twilio_auth_token),
						'twilio_phone_no'		=> encrypt_if_has_value($request->twilio_phone_no)];

				$non_encrypted_keys = ['sms_service'];

				Setting::mergeSave($data);
				Setting::updateEnv($data, $non_encrypted_keys);
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function payment()
	{
		$page = ['title' => 'Payment Gateways', 'item' => 'The payment setting', 'view' => 'admin.setting', 'tabs' => ['list' => ['paypal' => 'Paypal', 'stripe' => 'Stripe'], 'default' => 'paypal', 'item_id' => null, 'url' => 'setting-payment']];		
		return view('admin.setting.payment', compact('page'));
	}



	public function paymentMethod(Request $request, $payment_method)
	{
		if($request->ajax()) :			
			if(isset($request->type) && $payment_method == $request->type && in_array($payment_method, Setting::getPaymentGateway())) :
				return view('admin.setting.partials.tabs.tab-' . $payment_method);
			endif;
		endif;	
	}



	public function updatePaymentMethod(Request $request, $payment_method)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			if(isset($request->type) && $payment_method == $request->type && in_array($payment_method, Setting::getPaymentGateway())) :
				$validation = Setting::paymentValidate($data, $payment_method);
				
				if(isset($validation) && $validation->passes()) :
					Setting::paymentMethodSave($data, $payment_method);
				else :
					$status = false;
					$errors = $validation->getMessageBag()->toArray();
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function notification()
	{
		$page = ['title' => 'Notification Settings', 'item' => 'The notification setting', 'view' => 'admin.setting', 'tabs' => ['list' => ['pusher' => 'Real Time (pusher.com)', 'case' => 'Cases'], 'default' => 'pusher', 'item_id' => null, 'url' => 'setting-notification']];		
		return view('admin.setting.notification', compact('page'));
	}



	public function notificationType(Request $request, $type)
	{
		if($request->ajax()) :			
			if(isset($request->type) && $type == $request->type && in_array($type, ['case', 'pusher'])) :
				$cases_json_column = table_json_columns(['case_display_name', 'web_notification', 'email_notification', 'sms_notification']);
				$cases_table = ['dataurl' => 'setting-notification-case', 'json_columns' => $cases_json_column, 'thead' => ['CASE', 'WEB NOTIFICATION', 'EMAIL NOTIFICATION', 'SMS NOTIFICATION'], 'checkbox' => false, 'action' => false, 'all_web' => NotificationCase::allWebNotificationHtml(), 'all_email' => NotificationCase::allEmailNotificationHtml(), 'all_sms' => NotificationCase::allSmsNotificationHtml()];
				return view('admin.setting.partials.tabs.tab-' . $type, compact('cases_table'));
			endif;
		endif;
	}



	public function updatePusher(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$data = $request->all();

			$validation = Setting::pusherSettingValidate($data);
			
			if(isset($validation) && $validation->passes()) :
				$data = ['pusher_app_id'	=> encrypt_if_has_value($request->pusher_app_id),
						'pusher_app_key'	=> encrypt_if_has_value($request->pusher_app_key),
						'pusher_app_secret'	=> encrypt_if_has_value($request->pusher_app_secret),
						'pusher_cluster'	=> $request->pusher_cluster,
						'realtime_notification'	=> $request->realtime_notification,
						'desktop_notification'	=> $request->desktop_notification];

				$non_encrypted_keys = ['pusher_cluster', 'realtime_notification', 'desktop_notification'];

				Setting::mergeSave($data);
				Setting::updateEnv($data, $non_encrypted_keys);
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors]);
		endif;
	}



	public function notificationCaseData(Request $request)
	{
		if($request->ajax()) :
			$cases = NotificationCase::orderBy('id')->get();
			return DatatablesManager::notificationCaseData($cases, $request);
		endif;
	}



	public function updateNotificationCase(Request $request)
	{
		if($request->ajax()) :
			$status = false;
			$checked = null;

			if(isset($request->id) && isset($request->checked)) :
				$type_id = explode('-', $request->id);
				if(count($type_id) == 2) :
					$type = $type_id[0];
					$id = $type_id[1];
					$case = NotificationCase::whereId($id)->first();
					if(in_array($type, ['web', 'email', 'sms']) && isset($case)) :
						$checked = $request->checked ? 1 : 0;
						$column = $type . '_notification';
						$case->update([$column => $checked]);
						$status = true;
					endif;
				endif;	
			endif;

			return response()->json(['status' => $status, 'checked' => $checked]);
		endif;
	}



	public function bulkUpdateNotificationCase(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$checked = null;

			if(isset($request->ids) && isset($request->checked) && isset($request->type)) :
				$checked = $request->checked ? 1 : 0;
				$column = $request->type . '_notification';
				NotificationCase::whereIn('id', $request->ids)->update([$column => $checked]);
			else :
				$status = false;	
			endif;

			return response()->json(['status' => $status]);
		endif;
	}



	public function cronjob()
	{
		$page = ['title' => 'Cron Job'];		
		return view('admin.setting.cronjob', compact('page'));
	}
}