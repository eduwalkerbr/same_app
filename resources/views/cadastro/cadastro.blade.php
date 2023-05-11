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
                                        Administração
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;">
                                            <a class="dropdown-item" href="{{ route('user.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Usuário
                                            </a>
                                            <a class="dropdown-item" href="{{ route('user.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Usuário
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('tipo_solicitacao.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Tipo Solicitação
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_tipo_solicitacao') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Tipo Solicitação
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('funcao.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Função
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_funcao') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Funcao
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('lista_sugestoes') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Sugestões
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('legenda.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Legenda
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_legenda') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Legenda
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('anosame.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Ano SAME
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_anosame') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Ano SAME
                                            </a>
                                            <!--<hr>
                                            <a class="dropdown-item" href="{{ route('termo.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Termo
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_termo') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Termo
                                            </a>-->
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('lista_manutencao') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Manutenção
                                            </a>
                                        </div>
                                    </li>
                                    @endif
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" style="color: black;" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;" v-pre onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                            Localização
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;">
                                            <a class="dropdown-item" href="{{ route('municipio.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Município
                                            </a>
                                            <a class="dropdown-item" href="{{ route('municipio.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Município
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('escola.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Escola
                                            </a>
                                            <a class="dropdown-item" href="{{ route('escola.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Escola
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('turma.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
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
                                            <a class="dropdown-item" href="{{ route('aluno.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
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
                                            <a class="dropdown-item" href="{{ route('disciplina.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Disciplina
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_disciplina') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Disciplina
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('habilidade.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Habilidade
                                            </a>
                                            <a class="dropdown-item" href="{{ route('habilidade.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Habilidade
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('tema.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Tema
                                            </a>
                                            <a class="dropdown-item" href="{{ route('tema.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Tema
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('destaque.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Destaque
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_destaque') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Destaque
                                            </a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" style="color: black;" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;" v-pre onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                            Gestão Escolar
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;">
                                            <a class="dropdown-item" href="{{ route('previlegio.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Privilégio
                                            </a>
                                            <a class="dropdown-item" href="{{ route('previlegio.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Privilégio
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('direcao_professor.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Direção Professor
                                            </a>
                                            <a class="dropdown-item" href="{{ route('direcao_professor.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Direção Professor
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('turma_previa.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Turma Prévia
                                            </a>
                                            <a class="dropdown-item" href="{{ route('turma_previa.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Turma Prévia
                                            </a>
                                        </div>
                                    </li>
                                    <li class="nav-item dropdown">
                                        <a id="navbarDropdown" style="color: black;" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:black;" v-pre onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                            Prova
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;">
                                            <a class="dropdown-item" href="{{ route('prova_gabarito.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Gabarito
                                            </a>
                                            <a class="dropdown-item" href="{{ route('prova_gabarito.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Gabarito
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('questao.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Questão
                                            </a>
                                            <a class="dropdown-item" href="{{ route('questao.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Questão
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('tipoquestao.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Tipo de Questão
                                            </a>
                                            <a class="dropdown-item" href="{{ route('lista_tipoquestao') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Tipo de Questão
                                            </a>
                                            <hr>
                                            <a class="dropdown-item" href="{{ route('criterios_questao.create') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Cadastro - Critérios da Questão
                                            </a>
                                            <a class="dropdown-item" href="{{ route('criterios_questao.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor=" black";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                                Listagem - Critérios da Questão
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

<!---------------------------------------------- Aluno -------------------------------------------------->    
<script type="text/javascript">
    $("#SAME").change(function() {
        $.ajax({
            url: "{{ route('aluno.get_by_same') }}?SAME=" + $(this).val(),
            method: 'GET',
            success: function(data) {
                $('#turmas_escolas_municipios_id').html(data.html);
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
<!-------------------------------------------------------------------------------------------------------->

<!-------------------------------------------- Município -------------------------------------------------> 
<script type="text/javascript">
    $("#SAME").change(function() {
        $.ajax({
            url: "{{ route('municipio.get_by_same') }}?SAME=" + $(this).val(),
            method: 'GET',
            success: function(data) {
                $('#municipios_id').html(data.html);
            }
        });
    });
</script>
<!-------------------------------------------------------------------------------------------------------->

<!-------------------------------------------- Escolas ---------------------------------------------------> 
<script type="text/javascript">
    $("#municipios_id").change(function() {
        $.ajax({
            url: "{{ route('escola.get_by_municipio') }}?municipios_id=" + $(this).val(),
            method: 'GET',
            success: function(data) {
                $('#escolas_id').html(data.html);
            }
        });
    });
</script>
<!-------------------------------------------------------------------------------------------------------->

<!-------------------------------------------- Turmas ----------------------------------------------------> 
<script type="text/javascript">
    $("#escolas_id").change(function() {
        $.ajax({
            url: "{{ route('turma.get_by_same_escola') }}?escolas_id=" + $(this).val(),
            method: 'GET',
            success: function(data) {
                $('#turmas_id').html(data.html);
            }
        });
    });
</script>
<!-------------------------------------------------------------------------------------------------------->