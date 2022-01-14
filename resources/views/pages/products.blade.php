@extends('layouts.app')

@section('title', 'Cards')

@section('content')
<style>
  .productButton {}
</style>

<section id="products">
  <div class="btn">
    @foreach($products as $product)
    <a href="{{ route('products.id', ['id' => $product->id]) }}">
      <div class="Product">{{ $product->productname }}</div>
    </a>
    @endforeach
  </div>
</section>
@endsection