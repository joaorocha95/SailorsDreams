@extends('layouts.app')

@section('content')
<title>About</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    h2 {
        margin: 10px;
    }
</style>
<!-- 
{
    "id":3,
    "seller":9,
    "productname":"Iate",
    "description":"Brand new",
    "active":true,
    "price":null,
    "priceperday":"75"
}
-->
<h2>{{ $user->id }}</h2>
<div class="details">
    <div class="photo">
        Imagem do User
    </div>
    <div class="username">
        Username: {{ $user->username }}
    </div>
    <div class="email">
        Email: {{ $user->email }}
    </div>
    <div class="birthDate">
        Birth date: {{ $user->birthdate }}
    </div>
    <div class="phone">
        Phone number: {{ $user->phone }}
    </div>
    <div class="accType">
        Account Type: {{ $user->acctype }}
    </div>

    @if($user->banned == FALSE)
    <form method="POST" action="{{ route('accounts.ban', ['id' => $user->id])}}">
        @method('PATCH')
        @csrf
        <div class="input-group">
            <button class="btn btn-info" type="submit">
                Ban account
            </button>
        </div>
    </form>
    @endif
    @if($user->banned == TRUE)
    <div class="banned">
        ACCOUNT BANNED
    </div>
    <form method="POST" action="{{ route('accounts.unban', ['id' => $user->id])}}">
        @method('PATCH')
        @csrf
        <div class="input-group">
            <button class="btn btn-info" type="submit">
                Unban account
            </button>
        </div>
    </form>
    @endif

</div>
@endsection