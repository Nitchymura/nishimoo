{{-- Edit-quest.blade --}}
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/quest/edit-quest.css') }}">
@endsection

@section('title', 'Add Quest')

@section('content')
{{-- ユーザーのロールIDをJSに渡す --}}
<script>
    window.authRoleId = @json(Auth::check() ? Auth::user()->role_id : null);
</script>
<div class="{{ Auth::user()->role_id === 1 ? 'bg-green' : 'bg-blue' }}">
    <div class="container py-5 col-9">
        <h3 class="color-navy poppins-semibold">Edit Travel</h3>
            <section id="form2" class="reveal-section">
                <form action="{{ route('questbody.store') }}" method="post" enctype="multipart/form-data" id="body_form" class="bg-white rounded-2 px-5 py-3 my-5">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="quest_id" id="quest_id_input" value="{{ $quest->id }}">

            
                    <div class="row p-2">
                        <label for="day_number" class="form-label">Choose the day</label>
                        @php
                            function ordinalSuffix($num) {
                                if (in_array($num % 100, [11, 12, 13])) return $num . 'th';
                                switch ($num % 10) {
                                    case 1: return $num . 'st Day';
                                    case 2: return $num . 'nd Day';
                                    case 3: return $num . 'rd Day';
                                    default: return $num . 'th Day';
                                }
                            }
                        @endphp
                    
                        <select id="day_number" name="day_number" class="w-25 p-2 input-box">
                            @foreach ($dayList as $day)
                                <option value="{{ $day['number'] }}" {{ old('day_number') == $day['number'] ? 'selected' : '' }}>
                                    {{-- @if(Auth::user()->role_id == 2)
                                        {{ ordinalSuffix($day['number']) }}
                                    @else --}}
                                        Day {{ $day['number'] }} ({{ $day['date'] }})
                                    {{-- @endif --}}
                                </option>
                            @endforeach
                        </select>
                    </div>
            
                    @php
                        $hasQuestBodies = isset($questBodies) && $questBodies->isNotEmpty();
                        $isRole2 = Auth::check() && Auth::user()->role_id == 3;
                        $isRole1 = Auth::check() && Auth::user()->role_id == 1;
                    @endphp

                    {{-- ラベル行 --}}
                    <div class="row align-items-end">
                        <div class="col-md-5">
                            <label for="spot_name" class="form-label">
                                {{ $isRole1 ? 'Search Spot on HopQuest' : 'Select Your Business' }}
                            </label>
                        </div>
                        <div class="col-md-2"></div>

                        @if ($isRole1)
                            <div class="col-lg-5 d-none d-lg-block text-start">
                                <p class="m-0 p-0 xsmall">No spot on HopQuest? Tell us your fav spot!</p>
                            </div>
                        @endif
                    </div>

                    {{-- 入力欄：role 2 の場合、縦並び --}}
                    @if ($isRole2)
                        <div class="row">
                            {{-- Business name --}}
                            <div class="col-lg-5 position-relative mt-2">
                                @php
                                    $firstBody = isset($questBodies) && $questBodies->isNotEmpty() ? $questBodies->first() : null;
                                    $isBusinessLocked = $firstBody && $firstBody->business;
                                @endphp
                        
                                <input 
                                    type="text" 
                                    name="spot_name" 
                                    id="spot_name" 
                                    value="{{ old('spot_name', $firstBody?->business->name ?? '') }}" 
                                    class="input-box form-control w-100 mb-0"
                                    @if ($isBusinessLocked) disabled @endif
                                >
                        
                                <input type="hidden" name="spot_business_type" id="spot_business_type" value="{{ old('spot_business_type', $firstBody?->business ? 'business' : 'spot') }}">
                                <input type="hidden" name="spot_business_id" id="spot_business_id" value="{{ old('spot_business_id', $firstBody?->business->id ?? '') }}">
                        
                                @error('spot_name')
                                    <p class="text-danger small">{{ $message }}</p>
                                @else
                                    <p id="spot-error" class="text-danger small d-none">Please choose a business.</p>
                                @enderror
                        
                                <div id="searchResults" class="search-results mt-0"></div>
                            </div>
                        
                            @if ($isBusinessLocked)
                                <div class="col-lg-6 m-0 p-0">
                                    <p class="xsmall text-secondary m-0">
                                        **You cannot include other businesses in this Quest.
                                        If you want to create a model Quest for a different business, please start a new Quest.
                                    </p>
                                </div>
                            @endif
                        </div>
                        

                        <div class="row">
                            {{-- Place name --}}
                            <div class="col-lg-5 position-relative mt-3">
                                <label for="business_title" class="form-label">Place name</label>
                                <input 
                                    type="text" 
                                    name="business_title" 
                                    id="business_title" 
                                    class="input-box form-control w-100 mb-0" 
                                    value="{{ old('business_title') }}"
                                    placeholder="Souvenir Shop"
                                >
                                @error('business_title')
                                    <p class="text-danger small">{{ $message }}</p>
                                @else
                                    <p id="business-title-error" class="text-danger small d-none">Please enter a place name</p>
                                @enderror

                            </div>
                        </div>
                    @endif

                    {{-- 入力欄：role 1 の場合（従来通り） --}}
                    @if ($isRole1)
                        <div class="row">
                            <div class="col-lg-5 position-relative mt-2">
                                <input 
                                    type="text" 
                                    name="spot_name" 
                                    id="spot_name" 
                                    value="{{ old('spot_name') }}" 
                                    placeholder="Tokyo Tower" 
                                    class="input-box form-control w-100 mb-0"
                                >
                                <input type="hidden" name="spot_business_type" id="spot_business_type" value="{{ old('spot_business_type') }}">
                                <input type="hidden" name="spot_business_id" id="spot_business_id" value="{{ old('spot_business_id') }}">

                                @error('spot_name')
                                    <p class="text-danger small">{{ $message }}</p>
                                @else
                                    <p id="spot-error" class="text-danger small d-none">Please choose a spot</p>
                                @enderror

                                <div id="searchResults" class="search-results mt-0"></div>
                            </div>

                            <div class="col-lg-2 d-flex align-items-center justify-content-center">
                                <p class="m-0 fs-4 text-center fw-bold">or</p>
                            </div>

                            <div class="col-lg-5 mt-3 mt-lg-0">
                                {{-- <button type="button" class="btn btn-blue w-100 px-0" data-bs-toggle="modal" data-bs-target="#addSpotModal-{{ $quest->id }}">
                                    <i class="fa-solid fa-plus icon-xs d-inline"></i> ADD SPOT
                                </button> --}}
                                <a href="{{ route('spots.create') }}" target="_blank" class="btn btn-blue w-100 px-0">
                                    <i class="fa-solid fa-plus icon-xs d-inline"></i> ADD SPOT
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="row mx-0 px-0 text-center">
                        <div class="text-start pb-3 px-0">
                            <label for="image" class="form-label p-2">Photos</label>
                            <div class="col-12 px-0">
                                <input type="file" id="image" class="custom-file-input form-control input-box w-100" multiple>
                                <p id="image-error" class="text-danger small d-none">Please upload at leaset one image.</p>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-green custom-file-label w-100 me-0 d-none" id="upload-btn">
                                    <i class="fa-solid fa-plus icon-xs d-inline"></i>Photo
                                </button>
                            </div>
                        </div>
                            <p class="mt-0 xsmall text-start">
                                Acceptable formats: jpeg, jpg, png, gif only.<br>Max size is 1048 KB
                            </p>
                    </div>
            
                    <div id="uploaded-file-names" class="row flex-nowrap overflow-auto mt-2"></div>
            
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="introduction" class="form-label">Description</label>
                        </div>
                        <div class="col-12">
                            <textarea id="introduction" name="introduction" class="text-area input-box w-100" rows="7" placeholder="How was your experience there!">{{ old('introduction') }}</textarea>
                            @error('introduction')
                                <p class="text-danger small">{{ $message }}</p>
                            @else
                                <p id="intro-error" class="text-danger small d-none">please enter a description.</p>
                            @enderror
                        </div>
                    </div>
            
                    <div class="row px-0">
                        <div class="form-check form-switch mx-2">
                            <input type="checkbox" name="agenda" id="agenda" class="form-check-input" {{ old('agenda', '1') ? 'checked' : '' }}>
                            <label for="agenda" class="form-check-label radio-inline raleway-semibold">Add to Agenda</label>
                            <p class="mt-0 xsmall">
                                The Agenda shows a summary of your Quest by listing the place names you’ve selected. You can freely choose whether to display each item in the Agenda, and you can change this later anytime from the Edit page.
                            </p>
                        </div>
                    </div>
        
                    <div class="row pb-3 mx-0 justify-content-end">
                        <button type="button" id="addbodybtn" class="btn btn-navy w-50">
                            <i class="fa-solid fa-plus icon-xs d-inline"></i>Add on your quest
                        </button>
                    </div>
                </form>
            </section>
        

            <section id="header" class="reveal-section">
                <div class="position-relative my-4">
                    @php
                        $hasCertifiedBusiness = $quest->user_id === 2 && $quest->questBodies->contains(function($body) {
                            return $body->business && $body->business->official_certification == 3;
                        });
                    @endphp
                    <!-- メイン画像 -->
                    @if(Str::startsWith($quest->main_image, 'http') || Str::startsWith($quest->main_image, 'data:'))
                        <img src="{{ $quest->main_image }}" alt="header-img" class="img-fluid w-100 rounded-3 {{ $hasCertifiedBusiness ? 'border-quest-red' : '' }}">
                    @else
                        <img src="{{ $quest->main_image }}" alt="header-img" 
                        class="img-fluid w-100 rounded-3 {{ $hasCertifiedBusiness ? 'border-quest-red' : '' }}">
                    @endif

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
                    <!-- 右上のオーバーレイ部分 -->
                    <div class="overlay position-absolute top-0 end-0 p-3 text-white">
                        <!-- 編集・削除ボタン -->
                        <div>
                            <button class="btn btn-sm btn-green" data-bs-toggle="modal" data-bs-target="#edit-quest-{{ $quest->id }}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <button class="btn btn-sm btn-red" data-bs-toggle="modal" data-bs-target="#delete-quest-{{ $quest->id }}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </div>
            
                    <!-- 左下のオーバーレイ部分 -->
                    <div class="overlay position-absolute bottom-0 start-0 p-3 text-white">
                        @php
                            // questBodies の中で business が存在する最初のものを取得
                            $firstBusiness = $quest->questBodies->first(function ($body) {
                                return $body->business !== null;
                            });
                        @endphp
        
                        <h3 id="header-title">{{ $quest->title }}</h3>
        
                        @if($quest->user_id !== 1 && $firstBusiness)
                            <h4>
                                <i class="fa-solid fa-location-dot"></i> {{ $firstBusiness->business->name }}
                            </h4>
                        @endif
        
                        <h4>
                            @if ($quest->start_date && $quest->end_date)
                                {{ $quest->start_date }} - {{ $quest->end_date }}
                            @elseif ($quest->duration)
                                {{ $quest->duration }} {{ $quest->duration == 1 ? 'day' : 'days' }} Quest
                            @endif
                        </h4>
                    </div>
                </div>
            
                <div class="bg-white rounded-3 my-4 p-3">
                    <!-- 紹介文 -->
                    <h4 class="fs-5 raleway-semibold text-center">Introduction</h4>
                    <p class="my-0" id="header-intro">{{ $quest->introduction ?? '' }}</p>
                </div>
            </section>
            @include('quests.modals.quest.delete-modal', ['quest' =>$quest])
            @include('quests.modals.quest.edit-modal', ['quest' =>$quest])
            

            <!-- dayセクションを追加する場所 -->
            <div id="quest-body-container" class="reveal-section">
                @if(isset($questBodies) && $questBodies->isNotEmpty())
                
                        @foreach ($questBodies->groupBy('day_number') as $day => $bodies)
                        @php
                            $firstBody = $bodies->first(); // クラス取得用
                        @endphp
                        <div class="day-group bg-white rounded-3 p-3 my-5 border {{ $firstBody->border_class }}" data-day="{{ $day }}">
                            <p class="day-number p-4 text-center fs-3 poppins-semibold {{ $firstBody->color_class }}">DAY {{ $day }}</p>
                    
                            @foreach ($bodies as $questbody)
                                <div class="spot-entry">
                                    <div class="row pb-3 justify-content-between align-items-center">
                                        <h4 class="spot-name poppins-bold col-md-10 text-start">
                                            @if ($quest->user->role_id == 2 && $questbody->business_title)
                                                {{ $questbody->business_title }} {{-- カスタム入力なのでリンクなし --}}
                                            @else
                                                @if ($questbody->spot)
                                                    <a href="{{ route('spot.show', ['id' => $questbody->spot->id]) }}" class="text-decoration-none text-dark">
                                                        {{ $questbody->spot->title }}
                                                    </a>
                                                @elseif ($questbody->business)
                                                    <a href="{{ route('business.show', ['id' => $questbody->business->id]) }}" class="text-decoration-none text-dark">
                                                        {{$questbody->business->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Undefined</span>
                                                @endif
                                            @endif
                                        </h4>
                                        
                                        <div class="col ms-0 text-end pe-0">
                                            <div class="d-flex justify-content-end gap-2 p-0 m-0">
                                                <!-- 編集ボタン -->
                                                <button class="btn btn-sm btn-green edit-modal" data-bs-toggle="modal" data-bs-target="#edit-questbody-{{ $questbody->id }}">
                                                    <i class="fa-solid fa-pen-to-square text-white"></i>
                                                </button>
                                            
                                                <!-- 削除ボタン -->
                                                <button class="btn btn-sm btn-red delete-questbody-btn" data-bs-toggle="modal" data-bs-target="#delete-questbody-{{ $questbody->id }}">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                            <div class="form-check form-switch d-flex align-items-center justify-content-end mt-2 pe-2">
                                                <input 
                                                    type="checkbox" 
                                                    class="form-check-input agenda-toggle me-2" 
                                                    id="agenda-{{ $questbody->id }}" 
                                                    data-id="{{ $questbody->id }}" 
                                                    @if($questbody->is_agenda) checked @endif
                                                >
                                                <label class="form-check-label" for="agenda-{{ $questbody->id }}">Agenda</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        @php
                                            $images = [];
                                            if (!empty($questbody->image)) {
                                                $decoded = json_decode($questbody->image, true);
                                                if (is_array($decoded)) {
                                                    $images = $decoded;
                                                }
                                            }
                                        @endphp

                                        <div class="col-lg-6 image-container d-block flex-column">
                                            @if (!empty($images))
                                                @foreach ($images as $image)
                                                    <img src="{{ $image }}" alt="画像" class="img-fluid rounded">
                                                @endforeach
                                            @endif
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
                        <h4>No Entry. </h4>
                @endif
            </div>
            {{-- CONFIRM + BACK BUTTONS --}}
            <div class="row justify-content-center">
                <a href="{{ route('quest.confirm', ['quest_id' => $quest->id]) }}" class="btn btn-navy w-75 mb-5 px-5">
                    Check
                </a>
            </div>
    </div>
    </div>
</div>

{{-- すべてのモーダルをループでまとめて描画 --}}
@if(isset($questBodies) && $questBodies->isNotEmpty())
    @foreach ($questBodies as $questbody)
        @include('quests.modals.quest-body.edit-modal', ['questbody' => $questbody, 'dayList' => $dayList])
    
        @include('quests.modals.quest-body.delete-modal', ['questbody' => $questbody])
    @endforeach
@endif
{{-- @include('quests.modals.quest.quest-add-spot') --}}
{{-- 編集モーダルの JS --}}
@vite(['resources/js/quest/edit-quest.js'])
@endsection




