@extends('layouts.app')

@section('content')
    @include('home.posts.post-header')  <!-- タブの表示部分をここで埋め込む -->

    <div id="content-container">
        @yield('content') <!-- タブに対応するコンテンツがここに読み込まれる -->
    </div>
@endsection

@section('js')
    <!-- ビルドされたJavaScriptファイルを読み込む -->
    <script src="{{ mix('js/tab-switch.js') }}"></script>
@endsection
