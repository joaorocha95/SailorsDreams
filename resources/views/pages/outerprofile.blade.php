@extends('layouts.app')

@section('title', 'Cards')

@section('content')
<style>
    .userProfile {
        max-width: 500px;
    }

    .profImg {
        max-height: 500px;
    }
</style>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item active">User Profile</li>
</ol>

<section class="">

    <div class="userProfile">
        <div class="card mb-3">
            <h3 class="card-header">{{$user->username}}</h3>
            <div class="card-body">
                <h5 class="card-title">{{$user->acctype}}</h5>
                <h6 class="card-subtitle text-muted">Support card subtitle</h6>
            </div>
            <img src="{{ asset('uploads/avatarImages/'. $user->img) }}" class="profImg"></img>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">{{$user->email}}</li>
                <li class="list-group-item">{{$user->phone}}</li>
            </ul>
            <div class="card-body">
            </div>
        </div>
    </div>



</section>

@endsection