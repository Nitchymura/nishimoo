@extends('layouts.app')

@section('title', $business->name . ' - Promotions')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/promotion-body.css') }}">
    <style>
        .promotion-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }
        
        .promotion-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .promotion-img {
            height: 200px;
            object-fit: cover;
        }
        
        .promotion-description {
            max-height: 80px;
            overflow: hidden;
        }
    </style>
@endsection

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">{{ $business->name }} - Promotions</h2>
                <a href="{{ route('business.show', $business->id) }}" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>Back to Business Page
                </a>
            </div>
            <hr>
        </div>
    </div>

    <div class="row">
        @forelse($promotions as $promotion)
            <div class="col-md-4 col-sm-6 mb-4">
                @include('businessusers.posts.promotions.body', ['promotion' => $promotion])
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    There are no promotions related to this business.
                </div>
            </div>
        @endforelse
    </div>

    @if($promotions->count() > 0)
        <div class="row">
            <div class="col-12">
                {{ $promotions->links() }}
            </div>
        </div>
    @endif
</div>
@endsection 