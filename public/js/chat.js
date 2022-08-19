$(document).ready(function()
{
	$('#chat-message-box').scrollTop($('#chat-message-box').prop('scrollHeight'));
	$('.navlist').scrollTop(0);

	$('#search-room-member').keyup(function()
	{
		var searchVal = $(this).val().toLowerCase();

		if(searchVal == '')
		{
			$('.navlist-item-type-a').show();
		}
		else
		{
			$('.navlist-item-type-a').each(function()
			{
			    var filter = $(this).find('h5').html().toLowerCase().indexOf(searchVal);

			    if(filter > -1)
			    {
			    	$(this).show();
			    }
			    else
			    {
			    	$(this).hide();
			    }
			});
		}
	});

	var previousKeyDownTime = new Date();

	$('#send-msg').keydown(function(e)
	{
		var currentKeyDownTime = new Date();
		var diffKeyDownTime = (currentKeyDownTime.getTime() - previousKeyDownTime.getTime()) / 1000;

		if(diffKeyDownTime > 0.5)
		{						
			var message = $(this).val();
			var chatRoom = $('#room').val();

			if(e.keyCode == 13 && e.shiftKey == false)
			{
				e.preventDefault();
				if($.trim(message).length != 0)
				{
					$.ajax({
						type 	: 'POST',
						url 	: globalVar.baseAdminUrl + '/message',
						data 	: { room : chatRoom, message : message },
						dataType: 'JSON',
						success : function(data)
								  {
								  	if(data.status == true)
								  	{ 	
								  		var lastChild = $('#chat-message-box .chat-message:last');

								  		if(lastChild.hasClass('right'))
								  		{
								  			lastChild.find('.msg-content').append(data.messagechildhtml);
								  		}
								  		else
								  		{
								  			$('#chat-message-box').append(data.messagehtml);
								  		}	
								  		$(".navlist-item-type-a[chatroomid='"+chatRoom+"'").parent("li").remove();
								  		$('.navlist').prepend(data.chatroomhtml);
								  		$('[data-toggle="tooltip"]').tooltip();

								  		$('#send-msg').val('');
								  		$('#send-msg').css('height', '50px');
								  		$('#chat-message-box').css('height', '425px');		
								  		$('#chat-message-box').scrollTop($('#chat-message-box').prop('scrollHeight'));
								  	}
								  	else
								  	{	
								  		alert('Something went wrong! Please try again.');
								  	}
								  }
					});
				}
			}

			previousKeyDownTime = currentKeyDownTime;
		}	
	});

	$('main').hover(function()
	{
		if($('#chat-room').get(0))
		{
			var notificationSignal = $('#top-msg-notification').find('.notification-signal');

			if(notificationSignal.length > 0 && notificationSignal.css('display') != 'none')
			{
				$.ajax({
				    type 	: 'POST',
				    url  	: globalVar.baseAdminUrl + '/message-read',
				    dataType: 'JSON',
				    success : function(data)
				    		  {
				    			if(data.status == true)
				    			{
				    				$('#top-msg-notification').find('.notification-signal').hide();
				    			}  	
				    		  }
				});
			}
		}		
	});

	$('.navlist').on('click', '.navlist-item-type-a', function(event)
	{
		var chatRoomId = $(this).attr('chatroomid');
		var thisChatRoom = $(this);

		if(!$(this).hasClass('active'))
		{
			$('#chat-message-box .chat-message').hide();
			$('#chat-message .content-loader').show();

			$.ajax(
			{
				type 	: 'POST',
				url		: globalVar.baseAdminUrl + '/message/chatroom/history',
				data 	: { id : chatRoomId },
				dataType: 'JSON',
				success	: function(data)
						  {
						  	if(data.status == true)
						  	{
						  		$('#room').val(chatRoomId);
						  		$('#send-msg').val('');
						  		$('#send-msg').prop('disabled', data.inactive);
						  		$('#send-msg').prop('placeholder', data.inactivemsg);
						  		$('.navlist-item-type-a').removeClass('active');	
						  		thisChatRoom.addClass('active');	
						  		$('#chat-message-title').children('img').remove();
						  		$('#chat-message-title').prepend(data.avatar);				  		
						  		$('#chat-message-title h5').html(data.name);
						  		$('#chat-message-title p').html(data.title);						  		
						  		$('#chat-message-box').html(data.history);						  		
						  		$('#chat-message-box').scrollTop($('#chat-message-box').prop('scrollHeight'));
						  		$('#chat-message .content-loader').fadeOut(500);
						  		$('[data-toggle="tooltip"]').tooltip();
						  		window.history.pushState({ 'name' : data.name, 'title' : data.title, 'avatar' : data.avatar, 'inactive' : data.inactive, 'inactivemsg' : data.inactivemsg, 'html' : data.history }, '', chatRoomId);
						  	}
						  	else
						  	{
						  		alert('Something went wrong! Please try again.');
						  	}
						  }
			});
		}		
	});

	window.onpopstate = function(e)
	{
	    if(e.state)
	    {
	    	var activeChat = window.location.href.split('/').last();
	    	$('#room').val(activeChat);
	    	$('.navlist-item-type-a').removeClass('active');
	    	$(".navlist-item-type-a[chatroomid='"+activeChat+"']").addClass('active');
	    	$('#send-msg').val('');
	    	$('#send-msg').prop('disabled', e.state.inactive);
	    	$('#send-msg').prop('placeholder', e.state.inactivemsg);
	    	$('#chat-message-title').children('img').remove();
	    	$('#chat-message-title').prepend(e.state.avatar);
	    	$('#chat-message-title h5').html(e.state.name);
	    	$('#chat-message-title p').html(e.state.title);	    	
	        $('#chat-message-box').html(e.state.html);
	    }
	};
});