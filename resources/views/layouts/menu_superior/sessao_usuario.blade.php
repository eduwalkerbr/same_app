<ul class="navbar-nav ml-auto" style="font-weight:bold;color:#f9821E;">
    <!-- Authentication Links -->
    @guest
    <li class="nav-item dropdown">
        <a id="navbarDropdown" style="color: #f9821E;" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre onmouseover='this.style.backgroundColor="#f9821E";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#f9821E"'>
            Convidado
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: black;">
            <a class="dropdown-item" href="{{ route('registro_base.index') }}" onmouseover='this.style.backgroundColor="#f9821E";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#f9821E"'>
                Registrar
            </a>
            <hr>
            <a class="dropdown-item" href="{{ route('login') }}" onmouseover='this.style.backgroundColor="#f9821E";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#f9821E"'>{{ __('Login') }}</a>
        </div>

    </li>
    @else
    <li class="nav-item dropdown">
        <a id="navbarDropdown" style="color: #f9821E;" onmouseover='this.style.backgroundColor="#f9821E";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#f9821E"' class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }}
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown" style="color: #f9821E;">
            <a class="dropdown-item" href="{{ route('alterar_registro.index')}}" style="color: #f9821E;" onmouseover='this.style.backgroundColor="#f9821E";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#f9821E"'>
                Alterar Registro
            </a>
            <hr>
            <a class="dropdown-item" style="color: #f9821E;" href="{{ route('home.index') }}" onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();" onmouseover='this.style.backgroundColor="#f9821E";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#f9821E"'>
                {{ __('Logout') }}
            </a>


            <form id="logout-form" action="{{ route('deslogar') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </li>
    @endguest
</ul>