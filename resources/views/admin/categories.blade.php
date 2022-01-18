@extends('layouts.app')

@section('title', 'Cards')

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

<section id="categories">

    <a class="btn btn-outline-primary" href="{{ route('addCategory') }}"> Add Category </a>

    <div class="biggerCategory">
        @foreach($categories as $category)
        <a href="{{ route('showCategory', ['category' => $category->id]) }}">
            <div class="btn btn-outline-dark" style="margin-left: 5px;">{{ $category -> name}}</div>
        </a>
        @endforeach

    </div>



</section>

@endsection