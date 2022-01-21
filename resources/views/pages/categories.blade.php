@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<style>
    .biggerCategory {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 5px;
    }

    .category {
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
        text-align: center;
    }

    .category:hover {
        background-color: #8AFCFA;
        width: 105px;
        height: 105px;
    }
</style>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item active">Categories</li>
</ol>

<section id="categories">
    <div class="biggerCategory">
        @foreach($categories as $category)
        <a href="{{ route('category.name', ['name' => $category->id]) }}">
            <div class="btn btn-outline-dark" style="margin-left: 5px;">{{ $category -> name}}</div>
        </a>
        @endforeach

    </div>

</section>

@endsection