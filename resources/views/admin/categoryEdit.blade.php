@extends('layouts.app')

@section('content')

@section('title','Edit Category')

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
  <form method="POST" action="{{ route('updateCategory', ['category' => $category->id]) }}">
    {{ csrf_field() }}
    @method('PATCH')
    <Legend>Edit Category</Legend>
    <div class="form-group row">
      <label for="name" class="col-sm-2 col-form-label">Category Name:</label>
      <div class="col-sm-10"></div>
      <input class="form-control-plaintext smaller" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="{{$category->name}}" autofocus>
    </div>
    @if ($errors->has('name'))
    <span class="error">
      {{ $errors->first('name') }}
    </span>
    @endif
    <button type="submit" class="btn btn-primary">
      Submit
    </button>
</fieldset>
</form>
@endsection