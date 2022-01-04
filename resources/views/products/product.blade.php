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


    .details {
        display: grid;
        height: fit-content;
        align-content: start;
        grid-template-columns: 300px auto;
        grid-template-rows: 300px 100px;
        grid-gap: 10px;
        background-color: #2196F3;
        padding: 10px;
    }

    .grid-container>div {
        background-color: rgba(255, 255, 255, 0.8);
        text-align: center;
        padding: 20px 0;
        font-size: 30px;
    }

    .photo {
        background-color: blue;
    }

    .descricao {
        background-color: red;
    }

    .item3 {
        background-color: green;
    }

    .item4 {
        background-color: yellow;
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
<h2>{{ $product->productname }}</h2>
<div class="details">
    <div class="photo">
        Imagem do Produto
    </div>
    <div class="descricao">
        Descrição: {{ $product->description }}
    </div>
    <div class="item3">
        Price: {{ $product->price }}<br>
        Price per Day: {{ $product->priceperday }}
    </div>
    <div class="item4">
        Seller: {{ $product->seller }}
    </div>
</div>

@endsection