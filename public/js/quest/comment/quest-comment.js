// Js/quest/comment/quest-comment.js
document.addEventListener("DOMContentLoaded", () => {
    document.body.addEventListener("click", async function (e) {
        const likeBtn = e.target.closest(".comment-like-btn");

        if (!likeBtn) return;
        e.preventDefault();

        const form = likeBtn.closest("form.like-comment-form");
        const commentId = form.dataset.commentId;
        const token = form.querySelector('input[name="_token"]').value;
        const icon = likeBtn.querySelector("i");
        const countSpan = document.querySelector(`.comment-like-count[data-comment-id="${commentId}"]`);
        const modalBody = document.querySelector(`#comment-likes-modal-${commentId} .modal-body`);

        try {
            const res = await fetch(`/questcomment/${commentId}/toggle-like`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": token,
                    "Accept": "application/json"
                }
            });

            if (!res.ok) {
                const err = await res.text();
                console.error("❌ エラー発生:", err);
                return;
            }

            const result = await res.json();

            // ❤️ アイコン更新
            icon.classList.remove("fa-regular", "fa-solid", "text-danger");
            if (result.liked) {
                icon.classList.add("fa-solid", "text-danger");
            } else {
                icon.classList.add("fa-regular");
            }

            // 🧮 いいね数更新
            if (countSpan) {
                countSpan.textContent = result.like_count;
            }

            // 👥 モーダル内容を取得して動的に差し替え
            const likeListRes = await fetch(`/questcomment/${commentId}/likes`);
            if (!likeListRes.ok) {
                console.error("❌ モーダル用データ取得エラー");
                return;
            }

            const likeUsers = await likeListRes.json();
            modalBody.innerHTML = ""; // 一旦中身リセット

            if (likeUsers.length === 0) {
                modalBody.innerHTML = `<p class="text-center text-muted">No likes yet.</p>`;
            } else {
                likeUsers.forEach(user => {
                    const isOwn = user.is_own;
                    const followBtn = isOwn
                        ? ""
                        : `
                            <div class="col-3 text-end">
                                <form class="follow-toggle-form" data-user-id="${user.id}">
                                    <button type="button" class="btn px-3 py-0 ${user.is_followed ? 'btn-following' : 'btn-follow'}">
                                        ${user.is_followed ? 'Following' : 'Follow'}
                                    </button>
                                </form>
                            </div>
                        `;

                    modalBody.innerHTML += `
                        <div class="row align-items-center mb-3">
                            <div class="col-2 d-flex justify-content-center">
                                ${user.avatar ? `<img src="/storage/${user.avatar}" alt="" class="rounded-circle avatar-sm">` 
                                               : `<i class="fa-solid fa-circle-user text-secondary icon-mmd text-center"></i>`}
                            </div>
                            <div class="col-7 text-start">
                                <a href="#" class="text-decoration-none text-dark fw-bold">
                                    ${user.name}
                                </a>
                            </div>
                            ${followBtn}
                        </div>
                    `;
                });
            }

            console.log("💬 コメント liked:", result.liked, "count:", result.like_count);

        } catch (error) {
            console.error("🚨 JSエラー:", error);
        }
    });
});

