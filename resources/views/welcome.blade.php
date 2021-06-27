<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@latest/dist/css/splide.min.css">     <!-- Styles -->
    <link  href="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="manifest" href="manifest.json" />

    <style>
    
        @font-face {
            font-family: 'AraJozoor';
            src: url("{{ asset('fonts/AraJozoor-Regular.ttf') }}");
        }   
        #app {
            font-family: 'AraJozoor' !important;
        }
        body {
            font-family: 'AraJozoor';
        }
    </style>

</head>

<body class="antialiased">
   

    <div id="app">
        <index :settings="{{ $settings }}"></index>
    </div>
  


    <!-- jQuery 1.8 or later, 33 KB -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

    <!-- Fotorama from CDNJS, 19 KB -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fotorama/4.6.4/fotorama.js"></script>
    <script src="{{ mix('js/app.js') }}" defere></script>

</body>

</html>
