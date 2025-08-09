@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/quest/view-quest.css') }}">
@endsection

@section('title', 'Add Quest - Confirmation')

@section('content')
<div class="bg-green">
    <div class="container py-5 col-9 px-0">
        @php
            $hasCertifiedBusiness = $quest_a->user->role_id === 2 && $quest_a->questBodies->contains(function($body) {
                return $body->business && $body->business->official_certification == 3;
            });
        @endphp

        <!-- Main Image Section -->
        <section class="main-image-section">
            <div class="main-image-wrapper mt-3">
                <img src="{{  $quest_a->main_image }}" alt="{{ $quest_a->title }}" class="card-img-top body-image" alt="image">
                {{-- <img class="main-image" alt="Main picture" src="{{ $business->main_image }}" /> --}}


                <div class="main-title ms-4 text-center" style="font-size: 2.5rem; text-shadow: 2px 2px 4px rgba(0,0,0,0.7)">
                    {{ $quest_a->title }}
                </div>
                {{-- <div class="col-auto h3 main-title ">
                    {{ $quest_a->title }}
                </div> --}}
                <div class="main-subtitle ">
                    @php
                        $firstBusiness = $quest_a->questBodies->first(function ($body) {
                            return $body->business !== null;
                        });
                    @endphp
                    @if($quest_a->user_id !== 1 && $firstBusiness)
                        <h4><i class="fa-solid fa-location-dot"></i> {{ $firstBusiness->business->name }}</h4>
                    @endif
                </div>
                {{-- <div class="icon-container position-absolute d-flex align-items-center mt-3 ms-5">
                    <!-- アイコン（ハート） -->
                    <div class="me-2 mt-2">
                        @if($quest_a->isLiked())                            
                            <form action="{{ route('quests.like.delete', $quest_a->id) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn p-0">
                                    <i class="fa-solid fa-heart color-red"></i>
                                </button>
                            </form>
                        @else
                            <form action="{{ route('quests.like.store', $quest_a->id) }}" method="post">
                                @csrf
                                <button type="submit" class="btn p-0">
                                    <i class="fa-regular fa-heart"></i>
                                </button>
                            </form>
                        @endif
                    </div>
                
                    <!-- カウント -->
                    <div class="icons-in-image">
                            <span>{{ $quest_a->likes->count() }}</span>
                    </div>

                    <div class="icons-in-image ms-5 mt-2 p-0">
                        <div>
                            <i class="fa-regular fa-comment h1"></i>
                        </div>
                    </div>
                    <div class="icons-in-image px-2">
                        <span>{{ $quest_a->comments->count()}}</span>
                    </div>
                    <div class="icons-in-image ms-5 p-0">
                        <div>
                            <i class="fa-solid fa-chart-simple"></i>
                        </div>
                    </div>
                    <div class="icons-in-image px-2">
                            <span>&nbsp;{{ $quest_a->views->sum('views') ?? 0}}</span>
                    </div>
                </div> --}}
                

                <div class="event-dates">
                    <h4 class="text-center">
                        @if ($quest_a->start_date && $quest_a->end_date && $quest_a->start_date != $quest_a->end_date)
                            {{ $quest_a->start_date }} ~ {{ $quest_a->end_date }}
                        @else
                            {{ $quest_a->start_date }}
                        @endif
                    </h4>
                </div>
                {{-- <div class="post-dates">
                    @if($quest_a->updated_at)
                        <h5 >Posted: {{ $quest_a->updated_at->format('M d Y')}}</h5>
                    @else
                        <h5 >Posted: {{ $quest_a->created_at->format('M d Y')}}</h5>
                    @endif
                </div> --}}

            </div>
        </section>

        {{-- <div class="px-0">
            @include('quests.user-bar')
        </div> --}}

        <div class="container mt-5">
            <div class="row align-items-stretch p-0">
                <div class="col d-flex px-0" id="agenda-list">
                    <div class="bg-white rounded-3 w-100 p-3 me-0 me-md-2 mb-3 mb-md-0">
                        <h4 class="raleway-semibold fs-5 mb-3 text-center">Quest - Agenda</h4>
                        <div class="agenda-wrapper">
                            <ul class="list-unstyled">
                                @foreach($agenda_bodys->groupBy('day_number') as $day => $bodys)
                                    <li class="day-tag mb-2">
                                        <p class="text-decoration-underline p-0 m-0">Day - {{ $day }}</p>
                                        <ul>
                                            @foreach($bodys as $body)
                                                <li>
                                                    @if ($body->spot)
                                                        {{ $body->spot->title }}
                                                    @elseif ($body->business)
                                                        @if ($quest_a->user->role_id === 2)
                                                            {{ $body->business_title }}
                                                        @else
                                                            {{ $body->business->name }}
                                                        @endif
                                                    @else
                                                        <span class="text-muted">Undefined</span>
                                                    @endif

                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                {{-- <div class="col-md-6 px-0">
                    <div class="bg-white rounded-3 container-fluid p-2">
                        <script>
                            window.questMapLocations = @json($locations);
                        </script>
                        <div id="map" style="height: 500px; width: 100%;"></div>
                    </div>
                </div> --}}
            </div>
        </div>

        <div class="bg-white rounded-3 my-4 p-3">
            <h4 class="fs-5 raleway-semibold text-center">Introduction</h4>
            <p class="my-0" id="header-intro">{{ $quest_a->introduction ?? '' }}</p>
        </div>

        <div id="quest-body-container" class="reveal-section revealed">
            @if(isset($questBodies) && $questBodies->isNotEmpty())
                @foreach ($questBodies->groupBy('day_number') as $day => $bodies)
                    @php $firstBody = $bodies->first(); @endphp
                    <div class="day-group bg-white rounded-3 p-3 my-3 border {{ $firstBody->border_class }}" data-day="{{ $day }}">
                        <p class="day-number p-4 text-center fs-3 poppins-semibold {{ $firstBody->color_class }}">DAY {{ $day }}</p>
                        @foreach ($bodies as $questbody)
                            <div class="spot-entry">
                                <div class="row pb-3 justify-content-between align-items-center">
                                    <h4 class="spot-name poppins-bold col-md-10 text-start">
                                        @if ($quest_a->user->role_id == 2 && $questbody->business_title)
                                            {{ $questbody->business_title }} {{-- カスタム入力なのでリンクなし --}}
                                        @else
                                            @if ($questbody->spot)
                                                <a href="{{ route('spot.show', ['id' => $questbody->spot->id]) }}" class="text-decoration-none text-dark">
                                                    {{ $questbody->spot->title }}
                                                </a>
                                            @elseif ($questbody->business)
                                                <a href="" class="text-decoration-none text-dark">
                                                    {{-- route('business.show', ['id' => $questbody->business->id])  --}}
                                                    {{ $questbody->business->name }}
                                                </a>
                                            @else
                                                <span class="text-muted">Undefined</span>
                                            @endif
                                        @endif
                                    </h4>
                                </div>

                                <div class="row">
                                    @php
                                        $images = is_array(json_decode($questbody->image, true)) ? json_decode($questbody->image, true) : [];
                                    @endphp
                                    <div class="col-lg-6 image-container">
                                        @foreach ($images as $image)
                                            {{-- <img src="{{ asset($image) }}" alt="画像" class="img-fluid mb-2 rounded"> --}}
                                            <img src="{{  $image }}" alt="画像" class="img-fluid mb-2 rounded" alt="image">
                                        @endforeach
                                    </div>
                                    <div class="col-lg-6 mt-4 mt-lg-0 spot-description-container">
                                        <p class="spot-description w-100">{!! nl2br(e($questbody->introduction)) !!}</p>
                                    </div>
                                </div>

                                @if(!$loop->last)
                                    <hr class="my-3 mt-5">
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                {{-- <h4>No Entry.</h4> --}}
            @endif
        </div>
        <div class="text-center mt-5 pt-3" id="comment-section">
            @include('quests.comment.body')
        </div>
    </div>

    {{-- <div class="top-button-container">
        <button class="top-button"  id="goTopButton">
            <i class="fa-solid fa-plane-up fs-3 color-navy"></i>
            <p class="color-navy m-0 p-0 text-center fs-8 poppins-semibold">Go TOP</p>
        </button>
    </div> --}}
</div>

@vite(['resources/js/quest/view-quest.js'])
<script type="text/javascript" src="{{ Vite::asset('resources/js/quest/map.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap&loading=async" async defer></script>
@endsection

