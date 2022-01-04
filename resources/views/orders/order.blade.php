@extends('layouts.app')

@section('content')
<title>About</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    .id {
        font-family: 'Nunito', sans-serif;
        background-color: #343434;
        color: #B9B9B9;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
    }
</style>
<h2 class="id">{{ $order->id }}</h2>
<div class="details">
    <div class="product">{{ $product->productname }}</div>
    <div class="order_status">{{ $order->order_status }}</div>
    <div class="order_type">{{ $order->order_type }}</div>
    <div class="loan_start">{{ $order->loan_start }}</div>
    <div class="loan_end">{{ $order->loan_end }}</div>
    <div class="total_price">{{ $order->total_price }}</div>
</div>

@endsection