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
    html {
      height: 100%;
    }

    footer {
      background-color: black;
      position: absolute;
      right: 0;
      bottom: 0;
      left: 0;
      height: 200px;
      color: white;
      display: none;
    }


    body {
      position: relative;
      margin: 0;
      padding-bottom: 200px;
      min-height: 100%;
      z-index: -999;
    }

    .column {
      float: left;
      width: 25%;
      padding: 10px;
      height: 200px;
      color: white;
      text-align: center;
    }

    /* Clear floats after the columns */
    .row:after {
      content: "";
      display: table;
      clear: both;
    }

    /* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
    @media screen and (max-width: 600px) {
      .column {
        width: 100%;
      }
    }

    h3 {
      color: white;
    }
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
        @if (Auth::user()->acctype == 'Admin')
        <a class="btn btn-outline-light" style="margin-left: 5px;" href="{{ route('user.id', ['id' => ($id = (auth()->user()->id ) ) ] ) }}"> Admin Tools </a>
        @else
        <a class="btn btn-outline-light" style="margin-left: 5px;" href="{{ route('user.id', ['id' => ($id = (auth()->user()->id ) ) ] ) }}"> User Profile </a>
        @endif
        <a class="btn btn-outline-light" style="margin-left: 5px;" href="{{ url('/logout') }}"> Logout </a> <span>{{ Auth::user()->name }}</span>
        @else
        <a class="btn btn-outline-light" style="margin-left: 5px;" href="{{ url('/login') }}"> Login </a>
        @endif
      </div>
    </div>
  </nav>


  @yield('content')


  </main>
  </section>
  <footer class="page-footer">
    <div class="column">
      <h3>Categories</h2>
    </div>
    <div class="column">
      <h3>User Profile</h2>
        <p>Edit Profile</p>
        <p>Order History</p>
        <p>Wishlist</p>
    </div>
    <div class="column">
      <h3>About Us</h2>
    </div>
    <div class="column">
      <h3>Helpdesk</h2>
        <p>Send Ticket</p>
        <p>FAQ</p>
    </div>
  </footer>
</body>

</html>