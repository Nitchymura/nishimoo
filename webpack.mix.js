const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')  
    .js('resources/js/tab-switch.js', 'public/js')  // 追加したJavaScriptファイル
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ]);
