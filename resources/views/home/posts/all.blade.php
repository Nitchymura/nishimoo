<div class="bg-green">
@extends('layouts.app')

@section('title', 'Home')
{{-- @section('css')
    <link rel="stylesheet" href="{{ asset('css/home.css')}}">
    <link rel="stylesheet" href="{{ asset('css/home-cards.css')}}">
@endsection --}}

@section('content')

    {{-- Header video --}}   
    <div class="wrapper-header position-relative overflow-hidden d-flex align-items-center justify-content-center">
        <video autoplay muted loop playsinline class="header_video">
            <source src="{{ asset('videos/intro.mp4') }}" type="video/mp4">
        </video>
    
        {{-- Header video's title --}}
        <div class="title">
            @guest
                <h3 class="text-light z-1 title-text poppins-bold">Hello, Guest user !</h3>
            @else
                <h3 class="text-light z-1 title-text poppins-bold">Hello, <span>{{Auth::user()->name}}</span>!</h3>
            @endguest
            
        </div>        
    </div>

    
    
@include('home.posts.intro')

@include('home.posts.post-header')

    
<div class="mt-4 mb-5 row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-11 mb-5 ">
        <div class="col-2 ms-auto dropdown">
            <form method="GET" action="{{ route('posts.all') }}">
                <select name="sort" onchange="this.form.submit()" class="form-control">
                    <option value="" disabled selected>Sort</option>
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest </option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="likes" {{ request('sort') == 'likes' ? 'selected' : '' }}>Number of Likes</option>
                    <option value="comments" {{ request('sort') == 'comments' ? 'selected' : '' }}>Number of Comments</option>
                    {{-- <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Number of Views</option> --}}
                </select>
            </form>
        </div>  
        <div class="row mb-3">
        @forelse($all as $post)
            <div class="col-lg-4 col-md-6 col-sm">
                @include('home.posts.post-body')
            </div>         
        @empty
            <h4 class="h4 text-center text-secondary">No posts yet</h4>
        @endforelse
        </div>
        <div class="d-flex justify-content-end mb-5">
            {{ $all->links() }}
        </div>
    </div>   
</div>
<div class="top-button-container">
    {{-- <button class="top-button">
        <a href="#" class="text-decoration-none color-navy">
            <i class="fa-solid fa-plane-up fs-3"></i>
            <p class="color-navy m-0 p-0 text-center fs-8 poppins-semibold">Go TOP</p>
        </a>
    </button> --}}
</div>
</div>
    @endsection
        