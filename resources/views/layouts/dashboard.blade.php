<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel'). (isset($title)?"- $title":'') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss'])
    <link rel="stylesheet" href="{{asset('css/style.css?t=').strtotime('now')}}">
    @yield('css')
    @vite(['resources/js/app.js'])
    @yield('alpinejs')
</head>
<body>
    <div id="app" class="bg-light" data-plugin="{{json_encode($plugins ?? [])}}">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container flex-wrap">
                <div class="d-flex w-100">
                    <a class="navbar-brand" href="{{ route('home') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    <ul class="navbar-nav ms-auto d-none d-md-flex">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a href="{{route('profile')}}" class="dropdown-item">
                                        <i class="fa fa-user me-1"></i> Profile
                                    </a>
                                    <hr class="w-100">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </div>
                            </li>
                        @endguest
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                    <div class="px-2 ms-auto ms-lg-0 d-md-none d-block"></div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
        </nav>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm py-0 position-relative" x-data="{selectedMenu:''}">
            <div class="container flex-wrap">
                <div class="d-flex w-100">
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a href="{{route('home')}}" class="nav-link active" role="button">
                                    <i class="fa fa-home me-1"></i>Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{route('tickets.index')}}" class="nav-link" role="button"><i class="fa fa-tags me-1"></i> Ticket</a>
                            </li>
                            @feature('isAdmin')
                            <li class="nav-item">
                                <a href="{{route('categories.index')}}" class="nav-link" role="button"><i class="fa fa-th me-1"></i> Categories</a>
                            </li>
                            @endfeature
                            @feature('isAdmin')
                            <li class="nav-item">
                                <a href="{{route('users.index')}}" class="nav-link" role="button"><i class="fa fa-users me-1"></i> Users</a>
                            </li>
                            @endfeature
                            @feature('isAdmin')
                            <li class="nav-item">
                                <a href="{{route('staff.index')}}" class="nav-link" role="button"><i class="fa fa-users me-1"></i> Staff</a>
                            </li>
                            @endfeature
                            @feature('isAdmin')
                            <li class="nav-item">
                                <a href="{{route('roles.index')}}" class="nav-link" role="button"><i class="fa fa-gears me-1"></i> Roles</a>
                            </li>
                            @endfeature
                            <div class="d-md-none">
                                @guest
                                    @if (Route::has('login'))
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                        </li>
                                    @endif
    
                                    @if (Route::has('register'))
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                        </li>
                                    @endif
                                @else
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                            {{ Auth::user()->name }}
                                        </a>
    
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>
                                        </div>
                                    </li>
                                @endguest
                            </div>
                        </ul>
    
                        <!-- Right Side Of Navbar -->
                    </div>
                </div>
            </div>
        </nav>
        
        <div class="master-content" @isset($x_data) x-data="{{$x_data}}" @endisset>
            <div class="submenu">
                <div class="submenu-content bg-secondary">
                    <div class="container d-flex">
                        <div class="me-auto align-self-center text-white">
                            <h4 class="title mb-0">{{$title}}</h4>
                            <div class="crumbs">
                                @foreach ($crumbs as $crumb)
                                @if (!$loop->last)
                                <a href="{{$crumb['url']}}" title="{{$crumb['title']}}" class="crumb-item">
                                    @if (isset($crumb['icon']))
                                        <i class="me-1 {{$crumb['icon']}}"></i>
                                    @endif
                                    {{$crumb['title']}}
                                </a>
                                @else
                                <small class="crumb-item">
                                    @if (isset($crumb['icon']))
                                        <i class="me-1 {{$crumb['icon']}}"></i>
                                    @endif
                                    {{$crumb['title']}}
                                </small>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        @yield('submenu')
                    </div>
                </div>
            </div>
            <main class="dashboard">
                @yield('content')
            </main>
            @yield('modal')
            <div class="toast-container position-fixed bottom-0 end-0 p-3">
                @if (count($errors) > 0)
                    @foreach ($errors->all() as $error)
                        <div class="toast show border-danger" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
                            <div class="toast-header bg-danger">
                                <strong class="me-auto text-white">Some Error Occurred!</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                {{$error}}
                            </div>
                        </div>
                    @endforeach
                @endif

                @if (session('success'))
                    @if (is_array(session('success')))
                        @foreach (session('success')->all() as $success)
                            <div class="toast show border-success" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
                                <div class="toast-header bg-success">
                                    <strong class="me-auto text-white">Success</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    {{$success}}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="toast show border-success" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
                            <div class="toast-header bg-success">
                                <strong class="me-auto text-white">Success</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                {{session('success')}}
                            </div>
                        </div>
                    @endif
                @endif

                @if (session('warning'))
                    @if (is_array(session('warning')))
                        @foreach (session('warning') as $warning)
                            <div class="toast show border-warning" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
                                <div class="toast-header bg-warning">
                                    <strong class="me-auto text-white">Attention! Please check again</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body">
                                    {{$warning}}
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="toast show border-warning" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
                            <div class="toast-header bg-warning">
                                <strong class="me-auto text-white">Attention! Please check again</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                            </div>
                            <div class="toast-body">
                                {!!session('warning')!!}
                            </div>
                        </div>
                    @endif
                @endif

                @if (session('error'))
                    <div class="toast show border-danger" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true">
                        <div class="toast-header bg-danger">
                            <strong class="me-auto text-white">Some Error Occurred!</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            {{session('error')}}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>
    </script>
    @yield('js')
</body>
</html>
