@extends('layouts.app')

@section('content')

@section('title','Product Details')

<!-- Fonts -->
<!-- Styles -->
<link href="{{ asset('css/reviews.css') }}" rel="stylesheet">
<style>
    h2 {
        color: white;
        font-size: 50px;
    }

    .productPicture {
        position: absolute;
        top: 100px;
        left: 0;
        z-index: -1;

        width: 50%;
        min-height: calc(100vh - 100px);
        max-height: 100vh;

        background-color: white;

        display: flex;
        align-items: center;
        justify-content: center;

    }

    .productPicture>img {
        width: 100%;
        height: calc(100vh - 100px);
    }

    .productInfo {
        position: absolute;
        padding-left: 50px;
        padding-top: 100px;
        top: 100px;
        right: 0;
        z-index: -1;

        background-color: #1a1a1a;
        color: white;

        width: 50%;
        min-height: calc(100vh - 100px);
    }

    .orderForms {
        padding-top: 30px;
        padding-bottom: 30px;
    }

    .productInfo .nav-link {
        color: white;
    }

    .reviewItem {
        width: 100%;
        height: fit-content;
        margin-right: 50px;
        border-style: solid;
        border-color: white;
        margin-bottom: 10px;
        padding: 5px;
    }

    .breadcrumb-item>a {
        color: white;
    }
</style>
<!-- 
{
    "id":3,
    "seller":9,
    "productname":"Iate",
    "description":"Brand new",
    "active":true,
    "price":null,
    "priceperday":"75"
}
-->

<title>{{ $product->productname }}</title>


<div class="productPicture">
    <img alt="Imagem do Produto" src="{{ asset('uploads/productImages/'. $product->img) }}" enctype="multipart/form-data">
</div>

<div class="productInfo">
    <ol class="breadcrumb" style="position: absolute; top: 0px; right: 40px; width: fit-content; padding-left: 5px; padding-right: 5px;">
        <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('user.id', ['id' => auth()->user()->id])}}">Admin Tools</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/admin/products') }}">Product Manager</a></li>
        <li class="breadcrumb-item active">Product</li>
    </ol>
    <h2> {{ $product->productname }} </h2>
    <h3 style="color: white;">
        @if($product->price != 0 && $product->pricePerDay != 0)
        Price: {{$product->price}}€ | Price: {{$product->pricePerDay}}€
        @else
        @if($product->price != 0)
        Price: {{$product->price}}€
        @endif
        @if($product->pricePerDay != 0)
        Price: {{$product->pricePerDay}}€
        @endif
        @endif
    </h3>
    <p style="margin-top: -5px;">
        @if($product->active)
        <span style="color:lightgreen;">
            Product Available
        </span>
        @else
        <span style="color:red;">
            Product Currently Unavailable
        </span>
        @endif
    </p>

    <div class="orderForms">
        <form method="POST" action="{{ route('admin.products.edit', ['id' => $product->id])}}">
            @method('PATCH')
            @csrf
            <div class="input-group">
                <button class="btn btn-outline-light" type="submit">
                    Edit product
                </button>
            </div>
        </form>

        <form method="POST" action="{{ route('admin.products.delete', ['id' => $product->id])}}">
            @method('DELETE')
            @csrf
            <div class="input-group">
                <button class="btn btn-outline-light" type="submit">
                    Delete product
                </button>
            </div>
        </form>
    </div>

    <ul class="nav nav-tabs" style="margin-right: 50px;">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" id="#Description">Description</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" id="#Profile">Seller</a>
        </li>
        <li>
            <a class="nav-link" data-bs-toggle="tab" id="#Reviews">Seller Reviews</a>
        </li>
    </ul>
    <div id="myTabContent" class="tab-content" style="margin-right: 50px;">
        <div class="tab-pane fade active show" id="Description">
            <p>{{$product->description}}</p>
        </div>
        <div class="tab-pane fade" id="Profile">
            <p><a style="color: white;" href="{{route('user.id' , ['id' => $seller->id])}}">Seller's Webpage</a></p>
            <p>Seller's E-mail: {{$seller->email}}</p>
            <p>Seller's Phone Number: {{$seller->phone}}</p>
        </div>
        <!--
        {
        id":2,"orderid":1,
        "to_user":6,
        "from_user":3,
        "rating":5,
        "comment":null,
        "review_date":"2022-01-18"
        }
        -->
        <div class="tab-pane fade" id="Reviews">
            @if($reviews != [])
            <div class="container">
                <ul class="hash-list cols-3 cols-1-xs pad-30-all align-center text-sm">
                    @foreach($reviews as $review)
                    <li>
                        <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="wpx-100 img-round mgb-20" title="" alt="" data-edit="false" data-editor="field" data-field="src[Image Path]; title[Image Title]; alt[Image Alternate Text]">
                        <p class="fs-110 font-cond-l" contenteditable="false">"{{$review->comment}}"</p>
                        <h5 class="font-cond mgb-5 fg-text-d fs-130" contenteditable="false">{{$review->from_user}}</h5>
                        <small class="font-cond case-u lts-sm fs-80 fg-text-l" contenteditable="false">{{$review->rating}}/5</small>
                    </li>
                    @endforeach
                </ul>
            </div>
            @else
            <p>No reviews</p>
            @endif
        </div>
    </div>
</div>

<script>
    navLinks = ["Description", "Profile", "Reviews"]

    document.getElementById("#Description").addEventListener("click", function() {
        hideAllOtherThan("Description")
    });
    document.getElementById("#Profile").addEventListener("click", function() {
        hideAllOtherThan("Profile")
    });
    document.getElementById("#Reviews").addEventListener("click", function() {
        hideAllOtherThan("Reviews")
    });

    function hideAllOtherThan(toShow) {
        document.getElementById("#" + toShow).classList.add("active");
        document.getElementById(toShow).classList.add("show");
        document.getElementById(toShow).classList.add("active");

        for (let navLink of navLinks) {
            if (navLink != toShow) {
                document.getElementById("#" + navLink).classList.remove("active");
                document.getElementById(navLink).classList.remove("show");
                document.getElementById(navLink).classList.remove("active");
            }
        }
    }
</script>

@endsection