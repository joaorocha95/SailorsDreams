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
  <link href="{{ asset('css/milligram.min.css') }}" rel="stylesheet">
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    html {
      margin: 0;
      padding: 0;
    }

    body {
      color: #B9B9B9;
      font-family: 'Nunito', sans-serif;
      margin: 0;
      padding: 0;
    }

    h1 {
      width: fit-content;
    }

    header {
      margin-top: 0;
      padding: 0;
    }

    .buttons {
      text-align: center;
    }

    .searchContainer {
      position: absolute;
      width: 500px;
      height: 60px;
      display: table-cell;
      vertical-align: middle;
      left: 50%;
      top: 44px;
      -webkit-transform: translateX(-50%) translateY(-50%);
      -moz-transform: translateX(-50%) translateY(-50%);
      transform: translateX(-50%) translateY(-50%);
    }

    #term {
      margin: auto;
      display: block;
      width: 500px;
      border-color: #56565e;
    }


    .searchItem {
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      margin: auto;
      background-color: white;
      height: 38px;
      /*height: 20px;
      /*requires explicit height*/
    }


    .searchForm {
      display: flex;
      background-color: white;
    }

    .searchField {
      position: absolute;
      background-color: white;
      width: 100%;
      padding: 10px 0px 10px 0px;
      border: none;
      border-radius: 100px;
      outline: none;
    }

    #footer {
      position: fixed;
      background-color: #83dfe4;
      bottom: 0;
      width: 100%;
      min-height: 100px;
      /* Footer height */
    }

    .buttonMenu {
      margin-top: 20px;
      margin-bottom: 20px;
      height: 38px;
      width: 100%;
      background-color: rbg(0, 0, 0);
    }

    .button {
      margin-right: 2%;
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
  <main class="relative flex items-top justify-center min-h-screen sm:items-center py-4 sm:pt-0">
    <header>
      <h1><a href="{{ url('/home') }}">Sailor's Dream</a></h1>
      <!--Search Bar-->
      <div class="searchContainer">
        <form action="{{ route('products') }}" method="GET" role="search" class="searchForm">
          <div class="searchItem">
            <input type="text" name="term" placeholder="Search Products" id="term" class="searchField">
          </div>
        </form>
      </div>
      @if (Auth::check())
      <a class="button" href="{{ route('user.id', ['id' => ($id = (auth()->user()->id ) ) ] ) }}"> User Profile </a>
      <a class="button" href="{{ url('/logout') }}"> Logout </a> <span>{{ Auth::user()->name }}</span>
      @else
      <a class="button" href="{{ url('/login') }}"> Login </a>
      @endif



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


    @yield('content')
    </section>

  </main>
</body>

</html>