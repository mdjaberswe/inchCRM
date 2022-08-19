<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\ChatRoom;
use App\Models\ChatSender;
use App\Models\ChatReceiver;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminMessageController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();
	}



	public function index()
	{
		$page = ['title' => 'Messenger'];
		$data = ['chat_rooms' => auth_staff()->chat_rooms, 'active_chatroom' => auth_staff()->chat_rooms->isEmpty() ? null : ChatRoom::find(auth_staff()->latest_chat_id)];
		
		return view('admin.message.index', compact('page', 'data'));
	}



	public function chatroom(ChatRoom $chatroom)
	{
		if(!in_array($chatroom->id, auth_staff()->chat_rooms->pluck('id')->toArray())) :
			return redirect()->route('admin.message.index');
		endif;

		$page = ['title' => 'Messenger'];
		$data = ['chat_rooms' => auth_staff()->chat_rooms, 'active_chatroom' => $chatroom];
		
		return view('admin.message.index', compact('page', 'data'));
	}



	public function store(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$errors = null;
			$message_html = '';
			$message_child_html = '';
			$aside_chatroom_html = '';
			$data = $request->all();
			$validation = ChatRoom::validate($data);	
			$chat_room_id = intval($request->room);	
			$chat_room = ChatRoom::find($chat_room_id);		
			$auth_chat_room = in_array($chat_room_id, auth_staff()->chat_rooms->pluck('id')->toArray());		

			if($validation->passes() && $auth_chat_room && isset($chat_room) && !$chat_room->inactive) :				
				$chat_room_member_id = auth_staff()->chatRoomMembers->where('chat_room_id', $chat_room_id)->first()->id;
				$rest_members_id_array = array_diff(ChatRoom::find($chat_room_id)->members->pluck('id')->toArray(), [$chat_room_member_id]);

				$chat_sender = new ChatSender;
				$chat_sender->chat_room_member_id = $chat_room_member_id;
				$chat_sender->message = $request->message;
				$chat_sender->save();

				foreach($rest_members_id_array as $rest_member) :
					$chat_receiver = new ChatReceiver;
					$chat_receiver->chat_sender_id = $chat_sender->id;
					$chat_receiver->chat_room_member_id = $rest_member;
					$chat_receiver->save();
				endforeach;	

				$message_html = "<div class='full chat-message right'>
					  				<img src='" . $chat_sender->avatar . "'>
					  				<div class='msg-content'>
					  					<div class='full'>
					  						<p data-toggle='tooltip' data-placement='left' title='" . $chat_sender->created_ampm . "' messageid='" . $chat_sender->id . "'>"
					  							. $request->message . "
					  						</p>
					  					</div>
					  				</div>
					  			</div>";	

				$message_child_html = "<div class='full'>
										<p data-toggle='tooltip' data-placement='left' title='" . $chat_sender->created_ampm . "' messageid='" . $chat_sender->id . "'>"
										. $request->message . "
										</p>
						  			   </div>";	 

				$aside_chatroom_html = "<li>
										    <a class='navlist-item-type-a active' chatroomid='" . $chat_room_id . "'>                                            
										        <img src='" . $chat_room->avatar . "' alt='" . $chat_room->meaningful_name . "'>
										        <p class='time' data-toggle='tooltip' data-placement='left' title='" . $chat_sender->created_time_ampm . "'>" . $chat_sender->created_short_format . "</p>
										        <h5>" . str_limit($chat_room->meaningful_name, 17, '.') . "</h5>
									        	<p>You: " . str_limit($request->message, 20) . "</p>
										    </a>
										</li>";		  			    			
			else :
				$status = false;
				$errors = $validation->getMessageBag()->toArray();
			endif;

			return response()->json(['status' => $status, 'errors' => $errors, 'messagehtml' => $message_html, 'messagechildhtml' => $message_child_html, 'chatroomhtml' => $aside_chatroom_html]);
		endif;
	}



	public function chatroomHistory(Request $request)
	{
		if($request->ajax()) :
			$status = true;
			$info = null;
			$history_html = null;
			$chatroom_name = null;
			$title = null;
			$inactive = null;
			$inactive_txt = null;
			$chatroom = isset($request->id) ? ChatRoom::find($request->id) : null;

			if(isset($chatroom)) :
				$auth_chat_room = in_array($chatroom->id, auth_staff()->chat_rooms->pluck('id')->toArray());
				if($auth_chat_room) :
					$info = $chatroom;
					$history_html = $chatroom->history_html;
					$chatroom_name = $chatroom->meaningful_name;
					$chatroom_avatar = "<img src='" . $chatroom->avatar . "' alt='" . $chatroom->meaningful_name . "'>";
					$title = is_null($chatroom->chat_partner) ? null : $chatroom->chat_partner->title;
					$inactive = $chatroom->inactive;
					$inactive_msg = $chatroom->inactive ? 'You can not reply to this conversation' : 'Type a message and press Enter. Use Shift + Enter for a new line';
				else :
					$status = false;
				endif;
			else :
				$status = false;
			endif;

			return response()->json(['status' => $status, 'name' => $chatroom_name, 'title' => $title, 'avatar' => $chatroom_avatar, 'inactive' => $inactive, 'inactivemsg' => $inactive_msg, 'history' => $history_html]);
		endif;

		return redirect()->route('admin.message.index');
	}



	public function read(Request $request)
	{
		if($request->ajax()) :
			$unread_id_array = ChatReceiver::authStaffOnly()->whereNull('read_at')->pluck('id')->toArray();
			ChatReceiver::whereIn('id', $unread_id_array)->update(['read_at' => Carbon::now()]);
			return response()->json(['status' => true]);
		endif;	
	}
}