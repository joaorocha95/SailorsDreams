@extends('layouts.app')

@section('content')
<title>About</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    .temp1 {
        /*
        background-color: #343434;
        */
        font-family: 'Nunito', sans-serif;
        color: #B9B9B9;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    #messsage_box {
        width: 400px;
        height: 500px;
        max-height: 400px;
        border-style: solid;
        border-color: black;
        overflow: scroll;
    }

    .message {
        height: 10px;
        color: black;
        margin-left: 5px;
    }

    .message:hover {
        background-color: black;
        color: white;
    }

    .selfMessage {
        background-color: lightblue;
        color: black;
        text-align: right;
        margin-right: 10px;
    }

    .selfMessage:hover {
        background-color: cyan;
    }
</style>

<h2 class="temp1">Application ID: {{ $application->id }}</h2>
<div class="temp2">
    <div Product: class="temp2"> User ID: {{ $application->userid }}
    </div>
    <div class="temp2"> Application State: {{ $application->application_state }}
    </div>
    <div class="temp2"> Title: {{ $application->title }}
    </div>
    <div class="temp2"> Description: {{ $application->description }}
    </div>
</div>

<form method="POST" action="{{ route('acceptApplication', [$application->id]) }}">
    {{ csrf_field() }}
    @method('PATCH')
    <button type="submit">
        Accept
    </button>
</form>

<form method="POST" action="{{ route('rejectApplication', [$application->id]) }}">
    {{ csrf_field() }}
    @method('PATCH')
    <button type="submit">
        Reject
    </button>
</form>
@endsection