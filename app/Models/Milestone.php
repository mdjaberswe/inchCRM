<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Milestone extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	
	protected $table = 'milestones';
	protected $fillable = ['project_id', 'name', 'description', 'start_date', 'end_date', 'completion_percentage'];
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
	public function project()
	{
		return $this->belongsTo(Project::class);
	}

	// relation: hasMany
	public function tasks()
	{
		return $this->hasMany(Task::class);
	}
}