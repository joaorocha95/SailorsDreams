@extends('layouts.app')

@section('content')

@section('title','Help Page')

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    .test {
        text-align: center;
    }
</style>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item active">Help</a></li>
</ol>

<h2 class="test">Help</h2>

<a class="btn btn-outline-primary" href="{{ route('faq') }}"> FAQ </a>

<a class="btn btn-outline-primary"> Tickets </a>

@endsection