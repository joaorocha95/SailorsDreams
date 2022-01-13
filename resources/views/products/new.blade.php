@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('newProduct') }}">
  {{ csrf_field() }}

  <label for="productname">Product Name:</label>
  <input id="productname" type="text" name="productname" value="{{ old('productname') }}" required autofocus>
  @if ($errors->has('productname'))
  <span class="error">
    {{ $errors->first('productname') }}
  </span>
  @endif

  <label for="description">Description:</label>
  <input id="description" type="description" name="description" value="{{ old('description') }}" required>
  @if ($errors->has('description'))
  <span class="error">
    {{ $errors->first('description') }}
  </span>
  @endif

  <label for="price">Price:</label>
  <input id="price" type="real" name="price" value="{{ old('price') }}" required autofocus>
  @if ($errors->has('price'))
  <span class="error">
    {{ $errors->first('price') }}
  </span>
  @endif

  <label for="pricePerDay">Price per day:</label>
  <input id="pricePerDay" type="real" name="pricePerDay" value="{{ old('pricePerDay') }}" required autofocus>
  @if ($errors->has('pricePerDay'))
  <span class="error">
    {{ $errors->first('pricePerDay') }}
  </span>
  @endif

  <label for="active">Product active:</label>
  <input type="checkbox" name="active" value="checkbox_value">

  <label for="photo">Select image to upload:</label>
  <input type="file" name="fileToUpload" id="fileToUpload">

  <button type="submit">
    Submit
  </button>
  <a class="button button-outline" href="{{ route('newProduct') }}">Cancel</a>
</form>
@endsection