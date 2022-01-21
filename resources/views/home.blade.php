@extends('layouts.app')

@section('content')

@section('title','Sailors Dream')

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    .everything {
        align-self: center;
        text-align: center;
    }

    .produtoDestaque {
        position: relative;
        display: inline-block;
        background-color: white;
        height: 700px;
        width: 1300px;
        max-width: 90%;
    }

    .img__description {
        display: flex;
        align-items: center;
        justify-content: center;

        position: absolute;
        height: 100%;
        width: 100%;

        top: 0;
        bottom: 0;
        left: 0;
        right: 0;

        background: rgba(208, 222, 235, 0.6);
        color: #343434;
        visibility: hidden;
        opacity: 0;

        /* transition effect. not necessary */
        transition: opacity .3s, visibility .2s;
    }

    .produtoDestaque:hover .img__description {
        visibility: visible;
        opacity: 1;
    }

    .produtoDestaque>img {
        width: 100%;
        height: 100%;
    }

    .multiplosDestaques {
        display: inline-block;
    }

    .multiplosDestaquesItems {
        position: relative;
        display: inline-block;
        background: white;
        width: 350px;
        height: 500px;
        margin-left: 40px;
        margin-right: 40px;
        margin-bottom: 50px;
    }

    .multiplosDestaquesItems:hover .img__description {
        visibility: visible;
        opacity: 1;
    }

    .multiplosDestaquesItems>img {
        width: 100%;
        height: 100%;
    }

    h2 {
        color: black;
        margin: 10px;
    }
</style>


<script>
</script>
<div class="everything">
    <h2>DESTAQUE</h2>
    <a class="produtoDestaque" href="{{ route('products.id', ['id' => $product[0]->id]) }}">
        <img alt="Destaque" src="{{ asset('uploads/productImages/'. $product[0]->img) }}">
        <div class="img__description">
            {{$product[0]->description}}
        </div>
    </a>

    <div style="height: 100px;">
    </div>

    <h3 style="color: black;">OUTROS DESTAQUES</h3>
    <div class="multiplosDestaques">
        <a class="multiplosDestaquesItems" href="{{ route('products.id', ['id' => $product[1]->id]) }}">
            <img alt="Destaque1" src="{{ asset('uploads/productImages/'. $product[1]->img) }}">
            <div class="img__description">
                {{$product[1]->description}}
            </div>
        </a>
        <a class="multiplosDestaquesItems" href="{{ route('products.id', ['id' => $product[2]->id]) }}">
            <img alt="Destaque2" src="{{ asset('uploads/productImages/'. $product[2]->img) }}">
            <div class="img__description">
                {{$product[2]->description}}
            </div>
        </a>
        <a class="multiplosDestaquesItems" href="{{ route('products.id', ['id' => $product[3]->id]) }}">
            <img alt="Destaque3" src="{{ asset('uploads/productImages/'. $product[3]->img) }}">
            <div class="img__description">
                {{$product[3]->description}}
            </div>
        </a>
        <a class="multiplosDestaquesItems" href="{{ route('products.id', ['id' => $product[4]->id]) }}">
            <img alt="Destaque4" src="{{ asset('uploads/productImages/'. $product[4]->img) }}">
            <div class="img__description">
                {{$product[4]->description}}
            </div>
        </a>
    </div>
</div>


@endsection