@extends('layouts.app')

@section('content')

@section('title','FAQ')

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

<!-- Styles -->
<style>
    .test {
        text-align: center;
    }

    .accordion {
        position: relative;
        width: 800px;
        margin: auto;
    }
</style>


<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{ url('/home')}}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ url('/help')}}">Help</a></li>
    <li class="breadcrumb-item active">FAQ</li>
</ol>

<h2 class="test">Frequentely Asked Questions</h2>

<div class="accordion" id="accordionExample">
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingOne">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                How do I buy a product?
            </button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample" style="">
            <div class="accordion-body">
                All you have to do is choose a product you like, buy or loan and then negotiate with the Seller!
            </div>
        </div>
    </div>
    <div class="accordion-item">
        <h2 class="accordion-header" id="headingTwo">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                How do I contact the Seller?
            </button>
        </h2>
        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample" style="">
            <div class="accordion-body">
                After you click "Buy" or "Loan", you will get access to the "Messages" button in your User Profile. There you select the order!
            </div>
        </div>
    </div>
</div>

<script>
    const uniptwo = document.getElementById("collapseTwo");
    document.getElementById("headingTwo").addEventListener("click", function() {
        if (uniptwo.classList.contains("show"))
            uniptwo.classList.remove("show");
        else uniptwo.classList.add("show");
    });

    const unipone = document.getElementById("collapseOne");
    document.getElementById("headingOne").addEventListener("click", function() {
        if (unipone.classList.contains("show"))
            unipone.classList.remove("show");
        else unipone.classList.add("show");
    });
</script>

@endsection