<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class EventAttendee extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'event_attendees';
	protected $fillable = ['event_id', 'linked_id', 'linked_type', 'status'];
	protected $appends = ['id_type'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getIdTypeAttribute()
	{
		return $this->linked_type .'-' . $this->linked_id;
	}

	public function getDisplayTypeAttribute()
	{
		$display_type = $this->linked_type == 'staff' ? 'user' : $this->linked_type;
		return ucfirst($display_type);
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function event()
	{
		return $this->belongsTo(Event::class);
	}

	// relation: morphTo
	public function linked()
	{
		return $this->morphTo()->withTrashed();
	}
}