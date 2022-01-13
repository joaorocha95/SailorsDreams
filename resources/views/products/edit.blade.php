@extends('layouts.app')

@section('content')

<form method="PATCH" action="{{ route('updatepage', ['id' => $product->id]) }}">
  {{ csrf_field() }}

  <label for="productname">Product Name:</label>
  <input id="productname" type="text" name="productname" value="{{ old('productname') }}" autofocus>
  @if ($errors->has('productname'))
  <span class="error">
    {{ $errors->first('productname') }}
  </span>
  @endif

  <label for="description">Description:</label>
  <input id="description" type="description" name="description" value="{{ old('description') }}">
  @if ($errors->has('description'))
  <span class="error">
    {{ $errors->first('description') }}
  </span>
  @endif

  <label for="price">Price:</label>
  <input id="price" type="real" name="price" value="{{ old('price') }}" autofocus>
  @if ($errors->has('price'))
  <span class="error">
    {{ $errors->first('price') }}
  </span>
  @endif

  <label for="priceperday">Price per day:</label>
  <input id="priceperday" type="real" name="priceperday" value="{{ old('priceperday') }}" autofocus>
  @if ($errors->has('priceperday'))
  <span class="error">
    {{ $errors->first('priceperday') }}
  </span>
  @endif

  <label for="active">Product active:</label>
  <input type="checkbox" name="checkbox_name" value="checkox_value">

  <label for="photo">Select image to upload:</label>
  <input type="file" name="fileToUpload" id="fileToUpload">

  <button type="submit">
    Submit
  </button>
  <a class="button button-outline" href="{{ route('productManager') }}">Cancel</a>
</form>
@endsection