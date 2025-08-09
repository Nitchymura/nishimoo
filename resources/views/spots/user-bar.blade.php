<!-- User Profile Header -->
<section class="profile-header">
    <div class="profile-container">
        <div class="profile-left d-flex align-items-center justify-content-between">
            
            <!-- 左側（プロフィール画像と名前） -->
            <div class="profile-main d-flex align-items-center">
                <div class="col-md-auto col-sm-2 my-auto p-0 profile-pic">                   
                    <button class="btn">
                        @if($spot->user->avatar)
                            <img src="{{ $spot->user->avatar }}" alt="" class="rounded-circle avatar-sm">
                        @else
                            <i class="fa-solid fa-circle-user text-secondary profile-sm d-block text-center"></i>
                        @endif
                    </button>
                </div>

                <div class="col-auto profile-name ms-2">{{$spot->user->name}}</div>
                
                @if($spot->user->official_certification == 3)
                <div class="col-md-1 col-sm-1 pb-1 p-1">
                    <img src="{{ asset('images/logo/official_personal.png')}}" class="official-personal d-inline ms-0 avatar-xs" alt="official-personal"> 
                </div>
                @endif
            </div>
                <!-- Followボタン -->
            @if($spot->user->id !== Auth::user()->id && Auth::user()->role_id == 1)
                <div class="col-1 mt-3 me-auto">
                    @if($spot->user->isFollowed())
                        {{-- unfollow --}}
                        <form action="{{route('follow.delete', $spot->user->id)}}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-following btn-sm w-100">Following</button>
                        </form>
                    @else
                        {{-- follow --}}
                        <form action="{{route('follow.store', $spot->user->id )}}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-follow btn-sm w-100">Follow</button>
                        </form>
                    @endif 
                </div>
            @endif
            <!-- 右側（SNSアイコン） -->
            <div class="sns-icons d-flex align-items-center">
                @if(!empty($spot->user->instagram))
                    <a href="https://instagram.com/{{ $spot->user->instagram }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                        <i class="fa-brands fa-instagram text-dark icon-md mx-1"></i><span class="text-dark ms-1">{{$spot->user->instagram}}</span>
                    </a>
                @endif
                @if(!empty($spot->user->facebook))
                    <a href="https://facebook.com/{{ $spot->user->facebook }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                        <i class="fa-brands fa-facebook text-dark icon-md mx-1"></i><span class="text-dark ms-1">{{$spot->user->facebook}}</span>
                    </a>
                @endif
                @if(!empty($spot->user->x))
                    <a href="https://x.com/{{ $spot->user->x }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                        <i class="fa-brands fa-x-twitter text-dark icon-md mx-1"></i><span class="text-dark ms-1">{{$spot->user->x}}</span>
                    </a>
                @endif
                @if(!empty($spot->user->tiktok))
                    <a href="https://www.tiktok.com/@{{ $spot->user->tiktok }}" class="text-decoration-none d-flex align-items-center px-2" target="_blank" rel="noopener">
                        <i class="fa-brands fa-tiktok text-dark icon-md mx-1"></i><span class="text-dark ms-1">{{$spot->user->tiktok}}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</section>
