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
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- our project just needs Font Awesome Solid + Brands -->
    <link href="{{ asset('fontawesome-free-6.0.0-web/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome-free-6.0.0-web/css/brands.css') }}" rel="stylesheet">
    <link href="{{ asset('fontawesome-free-6.0.0-web/css/solid.css') }}" rel="stylesheet">


    <!-- JavaScript Bundle with Popper -->




    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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

                            <div class="collapse navbar-collapse" id="navbarSupportedContent" style="font-weight:bold;font-size:14px;">
                                <!-- Left Side Of Navbar -->
                                
                                <!-- Sessão de Solicitações -->
                                @include('layouts/menu_superior.sessao_base')

                                <!-- Sessão de Solicitações -->
                                @include('layouts/menu_superior.sessao_solicitacoes')

                                <!-- Sessão de Solicitação de Turma -->
                                @include('layouts/menu_superior.solicitacao_turma')

                                <!-- Sessão de Gestão Escolar -->
                                @include('layouts/menu_superior.sessao_gestao_escolar')

                                <!-- Right Side Of Navbar -->
                                @include('layouts/menu_superior.sessao_usuario')

                            </div>
                        </div>
                    </nav>
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

<script src="{{url('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js')}}"></script>

<!------------------------------------ Ajax Escolas e Turmas ------------------->
<script type="text/javascript">
    $("#id_municipio").change(function() {
        $.ajax({
            url: "{{ route('solicitacao_turma.get_by_municipio') }}?municipio_id=" + $(this).val(),
            method: 'GET',
            success: function(data) {
                $('#id_escola').html(data.html);
                $('#id_turma').html('<option value=""></option>');
            }
        });
    });
</script>

<script type="text/javascript">
    $("#id_escola").change(function() {
        $.ajax({
            url: "{{ route('solicitacao_turma.get_by_escola') }}?escola_id=" + $(this).val(),
            method: 'GET',
            success: function(data) {
                $('#id_turma').html(data.html);
            }
        });
    });
</script>