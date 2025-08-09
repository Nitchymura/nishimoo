<!-- モーダル -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="modal fade" id="likes-modal-{{ $quest_a->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-green">
            <div class="modal-header border-0">
                <h4 class="color-green poppins-semibold text-center w-100">Liked by...</h4>
            </div>
            <div class="modal-body px-4">
                @forelse ($quest_a->likes as $like)
                    @php 
                        $user = $like->user;
                        $authUser = Auth::user();
                        $isOwn = $authUser && $authUser->id === $user->id;
                        $isFollowing = $authUser && $authUser->follows->contains('followed_id', $user->id);
                    @endphp

                    <div class="row align-items-center mb-3">
                        {{-- アバター --}}
                        <div class="col-2 d-flex justify-content-center">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" alt="" class="rounded-circle avatar-sm">
                            @else
                                <i class="fa-solid fa-circle-user text-secondary icon-mmd text-center"></i>
                            @endif
                        </div>

                        {{-- ユーザー名 --}}
                        <div class="col-7">
                            <a href="#" class="text-decoration-none text-dark fw-bold">
                                {{ $user->name }}
                            </a>
                        </div>

                        {{-- フォローボタン（自分以外のときだけ） --}}
                        @if(!$isOwn)
                            <div class="col-3 text-end">
                                <form class="follow-toggle-form" data-user-id="{{ $user->id }}">
                                    @csrf
                                    <button type="button" class="btn px-3 py-0 {{ $isFollowing ? 'btn-following' : 'btn-follow' }}">
                                        {{ $isFollowing ? 'Following' : 'Follow' }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-center text-muted">No likes yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

