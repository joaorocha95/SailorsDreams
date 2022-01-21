@extends('layouts.app')

@section('content')

@section('title','Register')

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
  <li class="breadcrumb-item active">Register</li>
</ol>
<!--NEW-->
<section>
  <form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}
    <h2 style="text-align: center;">Register</h2>
    <label for="username">Username*</label>
    <input id="username" type="text" name="username" value="{{ old('username') }}" class="form-control" placeholder="Enter username" required autofocus>
    @if ($errors->has('username'))
    <span class="error">
      {{ $errors->first('username') }}
    </span>
    @endif
    <label for="email" class="form-label mt-4">E-mail Address*</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="Enter e-mail address" required>
    @if ($errors->has('email'))
    <span class="error">
      {{ $errors->first('email') }}
    </span>
    @endif
    <label for="birthdate">Birth Date*</label>
    <input id="birthdate" type="date" name="birthdate" value="{{ old('birthdate') }}" class="form-control" required>
    @if ($errors->has('birthdate'))
    <span class="error">
      {{ $errors->first('birthdate') }}
    </span>
    @endif

    <label for="phone">Phone Number*</label>
    <input id="phone" type="integer" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="Enter phone number" required>
    @if ($errors->has('phone'))
    <span class="error">
      {{ $errors->first('phone') }}
    </span>
    @endif

    <label for="password" class="form-label mt-4">Password*</label>
    <input id="password" type="password" name="password" class="form-control" placeholder="Enter password" required>
    @if ($errors->has('password'))
    <span class="error">
      {{ $errors->first('password') }}
    </span>
    @endif

    <label for="password-confirm">Confirm Password*</label>
    <input id="password-confirm" type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>

    <button type="submit" class="btn btn-primary">
      Register
    </button>
  </form>
</section>
@endsection