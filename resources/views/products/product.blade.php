@extends('layouts.app')

@section('content')

@section('title','Product Page')

<!-- Fonts -->
<!-- Styles -->
<link href="{{ asset('css/reviews.css') }}" rel="stylesheet">
<style>
    h2 {
        color: white;
        font-size: 50px;

    }

    footer {
        bottom: -200px;
    }

    .productPicture {
        position: absolute;
        top: 100px;
        left: 0;
        z-index: -1;

        width: 50%;
        min-height: calc(100vh - 100px);
        max-height: calc(100vh);

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

    .bottom-div {
        position: absolute;
        bottom: 10%;
        left: 41%;
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



<section>
    <div class="productPicture">
        <img alt="Imagem do Produto" src="{{ asset('uploads/productImages/'. $product->img) }}" enctype="multipart/form-data">
    </div>

    <div class="productInfo">
        <ol class="breadcrumb" style="position: absolute; top: 0px; right: 40px; width: fit-content; padding-left: 5px; padding-right: 5px;">
            <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/products') }}">Products</a></li>
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
            @if($product->price != NULL && $product->active== 'true')
            <form method="post" action="{{ route('order.create', ['id' => $product->id, 'order_type' => 'Purchase'])}}" enctype="multipart/form-data">
                @csrf
                <div class=" input-group">
                    <button class="btn btn-outline-light" type="submit">
                        Buy
                    </button>
                </div>
            </form>
            @endif
            @if($product->priceperday != NULL && $product->active== 'true')
            <form method="post" action="{{ route('order.create', ['id' => $product->id, 'order_type' => 'Loan'])}}">
                @csrf
                <div class="input-group">
                    <button class="btn btn-outline-light" type="submit">
                        Loan
                    </button>
                </div>
            </form>
            @endif
        </div>
        <ul class="nav nav-tabs" style="margin-right: 50px;">
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" id="#Description">Description</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" id="#Profile">Seller</a>
            </li>
            <li>
                <a class="nav-link active" data-bs-toggle="tab" id="#Reviews">Seller Reviews</a>
            </li>
        </ul>
        <div id="myTabContent" class="tab-content" style="margin-right: 50px;">
            <div class="tab-pane fade" id="Description">
                <p>{{$product->description}}</p>
            </div>
            <div class="tab-pane fade" id="Profile">
                <p><a style="color: white;" href="{{route('user.id' , ['id' => $product->seller])}}">Seller's Webpage</a></p>

            </div>

            <!--Reviews-->
            <div class="tab-pane fade active show" id="Reviews">
                @if(count($reviews)+1 != 1)
                <div class="container">
                    <ul class="hash-list cols-3 cols-1-xs pad-30-all align-center text-sm">
                        @foreach($reviews as $review)
                        <li>
                            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="wpx-100 img-round mgb-20" title="" alt="" data-edit="false" data-editor="field" data-field="src[Image Path]; title[Image Title]; alt[Image Alternate Text]">
                            <p class="fs-110 font-cond-l" contenteditable="false">"{{$review->comment}}"</p>
                            <h5 class="font-cond mgb-5 fg-text-d fs-130" contenteditable="false">{{$review->from_user}}</h5><img src="{{ asset('uploads/icons/Star_icon.png') }}" style="height:20px;width:20px;margin-bottom:5px;"></img>
                            <small class="font-cond case-u lts-sm fs-80 fg-text-l" contenteditable="false">{{$review->rating}}/5</small>
                        </li>
                        @endforeach
                    </ul>

                </div>
                @if (Auth::check())
                <div class="bottom-div">
                    <a class="btn btn-outline-light" href="{{ route('moreReviews', ['id' => $product->seller]) }}"> More Reviews </a>
                </div>
                @endif
                @else
                <p>No reviews</p>
                @endif

            </div>
        </div>
    </div>
</section>
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