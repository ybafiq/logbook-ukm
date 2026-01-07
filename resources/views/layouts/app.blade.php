<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        /* === UKM Color Theme === */
        :root {
            --ukm-red: #E41B13;
            --ukm-blue: #1B365D;
            --ukm-yellow: #FFD100;
            --ukm-gray: #F8F9FA;
        }

        body {
            background-color: var(--ukm-gray);
            font-family: 'Nunito', sans-serif;
            padding-bottom: 70px; 
        }

        .navbar {
            background: linear-gradient(90deg, var(--ukm-blue), var(--ukm-red));
        }
        .navbar-logo {
            height: 30px;
            margin-right: 5px;
            filter: grayscale(50%);
        }

        .navbar-brand {
            color: var(--ukm-yellow) !important;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .navbar-nav .nav-link {
            color: white !important;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .navbar-nav .nav-link:hover {
            color: var(--ukm-yellow) !important;
            text-decoration: underline;
        }

        .dropdown-menu {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        main {
            color: #f0f0f0;
            padding-top: 1rem; /* Adjust this padding as needed */
            padding-bottom: 1rem;
        }

        footer {
            background: var(--ukm-blue);
            color: white;

            display: flex;
            justify-content: center;   
            align-items: center;       

            padding: 0.5rem 0;         

            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;

            z-index: 999;
        }

        footer p {
            margin: 0;                 
        }

        .auth-background {
            min-height: 40vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        body {
            background-image: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('/images/2.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed; /* optional: makes it stay in place */
        }

        .card {
            background: rgba(255, 255, 255, 0.9); /* transparent white */
            backdrop-filter: blur(10px); /* optional: smooth glass effect */
        }

        .card-container {
            /* This is the key change for vertical centering */
            min-height: calc(80vh - 56px); /* 100vh minus navbar height (approx 56px) */
            display: flex;
            justify-content: center; /* Centers the card horizontally */
            align-items: center;     /* Centers the card vertically */
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .login-card {
            max-width: 420px; 
            width: 100%;
            border-radius: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.25); 
        }

        .card-header {
            font-size: 1rem;
            padding: 1rem;
        }


    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <!-- <img src="/images/ukm3.png" alt="UKM Logo" class="navbar-logo"> -->
                    {{ config('app.name', 'UKM Logbook') }}
                </a>
                <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if(auth()->user()->isStudent())
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="logEntriesDropdown" role="button" data-bs-toggle="dropdown">
                                        {{ __('Log Entries') }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('log-entries.index') }}">{{ __('View All') }}</a></li>
                                        <li><a class="dropdown-item" href="{{ route('log-entries.create') }}">{{ __('Create New') }}</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="projectEntriesDropdown" role="button" data-bs-toggle="dropdown">
                                        {{ __('Project Entries') }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('project-entries.index') }}">{{ __('View All') }}</a></li>
                                        <li><a class="dropdown-item" href="{{ route('project-entries.create') }}">{{ __('Create New') }}</a></li>
                                    </ul>
                                </li>
                            @endif
                            
                            @if(auth()->user()->isAdmin())
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="logEntriesDropdown" role="button" data-bs-toggle="dropdown">
                                        {{ __('Log Entries') }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('log-entries.index') }}">{{ __('View All') }}</a></li>
                                        <li><a class="dropdown-item" href="{{ route('log-entries.create') }}">{{ __('Create New') }}</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="projectEntriesDropdown" role="button" data-bs-toggle="dropdown">
                                        {{ __('Project Entries') }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('project-entries.index') }}">{{ __('View All') }}</a></li>
                                        <li><a class="dropdown-item" href="{{ route('project-entries.create') }}">{{ __('Create New') }}</a></li>
                                    </ul>
                                </li>
                            @endif
                            
                            <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">{{ __('Users') }}</a></li>
                            
                            @if(auth()->user()->isSupervisor())
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="supervisorDropdown" role="button" data-bs-toggle="dropdown">
                                        {{ __('Supervisor') }}
                                        @if(auth()->user()->unreadNotifications->count() > 0)
                                            <span class="badge bg-danger">{{ auth()->user()->unreadNotifications->count() }}</span>
                                        @endif
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('supervisor.dashboard') }}">{{ __('Dashboard') }}</a></li>
                                        <li><a class="dropdown-item" href="{{ route('supervisor.pendingEntries') }}">{{ __('Pending Log Entries') }}</a></li>
                                        <li><a class="dropdown-item" href="{{ route('supervisor.pendingProjectEntries') }}">{{ __('Pending Project Entries') }}</a></li>
                                    </ul>
                                </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('users.profile') }}">{{ __('My Profile') }}</a>
                                    <a class="dropdown-item" href="{{ route('home') }}">{{ __('Dashboard') }}</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                       document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <footer>
            <p>© {{ date('Y') }} Universiti Kebangsaan Malaysia</p>
        </footer>
    </div>
</body>
</html>

