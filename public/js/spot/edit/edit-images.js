// 最大6枚までアップロードできる画像リスト
let uploadedImagesList = [];

document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.getElementById("images");
    const previewContainer = document.getElementById("image-preview"); // ← 追加画像のプレビュー用 (必要ならBlade側で用意)
    const existingImagesWrapper = document.getElementById("existing-images-wrapper");
    const hiddenExistingInputWrapper = document.getElementById("hidden-existing-images");

    // --- 既存画像の管理 ---
    let existingImages = Array.from(existingImagesWrapper.querySelectorAll(".image-preview-box"))
        .map(box => box.dataset.image);

    updateExistingHiddenInputs();

    existingImagesWrapper.addEventListener("click", function (e) {
        if (e.target.closest(".remove-existing-image")) {
            const targetBox = e.target.closest(".image-preview-box");
            const imagePath = targetBox.dataset.image;
            existingImages = existingImages.filter(img => img !== imagePath);
            targetBox.remove();
            updateExistingHiddenInputs();
        }
    });

    function updateExistingHiddenInputs() {
        hiddenExistingInputWrapper.innerHTML = "";
        existingImages.forEach(image => {
            const input = document.createElement("input");
            input.type = "hidden";
            input.name = "existing_images[]";
            input.value = image;
            hiddenExistingInputWrapper.appendChild(input);
        });
    }

    // --- 新規画像追加処理 ---
    if (fileInput) {
        fileInput.addEventListener("change", function () {
            const selectedFiles = Array.from(fileInput.files);

            if (uploadedImagesList.length + selectedFiles.length > 6 - existingImages.length) {
                alert("画像は最大6枚までアップロードできます");
            }

            const availableSlots = 6 - existingImages.length - uploadedImagesList.length;
            uploadedImagesList.push(...selectedFiles.slice(0, availableSlots));

            updatePreview();
            updateFormFiles();
            // fileInput.value = ""; ← 必要なら初期化
        });
    }

    function updatePreview() {
        if (!previewContainer) return;

        previewContainer.innerHTML = "";

        uploadedImagesList.forEach((file, index) => {
            const fileItem = document.createElement("div");
            fileItem.classList.add("position-relative", "me-2", "mb-2", "d-inline-block");

            const reader = new FileReader();
            reader.onload = function (e) {
                const thumbnail = document.createElement("img");
                thumbnail.classList.add("img-thumbnail");
                thumbnail.src = e.target.result;
                thumbnail.alt = `Image ${index + 1}`;
                thumbnail.style.width = "150px";
                fileItem.appendChild(thumbnail);
            };
            reader.readAsDataURL(file);

            const deleteButton = document.createElement("button");
            deleteButton.classList.add("btn", "btn-sm", "btn-danger", "position-absolute", "bottom-0", "end-0", "m-1");
            deleteButton.innerHTML = "<i class='fa-solid fa-trash'></i>";
            deleteButton.addEventListener("click", function () {
                removeImage(index);
            });

            fileItem.appendChild(deleteButton);
            previewContainer.appendChild(fileItem);
        });
    }

    function removeImage(index) {
        uploadedImagesList.splice(index, 1);
        updatePreview();
        updateFormFiles();
    }

    function updateFormFiles() {
        const dt = new DataTransfer();
        uploadedImagesList.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
        // fileInput.value = "";
    }
    
});
