document.addEventListener('DOMContentLoaded', function () {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // コメントLikeモーダル表示直前に更新
    document.querySelectorAll('[id^="comment-likes-modal-"]').forEach(modal => {
        modal.addEventListener('show.bs.modal', function () {
            const commentId = this.id.replace('comment-likes-modal-', '');
            refreshSpotCommentLikesModal(commentId);
        });
    });

    // コメントLikeボタン処理
    document.querySelectorAll('.comment-like-button').forEach(button => {
        button.addEventListener('click', function () {
            const commentId = this.dataset.commentId;
            const liked = this.dataset.liked === '1';
            const url = liked
                ? `/spot/comment/${commentId}/unlike`
                : `/spot/comment/${commentId}/like`;
            const method = liked ? 'DELETE' : 'POST';

            fetch(url, {
                method,
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Content-Type': 'application/json',
                }
            })
            .then(res => res.json())
            .then(data => {
                const icon = this.querySelector('.like-icon');
                const countSpan = document.getElementById(`like-count-${commentId}`);
                
                if (liked) {
                    icon.classList.remove('fas', 'text-danger');
                    icon.classList.add('far');
                    this.dataset.liked = '0';
                } else {
                    icon.classList.remove('far');
                    icon.classList.add('fas', 'text-danger');
                    this.dataset.liked = '1';
                }

                if (data.count !== undefined) {
                    countSpan.textContent = data.count;
                }

                refreshSpotCommentLikesModal(commentId);
            })
            .catch(error => {
                console.error('Like toggle failed:', error);
            });
        });
    });

    bindFollowButtons();
});

// モーダル再描画関数（コメント用）
async function refreshSpotCommentLikesModal(commentId) {
    try {
        const res = await fetch(`/spot/comment/${commentId}/likes/modal`);
        if (!res.ok) throw new Error('モーダルの取得に失敗');

        const html = await res.text();
        const oldModal = document.getElementById(`comment-likes-modal-${commentId}`);

        if (oldModal) {
            oldModal.outerHTML = html;
            const newModal = document.getElementById(`comment-likes-modal-${commentId}`);
            if (newModal) {
                newModal.addEventListener('show.bs.modal', function () {
                    refreshSpotCommentLikesModal(commentId);
                });
            }
        }

        bindFollowButtons();
    } catch (error) {
        console.error("🚨 コメントモーダルHTML更新失敗:", error);
    }
}
function bindFollowButtons() {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    document.querySelectorAll('.follow-toggle-form button').forEach(button => {
        button.addEventListener('click', async function (e) {
            e.preventDefault();

            const parent = button.closest('.follow-toggle-form');
            const userId = parent.dataset.userId;

            const isCurrentlyFollowing = button.classList.contains('btn-following');
            const url = isCurrentlyFollowing
                ? `/follow/${userId}/delete`
                : `/follow/${userId}/store`;
            const method = isCurrentlyFollowing ? 'DELETE' : 'POST';

            try {
                const res = await fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Content-Type': 'application/json',
                    }
                });

                const result = await res.json();

                // ✅ message で分岐
                if (result.message === 'Followed') {
                    button.classList.remove('btn-follow');
                    button.classList.add('btn-following');
                    button.textContent = 'Following';
                } else if (result.message === 'Unfollowed') {
                    button.classList.remove('btn-following');
                    button.classList.add('btn-follow');
                    button.textContent = 'Follow';
                }

            } catch (error) {
                console.error("❌ follow toggle failed:", error);
            }
        });
    });
}
