<div class="modal fade" id="like-post-{{ $post->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-success">
                <div class="h5 modal-title text-success">
                    <i class="fa-solid fa-hurt"></i> Likes
                </div>
            </div>

            <div class="modal-body">
                <div class="mt-3">
                    <ul>
                        @if ($likeUsers->isEmpty())
                            <p>いいねしたユーザーはいません。</p>
                        @else
                            @foreach ($likeUsers as $user)
                                <div class="user-info">
                                    <img src="{{ $user['avatar'] }}" alt="{{ $user['name'] }}" class="avatar">
                                    <span>{{ $user['name'] }}</span>
                                </div>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>