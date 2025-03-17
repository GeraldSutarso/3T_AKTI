<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
          crossorigin="anonymous">

    <title>3T | Evaluasi</title>
    <link rel="icon" type="image/png" href="{{ asset('img/Favicon akti.png') }}">

    <!-- Yield styles -->
    @yield('styles')

    <!-- Vite (For Vue & JS) -->
    @vite(['resources/js/app.js'])
</head>
<body style="background-color: rgb(255, 255, 255)">

    @include('partial/navbar')

    <!-- Dynamic Content -->
    <div class="container mt-4">
        <div id="app">  <!-- Vue needs this to work -->
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>

    <!-- Yield Scripts -->
    @yield('scripts')

</body>
</html>
