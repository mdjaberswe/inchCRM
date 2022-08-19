<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Reminder extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'reminders';
	protected $fillable = ['reminder_to', 'reminder_before', 'reminder_before_type', 'reminder_date', 'is_notified', 'email_notification', 'sms_notification', 'description', 'linked_id', 'linked_type'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function notifee()
	{
		return $this->belongsTo(Staff::class, 'reminder_to')->withTrashed();
	}

	// relation: morphTo
	public function linked()
	{
		return $this->morphTo();
	}
}