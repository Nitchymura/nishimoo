<div class="bg-blue">
@extends('admin.admin_main')

@section('title', 'Admin: Posts')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection


@section('sub_content')
<div class="">
    <table class="table border bg-white table-hover align-middle text-secondary mt-5">
        <thead class="table-primary text-secondary text-uppercase small">
            <tr>
                {{-- <th class="align-middle">ID</th> --}}
                <th rowspan="2"></th>
                <th class="align-middle">Title</th>
                <th class="align-middle">User name</th>
                {{-- <th>Email</th> --}}
                <th rowspan="2">
                    <form method="GET" action="" class="d-inline-block ms-2">
                        <label for="sort" class=""></label>
                        <select name="sort" id="sort" onchange="this.form.submit()" class="bg-skyblue-thead mt-3 text-sm">
                            <option value="" disabled selected>POSTED AT</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>FROM LATEST</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>FROM OLDEST</option>
                        </select>
                    </form>
                </th>
                <th rowspan="2" class="align-middle">status</th>
                <th rowspan="2"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $post)
                <tr>
                    {{-- <td>{{$user->id}}</td> --}}
                    <td>
                        @if($post['type'] == 'quests')
                            <a href="{{route('quest.show', $post['id'])}}" >
                                <img src="{{ $post['main_image'] }}" alt="{{ $post['title'] }}" class="img-sm d-block mx-auto">
                        @elseif($post['type'] == 'spots')
                            <a href="{{route('spot.show', $post['id'])}}" >
                                <img src="{{ $post['main_image'] }}" alt="{{ $post['title'] }}" class="img-sm d-block mx-auto">
                        @endif
                        </a>
                    </td>
                        <td>
                        <a href="#" class="text-decoration-none text-dark">{{ $post['title'] }}</a>
                    </td>
                    <td>
                        <a href="#" class="text-decoration-none text-dark">{{ $post['user_name'] }}</a>
                    </td>
                    <td>
                        {{date('M d, Y H:i:s', strtotime($post['created_at']))}}
                    </td>
                    <td>
                        {{-- status --}}
                        @if($post['is_trashed'])
                            <i class="fa-solid fa-circle color-red"></i> Invisible
                        @else
                            <i class="fa-solid fa-circle color-green"></i> Visible
                        @endif
                    </td>
                    <td>
                        @if($post['user_id'] != Auth::user()->id)
                        <div class="dropdown">
                            <button class="btn btn-sm" data-bs-toggle="dropdown"> 
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <div class="dropdown-menu">
                                @if($post['is_trashed'])
                                
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#activate-post{{$post['id']}}">
                                        <i class="fa-solid fa-eye"></i> Activate {{$post['title']}}
                                    </button>
                                @else
                                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deactivate-post{{ $post['id'] }}">
                                        <i class="fa-solid fa-eye-slash"></i> Deactivate {{$post['title']}}
                                    </button>
                                @endif
                            </div>                         
                        </div>
                        @include('admin.posts.tourist_post_status')
                        @endif
                    </td>
                </tr>
                    {{-- <td>
                        {{ $user->email }}
                    </td> --}}
                <tr>
                    
                    
                    
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="6">No users found.</td>
                </tr>

            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-end mb-5">
        {{ $posts->links() }}
    </div>
</div>
</div>
@endsection