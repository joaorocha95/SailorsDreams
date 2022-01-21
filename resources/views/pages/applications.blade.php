@extends('layouts.app')

@section('title', 'Applications')

@section('content')
<style>
    .biggerApplication {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
    }

    .applications {
        border-radius: 10px;
        background-color: #4ACBC9;
        width: 100px;
        height: 100px;
        color: #343434;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 5px;
        text-align: center;
    }

    .category:hover {
        background-color: #8AFCFA;
        width: 105px;
        height: 105px;
    }
</style>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('user.id', ['id' => ($id = (auth()->user()->id ) ) ] ) }}">User Profile</a></li>
    <li class="breadcrumb-item active">Applications</li>
</ol>

<section id="applications">
    <div class="biggerApplication">
        @foreach($applications as $application)
        <a href="{{ route('showApplication.id', ['id' => $application->id]) }}">
            <div class="btn btn-outline-dark" style="margin-left: 5px;">{{ $application -> id}}</div>
        </a>
        @endforeach

    </div>

</section>

@endsection