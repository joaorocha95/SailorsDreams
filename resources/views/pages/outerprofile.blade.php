@extends('layouts.app')

@section('title','User Profile')

@section('content')
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
    <li class="breadcrumb-item active">User Profile</li>
</ol>

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
            </div>
        </div>
    </div>



</section>

@endsection