@php
$previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
@endphp
@if (($previlegio->funcaos_id == 6 || Auth::user()->perfil == 'Administrador') && ( ((isset($solRegistro) && count($solRegistro) > 0)) || ((isset($solAltCadastral) && count($solAltCadastral) > 0)) || ((isset($solAddTurma) && count($solAddTurma) > 0))))
<ul class="navbar-nav mr-auto">
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
            Solicitações Pendentes <span class="badge badge-danger" style="background-color:red;color: white; font-weight: normal; font-size: 12px;">{{sizeof($solRegistro) + sizeof($solAltCadastral) + sizeof($solAddTurma)}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;">
            @if(isset($solRegistro) && count($solRegistro) > 0)
            <a class="dropdown-item" href="{{ route('lista_registros_usuario') }}" style="color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                Registros de Usuário <span class="badge badge-danger" style="background-color:red;color: white; font-weight: normal; font-size: 12px;"> {{sizeof($solRegistro)}}</span>
            </a>
            @endif
            @if(isset($solAltCadastral) && count($solAltCadastral) > 0)
            <a class="dropdown-item" href="" style="color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                Alteração Cadastral <span class="badge badge-danger" style="background-color:red;color: white; font-weight: normal; font-size: 12px;"> {{sizeof($solAltCadastral)}}</span>
            </a>
            @endif
            @if(isset($solAddTurma) && count($solAddTurma) > 0)
            <a class="dropdown-item" href="{{ route('lista_solicitacao_turma') }}" style="color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                Adição de Turmas <span class="badge badge-danger" style="background-color:red;color: white; font-weight: normal; font-size: 12px;"> {{sizeof($solAddTurma)}}</span>
            </a>
            @endif
        </div>
    </li>

</ul>
@endif