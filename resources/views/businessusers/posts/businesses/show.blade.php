@extends('layouts.app')


<link rel="stylesheet" href="{{ asset('css/viewbusiness.css') }}">

@section('title', 'Business View')

@section('content')
    <div class="page-wrapper">
        <div class="page-container">

            <!-- Main Image Section -->
            <section class="main-image-section">
                <div class="main-image-wrapper mt-3">
                    <img src="{{  $business->main_image }}" alt="{{ $business->title }}" class="card-img-top body-image" alt="image">
                    {{-- <img class="main-image" alt="Main picture" src="{{ $business->main_image }}" /> --}}



                    @if($business->official_certification==3)
                        <img src="{{ asset('images/logo/Official_Badge.png') }}" class="official-badge" alt="official">              
                    @else
                    @endif
                </div>  
                                
            </section>
                <div class="main-title ms-4 text-center">
                    {{ $business->name }}
               
                    <h4 class="text-center">
                        {{ $business->created_at->format('M d Y')}}
                    </h4>
                </div>

            <!-- User Profile Header -->
            {{-- <section class="profile-header">
                <div class="profile-container">
                    <div class="profile-left d-flex align-items-center justify-content-between">
                        
                        <!-- 左側（プロフィール画像と名前） -->
                        <div class="profile-main d-flex align-items-center">
                            <div class="col-md-auto col-sm-2 my-auto p-0 profile-pic">                   
                                <button class="btn">
                                    <a href="{{route('profile.header', $business->user->id)}}">
                                    @if($business->user->avatar)
                                        <img src="{{ $business->user->avatar }}" alt="" class="rounded-circle avatar-sm">
                                    @else
                                        <i class="fa-solid fa-circle-user text-secondary profile-sm d-block text-center"></i>
                                    @endif
                                    </a>
                                </button>
                            </div>
                            <div class="col-auto profile-name ms-2 ">
                                <a href="{{route('profile.header', $business->user->id)}}" class="text-decoration-none d-inline text-dark">
                                    {{$business->user->name}}
                                </a>
                            </div>

                            @if($business->user->official_certification == 3)
                            <div class="col-md-1 col-sm-1 pb-1 p-1">
                                <img src="{{ asset('images/logo/official_personal.png')}}" class="official-personal d-inline ms-0 avatar-xs" alt="official-personal"> 
                            </div>
                            @endif
                        </div>
                         <!-- Followボタン -->
                        @if($business->user->id !== Auth::user()->id)
                            <div class="col-1 mt-3 me-auto">
                                @if($business->user->isFollowed())
                                    <!-- unfollow -->
                                    <form action="{{route('delete.follow', $business->user->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-following btn-sm w-100">Following</button>
                                    </form>
                                @else
                                    <!-- follow -->
                                    <form action="{{route('store.follow', $business->user->id )}}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-follow btn-sm w-100">Follow</button>
                                    </form>
                                @endif 
                            </div>
                        @endif
                        <!-- 右側（SNSアイコン） -->
                    </div>
                </div>
            </section> --}}
            

            <!-- Photo Section -->
            <section class="business-promotion">
            <div class="promotion-container">
                @if(count($business_photos) > 0)
                    <div class="promotion-carousel">
                        <div class="carousel-controls">
                            <button class="carousel-arrow prev" role="button" aria-label="Previous slide">&larr;</button>
                            <button class="carousel-arrow next" role="button" aria-label="Next slide">&rarr;</button>
                        </div>

                        <div class="carousel-items-container">
                            @foreach($business_photos as $index => $post)
                                <div class="promotion-item {{ $index < 5 ? 'active' : '' }}">
                                    <div class="col">
                                        @if($post->image)
                                        <img src="{{ $post->image }}" alt="Business Photo" class="img-photo mb-2 mx-auto" style="width: 100%; height:300px; object-fit:cover">
                                        @else
                                        No photos yet
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="carousel-indicators">
                            @php
                                $totalSlides = ceil(count($business_photos) / 3);
                            @endphp
                            @for($i = 0; $i < $totalSlides; $i++)
                                <div class="carousel-indicator {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}"></div>
                            @endfor
                        </div>
                    </div>
                @else
                    <div class="text-center">No photos yet</div>
                @endif
            </div>
            </section>

          

            <!-- Business Introduction -->
            <section class="business-introduction">
                <h3>Introduction</h3>
                <div class="introduction-box w-75 mx-5">                   
                    <p>{{ $business->introduction }}</p>
                </div>
            </section>




            <!-- Comments Section -->
            <hr>
            @include('businessusers.posts.businesses.partials.comment_body')
            <div class="d-flex justify-content-center mt-3">
                {{-- {{ $business_comments->links() }} --}}
            </div>

            <!-- Go to Top Button -->
            {{-- <div class="top-button-container">
                <button class="top-button">
                    <a href="#" class="text-decoration-none color-navy">
                        <i class="fa-solid fa-plane-up fs-3"></i>
                        <p class="color-navy m-0 p-0 text-center fs-8 poppins-semibold">Go TOP</p>
                    </a>
                </button>
            </div> --}}
        </div>
    </div>


    {{-- public/map.js --}}
    <script src="{{ asset('map.js') }}"></script>

    {{-- Google Maps API (callback=initMap) --}}
    <script async
        src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.key') }}&libraries=places&callback=initMap">
    </script>

    {{--promotion carousel --}}
    <script src="{{ asset('js/viewbusiness.js') }}"></script>

@endsection