@extends('layouts.app')

@section('content')

@section('title','Edit Product')

<style>
  .smaller {
    width: 300px;
    margin-left: 20px;
  }

  .newProduct {
    align-items: center;
  }

  fieldset {
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    grid-template-columns: 500px 500px 500px;
    justify-content: center;
    justify-self: center;

  }

  legend {
    align-self: center;
  }

  form {
    width: 400px;
    text-align: center;
  }

  label {
    float: left;
    display: block;
  }

  form .form-group {
    margin-bottom: 5px;
  }

  form .form-check {
    margin-bottom: 5px;
  }
</style>

<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('user.id', ['id' => ($id = (auth()->user()->id ) ) ] ) }}">User Profile</a></li>
  <li class="breadcrumb-item"><a href="{{ route('productManager') }}">Product Manager</a></li>
  <li class="breadcrumb-item active">Edit Product</li>
</ol>

<fieldset>
  <form method="POST" action="{{ route('updatepage', ['id' => $product->id]) }}" enctype="multipart/form-data">
    {{ csrf_field() }}
    @method('PATCH')
    <Legend>Edit Product</Legend>
    <div class="form-group">
      <label for="productname" class="col-sm-2 col-form-label">Product Name:</label>
      <div class="col-sm-10"></div>
      <input class="form-control-plaintext smaller" id="productname" type="text" name="productname" value="{{ old('productname') }}" placeholder="{{$product->productname}}" autofocus>
      @if ($errors->has('productname'))
      <span class="error">
        {{ $errors->first('productname') }}
      </span>
      @endif
    </div>

    <div class="form-group">
      <label for="name" class="form-label mt-4">Category</label>
      <select class="form-select smaller" id="name" type="name" name="name" value="{{ old('name') }}" placeholder="{{$product->name}}" autofocus>
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
      <label for="description">Description:</label>
      <input id="description" type="description" name="description" value="{{ old('description') }}" placeholder="{{$product->description}}" autofocus>
      @if ($errors->has('description'))
      <span class="error">
        {{ $errors->first('description') }}
      </span>
      @endif
    </div>

    <div class="form-group">
      <label for="price">Price:</label>
      <input id="price" type="real" name="price" value="{{ old('price') }}" placeholder="{{$product->price}}" autofocus>
      @if ($errors->has('price'))
      <span class="error">
        {{ $errors->first('price') }}
      </span>
      @endif
    </div>

    <div class="form-group">
      <label for="pricePerDay">Price per day:</label>
      <input id="pricePerDay" type="real" name="pricePerDay" value="{{ old('pricePerDay') }}" placeholder="{{$product->price}}" autofocus>
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
      <label for="pic" class="form-label mt-4">Select image to upload:</label>
      <input class="form-control" type="file" name="pic" id="pic">
    </div>

    <button type="submit" class="btn btn-primary">
      Submit
    </button>
    <a class="btn btn-outline-primary" href="{{ route('updatepage', ['id' => $product->id]) }}">Cancel</a>
  </form>
</fieldset>
@endsection