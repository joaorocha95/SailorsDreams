@extends('layouts.app')

@section('content')

@section('title','User Profile')

<style>
    .userProfile {
        display: table-row;
        max-width: 500px;
    }

    .profImg {
        min-height: 500px;
        max-height: 500px;
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
    @if ($user -> acctype == 'Admin')
    <li class="breadcrumb-item active">Admin Tools</li>
    @else
    <li class="breadcrumb-item active">User Profile</li>
    @endif
</ol>

<section class="geral">

    <div class="userProfile">
        <div class="card mb-3">
            <h3 class="card-header">Username: {{$user->username}}</h3>
            <div class="card-body">
                <h5 class="card-title">Account type: {{$user->acctype}}</h5>

            </div>
            <img alt="User Image" src="{{ asset('uploads/avatarImages/'. $user->img) }}" class="profImg"></img>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Email: {{$user->email}}</li>
                <li class="list-group-item">Phone number: {{$user->phone}}</li>
                <li class="list-group-item">Birth date: {{$user->birthdate}}</li>
            </ul>
            <div class="card-body">
                @if ($user -> acctype == 'Admin')
                <a class="btn btn-outline-primary" href="{{ route('admin.products') }}"> Product Manager </a>

                <a class="btn btn-outline-primary" href="{{ route('accounts') }}"> Account Manager </a>

                <a class="btn btn-outline-primary" href="{{ route('showApplications') }}"> Application Manager </a>

                <a class="btn btn-outline-primary" href="{{ route('showCategories') }}"> Categories Manager </a>
                @else

                <a class="btn btn-outline-primary" href="{{ route('orders') }}"> Orders </a>

                <form action="{{ route('deleteUser', [ 'id' => $user->id]) }}" method="post">
                    <input class="btn btn-outline-primary" type="submit" value="Delete Account" />
                    <input type="hidden" name="_method" value="delete" />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>

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
                <a class="btn btn-outline-primary" href="{{ route('moreReviews', ['id' => $user->id] )}}"> Reviews </a>
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