@extends('layouts.app')

@section('content')
<title>FAQ</title>

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

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/help')}}">Help</a></li>
    <li class="breadcrumb-item active">FAQ</li>
</ol>

<h2 class="test">Frequentely Asked Questions</h2>

@endsection