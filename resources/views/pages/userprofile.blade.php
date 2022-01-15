@extends('layouts.app')

@section('title', 'Cards')

@section('content')
<style>
</style>

<section class="">
    <a class="btn btn-outline-primary" href="{{ route('orders') }}"> Orders </a>

    <a class="btn btn-outline-primary" href="{{ route('myMessages') }}"> Messages </a>

    @if ($user -> acctype == 'Seller')
    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <button type="button" class="btn btn-primary">Products</button>
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
            <div class="dropdown-menu show" aria-labelledby="btnGroupDrop1">
                <a class="dropdown-item" href="{{ route('productManager') }}">Product Manager</a>
                <a class="dropdown-item" href="{{ route('newProduct') }}">New Product</a>
            </div>
        </div>
    </div>
    @endif

</section>

@endsection