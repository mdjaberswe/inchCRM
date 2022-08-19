<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class NotificationInfo extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'notification_infos';
	protected $fillable = ['case', 'linked_id', 'linked_type'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: hasMany
	public function notifications()
	{
		return $this->hasMany(Notification::class, 'notification_info_id');
	}

	// relation: morphTo
	public function linked()
	{
		return $this->morphTo()->withTrashed();
	}
}