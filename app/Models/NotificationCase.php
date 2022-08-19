<?php

namespace App\Models;

use App\Models\BaseModel;
use Venturecraft\Revisionable\RevisionableTrait;

class NotificationCase extends BaseModel
{
	use RevisionableTrait;

	protected $table = 'notification_cases';
	protected $fillable = ['case_name', 'case_display_name', 'web_notification', 'email_notification', 'sms_notification'];
	protected $revisionCreationsEnabled = false;

	public function getWebNotificationHtmlAttribute()
	{
		$status = "<label class='switch switch-notify' type='web' data-toggle='tooltip' data-placement='top' title='Web disabled'><input type='checkbox' value='web-" . $this->id . "'><span class='slider round'></span></label>";
		if($this->web_notification) :
			$status = "<label class='switch switch-notify' type='web' data-toggle='tooltip' data-placement='top' title='Web enabled'><input type='checkbox' value='web-" . $this->id . "' checked><span class='slider round'></span></label>";
		endif;

		return $status;
	}

	public function getEmailNotificationHtmlAttribute()
	{
		$status = "<label class='switch switch-notify' type='email' data-toggle='tooltip' data-placement='top' title='Email disabled'><input type='checkbox' value='email-" . $this->id . "'><span class='slider round'></span></label>";
		if($this->email_notification) :
			$status = "<label class='switch switch-notify' type='email' data-toggle='tooltip' data-placement='top' title='Email enabled'><input type='checkbox' value='email-" . $this->id . "' checked><span class='slider round'></span></label>";
		endif;

		return $status;
	}

	public function getSmsNotificationHtmlAttribute()
	{
		$status = "<label class='switch switch-notify' type='sms' data-toggle='tooltip' data-placement='top' title='SMS disabled'><input type='checkbox' value='sms-" . $this->id . "'><span class='slider round'></span></label>";
		if($this->sms_notification) :
			$status = "<label class='switch switch-notify' type='sms' data-toggle='tooltip' data-placement='top' title='SMS enabled'><input type='checkbox' value='sms-" . $this->id . "' checked><span class='slider round'></span></label>";
		endif;

		return $status;
	}

	public static function allWebNotificationHtml()
	{
		$has_enable_web_notification = self::whereWeb_notification(1)->count();

		$status = "<label class='switch switch-all' child='web' data-toggle='tooltip' data-placement='bottom' title='All Enable/Disable'><input type='checkbox'><span class='slider round'></span></label>";
		if($has_enable_web_notification) :
			$status = "<label class='switch switch-all' child='web' data-toggle='tooltip' data-placement='bottom' title='All Enable/Disable'><input type='checkbox' checked><span class='slider round'></span></label>";
		endif;

		return $status;
	}

	public static function allEmailNotificationHtml()
	{
		$has_enable_email_notification = self::whereEmail_notification(1)->count();

		$status = "<label class='switch switch-all' child='email' data-toggle='tooltip' data-placement='bottom' title='All Enable/Disable'><input type='checkbox'><span class='slider round'></span></label>";
		if($has_enable_email_notification) :
			$status = "<label class='switch switch-all' child='email' data-toggle='tooltip' data-placement='bottom' title='All Enable/Disable'><input type='checkbox' checked><span class='slider round'></span></label>";
		endif;

		return $status;
	}

	public static function allSmsNotificationHtml()
	{
		$has_enable_sms_notification = self::whereSms_notification(1)->count();

		$status = "<label class='switch switch-all' child='sms' data-toggle='tooltip' data-placement='bottom' title='All Enable/Disable'><input type='checkbox'><span class='slider round'></span></label>";
		if($has_enable_sms_notification) :
			$status = "<label class='switch switch-all' child='sms' data-toggle='tooltip' data-placement='bottom' title='All Enable/Disable'><input type='checkbox' checked><span class='slider round'></span></label>";
		endif;

		return $status;
	}
}