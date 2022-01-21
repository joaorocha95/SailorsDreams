@extends('layouts.app')

@section('content')

@section('title','Create Application')

<style>
  .smaller {
    width: 300px;
    margin-left: 20px;
  }

  .newProduct {
    align-items: center;
  }
</style>


<fieldset>
  <form method="POST" action="{{ route('newApplication', ['id' => auth()->user()]) }}">
    {{ csrf_field() }}
    <Legend>New Product</Legend>
    <div class="form-group row">
      <label for="title" class="col-sm-2 col-form-label">Title:</label>
      <div class="col-sm-10"></div>
      <input class="form-control-plaintext smaller" id="title" type="text" name="title" value="{{ old('title') }}" required autofocus>
    </div>
    @if ($errors->has('title'))
    <span class="error">
      {{ $errors->first('title') }}
    </span>
    @endif
    <div class="form-group row">
      <label for="description" class="col-sm-2 col-form-label">Description:</label>
      <div class="col-sm-10"></div>
      <input class="form-control-plaintext smaller" id="description" type="text" name="description" value="{{ old('description') }}" required>
    </div>
    @if ($errors->has('description'))
    <span class="error">
      {{ $errors->first('description') }}
    </span>
    @endif
    <button type="submit" class="btn btn-primary">
      Submit
    </button>
    <a class="btn btn-outline-primary" href="{{ route('newProduct') }}">Cancel</a>
</fieldset>
</form>
@endsection