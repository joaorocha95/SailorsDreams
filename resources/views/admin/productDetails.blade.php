@extends('layouts.app')

@section('content')
<title>About</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    h2 {
        margin: 10px;
    }
</style>
<!-- 
{
    "id":3,
    "seller":9,
    "productname":"Iate",
    "description":"Brand new",
    "active":true,
    "price":null,
    "priceperday":"75"
}
-->
<h2>{{ $product->id }}</h2>
<div class="details">
    <div class="photo">
        Imagem do Produto
    </div>
    <div class="productname">
        Product Name: {{ $product->username }}
    </div>
    <div class="seller">
        Seller: {{ $product->seller }}
    </div>
    <div class="description">
        Description: {{ $product->description }}
    </div>
    <div class="price">
        Price: {{ $product->price }}
    </div>
    <div class="priceperday">
        Price per day: {{ $product->priceperday }}
    </div>

    <form method="POST" action="{{ route('updatepage', ['id' => $product->id])}}">
        @method('PATCH')
        @csrf
        <div class="input-group">
            <button class="btn btn-info" type="submit">
                Edit product
            </button>
        </div>
    </form>

    <form method="POST" action="{{ route('admin.products.delete', ['id' => $product->id])}}">
        @method('DELETE')
        @csrf
        <div class="input-group">
            <button class="btn btn-info" type="submit">
                Delete product
            </button>
        </div>
    </form>


</div>
@endsection