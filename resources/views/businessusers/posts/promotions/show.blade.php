delete<div class="bg-blue">
@extends('layouts.app')

@section('title', 'Promotion View')

@section('title', 'Promotion View')


    <link rel="stylesheet" href="{{ asset('css/promotion-body.css')}}">
    {{-- <link rel="stylesheet" href="{{ asset('css/viewbusiness.css') }}"> --}}

    {{-- <link rel="stylesheet" href="{{ asset('css/viewbusiness.css') }}"> --}}


@section('content')

    <div class="page-wrapper mt-5 pb-5">
        <div class="page-container">

            <!-- Main Image Section -->
            <section class="main-image-section">
                <div class="main-image-wrapper mt-3">
                    <img class="main-image" alt="Main picture" src="{{ $business_promotion->image }}" />

                    <div class="main-title">
                        {{ $business_promotion->title }}
                    </div>
                    <div class="sub-title">
                        {{ $business_promotion->business->name }}
                    </div>
                    <div class="event-dates">
                        {{date('M d Y', strtotime($business_promotion->promotion_start))}}~{{date('M d Y', strtotime($business_promotion->promotion_end))}}
                    </div>
                    <div class="post-dates">
                        @if($business_promotion->updated_at)
                            <h5 >Posted: {{ $business_promotion->updated_at->format('M d Y')}}</h5>
                        @else
                            <h5 >Posted: {{ $business_promotion->created_at->format('M d Y')}}</h5>
                        @endif
                    </div>
                </div>
            </section>

            <!-- User Profile Header -->
            <section class="profile-header">
                <div class="profile-container">
                    <div class="profile-left">
                        <div class="profile-main">
                            <div class="col-md-auto col-sm-2 my-auto p-0 profile-pic">                   
                                <button class="btn">
                                    @if($business_promotion->user->avatar)
                                        <img src="{{ $business_promotion->user->avatar }}" alt="" class="rounded-circle avatar-sm">
                                    @else
                                        <i class="fa-solid fa-circle-user text-secondary profile-sm d-block text-center"></i>
                                    @endif
                                </button>
                            </div>

                            <div class="col-auto profile-name">{{$business_promotion->user->name}}</div>
                            <div class="col-md-1 col-sm-1 pb-1 p-1">
                                @if($business_promotion->user->official_certification == 3)
                                    <img src="{{ asset('images/logo/official_personal.png')}}" class="official-personal d-inline ms-0 avatar-xs" alt="official-personal"> 
                                @endif
                            </div>
                        </div>
                        
                        <!--Follow-->
                        @if(Auth::user()->role_id == 1)
                        <div class="col-md-1 col-sm-1 ms-auto">
                            @if($business_promotion->user->isFollowed())
                            {{-- unfollow --}}
                                <form action="{{route('follow.delete', $business_promotion->user->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-following btn-sm mt-3 w-100">Following</button>
                                </form>
        
                            @else
                            {{-- follow --}}
                            <form action="{{route('follow.store', $business_promotion->user->id )}}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-follow btn-sm mt-3 w-100">Follow</button>
                            </form>
                            @endif 
                        </div>
                        @endif
                    </div>
                </div>
            </section>

            <!-- Business Introduction -->
            <section class="business-introduction">
                <h3>Introduction</h3>
                <div class="introduction-box">                   
                    <p>{{ $business_promotion->introduction }}</p>
                </div>
            </section>

            <div class="row pt-0">       
                <div class="col align-center mb-5">
                    <a href="{{ route('profile.header', ['id' => $business_promotion->user_id, 'tab' => 'promotions']) }}">
                        <button class="btn btn-green text-uppercase w-25 position-absolute start-50 translate-middle mt-1">Back to Profile</button>
                    </a>
                </div>
            </div>

            
            
            </div>

            
            
@endsection

    