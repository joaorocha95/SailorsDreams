@extends('layouts.app')

@section('title', 'Cards')

@section('content')
<style>
    .biggerProduct {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
    }

    .Product {
        border-radius: 10px;
        background-color: #4ACBC9;
        width: 100px;
        height: 100px;
        color: #343434;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 5px;
    }

    .Product:hover {
        background-color: #8AFCFA;
        width: 105px;
        height: 105px;
    }
</style>

<section id="products">
    <div class="biggerProduct">
        @foreach($productManager as $product)
        <a href="{{ route('updatepage', ['id' => $product->id]) }}">
            <div class="Product">{{ $product->productname }}</div>
        </a>
        @endforeach
    </div>
</section>
@endsection