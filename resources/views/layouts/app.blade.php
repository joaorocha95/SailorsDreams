<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Styles -->
  <!--<link href="{{ asset('css/milligram.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">-->
  <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

  <style>

  </style>

  <script type="text/javascript">
    // Fix for Firefox autofocus CSS bug
    // See: http://stackoverflow.com/questions/18943276/html-5-autofocus-messes-up-css-loading/18945951#18945951
  </script>
  <script type="text/javascript" src="{{ asset('js/app.js') }}" defer>
  </script>
</head>

<body class="antialiased">
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand" href="{{ url('/home')}}">Sailor's Dream</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarColor01">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <div class="buttons">
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/categories') }}"> Categories </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ url('/products') }}"> Products </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="{{ route('help') }}"> Help</a>
          </li>
          <li>
            <a class="nav-link" href="{{ url('/about') }}"> About Us</a>
          </li>
        </ul>
        <form action="{{ route('products') }}" method="GET" role="search" class="d-flex">
          <input class="form-control me-sm-2" type="text" placeholder="Search Products" name="term" id="term">
          <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Search</button>
        </form>
        @if (Auth::check())
        <a class="btn btn-outline-light" style="margin-left: 5px;" href="{{ route('user.id', ['id' => ($id = (auth()->user()->id ) ) ] ) }}"> User Profile </a>
        <a class="btn btn-outline-light" style="margin-left: 5px;" href="{{ url('/logout') }}"> Logout </a> <span>{{ Auth::user()->name }}</span>
        @else
        <a class="btn btn-outline-light" style="margin-left: 5px;" href="{{ url('/login') }}"> Login </a>
        @endif
      </div>
    </div>
  </nav>



  <!--OLD-->
  <main class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0">
    <!--
    <header>
        Search Bar
      <div class="searchContainer">
        <form action="{{ route('products') }}" method="GET" role="search" class="d-flex">
          <div class="searchItem">
            <input type="text" name="term" placeholder="Search Products" id="term" class="searchField">
          </div>
        </form>
      </div>
      
      
      
      
    </header>
      <div class="buttonMenu">
        <div class="header">
          <div class="input-group">
            <div class="buttons">
              <a class="button" href="{{ url('/categories') }}"> Categories </a>
              <a class="button" href="{{ url('/products') }}"> Products </a>
              <a class="button" href="{{ route('help') }}"> Help</a>
              <a class="button" href="{{ url('/about') }}"> About Us</a>
            </div>
          </div>
          
        </div>
      </div>
    -->

    @yield('content')
    </section>

  </main>

</body>

</html>