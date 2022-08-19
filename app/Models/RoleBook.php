<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class RoleBook extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'rolebooks';
	protected $fillable = ['staff_id', 'role_id', 'linked_id', 'linked_type'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function staff()
	{
		return $this->belongsTo(Staff::class)->withTrashed();
	}

	// relation: morphTo
	public function linked()
	{
		return $this->morphTo();
	}
}