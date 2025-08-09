document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("toggle-visibility-form");
    const checkbox = document.getElementById("togglePrivate");
    const submitBtn = document.getElementById("confirm-submit-btn");

    const restoreUrl = window.questRoutes.restoreUrl;
    const softDeleteUrl = window.questRoutes.softDeleteUrl;

    updateButtonLabel();

    checkbox.addEventListener("change", updateButtonLabel);

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        // 既存の _method input を削除（念のため）
        const oldMethod = form.querySelector('input[name="_method"]');
        if (oldMethod) {
            oldMethod.remove();
        }

        // 新しく _method input を追加
        const methodInput = document.createElement('input');
        methodInput.setAttribute('type', 'hidden');
        methodInput.setAttribute('name', '_method');

        // ❗ここが変更点！
        methodInput.value = checkbox.checked ? 'DELETE' : 'POST';

        form.appendChild(methodInput);

        // action切り替え
        form.action = checkbox.checked ? softDeleteUrl : restoreUrl;

        // 送信！
        form.submit();
    });

    function updateButtonLabel() {
        submitBtn.textContent = checkbox.checked ? "Save" : "Confirmed";
    }
});
