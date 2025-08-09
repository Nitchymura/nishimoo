<div class="bg-green">
@extends('layouts.app')

@section('title', 'Followings')

@section('content')

@include('home.posts.post-header')

    @auth
        <div class="mt-4 mb-5 row justify-content-center">
            <div class="col-8 mb-5 ">
                <div class="col-2 ms-auto dropdown">
                    <form method="GET" action="{{ route('posts.followings') }}">
                        <select name="sort" onchange="this.form.submit()" class="form-control">
                            <option value="" disabled selected>Sort</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest </option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                            <option value="likes" {{ request('sort') == 'likes' ? 'selected' : '' }}>Number of Likes</option>
                            <option value="comments" {{ request('sort') == 'comments' ? 'selected' : '' }}>Number of Comments</option>
                            <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Number of Views</option>
                        </select>
                    </form>
                </div> 
                <div class="row mb-3">
                @forelse($all_followings as $post)
                    <div class="col-lg-4 col-md-6 col-sm">
                        @include('home.posts.post-body')
                    </div>         
                @empty
                    <h4 class="h4 text-center text-secondary">No posts yet</h4>
                @endforelse
                </div>
                <div class="d-flex justify-content-end mb-5">
                    {{ $all_followings->links() }}
                </div>
            </div>   
        </div>
        </div>
    @endauth

    @guest
        <div class="d-flex flex-column justify-content-center align-items-center not-found p-4">
            <img src="{{ asset('images/home/login-required.png') }}" alt="Login required" style="width: 15rem;" class="mb-3">
            <h5 class="text-secondary mb-3">You need to log in to see posts from users you follow.</h5>
            <div>
                <a href="{{ route('login') }}" class="btn btn-primary me-2">Log In</a>
                <a href="{{ route('register') }}" class="btn btn-outline-secondary">Sign Up</a>
            </div>
        </div>

        </div>
    @endguest

@endsection
    