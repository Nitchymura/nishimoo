@extends('layouts.app')

@section('title', 'Add Quest')

@section('content')

<div class="{{ Auth::user()->role_id === 1 ? 'bg-green' : 'bg-blue' }}">
    <div class="container py-5 col-9">
        <h3 class="color-navy poppins-semibold ">Add Travel</h3>
        <section>
            <form action="{{ request('quest_id') ? route('quest.update', request('quest_id')) : route('quest.store') }}" method="POST" enctype="multipart/form-data" id="form1" class="bg-white rounded-2 p-5 my-3">

                <meta name="csrf-token" content="{{ csrf_token() }}">
                @csrf
                @method('POST')

                <input type="hidden" name="quest_id_hidden" id="quest_id_hidden" value="{{ request('quest_id') }}">

                {{-- タイトル --}}
                <div class="row pb-3">
                    <label for="title" class="form-label">Travel Title</label>
                    <input type="text" name="title" id="title" class="input-box" placeholder="Kyoto Trip"
                        value="{{ old('title', $quest->title ?? '') }}">
                    @error('title')
                        <p class="ps-0 mb-0 text-danger small">{{ $message }}</p>
                    @enderror
                </div>

                {{-- ロールに応じた入力 --}}
                @php
                    $role = Auth::user()->role_id;
                @endphp

                {{-- @if ($role == 3) --}}
                    {{-- 日付入力 --}}
                    <div class="row pb-3">
                        <div class="col-5 px-0">
                            <label for="start_date" class="form-label">Start date</label>
                            <input type="date" name="start_date" id="start_date" class="input-box"
                                value="{{ old('start_date', $quest->start_date ?? '') }}">
                            @error('start_date')
                                <p class="mb-0 text-danger small">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col d-flex align-items-end justify-content-center">
                            <i class="fa-solid fa-caret-right icon-md"></i>
                        </div>

                        <div class="col-5 px-0">
                            <label for="end_date" class="form-label">End date</label>
                            <input type="date" name="end_date" id="end_date" class="input-box form-control"
                                value="{{ old('end_date', $quest->end_date ?? '') }}">
                            @error('end_date')
                                <p class="mb-0 text-danger small">{{ $message }}</p>
                            @enderror
                        </div>
                            
                    </div>
                {{-- @elseif ($role == 2)
                    <div class="row pb-3">
                        <label for="duration" class="form-label">Choose Quest duration</label>
                        <select id="duration" name="duration" class="w-25 p-2 border rounded mb-1">
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ old('duration', $quest->duration ?? '') == $i ? 'selected' : '' }}>
                                    {{ $i }} Day{{ $i > 1 ? 's' : '' }}
                                </option>
                            @endfor
                        </select>
                        @error('duration')
                            <p class="mb-0 text-danger small">{{ $message }}</p>
                        @enderror
                    </div>
                @endif --}}

                {{-- 紹介文 --}}
                <div class="row pb-3">
                    <label for="introduction" class="form-label">Introduction</label>
                    <textarea name="introduction" id="introduction" class="text-area mx-0" cols="30" rows="5"
                            placeholder="My trip to Kyoto was...">{{ old('introduction', $quest->introduction ?? '') }}</textarea>
                    @error('introduction')
                        <p class="ps-0 mb-0 text-danger small">{{ $message }}</p>
                    @enderror
                </div>

                {{-- メイン画像 --}}
                <div class="row pb-3">
                    <label for="main_image" class="form-label">Header photo</label>
                    <div class="col-12 p-0">
                        <input type="file" name="main_image" id="main_image" class="custom-file-input form-control">
                        @error('main_image')
                            <p class="mb-0 text-danger small">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- <div class="col-3 ms-auto pe-0">
                        <label for="main_image" class="btn btn-green custom-file-label w-100">
                            <i class="fa-solid fa-plus icon-xs d-inline"></i> Photo
                        </label>
                    </div> --}}
                    <p class="mt-0 ps-0 pb-0 xsmall">
                        Acceptable formats: jpeg, jpg, png, gif only.<br>Max size is 1048 KB
                    </p>
                </div>

                {{-- サブミットボタン --}}
                <div class="row">
                    @if (isset($quest) && $quest->id)
                        <button type="submit" name="submit1" id="update" class="btn btn-navy">Update</button>
                    @else
                        <button type="submit" name="submit1" id="submit1" class="btn btn-navy">Create</button>
                    @endif
                </div>
            </form>

        </section>   
</div>
@endsection

