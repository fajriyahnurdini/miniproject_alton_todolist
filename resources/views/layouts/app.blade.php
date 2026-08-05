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
    
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            background-color: #f5f3ff;
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .navbar-custom {
            background-color: #e0d5ff !important;
            padding: 12px 24px;
        }
        .navbar-brand-custom {
            color: #6f42c1 !important;
            font-weight: 800;
            font-size: 1.4rem;
        }
        .nav-link-custom {
            color: #6f42c1 !important;
            font-weight: 600;
        }
        .card-auth {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(111, 66, 193, 0.08);
            background: #ffffff;
            overflow: hidden;
        }
        .card-auth-header {
            background-color: #e0d5ff;
            color: #6f42c1;
            font-weight: 700;
            font-size: 1.25rem;
            text-align: center;
            padding: 20px;
            border-bottom: none;
        }
        .btn-purple {
            background-color: #9d72ff;
            color: white;
            border-radius: 12px;
            font-weight: 700;
            padding: 10px 20px;
            transition: all 0.3s ease;
        }
        .btn-purple:hover {
            background-color: #8352ff;
            color: white;
            transform: translateY(-2px);
        }
        .form-control:focus {
            border-color: #9d72ff;
            box-shadow: 0 0 0 0.25rem rgba(157, 114, 255, 0.25);
        }
    </style>
</head>
<body>
    <div id="app" class="d-flex flex-column min-vh-100">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-md navbar-light navbar-custom shadow-sm">
            <div class="container">
                <a class="navbar-brand navbar-brand-custom d-flex align-items-center gap-2" href="{{ url('/') }}">
                    <i class="fas fa-check-double"></i> My To-Do List
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link nav-link-custom me-2" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i> {{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link btn btn-purple text-white btn-sm px-3 rounded-pill" href="{{ route('register') }}"><i class="fas fa-user-plus me-1"></i> {{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle nav-link-custom" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-1"></i> {{ __('Logout') }}
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

        <!-- Content Area -->
        <main class="py-5 flex-grow-1 d-flex align-items-center">
            @yield('content')
        </main>
    </div>
</body>
</html>