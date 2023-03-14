@php
$previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
@endphp
@if ($previlegio->funcaos_id == 6 || Auth::user()->perfil == 'Administrador')
<ul class="navbar-nav mr-auto">
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
            Gestão Escolar
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;">
            <a class="dropdown-item" href="{{ route('gest_previlegio.filtrar') }}" style="font-size: 14px;color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                Privilégios
            </a>
            <a class="dropdown-item" href="{{ route('gest_direcao_professor.filtrar') }}" style="color: black;" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="black"'>
                Direção Professor
            </a>
        </div>
    </li>

</ul>
@endif