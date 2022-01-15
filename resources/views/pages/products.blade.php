@extends('layouts.app')

@section('title', 'Cards')

@section('content')
<style>
  section {
    width: 100%;
    display: flex;
    flex-wrap: wrap;
    grid-template-columns: 500px 500px 500px;
    justify-content: center;
    justify-self: center;
  }

  @media screen and (max-width: 900px) {
    .column {
      flex: 50%;
    }
  }

  .productItem {
    position: relative;
    display: inline-block;
    background: white;
    width: 350px;
    height: 450px;
    margin: 70px;
    margin-top: 0px;
    color: #55595c;
    border-style: solid;
    border-color: black;

    vertical-align: center;
  }

  .img_description {
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;

    position: absolute;
    height: 100%;
    width: 100%;

    top: 0;
    bottom: 0;
    left: 0;
    right: 0;

    background-color: rgba(0, 0, 0, 0.4);
    color: white;
    visibility: hidden;
    opacity: 0;

    /* transition effect. not necessary */
    transition: color 0.15s ease-in-out, background-color 0.25s ease-in-out;
  }

  .productItem:hover .img_description {
    visibility: visible;
    opacity: 1;
  }

  .productItem:hover .productFooter {
    visibility: hidden;
  }

  .productItem>img {
    width: 100%;
    height: 100%;
  }

  .productFooter {
    position: absolute;
    bottom: 0px;
    height: 50px;
    width: 100%;
    background-color: black;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
  }
</style>

<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
  <li class=" breadcrumb-item active">Products</li>
</ol>

<h2 style="text-align: center;">Products</h2>
<section id="products">
  @foreach($products as $product)
  <a class="productItem" href="{{ route('products.id', ['id' => $product->id]) }}">
    <div class="productFooter">
      <span>
        {{ $product->productname }}
      </span>
    </div>
    <img alt="Imagem do Produto" src="https://scontent.fopo2-1.fna.fbcdn.net/v/t1.18169-1/p200x200/18664317_1456148631095681_8921032841732140123_n.jpg?_nc_cat=111&ccb=1-5&_nc_sid=7206a8&_nc_ohc=M-v9k0XVwMQAX9GgxV6&_nc_ht=scontent.fopo2-1.fna&oh=00_AT8e6Ny0GlD3oMevMNkLI0Bd7ZKEIxwPr66uwmQxFnnGEw&oe=62031120">
    <div class="img_description">
      {{ $product->productname }}
      <br>
      {{$product->description}}
    </div>
  </a>
  @endforeach
</section>
@endsection