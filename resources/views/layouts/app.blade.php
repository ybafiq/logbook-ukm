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
        }

        .navbar {
            background: linear-gradient(90deg, var(--ukm-blue), var(--ukm-red));
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
            background-color: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin: 2rem auto;
            width: 90%;
            max-width: 1200px;
        }

        footer {
            background: var(--ukm-blue);
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: 3rem;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'UKM Logbook') }}
                </a>
                <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ route('log-entries.create') }}">Log Entries</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Comments</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">Weekly Reflection</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}">Personal Information</a></li>
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
            <p>© {{ date('Y') }} Universiti Kebangsaan Malaysia | Logbook System</p>
        </footer>
    </div>
</body>
</html>

