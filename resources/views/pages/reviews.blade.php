@extends('layouts.app')

@section('title', 'Cards')

@section('content')
<style>
  .biggerOrder {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 5px;
  }

  .Order {
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
  }

  .Order:hover {
    background-color: #8AFCFA;
    width: 105px;
    height: 105px;
  }
</style>

<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
  <li class="breadcrumb-item"><a href="{{ route('user.id', ['id' => $id = $reviews[1]->to_user ] ) }}">User Profile</a></li>
  <li class="breadcrumb-item active">Reviews</li>
</ol>

<section id="orders">
  @if (Auth::check())
  @foreach($reviews as $review)
  <h2 class="temp1">Review: {{ $review->id }}</h2>
  <div class="temp2">
    <div Product: class="temp2"> orderid: {{ $review->orderid }}
    </div>
    <div class="temp2"> to_user: {{ $review->to_user }}
    </div>
    <div class="temp2"> from_user: {{ $review->from_user }}
    </div>
    <div class="temp2"> rating: {{ $review->rating }}
    </div>
    <div class="temp2"> comment: {{ $review->comment }}
    </div>
    <div class="temp2"> review_date: {{ $review->review_date }}
    </div>
  </div>
  @endforeach
  @endif
</section>

@endsection