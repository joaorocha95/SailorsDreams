@extends('layouts.app')

@section('title', 'Cards')

@section('content')
<style>
    .userProfile {
        max-width: 500px;
    }

    .profImg {
        max-height: 500px;
    }
</style>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item active">User Profile</li>
</ol>

<section class="">

    <div class="userProfile">
        <div class="card mb-3">
            <h3 class="card-header">{{$user->username}}</h3>
            <div class="card-body">
                <h5 class="card-title">{{$user->acctype}}</h5>
                <h6 class="card-subtitle text-muted">Support card subtitle</h6>
            </div>
            <img src="{{ asset('uploads/avatarImages/'. $user->img) }}" class="profImg"></img>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">{{$user->email}}</li>
                <li class="list-group-item">{{$user->phone}}</li>
            </ul>
            <div class="card-body">
                @if ($user -> acctype == 'Admin')
                <a class="btn btn-outline-primary" href="{{ route('admin.products') }}"> Product Manager </a>

                <a class="btn btn-outline-primary" href="{{ route('accounts') }}"> Account Manager </a>

                <a class="btn btn-outline-primary" href="{{ route('showApplications') }}"> Application Manager </a>

                <a class="btn btn-outline-primary" href="{{ route('showCategories') }}"> Categories Manager </a>
                @else

                <a class="btn btn-outline-primary" href="{{ route('orders') }}"> Orders </a>

                <a class="btn btn-outline-primary" href="{{ route('myMessages') }}"> Messages </a>
                @if ($user -> acctype != 'Seller' && $user -> acctype != 'Admin')
                <a class="btn btn-outline-primary" href="{{ route('newApplication') }}"> Apply to Seller </a>
                @endif
                @if ($user -> acctype == 'Seller')
                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <button type="button" class="btn btn-primary">Products</button>
                    <div id="dropdown-button" class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                        <div id="dropdown-product" class="dropdown-menu" style="top: 50px; right: 0px;" aria-labelledby="btnGroupDrop1">
                            <a class="dropdown-item" href="{{ route('productManager') }}">Product Manager</a>
                            <a class="dropdown-item" href="{{ route('newProduct') }}">New Product</a>
                        </div>
                    </div>
                </div>
                @endif
                @endif
                <a class="btn btn-outline-primary" href="{{ route('editProfile', ['id' => $user -> id])}}">Edit Profile</a>
            </div>
        </div>
    </div>



</section>
<script>
    const dpdProduct = document.getElementById("dropdown-product");
    document.getElementById("dropdown-button").addEventListener("click", function() {
        if (dpdProduct.classList.contains("show"))
            dpdProduct.classList.remove("show");
        else dpdProduct.classList.add("show");
    });
</script>
@endsection