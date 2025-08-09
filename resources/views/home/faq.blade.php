@extends('layouts.app')

@section('title', 'FAQ')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/faq.css')}}">

@section('js')
    <script src="{{ asset('js/home/faq.js')}}"></script>


@section('content')

    <div class="container-fluid wrapper">
        <div class='title text-center'>
            <h1 class="h1 poppins-bold">Frequently Asked Questions</h1>
        </div>

        <div class="line-faq"></div>

        <div class="faq">
            @foreach ($faqs as $faq)
                <div class="faq-item">
                    <button class="question bo"><h1 class="h4">Q{{ $loop->iteration }}: {{ $faq->question }}</h1></button>
                    <div class="answer">A: {{ $faq->answer}}</div>
                </div>
            @endforeach
        </div>

    </div>

@endsection
@endsection
@endsection

