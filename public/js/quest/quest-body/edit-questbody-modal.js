document.addEventListener("DOMContentLoaded", () => {
    const uploadedImagesMap = {}; // モーダルごとの画像リストを保持

    document.querySelectorAll('.edit-questbody-modal').forEach(modal => {
        const questbodyId = modal.id.replace('edit-questbody-', '');

        modal.addEventListener('shown.bs.modal', () => {
            const textarea = modal.querySelector('textarea');
            textarea?.focus();

            const fileInput = modal.querySelector(`#edit-image-${questbodyId}`);
            const uploadBtn = modal.querySelector(`#upload-btn-${questbodyId}`);
            const newImagesWrapper = modal.querySelector(`#image-list-wrapper-${questbodyId}`);
            const hiddenInputContainer = modal.querySelector(`#hidden-inputs-${questbodyId}`);

            const submitBtn = modal.querySelector("button.update-btn");
            const form = modal.querySelector("form");

            // 初期化
            uploadedImagesMap[questbodyId] = [];

            // 既存画像の hidden input を再構築
            hiddenInputContainer.innerHTML = "";
            const existingImages = window.questBodyImages?.[questbodyId] || [];
            const seen = new Set();
            existingImages.forEach(imgPath => {
                if (!seen.has(imgPath)) {
                    seen.add(imgPath);
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = "existing_images[]";
                    input.value = imgPath;
                    hiddenInputContainer.appendChild(input);
                }
            });

            // ファイル選択
            fileInput.addEventListener("change", () => {
                const files = Array.from(fileInput.files);
            
                if (files.length === 0) return;
            
                // 上限チェック（例：10枚）
                const existingCount = hiddenInputContainer.querySelectorAll(`input[name="existing_images[]"]`).length;
                const total = uploadedImagesMap[questbodyId].length + files.length + existingCount;
            
                if (total > 10) {
                    alert("一度にアップロードできるのは最大10枚までです。");
                    fileInput.value = ""; // リセット
                    return;
                }
            
                // 画像追加
                uploadedImagesMap[questbodyId].push(...files);
            
                // サムネイル表示
                requestAnimationFrame(() => {
                    renderNewImages(questbodyId, newImagesWrapper);
                });
            
                fileInput.value = ""; // 同じ画像を再選択できるようにクリア
            });
            

            // 削除済み既存画像の hidden input も削除
            modal.querySelectorAll('.remove-existing-image').forEach(button => {
                button.addEventListener('click', function () {
                    const imagePath = this.dataset.img;
                    this.closest('.col-auto')?.remove();
                    modal.querySelectorAll(`input[name="existing_images[]"][value="${imagePath}"]`).forEach(input => input.remove());
                    console.log("🗑 削除（既存画像）:", imagePath);
                });
            });

            // フォーム送信処理
            if (!submitBtn.dataset.bound) {
                submitBtn.addEventListener("click", async function (event) {
                    event.preventDefault();

                    const introInput = modal.querySelector(`#introduction_${questbodyId}`);
                    const businessTitleInput = modal.querySelector(`#business_title_${questbodyId}`);
                    const introError = modal.querySelector(`#intro-error-${questbodyId}`);
                    const imageError = modal.querySelector(`#image-error-${questbodyId}`);
                    const businessTitleError = modal.querySelector(`#business-title-error-${questbodyId}`);
                    const roleId = parseInt(window.authRoleId);

                    let hasError = false;

                    if (!introInput?.value.trim()) {
                        introError?.classList.remove("d-none");
                        hasError = true;
                    } else {
                        introError?.classList.add("d-none");
                    }

                    if (roleId === 2 && (!businessTitleInput?.value.trim())) {
                        businessTitleError?.classList.remove("d-none");
                        hasError = true;
                    } else {
                        businessTitleError?.classList.add("d-none");
                    }

                    const existingCount = hiddenInputContainer.querySelectorAll(`input[name="existing_images[]"]`).length;
                    const totalImages = uploadedImagesMap[questbodyId].length + existingCount;
                    if (totalImages === 0) {
                        imageError?.classList.remove("d-none");
                        hasError = true;
                    } else {
                        imageError?.classList.add("d-none");
                    }

                    if (hasError) return;

                    // FormData生成
                    const formData = new FormData(form);
                    uploadedImagesMap[questbodyId].forEach(file => {
                        formData.append("images[]", file);
                    });

                    try {
                        const response = await fetch(form.action, {
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: formData
                        });

                        const result = await response.json();

                        if (result.status === 'success') {
                            console.log("✅ 保存成功:", result);
                            window.location.reload(); // 必要に応じて変更
                        } else {
                            console.error("⚠️ サーバー側エラー:", result);
                            alert("保存に失敗しました。");
                        }
                    } catch (error) {
                        console.error("❌ 通信エラー:", error);
                        alert("通信エラーが発生しました。");
                    }
                });
                submitBtn.dataset.bound = "true";
            }

            // モーダル閉じるときにフォーカス解除（UX向上）
            modal.addEventListener('hide.bs.modal', () => {
                const active = document.activeElement;
                if (modal.contains(active)) {
                    active.blur();
                    document.body.setAttribute('tabindex', '-1');
                    document.body.focus();
                    document.body.removeAttribute('tabindex');
                }
            });

            console.log(`✅ モーダル処理バインド済み (ID: ${modal.id})`);
        });
    });

    // サムネイルを表示
    function renderNewImages(questbodyId, wrapper) {
        wrapper.querySelectorAll(".new-uploaded-image").forEach(el => el.remove());

        uploadedImagesMap[questbodyId].forEach((file, index) => {
            const fileItem = document.createElement("div");
            fileItem.classList.add("col-auto", "text-center", "me-2", "position-relative", "new-uploaded-image");

            const reader = new FileReader();
            reader.onload = function (e) {
                requestAnimationFrame(() => {
                    const thumbnail = document.createElement("img");
                    thumbnail.classList.add("img-thumbnail");
                    thumbnail.src = e.target.result;
                    thumbnail.alt = `Uploaded Image ${index + 1}`;
                    thumbnail.style.width = "150px";
                    thumbnail.style.height = "auto";
                    fileItem.appendChild(thumbnail);
                });
            };
            reader.readAsDataURL(file);

            const deleteButton = document.createElement("button");
            deleteButton.classList.add("btn", "btn-sm", "btn-red", "position-absolute", "bottom-0", "end-0", "m-1", "text-white");
            deleteButton.innerHTML = "<i class='fa-solid fa-trash'></i>";
            deleteButton.addEventListener("click", function () {
                uploadedImagesMap[questbodyId].splice(index, 1);
                renderNewImages(questbodyId, wrapper);
            });

            fileItem.appendChild(deleteButton);
            wrapper.appendChild(fileItem);
        });
    }
});

