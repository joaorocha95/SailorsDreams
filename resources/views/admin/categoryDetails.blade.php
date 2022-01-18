@extends('layouts.app')

@section('content')
<title>About</title>

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    h2 {
        margin: 10px;
    }
</style>

<a class="btn btn-outline-primary" href="{{ route('updateCategory', ['category' => $category->id]) }}"> Edit Category </a>

<form method="POST" action="{{ route('deleteCategory', ['category' => $category->id])}}">
    @method('DELETE')
    @csrf
    <div class="input-group">
        <button class="btn btn-outline-primary" type="submit">
            Delete product
        </button>
    </div>
</form>

<h2>Category name: {{ $category->name }}</h2>

@endsection