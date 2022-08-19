<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Call extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'calls';
	protected $fillable = ['subject', 'type', 'client_type', 'client_id', 'related_type', 'related_id', 'call_time', 'description'];
	protected $appends = ['name'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;
	protected static $related_types = ['account', 'deal', 'project', 'campaign', 'event', 'task', 'estimate', 'invoice'];

	public static function validate($data)
	{	
		$valid_related_types = implode(',', self::$related_types);

		$rules = ['subject'			=> 'required|max:200',
				  'type'			=> 'required|in:incoming,outgoing',
				  'client_type'		=> 'required|in:lead,contact',
				  'client_id'		=> 'required|exists:' . $data['client_type'] . 's,id,deleted_at,NULL',
				  'related_type'	=> 'in:' . $valid_related_types,
				  'call_time'		=> 'required|date',
				  'description'		=> 'max:65535'];

		if(array_key_exists('related_type', $data) && !empty($data['related_type'])) :
			$rules['related_id'] = 'required|exists:' . $data['related_type'] . 's,id,deleted_at,NULL';
		endif;  

		return \Validator::make($data, $rules);
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getTypeHtmlAttribute()
	{
		$html = ucfirst($this->type) . ' Call<br>';
		$html.= "<span class='sm-txt'>" . $this->readableDateHtml('call_time', true). "</span>";
		return $html; 
	}

	public function getNameAttribute()
	{
		return $this->subject;
	}

	public function getStartDateAttribute()
	{
		return $this->call_time;
	}

	public function getDueDateAttribute()
	{
		return null;
	}

	public function getOwnerAttribute()
	{
		return $this->createdBy()->linked;
	}

	public function getIconAttribute()
	{
		return module_icon($this->identifier . '-' . $this->type);
	}

	public function getActivityNameHtmlAttribute()
	{
		$html = $this->name_icon_html . '<br>' . $this->client->name_link_icon;
		return $html;
	}

	public function getActivityFromAttribute()
	{
		return $this->readableDateHtml('call_time', true);
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: morphTo
	public function client()
	{
		return $this->morphTo();
	}

	public function related()
	{
		return $this->morphTo();
	}

	// relation: morphOne
	public function activity()
	{
		return $this->morphOne(Activity::class, 'linked');
	}
}	