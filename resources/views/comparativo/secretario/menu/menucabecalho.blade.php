<nav class="navbar fixed-top navbar-expand-lg navbar-light" style="padding: 0.5em 0 0.5em 0; background-color: white;box-shadow: 5px 5px 5px rgba(0,0,139);font-size: 17px;">
    <div class="container">
        <div class="col-md-2" style="background-color: white;border: 1px solid white;">
            <a class="navbar-brand" href="{{route('home.index')}}">
                <img src="{{ asset('img/logo.png') }}" width="90" height="90" class="d-inline-block align-center" alt="" loading="lazy"></a>
            </a>
        </div>
        <div class="col-md-10" style="background-color: white;border: 1px solid white;;">
            <div class="row justify-content-center" style="font-weight:bold;">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a id="navbar" class="nav-link" href="{{ route('home.index')}}" role="button" aria-haspopup="true" aria-expanded="false" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                Home
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a id="navbar" class="nav-link" href="{{route('sobre.index')}}" role="button" aria-haspopup="true" aria-expanded="false" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                Sobre
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="{{ route('secretario_comparativo.index') }}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                            Comparativos
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="font-size: 15px;color: black;">
                                <a class="dropdown-item" href="{{ route('secretario.exibirMunicipio',['id' => $municipio_selecionado[0]->id , 'id_disciplina' => $disciplina_selecionada[0]->id, 'ano_same' => $ano_same_selecionado]) }}" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                    Proficiências
                                </a>
                            </div>
                        </li>
                    </ul>
                    @php
                    $previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
                    @endphp
                    @if (($previlegio->funcaos_id == 6 || Auth::user()->perfil == 'Administrador') && ( ((isset($solRegistro) && count($solRegistro) > 0)) || ((isset($solAltCadastral) && count($solAltCadastral) > 0)) || ((isset($solAddTurma) && count($solAddTurma) > 0))))
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                Solicitações Pendentes <span class="badge badge-danger" style="background-color:red;color: white; font-weight: normal; font-size: 12px;">{{sizeof($solRegistro) + sizeof($solAltCadastral) + sizeof($solAddTurma)}}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="font-size: 15px;color: black;">
                                @if(isset($solRegistro) && count($solRegistro) > 0)
                                <a class="dropdown-item" href="{{ route('lista_registros_usuario') }}" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                    Registros de Usuário <span class="badge badge-danger" style="background-color:red;color: white; font-weight: normal; font-size: 12px;"> {{sizeof($solRegistro)}}</span>
                                </a>
                                @endif
                                @if(isset($solAltCadastral) && count($solAltCadastral) > 0)
                                <a class="dropdown-item" href="" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                    Alteração Cadastral <span class="badge badge-danger" style="background-color:red;color: white; font-weight: normal; font-size: 12px;"> {{sizeof($solAltCadastral)}}</span>
                                </a>
                                @endif
                                @if(isset($solAddTurma) && count($solAddTurma) > 0)
                                <a class="dropdown-item" href="{{ route('lista_solicitacao_turma') }}" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                    Adição de Turmas <span class="badge badge-danger" style="background-color:red;color: white; font-weight: normal; font-size: 12px;"> {{sizeof($solAddTurma)}}</span>
                                </a>
                                @endif
                            </div>
                        </li>

                    </ul>
                    @endif
                    @include('layouts/menu.solicitacao_turma')
                    @if ($previlegio->funcaos_id == 6 || Auth::user()->perfil == 'Administrador')
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                Gestão Escolar
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="font-size: 15px;color: black;">
                                <a class="dropdown-item" href="{{ route('gest_previlegio.filtrar') }}" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                    Privilégios
                                </a>
                                <a class="dropdown-item" href="{{ route('gest_direcao_professor.filtrar') }}" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                    Direção Professor
                                </a>
                            </div>
                        </li>

                    </ul>
                    @endif
                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" style="font-size: 15px;color: black;" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                Convidado
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;font-size: 15px">
                                <a class="dropdown-item" href="{{ route('registro_base.index') }}" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                    Registrar
                                </a>
                                <hr>
                                <a class="dropdown-item" href="{{ route('login') }}" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>{{ __('Login') }}</a>
                            </div>

                        </li>
                        @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"' class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }}
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;font-size: 16px">
                                <a class="dropdown-item" href="{{ route('alterar_registro.index')}}" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                    Alterar Registro
                                </a>
                                <hr>
                                <a class="dropdown-item" style="font-size: 15px;color: black;" href="{{ route('home.index') }}" onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                                    {{ __('Logout') }}
                                </a>


                                <form id="logout-form" action="{{ route('deslogar') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                    </ul>
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
                        @php
                        $previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
                        @endphp
                        @if($previlegio->funcaos_id == 6 || $previlegio->funcaos_id == 8 || Auth::user()->perfil == 'Administrador' || (($previlegio->funcaos_id == 13 || $previlegio->funcaos_id == 14) && $previlegio->municipios_id == 5))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="" role="button" aria-expanded="false" style="color:white;">{{$municipio_selecionado[0]->nome ?? 'Município'}}</a>
                            <ul class="dropdown-menu">
                                @foreach($municipios as $municipio)
                                <li><a class="dropdown-item" style="color:black;" href="{{ route('secretario_comparativo.exibirMunicipioComparativo', ['id' => $municipio->id, 'id_disciplina' => $disciplina_selecionada[0]->id, 'sessao' => 'municipio_comparativo']) }}">{{ $municipio->nome ?? ''}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                    </ul>
                    <ul class="navbar-nav mr-4">
                        <!------------------------------------ Escola ------------------->
                        @php
                        $previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
                        @endphp
                        @if($previlegio->funcaos_id == 5 || $previlegio->funcaos_id == 6 || $previlegio->funcaos_id == 8 || Auth::user()->perfil == 'Administrador' || (($previlegio->funcaos_id == 13 || $previlegio->funcaos_id == 14) && $previlegio->municipios_id == 5))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="" role="button" aria-expanded="false" style="color:white;">{{$escola_selecionada[0]->nome ?? 'Escola'}}</a>
                            <ul class="dropdown-menu">
                                @foreach($escolas as $escola)
                                <li><a class="dropdown-item" style="color:black;" href="{{ route('diretor_comparativo.exibirEscolaComparativo', ['id' => $escola->id, 'id_municipio' => $municipio_selecionado[0]->id , 'id_disciplina' => $disciplina_selecionada[0]->id, 'sessao' => 'escola_comparativo']) }}">{{ $escola->nome ?? ''}}</a></li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                    </ul>
                    <ul class="navbar-nav mr-auto">
                    </ul>
                    <!-- Right Side Of Navbar -->
                    <!------------------------------------ Sessão Disciplina ------------------->
                    <ul class="navbar-nav ml-4">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="" role="button" aria-expanded="false" style="color:white;">{{$disciplina_selecionada[0]->desc ?? 'Disciplina'}}</a>
                            <ul class="dropdown-menu">
                                @foreach($disciplinas as $disciplina)
                                <li><a class="dropdown-item" style="color:black;" href="{{ route('secretario_comparativo.exibirMunicipioComparativo', ['id' => $municipio_selecionado[0]->id, 'id_disciplina' => $disciplina->id, 'sessao' => 'municipio_comparativo']) }}">{{ $disciplina->desc ?? ''}}</a></li>
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