@if ((isset($previlegio) && $previlegio->funcaos_id == 7) || Auth::user()->perfil == 'Administrador')
<ul class="navbar-nav mr-auto">
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
            Solicitações
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="font-size: 15px;color: black;">
            <a class="dropdown-item" href="{{ route('solicitacao_turma.index') }}" style="font-size: 15px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                Adição de Turma
            </a>
        </div>
    </li>
</ul>
@endif