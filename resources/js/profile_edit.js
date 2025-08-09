// Header image preview
document.getElementById('header').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const url = URL.createObjectURL(file);
        document.getElementById('headerPreview').src = url;
        setTimeout(() => URL.revokeObjectURL(url), 100);
    }
});
// Avatar image preview
document.getElementById('avatar').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        console.log('ファイルが選択されました:', file);
        const url = URL.createObjectURL(file);
        const avatarPreview = document.getElementById('avatarPreview');
        if (avatarPreview) {
            avatarPreview.src = url;
            console.log('生成URL:', url);
            setTimeout(() => URL.revokeObjectURL(url), 100);
        } else {
            console.warn('avatarPreviewが見つかりません');
        }
    } else {
        console.warn('ファイルが選択されていません');
    }
});

// Avatar preview and base64保存
document.getElementById('avatar').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
            document.getElementById('avatarBase64').value = e.target.result; // hiddenに入れる
        };
        reader.readAsDataURL(file);
    }
});

// Header preview and base64保存
document.getElementById('header').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('headerPreview').src = e.target.result;
            document.getElementById('headerBase64').value = e.target.result; // hiddenに入れる
        };
        reader.readAsDataURL(file);
    }
});
