<div class="bg-blue">
@extends('layouts.app')

@section('title', 'Add Promotion')

@section('content')
<link rel="stylesheet" href="{{asset('css/style.css')}}"> 

        
<main>
    <div class="pb-5 row justify-content-center pt-1">       
        <div class="col-8 mb-3">
            <div class="row">
                <div class="col">
                    <h3 class="mb-3 poppins-regular d-inline me-3">Add Promotion</h3>
                    <p class="d-inline ">(<span class="color-red fw-bold">*</span> Required items)<p>
                </div>
            </div>
            <form action="{{ route('promotions.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                {{-- @method('PATCH') --}}
            {{-- Promotion title --}}
            <div class="row mb-3">
                <div class="col">
                    <label for="title" class="form-label fw-bold">Title<span class="color-red">*</span></label>
                    <input type="text" name="title" id="title" value="{{old('title')}}" class="form-control">
                    @error('title')
                    <p class="mb-0 text-danger small">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            {{-- Retalted business --}}
            <div class="row mb-3">
                <div class="col">
                    <label for="business_id" class="form-label fw-bold">Related Business<span class="color-red">*</span></label>
                        <select name="business_id" id="business_id" class="form-control">
                            <option  value="{{old('business_id')}}" disabled selected>Select one</option>
                            @forelse($all_businesses as $business)
                                <option value="{{$business->id}}">{{$business->name}}</option>
                            @empty
                            @endforelse
                        </select>
                        @error('business_id')
                        <p class="mb-0 text-danger small">{{ $message }}</p>
                        @enderror
                </div>
            </div>

            {{-- Promotion period --}}
            <div class="row mb-3">
                <div class="col-6">
                    <label for="promotion_start" class="form-label">Promotion start date<span class="color-red">*</span></label>
                    <input type="date" name="promotion_start" id="promotion_start" value="" class="form-control">
                </div>
                <div class="col-6">
                    <label for="promotion_end" class="form-label">Promotion end date<span class="color-red">*</span></label>
                    <input type="date" name="promotion_end" id="promotion_end" value="" class="form-control">
                </div>
            </div>
            {{-- Display period --}}
            <div class="row mb-3">
                <div class="col-6">
                    <label for="display_start" class="form-label">Display start date (optional)</label>
                    <input type="date" name="display_start" id="display_start" value="" class="form-control">
                </div>
                <div class="col-6">
                    <label for="display_end" class="form-label">Display end date (optional)</label>
                    <input type="date" name="display_end" id="display_end" value="" class="form-control">
                </div>
            </div>
            {{-- Introduction --}}
            <div class="row mb-3">
                <div class="col">
                    <label for="introduction" class="form-label">Introduction<span class="color-red">*</span></label>
                    <textarea name="introduction" id="introduction" rows="3" class="form-control">{{old('introduction')}}</textarea>
                    @error('introduction')
                    <p class="mb-0 text-danger small">{{ $message }}</p>
                    @enderror
                </div>    
            </div>
            {{-- File --}}
            <div class="row mb-3">
                <div class="col">
                    <label for="image" class="form-label">Photo upload<span class="color-red">*</span></label>
                    <input type="file" name="image" id="image" class="form-control form-control-sm w-100 mb-auto p-2" >
                    @error('image')
                    <p class="mb-0 text-danger small">{{ $message }}</p>
                    @enderror
                    <p class="form-text text-danger">
                        Acceptable formats: jpeg, jpg, png, gif only <br>
                        Max file size is 1048 KB
                    </p>
                </div>
            </div>
            {{-- Buttons --}}
            <div class="row mt-3 justify-content-center">
                <div class="col-4"> 
                    {{-- <a href="{{route('promotion.check')}}">
                        <button class="btn btn-green w-100 ">CHECK</button>
                    </a>                        --}}
                    <button type="submit" class="btn btn-green w-100 mb-2">SAVE</button>
                </div>
                <div class="col-2"></div>
                         
                <div class="col-4">
                    <a href="{{route('profile.header', ['id' => Auth::user()->id, 'tab' => 'promotions'])}}">
                        <div class="btn btn-red w-100 ">CANCEL</div>
                    </a>
                </div>
            </div>               
            </form> 
        </div>
    </div>
</main>
</div>
@endsection