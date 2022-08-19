<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\RevisionableTrait;

class ChatRoom extends BaseModel
{
	use SoftDeletes;
	use RevisionableTrait;

	protected $table = 'chat_rooms';
	protected $fillable = ['name', 'type'];
	protected $appends = ['meaningful_name', 'inactive'];
	protected $dates = ['deleted_at'];
	protected $revisionCreationsEnabled = true;

	public static function validate($data)
	{	
		$rules = ['room' => 'required|exists:chat_rooms,id,deleted_at,NULL', 'message' => 'required|max:65535'];
		return \Validator::make($data, $rules);
	}

	/*
	|--------------------------------------------------------------------------
	| ACCESSOR
	|--------------------------------------------------------------------------
	*/
	public function getMeaningfulNameAttribute()
	{
		if($this->type == 'shared') :
			return $this->name;
		endif;	

		$has_auth_user = $this->members->where('linked_id', auth()->user()->linked_id)->where('linked_type', auth()->user()->linked_type)->first();
	
		if(!is_null($has_auth_user)) :
			$id = array_diff($this->members->pluck('id')->toArray(), [$has_auth_user->id]);
			return $this->members->whereIn('id', $id)->first()->linked->name;
		endif;

		$meaningful_name = $this->members->first()->linked->last_name . ' and ' . $this->members->last()->linked->last_name;
		
		return $meaningful_name;
	}

	public function getAuthMemberAttribute()
	{
		$auth_member = $this->members->where('linked_id', auth()->user()->linked_id)->where('linked_type', auth()->user()->linked_type)->first();
		return $auth_member;
	}

	public function getChatPartnerAttribute()
	{
		$has_auth_member = $this->auth_member;
		
		if(!is_null($has_auth_member) && $this->type == 'dedicated') :
			$id = array_diff($this->members->pluck('id')->toArray(), [$has_auth_member->id]);
			return $this->members->whereIn('id', $id)->first()->linked;
		endif;

		return null;
	}

	public function getAvatarAttribute()
	{
		if(isset($this->chat_partner)) :
			return $this->chat_partner->avatar;
		endif;	

		return Avatar::create($this->meaningful_name)->toBase64();
	}

	public function getInactiveAttribute()
	{
		$inactive = false;

		if(isset($this->chat_partner) && isset($this->chat_partner->user)) :
			$inactive = $this->chat_partner->status ? false : true;
		endif;

		return $inactive;
	}

	public function getLatestActivityAttribute()
	{
		$latest_activity = ChatSender::join('chat_room_members', 'chat_room_members.id', '=', 'chat_senders.chat_room_member_id')
									 ->join('chat_rooms', 'chat_rooms.id', '=', 'chat_room_members.chat_room_id')
									 ->where('chat_rooms.id', $this->id)
									 ->latest('chat_senders.id')						  
									 ->select('chat_rooms.id', 'chat_rooms.name', 'chat_rooms.type', 'chat_room_members.linked_id', 'chat_room_members.linked_type', 'chat_senders.created_at', 'chat_senders.message')
									 ->first();

		return $latest_activity;		
	}

	public function getHistoryAttribute()
	{
		$messages = ChatSender::join('chat_room_members', 'chat_room_members.id', '=', 'chat_senders.chat_room_member_id')
						      ->join('chat_rooms', 'chat_rooms.id', '=', 'chat_room_members.chat_room_id')
						      ->where('chat_room_members.chat_room_id', $this->id)
						      ->orderBy('chat_senders.id')
						      ->select('chat_senders.*', 'chat_room_members.linked_id', 'chat_room_members.linked_type')
						      ->get();

		return $messages;
	}

	public function getHistoryHtmlAttribute()
	{
		$html = '';

		if(!$this->history->isEmpty()) :
			$last_key = $this->history->keys()->last();
			$close_div = "</div></div>";
			$previous_msg_position = null;

			$html .= "<div class='full chat-message " . $this->history->first()->position . "'>
					  <img src='" . $this->history->first()->avatar . "'>
					  <div class='msg-content'>";	

			$open_opposite_div = '';

			foreach($this->history as $key => $message) :
				if(!is_null($previous_msg_position) && $previous_msg_position != $message->position) :
					$html .= $close_div;
					$html .= $open_opposite_div;
					$html .= "<img src='" . $message->avatar . "'><div class='msg-content'>";
				endif;	

				$html .= "<div class='full'>
							<p data-toggle='tooltip' data-placement='" . $message->opposite_position . "' title='" . $message->created_ampm . "' messageid='" . $message->id . "'>"
								. $message->message . "
							</p>
						  </div>";

				$previous_msg_position = $message->position;	

				$open_opposite_div = "<div class='full chat-message " . $message->opposite_position . "'>";

				if($key == $last_key) :
					$html .= $close_div;
				endif;			
			endforeach;
		endif;	
		
		return $html;
	}

	public function getReceivedHistoryAttribute($receivedId = null)
	{
		if(is_null($this->auth_member)) :
			return null;
		endif;
			
		$messages = ChatSender::join('chat_room_members', 'chat_room_members.id', '=', 'chat_senders.chat_room_member_id')
						      ->join('chat_rooms', 'chat_rooms.id', '=', 'chat_room_members.chat_room_id')
						      ->where('chat_room_members.chat_room_id', $this->id)
						      ->where('chat_room_member_id', '!=', $this->auth_member->id)
						      ->orderBy('chat_senders.id')
						      ->select('chat_senders.*', 'chat_room_members.linked_id', 'chat_room_members.linked_type');

		$messages = is_null($receivedId) ? $messages->get() : $messages->where('chat_senders.id', '>', $receivedId)->get();

		return $messages;
	}

	/*
	|--------------------------------------------------------------------------
	| RELATIONS
	|--------------------------------------------------------------------------
	*/
	// relation: hasMany
	public function members()
	{
		return $this->hasMany(ChatRoomMember::class, 'chat_room_id');
	}
}