<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\ChatRoom;
use App\Models\ChatSender;
use App\Models\ChatReceiver;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Library\DatatablesManager;
use App\Http\Controllers\Admin\AdminBaseController;

class AdminNotificationController extends AdminBaseController
{
	public function __construct()
	{
		parent::__construct();
	}



	public function index()
	{
		$page = ['title' => 'Notifications', 'item' => 'Notification', 'page_length' => 25, 'field' => 'notifications', 'view' => 'admin.notification', 'route' => 'admin.notification', 'modal_create' => false, 'modal_edit' => false, 'modal_delete' => false, 'modal_bulk_delete' => false];
		$table = ['thead' => [['NOTIFICATION FROM', 'style' => 'min-width: 200px'], 'DESCRIPTION', ['DATE', 'style' => 'min-width: 170px']], 'checkbox' => false, 'action' => false];
		$table['json_columns'] = table_json_columns(['notification_from', 'description', 'date']);
		Notification::authStaffOnly()->update(['read_at' => Carbon::now()]);
		
		return view('admin.notification.index', compact('page', 'table'));
	}



	public function notificationData(Request $request)
	{
		if($request->ajax()) :
			$notifications = Notification::authStaffOnly()->latest('id')->get();
			return DatatablesManager::notificationData($notifications, $request);
		endif;
	}



	public function read(Request $request)
	{
		if($request->ajax()) :
			Notification::authStaffOnly()->update(['read_at' => Carbon::now()]);
			return response()->json(['status' => true]);
		endif;	
	}



	public function realtimeNotification(Request $request)
	{
		if($request->ajax()) :
			if(!auth_staff()->has_new_notification && !auth_staff()->has_recent_sent_msg) :
				return response()->json(['status' => false]);
			endif;	
			
			$status = true;

			$unread_message_count = auth_staff()->unread_messages_count;
			$take_messages = $unread_message_count > 15 ? $unread_message_count : 15;
			$chat_messages = auth_staff()->getChatRoomsAttribute($take_messages);
			$chat_messages_html = '';
			if(count($chat_messages) > 0) :
				foreach($chat_messages as $chat_message) :
					$chat_messages_html .= "<li>
										        <a href='" . route('admin.message.chatroom', $chat_message->id) . "' class='dropdown-notification'>                                            
										            <img src='" . $chat_message->avatar . "' alt='User Name'>
										            <p class='time'>" . $chat_message->created_ampm . "</p>
										            <h5>" . str_limit($chat_message->meaningful_name, 20, '.') . "</h5>";
										            if($chat_message->linked_id == auth_staff()->id && $chat_message->linked_type == 'staff') :
										                $chat_messages_html .= "<p>You: " . str_limit($chat_message->message, 25) . "</p>";
										            else :
										                $chat_messages_html .= "<p>" . str_limit($chat_message->message, 30) . "</p>";
										        	endif;  
										        $chat_messages_html .= "</a>
										    </li>";
				endforeach;
			endif;

			$chat_rooms = auth_staff()->chat_rooms;
			$active_chatroom = auth_staff()->chat_rooms->isEmpty() ? null : auth_staff()->latest_chat_id;
			$active_chatroom_url = is_null($active_chatroom) ? route('admin.message.index') : route('admin.message.chatroom', $active_chatroom);
			$active_chatroom = is_numeric($request->activechatroom) ? intval($request->activechatroom) : $active_chatroom;
			$chat_rooms_html = '';
			if(count($chat_rooms) && !is_null($active_chatroom)) :
				foreach($chat_rooms as $chat_room) :
					$active_class = $active_chatroom == $chat_room->id ? ' active' : '';
					$chat_rooms_html .= "<li>
										    <a class='navlist-item-type-a" . $active_class . "' chatroomid='" . $chat_room->id . "'>                                            
										        <img src='" . $chat_room->avatar . "' alt='" . $chat_room->meaningful_name . "'>
										        <p class='time' data-toggle='tooltip' data-placement='left' title='" . $chat_room->created_time_ampm . "'>" . $chat_room->created_short_format . "</p>
										        <h5>" . str_limit($chat_room->meaningful_name, 17, '.') . "</h5>";
										        if($chat_room->linked_id == auth_staff()->id && $chat_room->linked_type == 'staff') :
										        	$chat_rooms_html .= "<p>You: " . str_limit($chat_room->message, 20) . "</p>";
										        else :
										        	$chat_rooms_html .= "<p>" . str_limit($chat_room->message, 25) . "</p>";
										        endif;	
										    $chat_rooms_html .= "</a>
										</li>";
				endforeach;
			endif;

			$notification_message_list = null;
			$chatroom_messsage = null;
			$message_html = '';
			$message_child_html = '';
			$aside_chatroom_html = '';

			// Chat Room Console in RealTime
			if($request->chatroom == true && is_numeric($request->activechatroom) && is_numeric($request->lastreceivedid)) :
				$chat_room_id = intval($request->activechatroom);
				$last_received_id = intval($request->lastreceivedid);
				$auth_chat_room = in_array($chat_room_id, auth_staff()->chat_rooms->pluck('id')->toArray());

				if($auth_chat_room) :
					$chat_room = ChatRoom::find($chat_room_id);
					$received_history = $chat_room->getReceivedHistoryAttribute($last_received_id);

					if(!$received_history->isEmpty()) :
						$message_html = "<div class='full chat-message left'>
							  				<img src='" . $chat_room->avatar . "'>
							  				<div class='msg-content'>";

						foreach($received_history as $single_history) :
							$message_child_html .= "<div class='full'>
														<p data-toggle='tooltip' data-placement='right' title='" . $single_history->created_ampm . "' messageid='" . $single_history->id . "'>"
														. $single_history->message . "
														</p>
									  			    </div>"; 	 
						endforeach;

						$message_html .= $message_child_html;
						$message_html .= "</div></div>";

						$aside_chatroom_html = "<li>
												    <a class='navlist-item-type-a active' chatroomid='" . $chat_room_id . "'>                                            
												        <img src='" . $chat_room->avatar . "' alt='" . $chat_room->meaningful_name . "'>
												        <p class='time' data-toggle='tooltip' data-placement='left' title='" . $received_history->last()->created_time_ampm . "'>" . $received_history->last()->created_short_format . "</p>
												        <h5>" . str_limit($chat_room->meaningful_name, 17, '.') . "</h5>
											        	<p>" . str_limit($received_history->last()->message, 20) . "</p>
												    </a>
												</li>";
					endif;
				endif;	
			endif;

			$data = [
					 'status' => true,
					 'unreadMessageCount' => $unread_message_count,
					 'chatMessagesHtml' => $chat_messages_html,
					 'activeChatroomUrl' => $active_chatroom_url,
					 'chatRoomsHtml' => $chat_rooms_html,
					 'chatRoom' => $request->chatroom,
					 'activeChatRoom' => $request->activechatroom,
					 'lastReceivedId' => $request->lastreceivedid,
					 'messageHtml' => $message_html,
					 'messageChildHtml' => $message_child_html,
					 'asideChatroomHtml' => $aside_chatroom_html
					];

			return response()->json($data);
		endif;
	}
}
