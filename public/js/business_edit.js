document.addEventListener("DOMContentLoaded", function () {
    const headerInput = document.getElementById('header');
    const headerPreview = document.getElementById('header-preview');
    const headerIcon = document.getElementById('header-icon');
    const deleteHeaderBtn = document.getElementById('delete-header');

    // ヘッダー画像のアップロード時プレビュー表示
    headerInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            headerPreview.src = e.target.result;
            headerPreview.style.display = 'block';
            if (headerIcon) headerIcon.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    // ヘッダー画像の削除
    deleteHeaderBtn.addEventListener('click', function () {
        // if (!confirm('ヘッダー画像を削除しますか？')) return;

        fetch("http://127.0.0.1:8000/profile/header", {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Accept": "application/json"
            }
        })
        .then(response => {
            if (response.ok) {
                headerPreview.src = '';
                headerPreview.style.display = 'none';
                if (headerIcon) headerIcon.style.display = 'block';
                document.getElementById("header-preview").classList.add("d-none");
                document.getElementById("header-icon").classList.remove("d-none");
            } else {
                alert('ヘッダー画像の削除に失敗しました');
            }
        })
        .catch(() => {
            ;
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const avatarInput = document.getElementById('avatar');
    const preview = document.getElementById('avatar-preview');
    const icon = document.getElementById('default-icon');
    const deleteBtn = document.getElementById('delete-avatar');

    // アップロード時にプレビュー表示
    avatarInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (icon) icon.style.display = 'none';
        };
        reader.readAsDataURL(file);
    });

    // 削除ボタンクリックで画像削除処理
    deleteBtn.addEventListener('click', function () {
        // if (!confirm('画像を削除しますか？')) return;

        fetch("http://127.0.0.1:8000/profile/image", {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                "Accept": "application/json"
            }
        })
        .then(response => {
            if (response.ok) {
                preview.src = '';
                preview.style.display = 'none';
                if (icon) icon.style.display = 'block';
                // alert('画像を削除しました');
                document.getElementById("default-icon").classList.remove("d-none");
                document.getElementById("avatar-preview").classList.add("d-none");
            } else {
                alert('削除に失敗しました');
            }
        })
        .catch(() => {
            ;
        });
    });
});
