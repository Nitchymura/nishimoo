document.addEventListener("DOMContentLoaded", function() {
    var select = document.getElementById("category_id"); 
    var label = document.getElementById("name-label");
    
    // 要素が見つからない場合は処理を終了
    if (!select || !label) return;
    
    // 初期表示時にも選択されていれば変更
    updateLabel();
    
    select.addEventListener("change", function() {
        updateLabel();
    });
    function updateLabel() {
        if (select.value == "1") {
            label.innerHTML = "Location Name<span class='text-danger'>*</span>";
        } else if (select.value == "2") {
            label.innerHTML = "Event Name<span class='text-danger'>*</span>";
        }
    }
}); 