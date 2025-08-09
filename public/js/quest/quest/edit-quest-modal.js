//======================= UPDATE QUEST MODAL =======================//
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll('.edit-quest-modal').forEach(modal => {
        modal.addEventListener('shown.bs.modal', () => {
            const questId = modal.dataset.questId;
            const updateBtn = modal.querySelector(`#update-${questId}`);
            if (!updateBtn) return;

            // 多重バインド防止
            if (updateBtn.dataset.bound) return;
            updateBtn.dataset.bound = "true";

            const roleId = window.authRoleId;
            console.log(typeof roleId); // → number または null
            console.log(roleId); // → number または null




            updateBtn.addEventListener("click", (event) => {
                event.preventDefault();

                const titleInput = modal.querySelector(`#title-${questId}`)?.value.trim();
                const introInput = modal.querySelector(`#introduction-${questId}`)?.value.trim();
                const startDateInput = modal.querySelector(`#start-date-${questId}`)?.value;
                const endDateInput = modal.querySelector(`#end-date-${questId}`)?.value;
                const durationInput = modal.querySelector(`#duration-${questId}`)?.value;
                const fileInput = modal.querySelector(`#main-image-${questId}`)?.value;

                console.log(startDateInput);
                console.log(endDateInput);
                
                // エラーメッセージ要素
                const titleError = modal.querySelector(`#error-title-${questId}`);
                const introError = modal.querySelector(`#error-introduction-${questId}`);
                const startDateError = modal.querySelector(`#error-start-date-${questId}`);
                const endDateError = modal.querySelector(`#error-end-date-${questId}`);
                const dateComparisonError = modal.querySelector(`#error-date-comparison-${questId}`);
                const durationError = modal.querySelector(`#error-duration-${questId}`);

                // 初期化：すべて非表示
                // [titleError, introError, startDateError, endDateError, dateComparisonError, durationError].forEach(el => el?.classList.add("d-none"));

                let hasError = false;

                // タイトル
                if (!titleInput) {
                    titleError?.classList.remove("d-none");
                    hasError = true;
                }

                // 紹介文
                if (!introInput) {
                    introError?.classList.remove("d-none");
                    hasError = true;
                }

                if (roleId === 1) {
                    if (!startDateInput) {
                        startDateError?.classList.remove("d-none");
                        hasError = true;
                    }

                    if (!endDateInput) {
                        endDateError?.classList.remove("d-none");
                        hasError = true;
                    }

                    if (startDateInput > endDateInput) {
                        dateComparisonError.classList.remove("d-none");
                        console.warn("⚠️ d-none リムーブ");
                            hasError = true;
                    }
                } else if (roleId === 2) {
                    if (!durationInput) {
                        durationError?.classList.remove("d-none");
                        hasError = true;
                    }
                }

                if (hasError) {
                    console.warn("⚠️ バリデーションエラー：送信中止");
                    return;
                }

                // ヘッダーへの即時反映（画面上の表示のみ更新）
                const headerTitle = document.getElementById("header-title");
                if (headerTitle) headerTitle.textContent = titleInput;

                const headerIntro = document.getElementById("header-intro");
                if (headerIntro) headerIntro.textContent = introInput;

                const headerDates = document.getElementById("header-dates");
                if (headerDates) {
                    if (roleId === 1) {
                        headerDates.textContent = `${startDateInput}〜${endDateInput}`;
                    } else {
                        headerDates.textContent = `${durationInput}日間`;
                    }
                }

                // 画像のプレビュー
                if (fileInput?.files > 0) {
                    const file = fileInput.files[0];
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const headerImg = document.getElementById("header-img");
                        if (headerImg) {
                            headerImg.src = e.target.result;
                            sessionStorage.setItem("headerImage", e.target.result);
                        }
                    };
                    reader.readAsDataURL(file);
                }

                // フォーム送信
                const form = modal.querySelector(`#form-${questId}`);
                form?.submit();
            });
        });
    });
});
