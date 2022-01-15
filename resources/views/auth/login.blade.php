@extends('layouts.app')

@section('content')
<!--
<form method="POST" action="{{ route('login') }}">
    <legend>Login</legend>
    <div class="form-group">
        <label for="email" class="form-label mt-4">Email address</label>
        <input type="email" value="{{ old('email') }}" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email">
        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        @if ($errors->has('email'))
        <span class="error">
            {{ $errors->first('email') }}
        </span>
        @endif
    </div>
    <div class="form-group">
        <label for="password" class="form-label mt-4">Password</label>
        <input type="password" class="form-control" id="password" placeholder="Password">
        @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
        @endif
    </div>
    <label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
    </label>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
-->
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
</style>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class=" breadcrumb-item active">Login</li>
</ol>


<!--OLD -->
<section>
    <form method="POST" action="{{ route('login') }}">
        {{ csrf_field() }}
        <h2 style="text-align: center;">Login</h2>
        <div class="form-group">
            <label for="email" class="form-label mt-4">E-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter email" required autofocus>
            @if ($errors->has('email'))
            <span class="error">
                {{ $errors->first('email') }}
            </span>
            @endif
        </div>

        <label for="password" class="form-label mt-4">Password</label>
        <input id="password" type="password" name="password" class="form-control" placeholder="Enter password" required>
        @if ($errors->has('password'))
        <span class="error">
            {{ $errors->first('password') }}
        </span>
        @endif

        <label>
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
        </label>
        <p></p>
        <button type="submit" class="btn btn-primary">
            Login
        </button>
        <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
    </form>
</section>
@endsection