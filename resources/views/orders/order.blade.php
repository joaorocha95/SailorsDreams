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


<div id="messsage_box">
    @if (Auth::check())
    @foreach($messages as $message)
    @if (auth()->user()->id == $product->seller )
    <div class="message">{Seller: {{$message->message}}</div>
    @elseif (auth()->user()->id == $message->sender )
    <div class="selfMessage">Me: {{$message->message}}</div>
    @else
    <div class="message">{{$message->sender}}: {{$message->message}}</div>
    @endif
    @endforeach
    @endif

</div>

<form method="POST" action="{{ route('sendMessage', ['message_type' => 'Order', 'associated_order' => $order->id]) }}">
    {{ csrf_field() }}

    <label for="message">Write your message:</label>
    <input id="message" type="text" name="message" value="{{ old('message') }}" required autofocus>
    @if ($errors->has('message'))
    <span class="error">
        {{ $errors->first('message') }}
    </span>
    @endif
    <button type="submit">
        Submit
    </button>
    <a class="button button-outline" href="{{ route('orders') }}">Orders</a>
</form>

@endsection