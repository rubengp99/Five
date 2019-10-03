@php
    function getid($n = 10) { 
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
		$randomString = ''; 
	
		for ($i = 0; $i < $n; $i++) { 
			$index = rand(0, strlen($characters) - 1); 
			$randomString .= $characters[$index]; 
		} 
	
		return $randomString; 
	} 
@endphp

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="useName" content="{{ Auth::check() ? Auth::user()->name : 'guest' }}">
    <meta name="useId" content="{{ Auth::check() ? Auth::user()->id : getid() }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://unpkg.com/ionicons@latest/dist/css/ionicons.min.css" rel="stylesheet">
    @yield('css')
    <link rel="stylesheet" href="{{ URL::asset('/css/styles.css') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ URL::asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="parallax">
        <div id="fb-root"></div>
        <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v4.0&appId=449471642336763&autoLogAppEvents=1"></script>    <div id="app">
        <div>
            <nav class="navbar navbar-default navbar-dark navbar-static-top bg-transparent" style="margin:0;">
                <div class="container">
                    <div class="navbar-header">

                        <!-- Collapsed Hamburger -->
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse" aria-expanded="false">
                            <span class="sr-only">Toggle Navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>

                        <!-- Branding Image -->
                        <a class="navbar-brand bold-brand text-white" href="{{ url('/home') }}" style="margin-top: 0px;padding: 8px;font-family: adrip;letter-spacing: 2px;font-size: 23px!important;">
                            <img src="/storage/logo.png" width="40px" height="40px" style="display:inline-block;"> No Limit Roleplay
                        </a>
                    </div>

                    <div class="collapse navbar-collapse" id="app-navbar-collapse">
                        <!-- Left Side Of Navbar
                        <ul class="nav navbar-nav">
                            &nbsp;
                        </ul>
                        -->
                        <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right">
                            <!-- Authentication Links -->
                            @guest
                                <li><a class="text-white" href="/home"><ion-icon class="fix" name="home"></ion-icon>&nbsp;Home</a></li>
                                <li><a class="text-white" href="/forums"><ion-icon class="fix" name="list-box"></ion-icon>&nbsp;Forum</a></li>
                                <li><a class="text-white" href="{{ route('login') }}"><ion-icon class="fix" name="log-in"></ion-icon>&nbsp;Login</a></li>
                                <li><a class="text-white" href="{{ route('register') }}"><ion-icon class="fix" name="create"></ion-icon>&nbsp;Register</a></li>
                            @else
                                <li><a class="text-white" href="/home"><ion-icon class="fix" name="home"></ion-icon>&nbsp;Home</a></li>
                                <li><a class="text-white" href="/forums"><ion-icon class="fix" name="list-box"></ion-icon>&nbsp;Forum</a></li>
                                @if (Request::is('forums')) 
                                    <li><a class="text-white" id="new_discussion_btn"><ion-icon name="add-circle" class="fix"></ion-icon> @lang('chatter::messages.discussion.new')</a></li>
                                @endif
                                <li class="dropdown">
                                    <a class="text-white" style="height: 50px;padding:10px 15px 10px 15px;" id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <img src="/storage/{{ auth()->user()->image }}" style="width: 30px; height: 30px; border-radius: 50%;">
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="/profile"><ion-icon name="person" class="fix"></ion-icon>&nbsp;Profile</a>
                                        </li>
                                        <li>                                      
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                        document.getElementById('logout-form').submit();">
                                                <ion-icon name="log-out" class="fix"></ion-icon>&nbsp;Logout
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li>                                    
                                    </ul>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
            
                @yield('content')        
        </div>
    </div>
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/ionicons@latest/dist/ionicons.js"></script>
    <script src="{{ URL::asset('js/app.js') }}"></script>
    @yield('js')
    <script src="{{ URL::asset('js/scripts.js') }}"></script>
    <script src="https://c6.patreon.com/becomePatronButton.bundle.js"></script>

</body>
</html>
