document.addEventListener("DOMContentLoaded", function () {
    const fileInput = document.getElementById("images");
    const previewContainer = document.getElementById("image-preview");
    const existingImagesWrapper = document.getElementById("existing-images-wrapper");
    const hiddenExistingInputWrapper = document.getElementById("hidden-existing-images");
    let uploadedImagesList = [];
    let existingImages = [];

    if (existingImagesWrapper) {
        // Editモード用: 既存画像管理
        existingImages = Array.from(existingImagesWrapper.querySelectorAll(".image-preview-box"))
            .map(box => box.dataset.image);

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
            if (!hiddenExistingInputWrapper) return;
            hiddenExistingInputWrapper.innerHTML = "";
            existingImages.forEach(image => {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "existing_images[]";
                input.value = image;
                hiddenExistingInputWrapper.appendChild(input);
            });
        }

        updateExistingHiddenInputs();
    }

    if (fileInput && previewContainer) {
        fileInput.addEventListener("change", function () {
            const selectedFiles = Array.from(fileInput.files);
            const availableSlots = 6 - existingImages.length - uploadedImagesList.length;

            if (uploadedImagesList.length + selectedFiles.length > 6 - existingImages.length) {
                alert("画像は最大6枚までアップロードできます");
            }

            uploadedImagesList.push(...selectedFiles.slice(0, availableSlots));
            updatePreview();
            updateFormFiles();
            
        });

        function updatePreview() {
            previewContainer.innerHTML = "";
            uploadedImagesList.forEach((file, index) => {
                const fileItem = document.createElement("div");
                fileItem.classList.add("position-relative", "me-2", "mb-2", "d-inline-block");

                const reader = new FileReader();
                reader.onload = function (e) {
                    const thumbnail = document.createElement("img");
                    thumbnail.classList.add("img-thumbnail");
                    thumbnail.src = e.target.result;
                    thumbnail.style.width = "150px";
                    fileItem.appendChild(thumbnail);
                };
                reader.readAsDataURL(file);

                const deleteButton = document.createElement("button");
                deleteButton.classList.add("btn", "btn-sm", "btn-danger", "position-absolute", "bottom-0", "end-0", "m-1");
                deleteButton.innerHTML = "<i class='fa-solid fa-trash'></i>";
                deleteButton.addEventListener("click", () => {
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
        }
    }
});
