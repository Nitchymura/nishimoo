Js/quest/photo-container.js

window.addEventListener("beforeunload", function() {
    localStorage.clear();
});

// **アップロードした画像を保存するリスト**
let uploadedImagesList = []; 

// 📌 **アップロードしたファイル名と削除ボタンを `uploaded-file-names` に表示**
function updateUploadedFileNames() {
    const uploadedFileNames = document.getElementById("uploaded-file-names");
    uploadedFileNames.innerHTML = ""; // 一旦クリアして再表示

    uploadedImagesList.forEach((imageSrc, index) => {
        const fileItem = document.createElement("div");
        fileItem.classList.add("uploaded-file-item", "d-flex", "align-items-center", "justify-content-between");

        // **🖼 サムネイル画像**
        const thumbnail = document.createElement("img");
        thumbnail.classList.add("photo-thumbnail", "me-2");
        thumbnail.src = imageSrc;
        thumbnail.alt = `Uploaded Image ${index + 1}`;
        thumbnail.style.width = "6.25rem"; 
        thumbnail.style.objectFit = "cover";

        // **❌ 削除ボタン**
        const deleteButton = document.createElement("button");
        deleteButton.classList.add("btn", "btn-sm", "btn-red", "ms-2");
        deleteButton.innerHTML = "<i class='fa-solid fa-trash'></i>";
        deleteButton.addEventListener("click", function () {
            removeImage(index);
        });

        fileItem.appendChild(thumbnail);
        fileItem.appendChild(deleteButton);
        uploadedFileNames.appendChild(fileItem);
    });
}

// **画像を削除する関数**
function removeImage(index) {
    uploadedImagesList.splice(index, 1); // 配列から削除
    updateUploadedFileNames(); // **リストを再描画**
}

// **フォーム2のイベントリスナーを適用**
function initializeFormListeners() {
    const fileInput = document.getElementById("spot-images");
    const uploadBtn = document.getElementById("upload-btn");
    const uploadedFileNames = document.getElementById("uploaded-file-names");

    if (!fileInput || !uploadBtn || !uploadedFileNames) {
        console.error("📌 フォームの要素が見つかりません。");
        return;
    }



    // **ファイルが選択されたら「追加」ボタンを有効化**
    fileInput.addEventListener("change", function () {
        uploadBtn.disabled = fileInput.files.length === 0;
    });

    // **「追加」ボタンが押されたときの処理**
    uploadBtn.addEventListener("click", function (event) {
        event.preventDefault(); // デフォルトのボタン動作を防ぐ

        if (fileInput.files.length === 0) {
            alert("ファイルを選択してください");
            return;
        }

        // **選択されたファイルを `uploadedImagesList` に追加**
        Array.from(fileInput.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function (e) {
                uploadedImagesList.push(e.target.result); // Base64データを保存
                updateUploadedFileNames(); // **ファイルリストを更新**
            };
            reader.readAsDataURL(file);
        });

        // **ファイルインプットをクリア**
        fileInput.value = "";
        uploadBtn.disabled = true;
            // **「追加」ボタンを無効化**
            uploadBtn.disabled = true;
    });

    console.log("✅ フォームのリスナーを適用しました！");
}

// **ページロード時にイベントリスナーを適用**
document.addEventListener("DOMContentLoaded", function () {
    initializeFormListeners();
});

