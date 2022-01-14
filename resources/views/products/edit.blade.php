@extends('layouts.app')

@section('content')

<form method="POST" action="{{ route('updatepage', ['id' => $product->id]) }}">
  {{ csrf_field() }}
  @method('PATCH')

  <label for="productname">Product Name:</label>
  <input id="productname" type="text" name="productname" value="{{ old('productname') }}" placeholder="{{$product->productname}}" autofocus>
  @if ($errors->has('productname'))
  <span class="error">
    {{ $errors->first('productname') }}
  </span>
  @endif

  <label for="description">Description:</label>
  <input id="description" type="text" name="description" value="{{ old('description') }}" placeholder="{{$product->description}}">
  @if ($errors->has('description'))
  <span class="error">
    {{ $errors->first('description') }}
  </span>
  @endif

  <label for="price">Price:</label>
  <input id="price" type="real" name="price" value="{{ old('price') }}" placeholder="{{$product->price}}" autofocus>
  @if ($errors->has('price'))
  <span class="error">
    {{ $errors->first('price') }}
  </span>
  @endif

  <label for="priceperday">Price per day:</label>
  <input id="priceperday" type="real" name="priceperday" value="{{ old('priceperday') }}" placeholder="{{$product->priceperday}}" autofocus>
  @if ($errors->has('priceperday'))
  <span class="error">
    {{ $errors->first('priceperday') }}
  </span>
  @endif

  <label for="active">Product active:</label>
  <input type="checkbox" name="active" value="yes" placeholder="{{$product->active}}">

  <label for="img">Select image to upload:</label>
  <input type="file" name="img" id="img" placeholder="{{$product->img}}">

  <button type="submit">
    Submit
  </button>
  <a class="button button-outline" href="{{ route('productManager') }}">Cancel</a>
</form>
@endsection