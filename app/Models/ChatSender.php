<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class ChatSender extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'chat_senders';
	protected $fillable = ['chat_room_member_id', 'message'];
	protected $appends = ['position', 'opposite_position'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getPositionAttribute()
	{
		$outcome = 'left';
		if($this->senderInfo->linked_id == auth()->user()->linked_id && $this->senderInfo->linked_type == auth()->user()->linked_type) :
			$outcome = 'right';
		endif;
		
		return $outcome;
	}

	public function getOppositePositionAttribute()
	{
		$outcome = 'right';
		if($this->position == 'right') :
			$outcome = 'left';
		endif;	
		
		return $outcome;
	}

	public function getAvatarAttribute()
	{
		return $this->senderInfo->linked->avatar;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: belongsTo
	public function senderInfo()
	{
		return $this->belongsTo(ChatRoomMember::class, 'chat_room_member_id');
	}

	// relation: hasMany
	public function receivers()
	{
		return $this->hasMany(ChatReceiver::class, 'chat_sender_id');
	}
}