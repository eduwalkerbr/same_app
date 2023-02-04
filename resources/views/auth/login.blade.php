@extends('layouts.applogin')

@section('content')
@if (session('status'))
<script>
    alert("{{ session('status') }}");
</script>
@endif
<div class="container" >
    <div class="row justify-content-center" style="margin-bottom:30px;margin-top:100px;" 
             data-bs-toggle="tooltip" data-bs-placement="right" 
             title="SAME é um Sistema de Avaliação Municipal da Educação Básica, organizado pela UNIJUÍ - Universidade Regional do Noroeste do Estado do Rio Grando do Sul">
        <div class=" col-md-8 align-self-center">
            <div class="card">
                <div class="card-body">
                        <h4 style="font-weight: bolder; font-size: 18px; color: #f9821E; margin-bottom: 20px;">
                                 SEJA BEM VINDO(A) A PLATAFORMA SAME!
                        </h4>
                    <h6 style="color: black; font-weight: bold;">Para usufluir dos resultados e análises da Plataforma do SAME, siga as etapas abaixo, que a equipe gestora do vosso município, analisará os acessos possíveis.</h6>
                    <h6 style="color: black; font-weight: bold;">Siga as etapas abaixo para solicitar acesso:</h6>
                    <h6> - Registro (caso ainda não o tenha feito)</h6>
                    <h6> - Aguardar as devidas autorizações</h6>
                    <h6> - Login</h6>
                    <h6 style="color: black; font-weight: bold;">Estas etapas podem ser efetuadas nos campos abaixo ou no Menu Superior Direito</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8 align-self-center">
            <div class="card" style="box-shadow: 5px 5px 5px rgba(0,0,139);">
                <div class="card-header" style="background-color: #0046AD; color:white;">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right" style="color:black;">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right" style="color:black;">{{ __('Senha') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check" style="color:black;">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Lembrar Senha') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-success" style="background-color:#f9821E; border-color:#f9821E;">
                                    {{ __('Entrar') }}
                                </button>

                                @if (Route::has('password.request'))
                                <a class="btn btn-link" style="color:#f9821E;" href="{{ route('password.request') }}">
                                    {{ __('Esqueceu a senha?') }}
                                </a>
                                @endif
                                <a class="btn btn-link" style="color:#f9821E;" href="{{ route('registro_base.index') }}">
                                    {{ __('Novo Usuário') }}
                                </a>
                            </div>

                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-6 offset-md-2">
                                <p style="color: black; font-weight: normal; font-size: 13px; margin-top: 20px; margin-bottom: 5px; text-align: center;">Ao realizar o Registro na plataforma, estará de acordo com o Termo de Confidencialidade e Sigilo abaixo:
                                </p>
                            </div>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-md-6 offset-md-3">
                                <a style="text-decoration: none;text-align: center;color:#0046AD;" href="{{ asset('termo/Termo.pdf') }}" target="_blank">
                                    <i class="fa-solid fa-book-bookmark"></i>Acessar Termo de Confidencialidade e Sigilo do usuário
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>    

@endsection