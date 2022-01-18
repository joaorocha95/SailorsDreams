@extends('layouts.app')

@section('content')
<title>About</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    h2 {
        color: white;
        font-size: 50px;
    }

    .productPicture {
        position: absolute;
        top: 100px;
        left: 0;
        z-index: -1;

        width: 50%;
        min-height: calc(100vh - 100px);
        max-height: 100vh;

        background-color: white;

        display: flex;
        align-items: center;
        justify-content: center;

    }

    .productPicture>img {
        width: 100%;
        height: calc(100vh - 100px);
    }

    .productInfo {
        position: absolute;
        padding-left: 50px;
        padding-top: 100px;
        top: 100px;
        right: 0;
        z-index: -1;

        background-color: #1a1a1a;
        color: white;

        width: 50%;
        min-height: calc(100vh - 100px);
    }

    .orderForms {
        padding-top: 30px;
        padding-bottom: 30px;
    }

    .productInfo .nav-link {
        color: white;
    }

    .nav-item .active {
        color: black;
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

<title>{{ $product->productname }}</title>

<div class="productPicture">

    <img alt="Imagem do Produto" src="{{$product->img}}">
</div>
<div class="productInfo">
    <h2> {{ $product->productname }} </h2>
    <p style="margin-top: -15px;">
        @if($product->active)
        Produto Ativo
        @else
        Produto Atualmente Inativo
        @endif
    </p>

    <div class="orderForms">
        @if($product->price != NULL && $product->active== 'true')
        <form method="post" action="{{ route('order.create', ['id' => $product->id, 'order_type' => 'Purchase'])}}" enctype="multipart/form-data">
            @csrf
            <div class=" input-group">
                <button class="btn btn-outline-light" type="submit">
                    Buy
                </button>
            </div>
        </form>
        @endif
        @if($product->priceperday != NULL && $product->active== 'true')
        <form method="post" action="{{ route('order.create', ['id' => $product->id, 'order_type' => 'Loan'])}}">
            @csrf
            <div class="input-group">
                <button class="btn btn-outline-light" type="submit">
                    Loan
                </button>
            </div>
        </form>
        @endif
    </div>

    <ul class="nav nav-tabs" style="margin-right: 50px;">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#Description">Description</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#profile">Profile</a>
        </li>
    </ul>
    <div id="myTabContent" class="tab-content" style="margin-right: 50px;">
        <div class="tab-pane fade active show" id="Description">
            <p>{{$product->description}}</p>
        </div>
        <div class="tab-pane fade" id="profile">
            <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid. Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda labore aesthetic magna delectus mollit.</p>
        </div>
    </div>
</div>
@endsection