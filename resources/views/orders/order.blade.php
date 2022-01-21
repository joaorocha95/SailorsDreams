@extends('layouts.app')

@section('content')

@section('title','Order Page')

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    .temp1 {
        /*
        background-color: #343434;
        */
        font-family: 'Nunito', sans-serif;
        color: #B9B9B9;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }

    #messsage_box {
        width: 400px;
        height: 500px;
        max-height: 400px;
        border-style: solid;
        border-color: black;
        overflow: scroll;
    }

    .message {
        height: 10px;
        color: black;
        margin-left: 5px;
    }

    .message:hover {
        background-color: black;
        color: white;
    }

    .selfMessage {
        background-color: lightblue;
        color: black;
        text-align: right;
        margin-right: 10px;
    }

    .selfMessage:hover {
        background-color: cyan;
    }

    .orderProfile {
        display: table-row;
        min-width: 500px;
        max-width: 500px;
    }

    .productImg>img {
        width: 100%;
    }

    .geral {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        justify-self: center;
        text-align: center;

    }
</style>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('user.id', ['id' => ($id = (auth()->user()->id ) ) ] ) }}">User Profile</a></li>
    <li class="breadcrumb-item"><a href="{{ route('orders') }}">Orders</a></li>
    <li class="breadcrumb-item active">Order Status</li>
</ol>

<h2 class="temp1">Order ID: {{ $order->id }}</h2>

<section class="geral">

    <div class="orderProfile">
        <div class="card mb-3">
            <h3 class="card-header">Product: {{ $product->productname }}</h3>
            <div class="card-body">
                <h5 class="card-title">Order Status: {{ $order->order_status }}</h5>

            </div>
            <a class="productImg" href="{{ route('products.id', ['id' => $product->id]) }}">
                <img alt="Product Image" src="{{ asset('uploads/productImages/'. $product->img) }}" class="productImg"></img>
            </a>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Order Type: {{ $order->order_type }}</li>
                <li class="list-group-item">Loan Start: {{ $order->loan_start }}</li>
                <li class="list-group-item">Loan End: {{ $order->loan_end }}</li>
                <li class="list-group-item">Total Price: {{ $order->total_price }}€</li>
            </ul>
            @if (Auth::check())
            <div class="card-body">

                @if (Auth::user()->acctype == 'Client')
                <a class="btn btn-outline-primary" href="{{ route('newReview.id', ['id' => $order->id]) }}"> Review Order</a>
                @endif

                @if (Auth::user()->acctype == 'Seller')

                @if($order->order_status == 'In_Negotiation')

                <form method="POST" action="{{ route('endOrder', [$order->id]) }}">
                    {{ csrf_field() }}
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-primary">
                        Complete Order
                    </button>
                </form>

                <form method="POST" action="{{ route('cancelOrder', [$order->id]) }}">
                    {{ csrf_field() }}
                    @method('PATCH')
                    <button type="submit" class="btn btn-outline-primary">
                        Cancel Order
                    </button>
                </form>
                @endif
                @endif
            </div>
            @endif
        </div>
    </div>



</section>


</div>
@endsection