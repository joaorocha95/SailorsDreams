@extends('layouts.app')

@section('content')

@section('title','Application Page')

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

    .geral {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        justify-self: center;
        text-align: center;

    }
</style>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('user.id', ['id' => auth()->user()->id]) }}">Admin Tools</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/applications') }}">Applications Manager</a></li>
    <li class=" breadcrumb-item active">Application</li>
</ol>

<section class="geral">

    <div class="userProfile">
        <div class="card mb-3">
            <h3 class="card-header">Application ID: {{ $application->id }}</h3>
            <div class="card-body">
                <h5 class="card-title">User ID: {{ $application->userid }}</h5>
                <h5 class="card-title">Application State: {{ $application->application_state }}</h5>
                <h5 class="card-title">Title: {{ $application->title }}</h5>
            </div>
            <ul class="list-group list-group-flush">
                <h5 class="list-group-item">Description: {{ $application->description }}</h5>
            </ul>
            <div class="card-body">
                @if($application->application_state == 'Evaluating')
                <form method="POST" action="{{ route('acceptApplication', [$application->id]) }}">
                    {{ csrf_field() }}
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-primary">
                        Accept
                    </button>
                </form>

                <form method="POST" action="{{ route('rejectApplication', [$application->id]) }}">
                    {{ csrf_field() }}
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-primary">
                        Reject
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection