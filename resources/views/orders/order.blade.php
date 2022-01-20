@extends('layouts.app')

@section('content')
<title>About</title>

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
</style>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('user.id', ['id' => ($id = (auth()->user()->id ) ) ] ) }}">User Profile</a></li>
    <li class="breadcrumb-item"><a href="{{ route('orders') }}">Orders</a></li>
    <li class="breadcrumb-item active">Order Status</li>
</ol>

<h2 class="temp1">Order ID: {{ $order->id }}</h2>
<div class="temp2">
    <div Product: class="temp2"> Product: {{ $product->productname }}
    </div>
    <div class="temp2"> Order Status: {{ $order->order_status }}
    </div>
    <div class="temp2"> Order Type: {{ $order->order_type }}
    </div>
    <div class="temp2"> Loan Start: {{ $order->loan_start }}
    </div>
    <div class="temp2"> Loan End: {{ $order->loan_end }}
    </div>
    <div class="temp2"> Total Price: {{ $order->total_price }}
    </div>
</div>

@if (Auth::check())

@if (Auth::user()->acctype == 'Client')
<a class="btn btn-outline-primary" href="{{ route('newReview.id', ['id' => $order->id]) }}"> Review Order</a>
@endif

@if (Auth::user()->acctype == 'Seller')

@if($order->order_status == 'In_Negotiation')

<form method="POST" action="{{ route('endOrder', [$order->id]) }}">
    {{ csrf_field() }}
    @method('PATCH')
    <button type="submit">
        Complete Order
    </button>
</form>

<form method="POST" action="{{ route('cancelOrder', [$order->id]) }}">
    {{ csrf_field() }}
    @method('PATCH')
    <button type="submit">
        Cancel Order
    </button>
</form>
@endif
@endif
@endif
</div>
@endsection