<div class="bg-blue">
@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/post-body.css')}}">
@endsection

@section('content')

<main>
    <div class="row justify-content-center bg-blue">
        <div class="col-8">
    {{-- Management business --}}
            <div class="container">
                <div class="row justify-content-center">                   
                    <div class="col-3">
                        <h4>User list</h4>
                        <div class="list-group mb-3">                               
                            <a href="{{ route('admin.users.business') }}" class="list-group-item {{ request()->is('admin/users/business*') ? 'active' : '' }}">
                                <i class="fa-solid fa-users"></i> Business Users
                            </a>
                            <a href="{{ route('admin.users.tourists') }}" class="list-group-item {{ request()->is('admin/users/tourists*') ? 'active' : '' }}">
                                <i class="fa-solid fa-users"></i> Tourists
                            </a>
                            <a href="{{ route('admin.users.applied') }}" class="list-group-item d-flex justify-content-between align-items-center {{ request()->is('admin/users/applied*') ? 'active' : '' }}">
                                <i class="fa-solid fa-certificate"></i> Badge Applied Users
                                @php
                                    $pending_count = \App\Models\User::where('official_certification', 2)->count();
                                @endphp
                            
                                <span class="badge {{ $pending_count == 0 ? 'bg-info' : 'bg-danger' }} ms-auto">
                                {{ $pending_count }}
                                </span>
                            </a>
                        </div>

                        <h4>Post list</h4>
                        <div class="list-group mb-3">
                            
                            <a href="{{ route('admin.posts') }}" class="list-group-item {{ request()->is('admin/posts/business*') ? 'active' : '' }}">
                                <i class="fa-solid fa-images"></i> Business Posts
                            </a>
                            <a href="{{ route('admin.posts.tourists') }}" class="list-group-item {{ request()->is('admin/posts/tourists*') ? 'active' : '' }}">
                                <i class="fa-solid fa-images"></i> Other Posts
                            </a>
                            <a href="{{ route('admin.posts.applied') }}" class="list-group-item d-flex justify-content-between align-items-center {{ request()->is('admin/posts/applied*') ? 'active' : '' }}">
                                <i class="fa-solid fa-certificate"></i> Badge Applied Posts
                                @php
                                    $pending_count = \App\Models\Business::where('official_certification', 2)->count();
                                @endphp
                            
                                <span class="badge {{ $pending_count == 0 ? 'bg-info' : 'bg-danger' }} ms-auto">
                                {{ $pending_count }}
                                </span>
                            </a>
                        </div>

                        <h4>Comment list</h4>
                        <div class="list-group">
                            
                            <a href="{{ route('admin.comments') }}" class="list-group-item {{ request()->is('admin/comments*') ? 'active' : '' }}">
                                <i class="fa-solid fa-comments"></i> Comments
                            </a>
                        </div>
                         
                    </div>
                    <div class="col-9">
                        @yield('sub_content')
                    </div>
                </div>
            </div>
        </div>
    </div>   
</main>
</div>
@endsection
