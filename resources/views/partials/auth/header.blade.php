<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset='utf-8'>
        <meta name='csrf-token' content='{!! csrf_token() !!}'>
        <meta http-equiv='X-UA-Compatible' content='IE=edge'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>

        <title>{!! isset($page['title']) ? $page['title'] : 'inchCRM' !!}</title>
        
        @include('partials.global-css')

        {!! HTML::style('css/auth.css') !!}

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src='https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js'></script>
          <script src='https://oss.maxcdn.com/respond/1.4.2/respond.min.js'></script>
        <![endif]-->
    </head>

    <body>
        <header>
            <div id='logo' class='left-justify'>
                <a class='logo-txt'>inchCRM</a>
            </div> <!-- end logo -->

            <div id='top-nav'>
                <a class='nav-link'><i class='pe-7s-paint-bucket pe-2x pe-va'></i></a>             
            </div> <!-- end top-nav -->
        </header> <!-- end header -->