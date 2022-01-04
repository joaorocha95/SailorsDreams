@extends('layouts.app')

@section('content')
<title>About</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    .temp1 {
        font-family: 'Nunito', sans-serif;
        background-color: #343434;
        color: #B9B9B9;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }
</style>

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

@endsection