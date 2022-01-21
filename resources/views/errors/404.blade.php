<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    @section('title','404 Page Not Found')

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        html {
            font-family: 'Nunito', sans-serif;
            background-color: #343434;
            color: #B9B9B9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }
    </style>

</head>

<body>
    <div class="container mt-5 pt-5" style="text-align: center;">
        <h2 class="display-3">404 Error</h2>
        <p class="display-5">Oops! A página que procuras não existe.</p>
    </div>
</body>

</html>