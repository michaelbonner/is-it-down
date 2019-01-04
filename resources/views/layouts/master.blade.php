<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google" content="notranslate">

        <title>@yield('title', 'Is It Down Checker')</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.7.1/css/bulma.min.css">
        <style>
            a {
                word-break: break-all;
            }
        </style>
        <script defer src="https://use.fontawesome.com/releases/v5.1.0/js/all.js"></script>

    </head>
    <body>
        <div class="flex-center position-ref full-height">
            @include('layouts.partials.alerts')
            
            @yield('content')
        </div>


        <script
            src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>

        @yield('js')
    </body>
</html>
