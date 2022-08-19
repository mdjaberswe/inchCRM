<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class ChatReceiver extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'chat_receivers';
	protected $fillable = ['chat_sender_id', 'chat_room_member_id', 'read_at'];
	protected $dates = ['deleted_at', 'read_at'];
	protected $revisionCreationsEnabled = true;

	/*
	|--------------------------------------------------------------------------
	| SCOPES
	|--------------------------------------------------------------------------
	*/
	public function scopeAuthStaffOnly($query)
	{
		$query->join('chat_room_members', 'chat_room_members.id', '=', 'chat_receivers.chat_room_member_id')
			  ->where('chat_room_members.linked_type', 'staff')
			  ->where('chat_room_members.linked_id', auth_staff()->id)
			  ->select('chat_receivers.*');
	}

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
	public function sender()
	{
		return $this->belongsTo(ChatSender::class, 'chat_sender_id');
	}

	public function receiverInfo()
	{
		return $this->belongsTo(ChatRoomMember::class, 'chat_room_member_id');
	}
}