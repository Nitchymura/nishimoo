{{-- Confirm-quest.blade --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/quest/view-quest.css') }}">
@endsection

@section('title', 'Add Quest - Confirmation')

@section('content')
<div class="{{ Auth::user()->role_id === 1 ? 'bg-green' : 'bg-blue' }}">
    <p class="attention color-navy poppins-semibold text-center pt-5 pb-3 fs-4 {{ Auth::user()->role_id == 1 ? 'bg-quest-blue' : 'bg-quest-green' }}">
        Not yet published. This page is for confirmation only.
    </p>
    <div class="container py-5 col-9 px-0">
        <h3 class="color-navy poppins-semibold text-center">Check Your Quest</h3>
        {{-- <pre>{{ var_dump($questBodies) }}</pre> --}}
        {{-- HEADER --}}
        @php
            $hasCertifiedBusiness = $quest_a->user_id === 2 && $quest_a->questBodies->contains(function($body) {
                return $body->business && $body->business->official_certification == 3;
            });
        @endphp
        <section class="position-relative my-5" id="header">
            <img 
                src="{{ $quest_a->main_image }}" 
                alt="header-img" 
                class="img-fluid w-100 rounded-3 {{ $hasCertifiedBusiness ? 'border-quest-red' : '' }}">

                @if($hasCertifiedBusiness)
                    <img 
                        src="{{ asset('images/logo/OfficialBadge.png') }}" 
                        alt="Certified Badge"
                        class="official-badge avatar-xxl d-none d-md-block">
                    <img 
                        src="{{ asset('images/logo/OfficialBadge.png') }}" 
                        alt="Certified Badge"
                        class="official-badge-xl avatar-xl  d-md-none">
                @endif
            <div class="overlay position-absolute bottom-0 start-0 p-3 text-white">
                @php
                    // questBodies の中で business が存在する最初のものを取得
                    $firstBusiness = $quest_a->questBodies->first(function ($body) {
                        return $body->business !== null;
                    });
                @endphp

                <h3>{{ $quest_a->title }}</h3>

                @if($quest_a->user_id !== 1 && $firstBusiness)
                    <h4>
                        <i class="fa-solid fa-location-dot"></i> {{ $firstBusiness->business->name }}
                    </h4>
                @endif

                <h4>
                    @if ($quest_a->start_date && $quest_a->end_date)
                        {{ $quest_a->start_date }} - {{ $quest_a->end_date }}
                    @elseif ($quest_a->duration)
                        {{ $quest_a->duration }} {{ $quest_a->duration == 1 ? 'day' : 'days' }} Quest
                    @endif
                </h4>
            </div>
        </section>

        <div class="px-0">
            @include('quests.user-bar')
        </div>
        {{-- AGENDA + MAP --}}
        <div class="container mt-5">
            <div class="row align-items-stretch p-0">
                {{-- Agenda --}}
                <div class="col-md-6 d-flex px-0" id="agenda-list">
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

                {{-- Map --}}
                
                <div class="col-md-6 px-0">
                    <div class="bg-white rounded-3 container-fluid p-2">
                        {{-- 地図用位置データ --}}
                       
                        <script>
                            window.questMapLocations = @json($locations);
                            console.log(window.questMapLocations); // ← こっちだけで十分！
                        </script>

                        <div id="map" style="height: 500px; width: 100%;"></div>

                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3 my-4 p-3">
            <!-- 紹介文 -->
            <h4 class="fs-5 raleway-semibold text-center">Introduction</h4>
            <p class="my-0" id="header-intro">{{ $quest_a->introduction ?? '' }}</p>
        </div>

        {{-- QUEST BODY CONTENT --}}
        <div id="quest-body-container" class="reveal-section revealed">
            
            @if(isset($questBodies) && $questBodies->isNotEmpty())
                @foreach ($questBodies->groupBy('day_number') as $day => $bodies)
                @php
                    $firstBody = $bodies->first(); // クラス取得用
                @endphp
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
                                    <div class="col-lg-6 image-container text-center">
                                        @foreach ($images as $image)
                                            <img src="{{ $image }}" alt="画像" class="img-fluid mb-2 rounded">
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
                <h4>No Entry. Please add Spots or businesses on your Quest!</h4>
            @endif
        </div>

        {{-- CONFIRM + BACK BUTTONS --}}
        <div class="row justify-content-center mt-5">
            <form id="toggle-visibility-form" method="POST" action="{{ route('quest.restore', ['quest_id' => $quest_a->id]) }}" data-quest-id="{{ $quest_a->id }}" class="w-100">
                @csrf
                <input type="hidden" name="_method" id="form-method">
        
                <div class="row">
                    {{-- Left Column: Back Button (bottom aligned) --}}
                    <div class="col-md-6 d-flex align-items-end justify-content-end pe-4">
                        <a href="{{ route('quest.edit', ['quest_id' => $quest_a->id]) }}" class="btn btn-outline-navy w-75">Back</a>
                    </div>
        
                    {{-- Right Column: Checkbox + Save --}}
                    @php
                        $isFirstConfirm = $quest_a->created_at == $quest_a->updated_at;
                        $isPrivate = !$isFirstConfirm && $quest_a->trashed(); // 初回以外でソフトデリートされてる時のみchecked
                    @endphp

                    <div class="col-md-6 ps-4">
                        <div class="form-check mb-3 text-start">
                            <input class="form-check-input" type="checkbox" id="togglePrivate" name="make_private"
                               {{ $isPrivate ? 'checked' : '' }}>
                            <label class="form-check-label" for="togglePrivate">Save as a private Quest</label>
                        </div>
                        <button id="confirm-submit-btn" type="submit" class="btn btn-navy w-75">
                            {{ $isPrivate ? 'Save' : 'Confirm' }}
                        </button>                        
                    </div>
                </div>
                <script>
                    window.questRoutes = {
                        restoreUrl: "{{ route('quest.restore', ['quest_id' => $quest_a->id]) }}",
                        softDeleteUrl: "{{ route('quest.softDelete', ['quest_id' => $quest_a->id]) }}"
                    };
                    
                </script>
            </form>
        </div>
    </div>
    <!-- Go to Top Button -->
    <div class="top-button-container">
        <button class="top-button">
            <a href="#" class="text-decoration-none color-navy">
                <i class="fa-solid fa-plane-up fs-3"></i>
                <p class="color-navy m-0 p-0 text-center fs-8 poppins-semibold">Go TOP</p>
            </a>
        </button>
    </div>

</div>

@vite(['resources/js/quest/view-quest.js', 'resources/js/quest/confirm-quest.js' ])
<script type="text/javascript" src="{{ Vite::asset('resources/js/quest/map.js') }}"></script>
<!-- Google Maps API 読み込み：絶対ここ！ -->
<script
  src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap&loading=async"
  async defer>
</script>
@endsection


