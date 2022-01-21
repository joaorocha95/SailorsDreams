@extends('layouts.app')

@section('title','Edit User')

@section('content')

<style>
    section {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
        grid-template-columns: 500px 500px 500px;
        justify-content: center;
        justify-self: center;
    }

    form {
        width: 400px;
        text-align: center;
    }

    ::-webkit-input-placeholder {
        text-align: center;
    }

    :-moz-placeholder {
        /* Firefox 18- */
        text-align: center;
    }

    ::-moz-placeholder {
        /* Firefox 19+ */
        text-align: center;
    }

    :-ms-input-placeholder {
        text-align: center;
    }

    label {
        float: left;
    }

    .duoBtn {
        width: fit-content;
        height: fit-content;
        margin-top: 50px;
        margin-left: auto;
        margin-right: auto;
    }
</style>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('user.id', ['id' => ($id = (auth()->user()->id ) ) ] ) }}">User Profile</a></li>
    <li class="breadcrumb-item active">Edit User Profile</li>
</ol>


<!--NEW-->
<section>
    <form method="POST" action="{{ route('editProfile', ['id' => $user->id]) }}" enctype="multipart/form-data">
        {{ csrf_field() }}
        @method('PATCH')
        <h2 style="text-align: center;">Edit Profile</h2>
        <label for="phone">Phone Number</label>
        <input id="phone" type="integer" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Enter phone number" autofocus>
        @if ($errors->has('phone'))
        <span class="error">
            {{ $errors->first('phone') }}
        </span>
        @endif

        <label for="pic" class="form-label mt-4">Profile Image</label>
        <input class="form-control" type="file" id="pic" name="pic">
        @if ($errors->has('pic'))
        <span class="error">
            {{ $errors->first('pic') }}
        </span>
        @endif

        <label for="password" class="form-label mt-4">Password</label>
        <input id="password" type="password" name="password" class="form-control" placeholder="Enter password">
        @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
        @endif

        <label for="password-confirm">Confirm Password</label>
        <input id="password-confirm" type="password" name="password_confirmation" class="form-control" placeholder="Confirm password">

        <button type="submit" class="btn btn-primary">
            Confirm
        </button>
    </form>
</section>
@endsection