<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta name='csrf-token' content='{!! csrf_token() !!}'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>

        <title>{!! isset($page['title']) ? $page['title'] : 'inchCRM' !!}</title>
        
        @include('partials.global-css')
        @stack('css')

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src='https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js'></script>
          <script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
        <![endif]-->
    </head>

    <body>
        <header>
            <div id='logo' class='left-justify {!! $class['logo'] !!}'>
                <a class='logo-txt'>inchCRM</a>
                <a class='menu-toggler'><i class='fa fa-bars'></i></a> 
                <a class='mob-menu-toggler'><i class='fa fa-bars'></i></a>
            </div> <!-- end logo -->

            <div id='top-nav' class='left-justify {!! $class['top_nav'] !!}'>
                <div class='btn-group'>
                    <a class='nav-link me dropdown-toggle' data-toggle='dropdown'>
                        <span>{!! auth_staff()->first_name . ' ' . auth_staff()->last_name !!}</span>
                        <img src='{!! auth_staff()->avatar !!}' alt='me'>
                    </a>
                    <ul class='dropdown-menu'>
                        <li><a href=''><i class='fa fa-user'></i> My Profile</a></li>
                        <li><a href=''><i class='fa fa-key'></i> Change Password</a></li>
                        <li class='divider'></li>
                        <li><a href='{!! route('auth.signout') !!}'><i class='fa fa-power-off'></i> Sign Out</a></li>
                    </ul>
                </div>

                <a class='nav-link'><i class='pe-7s-paint-bucket pe-2x pe-va'></i></a>

                <div class='btn-group'>
                    <a id='top-notification' class='nav-link dropdown-toggle' data-toggle='dropdown'>
                        <i class='pe-7s-bell pe-2x pe-va'></i>
                        @if($unread_notifications_count)
                            <p class='notification-signal bg-a'>{!! $unread_notifications_count !!}</p>
                        @endif
                    </a>   
                    <div class='dropdown-menu'>   
                        @if(count($notifications) > 0)              
                            <ul class='scroll-dropdown'>                            
                                @foreach($notifications as $notification)
                                    <li>
                                        <a href='{!! $notification->link !!}' class='dropdown-notification'>                                            
                                            <img src='{!! $notification->avatar !!}' alt='User Name'>
                                            <p class='time'>{!! $notification->created_ampm !!}</p>
                                            <h5>{!! str_limit($notification->created_by_name, 20, '.') !!}</h5>
                                            <p>{!! $notification->title !!}</p>
                                            <p>{!! $notification->additional_info !!}</p>
                                        </a>
                                    </li>
                                @endforeach                           
                            </ul>   
                            <a href='{!! route('admin.notification.index') !!}' class='bottom-link'>View all notifications</a> 
                        @else
                            <ul class='scroll-dropdown'>
                                <li>
                                    <a href='#' class='dropdown-notification'></a>    
                                </li>
                            </ul>    
                            <a href='{!! route('admin.notification.index') !!}' class='bottom-link'>No notifications found</a> 
                        @endif
                    </div>  
                </div>   

                @if(count($chat_messages) > 0)
                    <div class='btn-group'>                        
                        <a id='top-msg-notification' class='nav-link dropdown-toggle' data-toggle='dropdown'>
                            <i class='pe-7s-chat pe-2x pe-va'></i>
                            @if($unread_messages_count)
                                <p class='notification-signal bg-a'>{!! $unread_messages_count !!}</p>
                            @endif
                        </a>
                        <div id='top-msg-list' class='dropdown-menu'>                            
                            <ul class='scroll-dropdown'>                            
                                @foreach($chat_messages as $chat_message)
                                    <li>
                                        <a href='{!! route('admin.message.chatroom', $chat_message->id) !!}' class='dropdown-notification'>                                            
                                            <img src='{!! $chat_message->avatar !!}' alt='User Name'>
                                            <p class='time'>{!! $chat_message->created_ampm !!}</p>
                                            <h5>{!! str_limit($chat_message->meaningful_name, 20, '.') !!}</h5>
                                            @if($chat_message->linked_id == auth_staff()->id && $chat_message->linked_type == 'staff')
                                                <p>{!! 'You: ' . str_limit($chat_message->message, 25) !!}</p>
                                            @else
                                                <p>{!! str_limit($chat_message->message, 30) !!}</p>
                                            @endif  
                                        </a>
                                    </li>
                                @endforeach                           
                            </ul>   
                            <a id='view-all-msg' href='{!! route('admin.message.chatroom', auth_staff()->latest_chat_id) !!}' class='bottom-link'>View all in Messenger</a>                           
                        </div>                        
                    </div>   
                @else
                    <a id='view-all-msg' href='{!! route('admin.message.index') !!}' class='nav-link'><i class='pe-7s-chat pe-2x pe-va'></i></a>
                @endif 

                <a class='nav-link task'>
                    <p class='time'>6:15<span>AM</span></p>
                    <p class='task-count'><i class='fa fa-flag'></i>5</p>
                </a>    

                <a class='nav-link expand' id='fullscreen'><i class='fa fa-expand'></i></a>
            </div> <!-- end top-nav -->
        </header> <!-- end header -->