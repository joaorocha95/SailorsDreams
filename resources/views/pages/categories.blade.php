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
    <div class="biggerCategory">
        @foreach($categories as $category)
        <div></div>
        <p class="category">{{ $category->name }}
        <p>
            @endforeach

    </div>

</section>

@endsection