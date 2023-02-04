@extends('layouts.app')

@section('content')
<div class="container" style="height: 100vh;">
    <div class="row justify-content-center" style="margin-top: 80px;">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="navbar-brand" href="#" style="margin-top: 50px; font-weight: bolder; font-size: 18px;">
                <h2 style="font-weight: bolder; font-size: 35px; color: black; margin-bottom: 20px;">SEJA BEM VINDO AO SAME! - welcome</h2>
                <h6 style="color: black; font-weight: bold;">Para usufruir de todos os recusos do SAME, siga as etapas abaixo:</h6>
                <h6> - Registro (caso ainda não o tenha feito)</h6>
                <h6> - Aguardar as devidas autorizações</h6>
                <h6> - Login</h6>
                <h6 style="color: black; font-weight: bold;margin-bottom: 20px;">Pode executar as mesmas nos campos abaixo ou no Menu Superior Direito</h6>
            </a>
        </nav>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection