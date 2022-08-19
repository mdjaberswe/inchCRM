<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class ChatRoomMember extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'chat_room_members';
	protected $fillable = ['chat_room_id', 'linked_id', 'linked_type'];
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
	public function room()
	{
		return $this->belongsTo(ChatRoom::class, 'chat_room_id');
	}

	// relation: hasMany
	public function sentMessages()
	{
		return $this->hasMany(ChatSender::class, 'chat_room_member_id');
	}

	public function receivedMessages()
	{
		return $this->hasMany(ChatReceiver::class, 'chat_room_member_id');
	}

	// relation: morphTo
	public function linked()
	{
		return $this->morphTo()->withTrashed();
	}
}