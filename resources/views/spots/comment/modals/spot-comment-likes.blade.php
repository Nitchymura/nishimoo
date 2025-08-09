<!-- resources/views/spots/comment/modals/likes.blade.php -->
<div class="modal fade" id="comment-likes-modal-{{ $comment->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-green border-3">
            <div class="modal-header border-0">
                <h4 class="color-green poppins-semibold text-center w-100">Liked by...</h4>
            </div>
            <div class="modal-body px-4">
                @forelse ($comment->spotCommentLikes as $like)
                    @php 
                        $user = $like->user;
                        $authUser = Auth::user();
                        $isOwn = $authUser && $authUser->id === $user->id;
                        $isFollowing = $authUser && $authUser->follows->contains('followed_id', $user->id);
                    @endphp

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        {{-- アバター＋ユーザー名 --}}
                        <a href="{{ route('profile.header', $user->id) }}" class="d-flex align-items-center text-decoration-none">
                            @if($user->avatar)
                                <img src="{{ $user->avatar }}" class="avatar-sm rounded-circle me-2" alt="icon">
                            @else
                                <i class="fa-solid fa-circle-user text-secondary icon-md text-center me-2"></i>
                            @endif

                            <span class="fw-bold text-dark">{{ $user->name }}</span>
                        </a>

                        {{-- フォローボタン（ログインかつ自分以外のときだけ） --}}
                        @auth
                            @if (!$isOwn && Auth::user()->role_id != 2)
                                <div class="col-3 text-end">
                                    <form class="follow-toggle-form" data-user-id="{{ $user->id }}">
                                        @csrf
                                        <button type="button" class="btn px-3 py-0 {{ $isFollowing ? 'btn-following' : 'btn-follow' }}">
                                            {{ $isFollowing ? 'Following' : 'Follow' }}
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @endauth
                    </div>

                @empty
                    <p class="text-center text-muted">No likes yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
