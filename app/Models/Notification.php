<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Notification extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'notifications';
	protected $fillable = ['notification_info_id', 'linked_id', 'linked_type', 'read_at'];
	protected $dates = ['deleted_at', 'read_at'];
	protected $revisionCreationsEnabled = true;

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeUnreadNotification($query)
	{
	    $query->whereNull('read_at');
	}

	public function scopeAuthStaffOnly($query)
	{
		$query->whereLinked_type('staff')->whereLinked_id(auth_staff()->id);
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getLinkAttribute()
	{
		return $this->info->linked->show_route;
	}

	public function getTitleAttribute()
	{
		return get_notification_title($this->info->case, $this->info->linked_type);
	}

	public function getAdditionalInfoAttribute()
	{
		$outcome = snake_to_ucwords($this->info->linked_type) . ': ' . $this->info->linked->name;
		return $outcome;
	}

	public function getNotificationFromAttribute()
	{
		$from = is_null($this->createdBy()) ? $this->linked->profile_html : $this->createdBy()->linked->profile_html;
		return $from;
	}

	public function getCreatedByNameAttribute()
	{
		$name = is_null($this->createdByName()) ? $this->linked->name : $this->createdByName();
		return $name;
	}

	public function getAvatarAttribute()
	{
		$avatar = is_null($this->createdByAvatar()) ? $this->linked->avatar : $this->createdByAvatar();
		return $avatar;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function info()
	{
		return $this->belongsTo(NotificationInfo::class, 'notification_info_id');
	}

	// relation: morphTo
	public function linked()
	{
		return $this->morphTo()->withTrashed();
	}
}