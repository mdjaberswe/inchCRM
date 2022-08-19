@extends('layouts.master')

@section('content')
	
	<div id='chat-room' class='row div-chat-room bg-type-a'>
		<div id='chat-room-member' class='col-xs-12 col-sm-5 col-md-4 col-lg-3 padding-zero'>			
			<div class='full'>
				<h3 class='title-center-box'>Messenger</h3>
			</div>	

			<div class='full padding-space-12'>
				<input id='search-room-member' class='form-control input-type-a' placeholder='Search Messenger'> 
			</div>
			
			<div class='full'>	
				<ul class='navlist scroll-box'>
					@if(count($data['chat_rooms']) && !is_null($data['active_chatroom']))
						@foreach($data['chat_rooms'] as $chat_room)
							<li>
							    <a class='navlist-item-type-a{!! $data['active_chatroom']->id == $chat_room->id ? ' active' : null !!}' chatroomid='{!! $chat_room->id !!}'>                                            
							        <img src='{!! $chat_room->avatar !!}' alt='{!! $chat_room->meaningful_name !!}'>
							        <p class='time' data-toggle='tooltip' data-placement='left' title='{!! $chat_room->created_time_ampm !!}'>{!! $chat_room->created_short_format !!}</p>
							        <h5>{!! str_limit($chat_room->meaningful_name, 17, '.') !!}</h5>
							        @if($chat_room->linked_id == auth_staff()->id && $chat_room->linked_type == 'staff')
							        	<p>{!! 'You: ' . str_limit($chat_room->message, 20) !!}</p>
							        @else
							        	<p>{!! str_limit($chat_room->message, 25) !!}</p>
							        @endif	
							    </a>
							</li>
						@endforeach
					@endif
				</ul>
			</div>	
		</div> <!-- end chat-room-member -->

		<div id='chat-message' class='col-xs-12 col-sm-7 col-md-8 col-lg-9 div-message-container'>
			<div id='chat-message-title' class='full title-container'>
				@if(count($data['chat_rooms']) && !is_null($data['active_chatroom']))
					<img src='{!! $data['active_chatroom']->avatar !!}' alt='{!! $data['active_chatroom']->meaningful_name !!}'>
					<h5>{!! $data['active_chatroom']->meaningful_name !!}</h5>
					@if(!is_null($data['active_chatroom']->chat_partner))
						<p>{!! $data['active_chatroom']->chat_partner->title !!}</p>
					@endif	
				@endif
			</div> <!-- end chat-message-title -->

			<div id='chat-message-box' class='full padding-top-space div-message-box scroll-box'>
				@if(count($data['chat_rooms']) && !is_null($data['active_chatroom']))
					{!! $data['active_chatroom']->history_html !!}
				@endif				
			</div> <!-- end chat-message-box -->

			<div class='content-loader'></div>

			<input type='hidden' id='room' name='room' value='{!! $data['active_chatroom']->id !!}'>
			<textarea name='message' id='send-msg' class='input-msg' placeholder='{!! $data['active_chatroom']->inactive ? 'You can not reply to this conversation' : 'Type a message and press Enter. Use Shift + Enter for a new line' !!}'@if($data['active_chatroom']->inactive) disabled @endif></textarea>
		</div> <!-- end chat-message -->
	</div> <!-- end chat-room -->

@endsection

@push('scripts')
	{!! HTML::script('js/chat.js') !!}
@endpush
