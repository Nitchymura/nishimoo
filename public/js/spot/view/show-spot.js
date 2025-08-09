document.addEventListener('DOMContentLoaded', function () {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // SpotのLikeモーダルを表示直前に更新
    document.querySelectorAll('[id^="likes-modal-"]').forEach(modal => {
        modal.addEventListener('show.bs.modal', function () {
            const spotId = this.id.replace('likes-modal-', '');
            refreshSpotLikesModal(spotId);
        });
    });

    // SpotのLikeボタン処理
    document.querySelectorAll('.btn-like-toggle').forEach(button => {
        button.addEventListener('click', function () {
            const spotId = this.dataset.spotId;
            const liked = this.dataset.liked === '1';
            const url = liked 
                ? `/spot/${spotId}/unlike/json`
                : `/spot/${spotId}/like/json`;
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
                const countElem = this.closest('.d-flex').querySelector('.like-count');

                if (liked) {
                    icon.classList.remove('fas', 'text-danger');
                    icon.classList.add('far');
                    this.dataset.liked = '0';
                    if (countElem) countElem.textContent = parseInt(countElem.textContent) - 1;
                } else {
                    icon.classList.remove('far');
                    icon.classList.add('fas', 'text-danger');
                    this.dataset.liked = '1';
                    if (countElem) countElem.textContent = parseInt(countElem.textContent) + 1;
                }

                refreshSpotLikesModal(spotId);
            })
            .catch(err => console.error('Like toggle failed:', err));
        });
    });

    bindFollowButtons(); // フォローボタン
});

// モーダル再描画関数
async function refreshSpotLikesModal(spotId) {
    try {
        const res = await fetch(`/spot/${spotId}/likes/modal`);
        if (!res.ok) throw new Error('モーダルの取得に失敗');

        const html = await res.text();
        const oldModal = document.getElementById(`likes-modal-${spotId}`);

        if (oldModal) {
            oldModal.outerHTML = html;
            const newModal = document.getElementById(`likes-modal-${spotId}`);
            if (newModal) {
                newModal.addEventListener('show.bs.modal', function () {
                    refreshSpotLikesModal(spotId);
                });
            }
        }

        bindFollowButtons();
    } catch (error) {
        console.error("🚨 モーダルHTML更新失敗:", error);
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