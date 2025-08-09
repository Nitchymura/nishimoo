@extends('layouts.app')

@section('title', 'Home')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/home.css')}}">
    <link rel="stylesheet" href="{{ asset('css/home-cards.css')}}">
@endsection

@section('js')
    <script src="{{ asset('js/like.js')}}"></script>
    <script src="{{ asset('js/follow.js')}}"></script>
@endsection

@section('content')
    {{-- @guest --}}
    {{-- Header video --}}
    <div class="wrapper-header position-relative overflow-hidden d-flex align-items-center justify-content-center">
        <video autoplay muted loop playsinline class="header_video">
            <source src="{{ asset('videos/header-video-1.mp4') }}" type="video/mp4">
        </video>
    
        {{-- Header video's title --}}
        <div class="title">
            @guest
                <h3 class="text-light z-1 title-text poppins-bold">Hello, Guest user !</h3>
            @else
                <h3 class="text-light z-1 title-text poppins-bold">Hello, <span>{{Auth::user()->name}}</span>!</h3>
            @endguest
            <h3 class="text-light z-1 title-text poppins-bold">Welcome to my page</h3>
        </div>

        
    </div>
  


    


        {{-- Body for FAQ --}}
        <div class="container faq-body d-flex justify-content-center align-items-center">
            <div class="faq-title col-6 text-center justify-content-center align-items-center">
                <h1 class="h1 poppins-bold">F A Q</h1>
                {{-- <h6 class="poppins-regular">Frequently asked questions</h6> --}}
            </div>
        </div>


        <div class="container">
            <div class="row justify-content-center">
                <div class="col-2 faq-jump col-6 text-center justify-content-center align-items-center mt-5">
                    <a href="{{ route('faq')}}" class="text-decoration-none">
                        <button class="btn-faq slide">
                            Read Full FAQ
                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection

@vite(['resources/js/home/home.js'])
