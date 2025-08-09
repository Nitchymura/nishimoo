$(document).ready(function() {
    // 初期状態でコンテンツをロード
    loadContent(window.location.pathname);

    // タブクリック時にコンテンツを切り替え
    $('a[data-category]').on('click', function(event) {
        event.preventDefault(); // デフォルトのリンクの動作をキャンセル

        // クリックしたリンクのURLを取得
        var url = $(this).attr('href');

        // リンクに対してactiveクラスを付ける
        $('a').removeClass('active');
        $(this).addClass('active');

        // コンテンツを非同期で読み込む
        loadContent(url);
    });

    // コンテンツを非同期で読み込む関数
    function loadContent(url) {
        $.get(url, function(data) {
            // 新しいコンテンツを表示
            $('#content-container').html(data);
        }).fail(function() {
            alert("コンテンツの読み込みに失敗しました。");
        });
    }
});
