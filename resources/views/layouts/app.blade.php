<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>HopQuest | @yield('title')</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <!-- Scripts -->
    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
    <link rel="stylesheet" href="{{ asset('css/app-C7NydqTG.css') }}">
    <link rel="stylesheet" href="{{ asset('js/app-BZpg9W-k.js') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-style.css') }}">
    @yield('css')
    <!-- jQuery -->
    <script src="{{ asset('js/home/jquery-3.6.0.min.js') }}"></script>
    <!-- CSS of slick -->
    <link rel="stylesheet" href="{{ asset('css/slick/slick.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/slick/slick-theme.css') }}" />
    <!-- JS of slick -->
    <script src="{{ asset('js/home/slick.min.js') }}"></script>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm fixed-top">
            <div class="container">
                <!-- left: LOGO -->
                @guest
                    <a class="navbar-brand me-lg-5" href="{{ route('posts.all') }}">
                        <img src="{{ asset('images/logo/NISHIMOO.png') }}" alt="HopQuest LOGO"
                            class="nav-img me-lg-5"></a>
                @else
                    @if (Auth::user()->role_id == 1)
                        <a class="navbar-brand me-lg-5" href="{{ route('posts.all') }}">
                            <img src="{{ asset('images/logo/NISHIMOO.png') }}" alt="HopQuest LOGO"
                                class="nav-img me-lg-5"></a>
                    @elseif(Auth::user()->role_id == 2)
                        <a class="navbar-brand me-lg-5" href="{{ route('posts.all') }}">
                            <img src="{{ asset('images/logo/NISHIMOO.png') }}"
                                alt="HopQuest LOGO for Business" class="nav-img me-lg-5"></a>
                    @elseif(Auth::user()->role_id == 3)
                        <a class="navbar-brand me-lg-5" href="{{ route('posts.all') }}">
                            <img src="{{ asset('images/logo/NISHIMOO.png') }}" alt="HopQuest LOGO"
                                class="nav-img me-lg-5"><span class="color-navy fw-bold"></span></a>
                    @endif
                @endguest
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
                    {{-- Search bar here --}}
                    <ul class="navbar-nav mx-auto">
                        {{-- <form action="" class="nav-search d-flex align-items-center justify-content-between ms-md-5 my-auto"> --}}
                        <form action="{{ route('search') }}"
                            class="nav-search d-flex align-items-center justify-content-between ms-md-5 my-auto">
                            <input type="search" name="search" placeholder="Search..."
                                class="form-control form-control-sm">
                        </form>
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-lg-5">
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
                            <li class="nav-item my-auto">
                                <a href="{{ route('posts.all') }}" class="nav-link" href="">HOME</a>
                            </li>
                            <li class="nav-item dropdown my-auto">

                                    <a id="navbarDropdown" class="nav-link btn " href="#" role="button"
                                        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>+Add</a>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        @if (Auth::user()->role_id == 3)
                                            <a href="{{ route('quest.add') }}" class="dropdown-item text-dark">
                                                <i class="fa-solid fa-circle-plus icon-sm"></i> Travel
                                            </a>
                                            <a href="{{ route('businesses.create') }}" class="dropdown-item text-dark">
                                                <i class="fa-solid fa-circle-plus icon-sm"></i> Favorite
                                            </a>                                            
                                            <a href="{{ route('spots.create') }}" class="dropdown-item text-dark">
                                                <i class="fa-solid fa-circle-plus icon-sm"></i> Others
                                            </a>
                                       

                                         
                                        @endif
                                    </div>
                            </li>
                            <li class="nav-item my-auto">
                            <li class="nav-item my-auto">
                                <a href="{{ route('faq') }}" class="nav-link" href="">FAQ</a>
                            </li>
                            {{-- <li class="nav-item my-auto"> --}}
                            {{-- @if (Auth::user()->role_id == 1)
                        <li class="nav-item my-auto">
                            <a href="" class="nav-link d-xl-block d-none" href="">For Business</a>
                            <a class="nav-link d-block d-xl-none text-center business"><img src="{{asset('images/navbar/icomoon-free--office.svg')}}" alt="For business"><br>business</a>
                        </li>
                    @endif --}}
                            <li class="nav-item dropdown my-auto">
                                <!-- ICON -->
                                <a id="navbarDropdown" class="nav-link btn " href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{-- {{ Auth::user()->name }} --}}
                                    {{-- DROPDOWN --}}

                                    @if (Auth::user()->role_id !== 3)
                                        @if(Auth::user()->avatar)
                                            <img src="{{ Auth::user()->avatar }}" alt="#" class="rounded-circle avatar-sm">
                                        @else
                                            <i class="fa-solid fa-circle-user text-secondary text-decoration-none profile-sm text-center"></i>
                                        @endif
                                    @else
                                        @if(Auth::user()->role_id == 3)
                                            <i class="fa-solid fa-circle-user color-green icon-md"></i>
                                        @else
                                            <i class="fa-solid fa-circle-user text-secondary icon-md"></i>
                                        @endif
                                    @endif
                                </a>
                                <!-- Dropdown menu -->
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    {{-- PROFILE --}}
                                    @if (Auth::user()->role_id == 1)
                                        <a href="{{ route('profile.header', Auth::user()->id) }}" class="dropdown-item">
                                            <i class="fa-solid fa-circle-user"></i> Profile
                                        </a>
                                    @elseif(Auth::user()->role_id == 2)
                                        <a href="{{ route('profile.header', Auth::user()->id) }}" class="dropdown-item">
                                            <i class="fa-solid fa-circle-user"></i> Profile
                                        </a>
                                    @elseif(Auth::user()->role_id == 3)
                                        @can('admin')
                                            <a href="{{ route('profile.header', Auth::user()->id) }}" class="dropdown-item">
                                                <i class="fa-solid fa-circle-user"></i> Profile
                                            </a>
                                            <a href="{{ route('admin.users.business', Auth::user()->id) }}"
                                                class="dropdown-item">
                                                <i class="fa-solid fa-circle-user"></i> Admin
                                            </a>
                                        @endcan
                                    @endif
                                    <hr class="dropdown-divider">
                                    <a class="dropdown-item" href="{{ route('home') }}"
                                        onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                                        <i class="fa-solid fa-arrow-right-from-bracket"></i> {{ __('Logout') }}
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <main class="pt-5">
            @yield('content')
        </main>
        {{-- フッターの追加 --}}
        {{-- <footer class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <small class="text-dark">&copy; 2025 Kredo 30th batch All Rights Reserved.</small>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item my-auto"><a href="" class="nav-link">About us</a></li>
                    <li class="nav-item my-auto"><a href="" class="nav-link">Sitemap</a></li>
                    <li class="nav-item my-auto"><a href="" class="nav-link">Terms of service</a></li>
                    <li class="nav-item my-auto"><a href="" class="nav-link">FAQ</a></li>
                </ul>
            </div>
        </footer> --}}
    </div>
    @yield('js')
</body>

</html>
