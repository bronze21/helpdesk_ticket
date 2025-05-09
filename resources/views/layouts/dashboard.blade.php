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
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('logo.png') }}">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss'])
    <link rel="stylesheet" href="{{asset('css/style.css?t=').strtotime('now')}}">
    @yield('css')
    @vite(['resources/js/app.js'])
    @yield('alpinejs')
</head>
<body>
    <div id="app" class="d-flex bg-light" data-plugin="{{json_encode($plugins ?? [])}}" x-data>
        <nav class="sidebar bg-dark shadow-sm py-0" x-data="{selectedMenu:''}" :class="{'collapsed':$store.menu.collapse, 'hide': $store.menu.hide}">
            <div class="sidebar-wrapper flex-wrap w-100" style="max-width: 100%;">
                <div class="d-flex align-items-center mb-3 p-3 border-bottom" id="logo">
                    <a href="{{route('home')}}" class="nav-link">
                        <img src="{{asset('logo.png')}}" alt="Logo" class="img-fluid" width="40">
                        <h5 class="mb-0 text-white">{{ config('app.name') }}</h5>
                    </a>
                </div>
                <div class="d-flex w-100 position-relative" id="nav-menus">
                    <ul class="navbar-nav w-100">
                        <li class="nav-item">
                            <a href="{{route('home')}}" class="nav-link {{Route::is('home')?'active':''}}" role="button">
                                <i class="fa fa-home"></i> 
                                <span>Home</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('tickets.index')}}" class="nav-link {{Route::is('tickets.*')?'active':''}}" role="button">
                                <i class="fa fa-tags"></i> 
                                <span>Ticket</span>
                            </a>
                        </li>
                        @feature('isAdmin')
                        <li class="nav-item">
                            <a href="{{route('categories.index')}}" class="nav-link {{Route::is('categories.*')?'active':''}}" role="button">
                                <i class="fa fa-th"></i> 
                                <span>Categories</span>
                            </a>
                        </li>
                        @endfeature
                        @feature('isAdmin')
                        <li class="nav-item">
                            <a href="{{route('users.index')}}" class="nav-link {{Route::is('users.*')?'active':''}}" role="button">
                                <i class="fa fa-users"></i> 
                                <span>Users</span>
                            </a>
                        </li>
                        @endfeature
                        @feature('isAdmin')
                        <li class="nav-item">
                            <a href="{{route('staff.index')}}" class="nav-link {{Route::is('staff.*')?'active':''}}" role="button">
                                <i class="fa fa-users"></i> 
                                <span>Staff</span>
                            </a>
                        </li>
                        @endfeature
                        @feature('isAdmin')
                        <li class="nav-item">
                            <a href="{{route('roles.index')}}" class="nav-link {{Route::is('roles.*')?'active':''}}" role="button">
                                <i class="fa fa-gears"></i> 
                                <span>Roles</span>
                            </a>
                        </li>
                        @endfeature
                        {{-- <div class="d-md-none">
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
                        </div> --}}
                    </ul>
                    <div class="sidebar-action-wrapper">
                        <button type="button" class="btn rounded btn-outline-light btn-collapsed d-none d-md-inline-block" @click="$store.menu.collapse = !$store.menu.collapse">
                            <i class="fa" :class="$store.menu.collapse?'fa-chevron-right':'fa-chevron-left'"></i>
                        </button>
                        <button type="button" class="btn rounded btn-outline-light btn-hide d-md-none d-inline-block" @click="$store.menu.hide = !$store.menu.hide">
                            <i class="fa" :class="$store.menu.hide?'fa-chevron-right':'fa-chevron-left'"></i>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
        <main class="flex-grow-1 flex-wrap">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm py-3" style="z-index: 1050;">
                <div class="container-fluid flex-wrap">
                    <div class="crumbs align-self-center">
                        <button type="button" class="btn btn-sm d-md-none d-inline-block" @click="$store.menu.hide = false">
                            <i class="fa fa-bars"></i>
                        </button>
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
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    {{-- <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button> --}}
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto">
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
                                <li class="nav-item dropdown d-none d-lg-block">
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
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                                <li class="nav-item d-block d-lg-none">
                                    <a href="{{route('profile')}}" class="nav-link">
                                        <i class="fa fa-user me-1"></i> {{ Auth::user()->name }}
                                    </a>
                                </li>
                                <li class="nav-item d-block d-lg-none">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            @endguest
                        </ul>
                        {{-- <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                            </li>
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                            </li>
                            <li class="nav-item">
                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                            </li>
                        </ul> --}}
                        {{-- <form class="d-flex">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form> --}}
                    </div>
                    
                    <div class="d-flex w-100">
                        {{-- <div class="px-2 ms-auto ms-lg-0 d-md-none d-block"></div> --}}
                    </div>
                    {{-- <div class="d-flex w-100">
                        <a class="navbar-brand" href="{{ route('home') }}">
                            {{ config('app.name', 'Laravel') }}
                    </div> --}}
                </div>
            </nav>
            <section class="master-content" @isset($x_data) x-data="{{$x_data}}" @endisset>
                <section class="submenu sticky-top bg-light">
                    <div class="submenu-content text-dark">
                        <div class="container-fluid d-flex">
                            <div class="me-auto align-self-center">
                                <h4 class="title mb-0">{{$title}}</h4>
                            </div>
                            @yield('submenu')
                        </div>
                    </div>
                </section>
                <section class="dashboard">
                    <div class="w-100 py-1"></div>
                    @yield('content')
                    <section class="modal-wrapper">
                        @yield('modal')
                    </section>
                </section>
                <section class="toast-container position-fixed bottom-0 end-0 p-3">
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
                </section>
            </section>
        </main>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('menu', {
                collapse: false,
                hide: false,
                init(){
                    if(window.innerWidth <= 560 && window.matchMedia("(orientation: portrait)").matches){
                        this.hide = true
                        console.log(this.hide)
                    }
                }
            })
        })
    </script>
    @yield('js')
</body>
</html>
