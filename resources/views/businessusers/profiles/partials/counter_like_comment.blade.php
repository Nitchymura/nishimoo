{{-- items --}}
    <!--Likes-->
    <div class="col-auto">
        @if($user->role_id == 1)                          
            {{-- @if($user->id == Auth::user()->id) --}}
                <a href="{{ route('profile.header', ['id' => $user->id, 'section' => 'likes']) }}" class="text-decoration-none text-white"><span class="fw-bold">{{$user->businessLikes->count()+$user->spotLikes->count()+$user->questLikes->count()}}</span> {{$user->businessLikes->count()+$user->spotLikes->count()+$user->questLikes->count()==1 ? 'like' : 'likes'}}</a>
        @endif
    </div>
    <!--Comments-->
    @if($user->role_id == 1)  
        <div class="col-auto">
            <a href="{{ route('profile.header', ['id' => $user->id, 'section' => 'comments']) }}" class="text-decoration-none text-white"><span class="fw-bold">{{$user->businessComments->count()+$user->questComments->count()+$user->spotComments->count()}}</span> {{$user->businessComments->count()+$user->questComments->count()+$user->spotComments->count()==1 ? 'comment' : 'comments'}}</a>
        </div>

    {{-- @else  
        <div class="col-auto">
            <a href="{{ route('profile.header', ['id' => $user->id, 'section' => 'followers']) }}" class="text-decoration-none text-dark"><span class="fw-bold">{{$user->followers->count()}}</span> {{$user->followers->count()==1 ? 'follower' : 'followers'}}</a>
        </div> --}}
    @endif



