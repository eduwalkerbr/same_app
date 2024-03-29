<nav class="navbar fixed-top navbar-expand-lg navbar-light" style="padding: 0.5em 0 0.5em 0; background-color: white;box-shadow: 5px 5px 5px rgba(0,0,139);font-size: 14px;">
    <div class="container">
        <div class="col-md-2" style="background-color: white;border: 1px solid white;">
            <a class="navbar-brand" href="{{route('home.index')}}">
                <img src="{{ asset('img/logo.png') }}" width="90" height="90" class="d-inline-block align-center" alt="" loading="lazy"></a>
            </a>
        </div>
        <div class="col-md-10" style="background-color: white;border: 1px solid white;padding-left:0px;font-size: 14px;">
            <div class="row justify-content-center" style="font-weight:bold;">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    
                    <!-- Sessão de Solicitações -->
                    @include('layouts/menu_superior.sessao_base')

                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="{{ route('secretario.index') }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                            Proficiências
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;">
                                <a class="dropdown-item" href="{{ route('secretario_comparativo.exibirMunicipioComparativo',['id' => $municipio_selecionado[0]->id, 'id_disciplina' => $disciplina_selecionada[0]->id, 'sessao' => 'municipio_comparativo']) }}" style="color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                    Comparativos
                                </a>
                            </div>
                        </li>
                    </ul>
                    
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
            <!------------------------------------ Seleção de Município, Escola, Turma e Disciplina ------------------->
            <div class="row justify-content-center" style="background-color: #0046AD;border: 1px solid #0046AD;color:white;box-shadow: 3px 3px 3px rgba(0,0,139);padding: 0.0em 0.0em;font-size: 14px;">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent2" aria-controls="navbarSupportedContent2" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent2">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-4">
                        <!------------------------------------ Município ------------------->
                        @if(session('previlegio')->funcaos_id == 6 || session('previlegio')->funcaos_id == 8 || Auth::user()->perfil == 'Administrador' || ((session('previlegio')->funcaos_id == 13 || session('previlegio')->funcaos_id == 14) && session('previlegio')->municipios_id == 5))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="" role="button" aria-expanded="false" style="color:white;">{{$municipio_selecionado[0]->nome ?? 'Município'}}</a>
                            <ul class="dropdown-menu navbar-nav-scroll" style="max-height:250px;overflow-y:auto;">
                                @foreach($municipios as $municipio)
                                <li><a class="dropdown-item" style="color:black;" href="{{ route('secretario.exibirMunicipio', ['id' => $municipio->id, 'id_disciplina' => $disciplina_selecionada[0]->id, 'ano_same' => $ano_same_selecionado]) }}">{{ $municipio->nome ?? ''}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                    </ul>
                    <ul class="navbar-nav mr-4">
                        <!------------------------------------ Escola ------------------->
                        @if(session('previlegio')->funcaos_id == 5 || session('previlegio')->funcaos_id == 6 || session('previlegio')->funcaos_id == 8 || Auth::user()->perfil == 'Administrador' || ((session('previlegio')->funcaos_id == 13 || session('previlegio')->funcaos_id == 14) && session('previlegio')->municipios_id == 5))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="" role="button" aria-expanded="false" style="color:white;">{{$escola_selecionada[0]->nome ?? 'Escola'}}</a>
                            <ul class="dropdown-menu navbar-nav-scroll" style="max-height:250px;overflow-y:auto;">
                                @foreach($escolas as $escola)
                                <li><a class="dropdown-item" style="color:black;" href="{{ route('diretor.exibirEscola', ['id' => $escola->id, 'id_municipio' => $municipio_selecionado[0]->id , 'id_disciplina' => $disciplina_selecionada[0]->id, 'ano_same' => $ano_same_selecionado]) }}">{{ $escola->nome ?? ''}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                    </ul>
                    <ul class="navbar-nav mr-4">
                        <!------------------------------------ Turma ------------------->
                        @isset($turmas)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="" role="button" aria-expanded="false" style="color:white;">{{$turma_selecionada[0]->DESCR_TURMA ?? 'Turma'}}</a>
                            <ul class="dropdown-menu navbar-nav-scroll" style="max-height:250px;overflow-y:auto;">
                                @foreach($turmas as $turma)
                                <li><a class="dropdown-item" style="color:black;" href="{{ route('professor.exibirTurma', ['id' => $turma->id, 'id_disciplina' => $disciplina_selecionada[0]->id, 'id_escola' => $escola_selecionada[0]->id, 'ano_same' => $ano_same_selecionado]) }}">{{ $turma->DESCR_TURMA ?? ''}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @endisset
                    </ul>
                    <ul class="navbar-nav mr-auto">
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <!------------------------------------ Sessão Disciplina ------------------->
                    <ul class="navbar-nav ml-4">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="" role="button" aria-expanded="false" style="color:white;">{{strval($ano_same_selecionado) ?? 'Anos'}}</a>
                            <ul class="dropdown-menu navbar-nav-scroll" style="max-height:250px;overflow-y:auto;">
                                @foreach($anos_same as $ano_same)
                                <li><a class="dropdown-item" style="color:black;" href="{{ route('secretario.exibirMunicipio', ['id' => $municipio_selecionado[0]->id, 'id_disciplina' => $disciplina_selecionada[0]->id, 'ano_same' => $ano_same->SAME]) }}">{{ $ano_same->SAME ?? ''}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                    <ul class="navbar-nav ml-4">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="" role="button" aria-expanded="false" style="color:white;">{{$disciplina_selecionada[0]->desc ?? 'Disciplina'}}</a>
                            <ul class="dropdown-menu">
                                @foreach($disciplinas as $disciplina)
                                <li><a class="dropdown-item" style="color:black;" href="{{ route('secretario.exibirMunicipio', ['id' => $municipio_selecionado[0]->id, 'id_disciplina' => $disciplina->id, 'ano_same' => $ano_same_selecionado]) }}">{{ $disciplina->desc ?? ''}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                </div>
            </div>
        </div>
    </div>
</nav>