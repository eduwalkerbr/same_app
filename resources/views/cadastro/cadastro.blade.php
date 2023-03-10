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

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</head>

<body>
    <div id="app">
        <header>
            <div class="container">
                <div class="row justify-content-center">
                    <nav class="navbar fixed-top navbar-expand-lg navbar-light" style="padding: 0.5em 0 0.5em 0; background-color: white;box-shadow: 5px 5px 5px rgba(0,0,139);">
                        <div class="container">
                            <a class="navbar-brand" href="{{route('home.index')}}">
                                <img src="{{ asset('img/logo.png') }}" width="70" height="70" class="d-inline-block align-center" alt="" loading="lazy"></a>
                            </a>
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                                <span class="navbar-toggler-icon"></span>
                            </button>

                            <div class="collapse navbar-collapse" id="navbarSupportedContent" style="font-size: 14px;font-weight:bold;">
                                <!-- Left Side Of Navbar -->
                                <ul class="navbar-nav mr-auto">
                                    @if(Auth::check() && Auth::user()->perfil == 'Administrador')
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" style="color: black;" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;" v-pre onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                        Administra????o
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;">
                                            <a class="dropdown-item" href="{{ route('cadastro_user') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Usu??rio
                                            </a>
                                            <a class="dropdown-item" href="{{ route('user.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Usu??rio
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_tipo_solicitacao') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Tipo Solicita????o
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_tipo_solicitacao') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Tipo Solicita????o
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_funcao') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Fun????o
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_funcao') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Funcao
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('lista_sugestoes') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Sugest??es
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_legenda') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Legenda
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_legenda') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Legenda
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_anosame') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Ano SAME
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_anosame') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Ano SAME
                                            </a>
                                            <!--<hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_termo') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Termo
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_termo') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Termo
                                            </a>-->
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('lista_manutencao') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Manuten????o
                                            </a>
                                        </div>
                                    </li>
                                    @endif
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" style="color: black;" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;" v-pre onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                            Localiza????o
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;">
                                            <a class="dropdown-item" href="{{ route('cadastro_municipio') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Munic??pio
                                            </a>
                                            <a class="dropdown-item" href="{{ route('municipio.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Munic??pio
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_escola') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Escola
                                            </a>
                                            <a class="dropdown-item" href="{{ route('escola.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Escola
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_turma') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Turma
                                            </a>
                                            <a class="dropdown-item" href="{{ route('turma.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Turma
                                            </a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" style="color: black;" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;" v-pre onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                            Alunos
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;">
                                            <a class="dropdown-item" href="{{ route('cadastro_aluno') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro
                                            </a>
                                            <a class="dropdown-item" href="{{ route('aluno.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem
                                            </a>
                                        </div>
                                    </li>

                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" style="color: black;" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;" v-pre onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                            Curriculares
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;">
                                            <a class="dropdown-item" href="{{ route('cadastro_disciplina') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Disciplina
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_disciplina') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Disciplina
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_habilidade') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Habilidade
                                            </a>
                                            <a class="dropdown-item" href="{{ route('habilidade.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Habilidade
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_tema') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Tema
                                            </a>
                                            <a class="dropdown-item" href="{{ route('tema.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Tema
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_destaque') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Destaque
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_destaque') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Destaque
                                            </a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" style="color: black;" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;" v-pre onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                            Gest??o Escolar
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;">
                                            <a class="dropdown-item" href="{{ route('cadastro_previlegio') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Privil??gio
                                            </a>
                                            <a class="dropdown-item" href="{{ route('previlegio.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Privil??gio
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_direcao_professor') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Dire????o Professor
                                            </a>
                                            <a class="dropdown-item" href="{{ route('direcao_professor.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Dire????o Professor
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_turma_previa') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Turma Pr??via
                                            </a>
                                            <a class="dropdown-item" href="{{ route('turma_previa.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Turma Pr??via
                                            </a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" style="color: black;" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;" v-pre onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                            Prova
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;">
                                            <a class="dropdown-item" href="{{ route('cadastro_prova_gabarito') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Gabarito
                                            </a>
                                            <a class="dropdown-item" href="{{ route('prova_gabarito.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Gabarito
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_questao') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Quest??o
                                            </a>
                                            <a class="dropdown-item" href="{{ route('questao.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Quest??o
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_tipoquestao') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Tipo de Quest??o
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_tipoquestao') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Tipo de Quest??o
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('cadastro_criterios_questao') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Crit??rios da Quest??o
                                            </a>
                                            <a class="dropdown-item" href="{{ route('criterios_questao.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Crit??rios da Quest??o
                                            </a>
                                        </div>
                                    </li>
                                </ul>

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
    </div>
</body>

</html>

<script src="{{url('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.js')}}"></script>


<script type="text/javascript">
    $("#id_escola").change(function() {
        $.ajax({
            url: "{{ route('turma_previa.get_by_escola') }}?id_escola=" + $(this).val(),
            method: 'GET',
            success: function(data) {
                $('#id_turma').html(data.html);
            }
        });
    });
</script>

<script type="text/javascript">
    $("#turmas_escolas_municipios_id").change(function() {
        $.ajax({
            url: "{{ route('aluno.get_by_municipio') }}?turmas_escolas_municipios_id=" + $(this).val(),
            method: 'GET',
            success: function(data) {
                $('#turmas_escolas_id').html(data.html);
            }
        });
    });
</script>

<script type="text/javascript">
    $("#turmas_escolas_id").change(function() {
        $.ajax({
            url: "{{ route('aluno.get_by_escola') }}?turmas_escolas_id=" + $(this).val(),
            method: 'GET',
            success: function(data) {
                $('#turmas_id').html(data.html);
            }
        });
    });
</script>

<script type="text/javascript">
    $("#SAME").change(function() {
        $.ajax({
            url: "{{ route('escola.get_by_same_municipio') }}?SAME=" + $(this).val(),
            method: 'GET',
            success: function(data) {
                $('#municipios_id').html(data.html);
            }
        });
    });
</script>

<script type="text/javascript">
    $("#SAME").change(function() {
        $.ajax({
            url: "{{ route('escola.get_by_same_escola') }}?SAME=" + $(this).val(),
            method: 'GET',
            success: function(data) {
                $('#escolas_id').html(data.html);
            }
        });
    });
</script>

<script type="text/javascript">
    $("#SAME").change(function() {
        $.ajax({
            url: "{{ route('aluno.get_by_same_escolav2') }}?SAME=" + $(this).val(),
            method: 'GET',
            success: function(data) {
                $('#turmas_escolas_id').html(data.html);
            }
        });
    });
</script>

<script type="text/javascript">
    $("#SAME").change(function() {
        $.ajax({
            url: "{{ route('direcao_professor.get_by_same_escolav3') }}?SAME=" + $(this).val(),
            method: 'GET',
            success: function(data) {
                $('#id_escola').html(data.html);
            }
        });
    });
</script>