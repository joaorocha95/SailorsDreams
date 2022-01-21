@extends('layouts.app')

@section('title','New Review')

@section('content')

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
  <form method="POST" action="{{ route('newReview.id', ['id' => $order->id]) }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    <Legend>New Review</Legend>
    <div class="form-group row">
      <label for="rating" class="col-sm-2 col-form-label">Score(1-5)*:</label>
      <div class="col-sm-10"></div>
      <input class="form-control-plaintext smaller" id="rating" type="int" name="rating" value="{{ old('rating') }}" pattern="/^[1-5]$/" required autofocus>
    </div>
    @if ($errors->has('rating'))
    <span class="error">
      {{ $errors->first('rating') }}
    </span>
    @endif

    <div class="form-group row">
      <label for="comment" class="col-sm-2 col-form-label">Comment:</label>
      <div class="col-sm-10"></div>
      <input class="form-control-plaintext smaller" id="comment" type="text" name="comment" value="{{ old('comment') }}">
    </div>
    @if ($errors->has('comment'))
    <span class="error">
      {{ $errors->first('comment') }}
    </span>
    @endif

    <button type="submit" class="btn btn-primary">
      Submit
    </button>
    <a class="btn btn-outline-primary" href="{{ route('newProduct')}}">Cancel</a>
  </form>
</fieldset>

@endsection