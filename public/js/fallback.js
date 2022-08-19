$(document).ready(function()
{
	$('.menu-toggler').click(function()
	{
	    var isCompress = 0;                    
	    if($('nav').hasClass('compress'))
	    {
	        isCompress = 1;
	    }
	    var data = {'is_compress' : isCompress};                    

	    $.ajax({
	        type : 'GET',
	        data : data,
	        url  : globalVar.baseUrl + '/set-sidenav-status'
	    });
	});

	$('#top-notification').click(function()
	{
	    $.ajax({
	        type 	: 'POST',
	        url  	: globalVar.baseAdminUrl + '/notification-read',
	        dataType: 'JSON',
	        success : function(data)
	        		  {
	        			if(data.status == true)
	        			{
	        				$('#top-notification').find('.notification-signal').hide();
	        			}  	
	        		  }
	    });
	});

	$('#top-msg-notification').click(function()
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
	});

	var getNotification = function ()
						  {
						  	var chatroom = 0;
						  	var activeChatroom = null;
						  	var lastReceivedId = null;

						  	if($('#chat-room').get(0))
						  	{
						  		chatroom = 1;
						  		activeChatroom = $('#room').val();
						  		lastReceivedId = $('#chat-message-box .left:last .full:last p').attr('messageid');
						  	}
						  		
							$.ajax(
							{
								type 	: 'POST',
								url 	: globalVar.baseAdminUrl + '/realtime-notification',
								data 	: { chatroom : chatroom, activechatroom : activeChatroom, lastreceivedid : lastReceivedId },
								dataType: 'JSON',
								success : function(data)
										  {
										  	if(data.status == true)
										  	{
										  		$('#view-all-msg').attr('href', data.activeChatroomUrl);

										  		if(data.unreadMessageCount > 0)
										  		{
										  			var notificationSignal = $('#top-msg-notification').find('.notification-signal');

										  			if(notificationSignal.length == 0)
										  			{
										  				$('#top-msg-notification').append("<p class='notification-signal bg-a'>"+data.unreadMessageCount+"</p>");
										  			}
										  			else
										  			{
										  				notificationSignal.html(data.unreadMessageCount);
										  				notificationSignal.show();
										  			}
										  		}

										  		if(data.chatMessagesHtml != '')
										  		{
										  			if($('#top-msg-list').get(0))
										  			{
										  				$('#top-msg-list ul').html(data.chatMessagesHtml);
										  			}
										  		}

										  		if($('#chat-room').get(0))
										  		{
										  			if(data.chatRoomsHtml != '')
										  			{
										  				$('.navlist').html(data.chatRoomsHtml);
										  			}

										  			afterchatroom = 1;
										  			afteractiveChatroom = $('#room').val();
										  			afterlastReceivedId = $('#chat-message-box .left:last .full:last p').attr('messageid');

										  			if(data.chatRoom == afterchatroom && data.activeChatRoom == afteractiveChatroom && data.lastReceivedId == afterlastReceivedId && data.messageHtml != '' && data.messageChildHtml != '' && data.asideChatroomHtml != '')
										  			{
										  				var lastChild = $('#chat-message-box .chat-message:last');
										  				if(lastChild.hasClass('left'))
										  				{
										  					lastChild.find('.msg-content').append(data.messageChildHtml);
										  				}
										  				else
										  				{
										  					$('#chat-message-box').append(data.messageHtml);
										  				}	

										  				$(".navlist-item-type-a[chatroomid='"+data.activeChatRoom+"'").parent("li").remove();
										  				$('.navlist').prepend(data.asideChatroomHtml);
										  				$('[data-toggle="tooltip"]').tooltip();
										  				$('#chat-message-box').scrollTop($('#chat-message-box').prop('scrollHeight'));										  				
										  			}											  												  			
										  		}
										  	}
										  }	  
							});
						  };

	setInterval(getNotification, 5000);
	
	$('#add-new-btn').click(function()
	{
		if(globalVar.defaultDropdown.length > 0)
		{
			$.each(globalVar.defaultDropdown, function(index, item)
			{
				$(item.identifier).val(item.default).trigger('change');
			});
		}
	});
});