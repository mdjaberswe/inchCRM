<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class AllowedStaff extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'allowed_staffs';
	protected $fillable = ['staff_id', 'linked_id', 'linked_type', 'can_view', 'can_edit', 'can_delete'];
	protected $dates = ['deleted_at'];
	protected $types = ['lead', 'account', 'contact', 'project', 'task', 'campaign', 'event', 'deal', 'estimate', 'invoice', 'goal'];
	protected $revisionCreationsEnabled = true;

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public static function getValidTypes()
    {
        return implode(',', with(new static)->types);
    }

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function staff()
	{
		return $this->belongsTo(Staff::class);
	}

	// relation: morphTo
	public function linked()
	{
		return $this->morphTo();
	}
}