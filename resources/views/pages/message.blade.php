@extends('layouts.app')

@section('title', 'Cards')

@section('content')
<style>
    .biggerOrder {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
    }

    .Order {
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

    .Order:hover {
        background-color: #8AFCFA;
        width: 105px;
        height: 105px;
    }
</style>


<section id="orders">
    <div class="biggerOrder">
        @if (Auth::check())
        @foreach($orders as $order)
        <a href="{{ route('messagePage.id', ['id' => $order->id]) }}">
            <div class="Order">{{ $order->id}}</div>
        </a>
        @endforeach
        @endif
    </div>
</section>

@endsection