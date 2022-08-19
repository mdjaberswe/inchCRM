<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class SocialMedia extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	
	protected $table = 'social_media';
	protected $fillable = ['linked_id', 'linked_type', 'media', 'data'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: morphTo
	public function linked()
	{
		return $this->morphTo()->withTrashed();
	}
}