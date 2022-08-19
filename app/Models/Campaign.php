<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Models\Traits\FinanceTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class Campaign extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;
	use FinanceTrait;

	protected $table = 'campaigns';
	protected $fillable = ['campaign_owner', 'campaign_type', 'name', 'description', 'start_date', 'end_date', 'status', 'currency_id', 'expected_revenue', 'budgeted_cost', 'actual_cost', 'numbers_sent', 'expected_response', 'access'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;
	protected static $member_status = ['planned', 'invited', 'sent', 'received', 'opened', 'responded', 'bounced', 'opted_out'];

	public static function validate($data)
	{	
		$start_date = $data['start_date'];
		$start_date_minus = date('Y-m-d', strtotime($start_date . ' -1 day'));

		$rules = ["campaign_owner"	=> "required|exists:users,linked_id,linked_type,staff,status,1,deleted_at,NULL",
				  "campaign_type"	=> "exists:campaign_types,id,deleted_at,NULL",
				  "name"			=> "required|max:200",
				  "description"		=> "max:65535",
				  "start_date"		=> "date",
				  "end_date"		=> "date|after:$start_date_minus",	
				  "status"			=> "in:planning,active,inactive,completed",
				  "currency_id"		=> "required|exists:currencies,id,deleted_at,NULL",
				  "expected_revenue"=> "numeric",
				  "budgeted_cost"	=> "numeric",
				  "actual_cost"		=> "numeric",
				  "numbers_sent"	=> "numeric",
				  "expected_response"=> "numeric|max:100"];

		return \Validator::make($data, $rules);
	}

	public static function memberValidate($data)
	{
		$valid_member_status = implode(',', self::memberStatus());

		$rules = ["campaigns"		=> "exists:campaigns,id,deleted_at,NULL",
				  "member_id"		=> "required|exists:" . $data['member_type'] . "s,id,deleted_at,NULL",
				  "member_type"		=> "required|in:lead,contact",
				  "member_status"	=> "required|in:$valid_member_status"];

		return \Validator::make($data, $rules);
	}

	public static function memberUpdateValidate($data)
	{
		$valid_member_status = implode(',', self::memberStatus());

		$rules = ["campaign_id"		=> "required|exists:campaigns,id,deleted_at,NULL|exists:campaign_members,campaign_id,member_id," . $data['member_id'] . ",member_type," . $data['member_type'],
				  "member_id"		=> "required|exists:" . $data['member_type'] . "s,id,deleted_at,NULL",
				  "member_type"		=> "required|in:lead,contact",
				  "member_status" 	=> "required|in:$valid_member_status"];
		
		return \Validator::make($data, $rules);
	}

	public static function memberStatus()
	{
		return self::$member_status;
	}

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeReadableIdentifier($query, $name)
	{
		return $query->where('name', $name);
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getStatusHtmlAttribute()
	{
		$currency_info ="<span class='symbol none' value='" . $this->currency->id . "' code='" . $this->currency->code . "' icon='" . currency_icon($this->currency->code, $this->currency->symbol) . "'>" . $this->currency->solid_symbol . "</span>";
		return "<span class='capitalize'>" . $this->status . "</span>" . $currency_info;
	}

	public function getMemberStatusAttribute()
	{
		if(is_object($this->pivot)) :
			return snake_to_ucwords($this->pivot->status);
		endif;	

		return null;
	}

	public function getMemberActionHtmlAttribute()
	{
		$html = "<div class='action-box'>
					<div class='inline-action'>
						<a class='common-edit-btn' data-item='campaign' modal-title='Update Campaign Member Status' modal-small='true' data-url='" . route('admin.member.campaign.edit', [$this->pivot->member_type, $this->pivot->member_id, $this->id]) . "' data-posturl='" . route('admin.member.campaign.update', [$this->pivot->member_type, $this->pivot->member_id, $this->id]) . "' editid='" . $this->id . "'>
							<i class='fa fa-pencil'></i>
						</a>
					</div>
					<div class='dropdown'>
						<a class='dropdown-toggle' data-toggle='dropdown' aria-expanded='false'>
							<i class='fa fa-ellipsis-v'></i>
						</a>
						<ul class='dropdown-menu'>
							<li>" .
								\Form::open(['route' => ['admin.member.campaign.remove', $this->pivot->member_type, $this->pivot->member_id, $this->id], 'method' => 'delete']) .
									\Form::hidden('member_id', $this->pivot->member_id) .
									\Form::hidden('member_type', $this->pivot->member_type) .
									\Form::hidden('campaign_id', $this->id) .
									"<button type='submit' class='delete' data-item='campaign' data-parentitem='" . $this->pivot->member_type . "'><i class='mdi mdi-delete'></i> Remove</button>" .
								\Form::close() . "
							</li>
						</ul>
					</div>
				</div>";

		return $html;		
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function owner()
	{
		return $this->belongsTo(Staff::class, 'campaign_owner')->withTrashed();
	}

	public function type()
	{
		return $this->belongsTo(CampaignType::class, 'campaign_type');
	}

	public function currency()
	{
		return $this->belongsTo(Currency::class, 'currency_id');
	}
	
	// relation: hasMany
	public function deals()
	{
		return $this->hasMany(Deal::class);
	}

	// relation: morphMany
	public function tasks()
	{
		return $this->morphMany(Task::class, 'linked');
	}

	public function calls()
	{
		return $this->morphMany(Call::class, 'related');
	}

	public function events()
	{
		return $this->morphMany(Event::class, 'linked');
	}

	public function notificationInfos()
	{
		return $this->morphMany(NotificationInfo::class, 'linked');
	}

	public function linearNotes()
	{
		return $this->morphMany(NoteInfo::class, 'linked');
	}

	public function notes()
	{
		return $this->morphMany(Note::class, 'linked');
	}

	public function attachfiles()
	{
		return $this->morphMany(AttachFile::class, 'linked');
	}

	// relation: morphedByMany
	public function leads()
	{
		return $this->morphedByMany(Lead::class, 'member', 'campaign_members')->withPivot('status', 'member_type');
	}

	public function contacts()
	{
		return $this->morphedByMany(Contact::class, 'member', 'campaign_members')->withPivot('status', 'member_type');
	}
}