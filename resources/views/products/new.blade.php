@extends('layouts.app')

@section('content')

@section('title','New Product')

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
  <form method="POST" action="{{ route('newProduct') }}" enctype="multipart/form-data">
    {{ csrf_field() }}

    <Legend>New Product</Legend>
    <div class="form-group row">
      <label for="productname" class="col-sm-2 col-form-label">Product Name*</label>
      <div class="col-sm-10"></div>
      <input class="form-control-plaintext smaller" id="productname" type="text" name="productname" value="{{ old('productname') }}" required autofocus>
    </div>
    @if ($errors->has('productname'))
    <span class="error">
      {{ $errors->first('productname') }}
    </span>
    @endif

    <div class="form-group">
      <label for="name" class="form-label mt-4">Category*</label>
      <select class="form-select smaller" id="name" type="name" name="name" value="{{ old('name') }}" required autofocus>
        @foreach($categories as $categoryItem)
        <option value="{{$categoryItem->id}}">{{$categoryItem->name}}</option>
        @if ($errors->has('name'))
        <span class="error">
          {{ $errors->first('name') }}
        </span>
        @endif
        @endforeach
      </select>
    </div>

    <div class="form-group">
      <label for="description">Description*</label>
      <input id="description" type="description" name="description" value="{{ old('description') }}" required>
      @if ($errors->has('description'))
      <span class="error">
        {{ $errors->first('description') }}
      </span>
      @endif
    </div>

    <div class="form-group">
      <label for="price">Price</label>
      <input id="price" type="real" name="price" value="{{ old('price') }}" autofocus>
      @if ($errors->has('price'))
      <span class="error">
        {{ $errors->first('price') }}
      </span>
      @endif
    </div>

    <div class="form-group">
      <label for="pricePerDay">Price per day</label>
      <input id="pricePerDay" type="real" name="pricePerDay" value="{{ old('pricePerDay') }}" autofocus>
      @if ($errors->has('pricePerDay'))
      <span class="error">
        {{ $errors->first('pricePerDay') }}
      </span>
      @endif
    </div>

    <div class="form-check">
      <label for="active" class="form-check-label">Product active</label>
      <input type="checkbox" class="form-check-input" name="active" checked="yes">
    </div>

    <div class="form-group">
      <label for="img" class="form-label mt-4">Select image to upload:</label>
      <input class="form-control" type="file" name="img" id="img">
    </div>

    <button type="submit" class="btn btn-primary">
      Submit
    </button>
    <a class="btn btn-outline-primary" href="{{ route('newProduct') }}">Cancel</a>
</fieldset>
</form>
@endsection