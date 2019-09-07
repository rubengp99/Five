<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://unpkg.com/ionicons@4.5.10-0/dist/css/ionicons.min.css" rel="stylesheet">
    @yield('css')
    <link rel="stylesheet" href="{{ asset('/css/styles.css') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
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
                    <a class="navbar-brand bold-brand text-white" href="{{ url('/home') }}" style="margin-top: 0px;padding: 8px;font-family: adrip;letter-spacing: 2px;font-size: 26px!important;">
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
                            <li><a class="text-white" href="{{ route('login') }}"><ion-icon class="fix" name="unlock"></ion-icon>&nbsp;Login</a></li>
                            <li><a class="text-white" href="{{ route('register') }}"><ion-icon class="fix" name="create"></ion-icon>&nbsp;Register</a></li>
                        @else
                            <li class="dropdown">
                                <a class="text-white" href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" aria-haspopup="true" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
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
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://unpkg.com/ionicons@4.5.10-0/dist/ionicons.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('js')
</body>
</html>
