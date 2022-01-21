@extends('layouts.app')

@section('content')

@section('title','Category Details')

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    h2 {
        margin: 10px;
    }
</style>

<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('user.id', ['id' => auth()->user()->id]) }}">Admin Tools</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/admin/categories') }}">Categories Manager</a></li>
    <li class=" breadcrumb-item active">Category</li>
</ol>

<h2>Category name: {{ $category->name }}</h2>

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

@endsection