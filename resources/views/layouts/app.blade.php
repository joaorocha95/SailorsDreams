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
    body {
      color: #B9B9B9;
      background-color: #343434;
      font-family: 'Nunito', sans-serif;
    }

    h1 {
      /*
      background-color: yellow;
      padding: 10px;
      */
    }

    .header {
      /*
      background-color: red;
      */
    }

    .buttons {
      /*
      background-color: green;
      margin-left: 15px;
      */
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

      <div class="header">
        <form action="{{ route('products') }}" method="GET" role="search">
          <div class="input-group">
            <input type="text" class="form-control mr-2" style="border-color: #56565e;" name="term" placeholder="Search Products" id="term">
            <button class="btn btn-info" type="submit" title="Search Products">
              <span class="fas fa-search"> Search </span>
            </button>

            <div class="buttons">
              <a class="button" href="{{ url('/categories') }}"> Categories </a>
              <a class="button" href="{{ url('/products') }}"> Products </a>

              @if (Auth::check())
              <a class="button" href="{{ route('user.id', ['id' => ($id = (auth()->user()->id ) ) ] ) }}"> User Profile </a>
              <a class="button" href="{{ url('/logout') }}"> Logout </a> <span>{{ Auth::user()->name }}</span>
              @else
              <a class="button" href="{{ url('/login') }}"> Login </a>
              @endif

              <a class="button" href="{{ route('help') }}"> Help</a>
              <a class="button" href="{{ url('/about') }}"> About Us</a>
            </div>

          </div>
        </form>

      </div>

    </header>
    <section id="content">
      @yield('content')
    </section>
  </main>
</body>

</html>