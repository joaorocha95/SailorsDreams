@extends('layouts.app')

@section('content')
<title>Help</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    .test {
        font-family: 'Nunito', sans-serif;
        background-color: #343434;
        color: #B9B9B9;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }
</style>

<h2 class="test">Help</h2>

<a class="button" href="{{ route('faq') }}"> faq </a>

<a class="button"> Tickets </a>

@endsection