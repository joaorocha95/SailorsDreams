@extends('layouts.app')

@section('content')

@section('title','Message Page')

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
        position: relative;
        width: 400px;
        height: 500px;
        max-height: 400px;
        border-style: solid;
        border-color: black;
        overflow-y: auto;
        -ms-overflow-style: none;
        /* IE and Edge */
        scrollbar-width: none;
        left: 11%;

    }

    #message_box::-webkit-scrollbar {
        display: none;
    }



    .message {
        height: fit-content;
        color: black;
        margin-left: 5px;
        text-align: left;

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

    .geral {
        flex-wrap: wrap;
        justify-content: center;
        justify-self: center;
        text-align: center;
        align-items: center;
    }

    .userProfile {
        width: 500px;
        margin-left: 15px;
        float: left;
    }

    .chatMes {
        position: absolute;
        margin-top: 30px;
        -webkit-transform: translateX(-50%) translateY(-50%);
        -moz-transform: translateX(-50%) translateY(-50%);
        transform: translateX(-50%) translateY(-50%);
        left: 50%;
    }
</style>

<script>
    var element = document.getElementById('message_box');

    element.scrollTop = element.scrollHeight;
</script>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('user.id', ['id' => ($id = (auth()->user()->id ) ) ] ) }}">User Profile</a></li>
    <li class="breadcrumb-item active">Messages</li>
</ol>

<section>
    <div>
        <h2 class="temp1">Messages</h2>
        <div class="userProfile">
            <div class="card mb-3">
                <h3 class="card-header">Order ID: {{ $order->id }}</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Product: {{ $product->productname }} </li>
                    <li class="list-group-item">Order Status: {{ $order->order_status }} </li>
                    <li class="list-group-item">Order Type: {{ $order->order_type }}</li>
                    <li class="list-group-item">Loan End: {{ $order->loan_end }}</li>
                    <li class="list-group-item">Total Price: {{ $order->total_price }}</li>
                </ul>
            </div>
        </div>



        <div id="messsage_box">
            @if (Auth::check())
            @foreach($messages as $message)
            @if (auth()->user()->id == $message->sender)
            <div class="selfMessage">Me: {{$message->message}}</div>
            @else
            <div class="message">{{$message->username}}: {{$message->message}}</div>
            @endif
            @endforeach
            @endif
        </div>


        <div class="chatMes">
            <form method="POST" action="{{ route('messagePage.id', ['id' => $order->id, 'message_type' => 'Order', 'associated_order' => $order->id]) }}">
                {{ csrf_field() }}

                <label for="message">Write your message:</label>
                <input id="message" type="text" name="message" value="" required autofocus>
                @if ($errors->has('message'))
                <span class="error">
                    {{ $errors->first('message') }}
                </span>
                @endif
                <button type="submit">
                    Submit
                </button>
            </form>
        </div>
    </div>
</section>


@endsection