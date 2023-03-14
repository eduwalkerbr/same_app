<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/delete.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- our project just needs Font Awesome Solid + Brands -->
    <link href="{{ asset('fontawesome-free-6.0.0-web/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome-free-6.0.0-web/css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome-free-6.0.0-web/css/solid.css') }}" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <!------------------------------------ Menu Superior ------------------->
        <header>
            <div class="container">
                <div class="row justify-content-center">
                    <nav class="navbar fixed-top navbar-expand-lg navbar-light" style="padding: 0.5em 0 0.5em 0; background-color: white;box-shadow: 5px 5px 5px rgba(0,0,139);font-size: 17px;">
                        <div class="container">
                            <a class="navbar-brand" href="{{route('home.index')}}">
                                <img src="{{ asset('img/logo.png') }}" width="70" height="70" class="d-inline-block align-center" alt="" loading="lazy"></a>
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent" style="font-weight:bold;font-size:15px;">
                                <!-- Left Side Of Navbar -->
                                
                                <!-- Sessão de Solicitações -->
                                @include('layouts/menu_superior.sessao_base')

                                <!-- Right Side Of Navbar -->
                                @include('layouts/menu_superior.sessao_usuario')
                                
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </header>

        <main class="py-4">
            @yield('content')
            @if (session('status'))
            <script>
                alert("{{ session('status') }}");
            </script>
            @endif
        </main>
        <!------------------------------------ Rodapé ------------------->
        @include('layouts/_parciais.footer')
    </div>
</body>

</html>