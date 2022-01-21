@extends('layouts.app')

@section('content')

@section('title','User Details')

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    .userProfile {
        display: table-row;
        min-width: 500px;
        max-width: 500px;
    }

    .profImg {
        min-height: 500px;
        max-height: 500px;
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
    <li class="breadcrumb-item"><a href="{{ url('/admin/accounts') }}">Account Manager</a></li>
    <li class=" breadcrumb-item active">Account</li>
</ol>

<h2>ID: {{ $user->id }}</h2>

<section class="geral">

    <div class="userProfile">
        <div class="card mb-3">
            <h3 class="card-header">Username: {{$user->username}}</h3>
            <div class="card-body">
                <h5 class="card-title">Account type: {{$user->acctype}}</h5>

            </div>
            <img alt="User Image" src="{{ asset('uploads/avatarImages/'. $user->img) }}" class="profImg"></img>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Email: {{$user->email}}</li>
                <li class="list-group-item">Phone number: {{$user->phone}}</li>
                <li class="list-group-item">Birth date: {{$user->birthdate}}</li>
            </ul>
            <div class="card-body">
                @if($user->banned == FALSE)
                <form method="POST" action="{{ route('accounts.ban', ['id' => $user->id])}}">
                    @method('PATCH')
                    @csrf
                    <div class="input-group">
                        <button class="btn btn-outline-primary" type="submit">
                            Ban account
                        </button>
                    </div>
                </form>
                @endif
                @if($user->banned == TRUE)
                <div class="list-group-item">
                    ACCOUNT BANNED
                </div>
                <form method="POST" action="{{ route('accounts.unban', ['id' => $user->id])}}">
                    @method('PATCH')
                    @csrf
                    <div class="input-group">
                        <button class="btn btn-outline-primary" type="submit">
                            Unban account
                        </button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>
    </div>
    @endsection