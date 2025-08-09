<div class="bg-blue">
@extends('admin.admin_main')

@section('title', 'Admin: Applied Users')

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
                            <option value="" disabled selected>APPLIED AT</option>
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>FROM LATEST</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>FROM OLDEST</option>
                        </select>
                    </form>
                </th>
                <th colspan="2" class="align-middle text-center"></th>
                
        </thead>
        <tbody>
            @forelse($applied_users as $user)
                <tr>
                    {{-- <td>{{$user->id}}</td> --}}
                    <td>
                        @if($user->avatar)
                            <img src="{{ $user->avatar }}" alt="" class="rounded-circle avatar-sm d-block mx-auto">
                        @else
                            <i class="fa-solid fa-circle-user text-secondary profile-sm d-block text-center"></i>
                        @endif
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
                        {{date('M d, Y H:i:s', strtotime($user->updated_at))}}
                    </td>
                    @if($user->official_certification == 2)
                        <td>
                            <form method="POST" action="{{ route('admin.users.certify', $user->id) }}">
                                @csrf
                                <input type="hidden" name="action" value="approve">
                                <button type="submit" class="btn btn-sm btn-green w-100">Approve</button>
                            </form>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.certify', $user->id) }}">
                                @csrf
                                <input type="hidden" name="action" value="reject">
                                <button type="submit" class="btn btn-sm btn-red w-100">Reject</button>
                            </form>
                        </td>
                    @elseif($user->official_certification == 3)
                        <td>
                            <button class="btn btn-sm btn-outline-green w-100 mb-3">Approved</button>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.certify', $user->id) }}">
                                @csrf
                                <input type="hidden" name="action" value="revoke">
                                <button type="submit" class="btn btn-sm btn-navy w-100 ">Revoke</button>
                            </form>
                        </td>
                    @endif
                    
                </tr>
            @empty
                <tr>
                    <td class="text-center" colspan="6">No pending applications found</td>
                </tr>

            @endforelse
        </tbody>
    </table>
        <div class="d-flex justify-content-end mb-5">
            {{ $applied_users->links() }}
        </div>
</div>
</div>
@endsection