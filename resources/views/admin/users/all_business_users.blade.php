<div class="bg-blue">
@extends('admin.admin_main')

@section('title', 'Admin: Business Users')

@section('css')
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('sub_content')
<div class="">
    <table class="table border bg-white table-hover align-middle text-secondary mt-5">
        <thead class="table-primary text-secondary text-uppercase small">
            <tr>
                {{-- <th class="align-middle">ID</th> --}}
                <th></th>
                <th class="align-middle">User Name</th>
                {{-- <th>Email</th> --}}
                <th>
                    <form method="GET" action="" class="d-inline-block ms-2">
                        <label for="sort" class=""></label>
                        <select name="sort" id="sort" onchange="this.form.submit()" class="bg-skyblue-thead mt-3 text-sm">
                            <option value="" disabled selected>CREATD AT</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>FROM LATEST</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>FROM OLDEST</option>
                        </select>
                    </form>
                </th>
                <th class="align-middle">status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    {{-- <td>{{$user->id}}</td> --}}
                    <td>
                        <a href="{{route('profile.header', $user->id)}}" class="text-decoration-none text-dark fw-bold">
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="" class="rounded-circle avatar-sm d-block mx-auto">
                        @else
                            <i class="fa-solid fa-circle-user text-secondary profile-sm d-block text-center"></i>
                        @endif
                        </a>
                    </td>
                    <td>
                        @if($user->official_certification ==3)
                            <a href="{{route('profile.header', $user->id)}}" class="text-decoration-none text-dark">{{ $user->name }}</a>&nbsp;<img src="{{ asset('images/logo/official_personal.png') }}"
                        class="official-personal d-inline ms-0 avatar-xxs" alt="official-personal">
                        @else
                            <a href="{{route('profile.header', $user->id)}}" class="text-decoration-none text-dark">{{ $user->name }}</a>
                        @endif
                    </td>
                    {{-- <td>
                        {{ $user->email }}
                    </td> --}}
                    <td>
                        {{date('M d, Y H:i:s', strtotime($user->created_at))}}
                    </td>
                    
                    <td>
                        {{-- status --}}
                        @if($user->trashed())
                            <i class="fa-solid fa-circle color-red"></i> Inactive
                        @else
                            <i class="fa-solid fa-circle color-green"></i> Active
                        @endif
                    </td>
                    <td>
                        @if($user->id != Auth::user()->id)
                        <div class="dropdown">
                            <button class="btn btn-sm" data-bs-toggle="dropdown"> 
                                <i class="fa-solid fa-ellipsis"></i>
                            </button>
                            <div class="dropdown-menu">
                                @if($user->trashed())
                                    {{-- activate --}}
                                    <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#activate-user{{$user->id}}">
                                        <i class="fa-solid fa-user-check"></i> Activate {{$user->name}}
                                    </button>
                                @else
                                    <button class="dropdown-item text-danger" data-bs-toggle="modal" data-bs-target="#deactivate-user{{ $user->id }}">
                                        <i class="fa-solid fa-user-slash"></i> Deactivate {{$user->name}}
                                    </button>
                                @endif
                            </div>                         
                        </div>
                        @include('admin.users.user_status')
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="6">No users found.</td>
                </tr>

            @endforelse
        </tbody>
    </table>
    <div class="d-flex justify-content-end mb-5">
        {{ $users->links() }}
    </div>
</div>
</div>
@endsection