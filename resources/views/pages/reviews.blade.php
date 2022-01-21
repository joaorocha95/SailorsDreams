@extends('layouts.app')

@section('title','Review')

@section('content')
<style>
  .reviews {
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

  .reviews:hover {
    background-color: #8AFCFA;
    width: 105px;
    height: 105px;
  }

  .userProfile {
    width: 500px;
    margin-left: 15px;
    float: left;
  }

  h2 {
    text-align: center;
  }
</style>

<ol class="breadcrumb">
  <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
  @if($id == auth()->user()->id)
  <li class="breadcrumb-item"><a href="{{ route('user.id', ['id' => auth()->user()->id] ) }}">User Profile</a></li>
  @else
  <li class="breadcrumb-item"><a href="{{ route('user.id', ['id' => $id = $reviews[1]->to_user ] ) }}">User Profile</a></li>
  @endif
  <li class="breadcrumb-item active">Reviews</li>
</ol>

<section id="reviews">
  <h2 class="temp1">Reviews</h2>
  @if (Auth::check())
  @foreach($reviews as $review)
  <div class="userProfile">
    <div class="card mb-3">
      <h3 class="card-header">From: {{ $from }}</h3>
      <ul class="list-group list-group-flush">
        <li class="list-group-item">Order Number: {{ $review->orderid }} </li>
        <li class="list-group-item">Rating: <img src="{{ asset('uploads/icons/Star_icon.png') }}" style="height:15px;width:15px;margin-bottom:5px"></img>{{ $review->rating }} </li>
        <li class="list-group-item">Comment: {{ $review->comment }}</li>
        <li class="list-group-item">Date: {{ $review->review_date }}</li>
      </ul>
    </div>
  </div>
  @endforeach
  @endif

</section>

@endsection