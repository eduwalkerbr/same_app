@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="margin-bottom:30px;margin-top: 130px;">
        <div class="col-md-8 align-self-center">
            <div class="card">
                <div class="card-body">
                    <h4 style="font-weight: bolder; font-size: 18px; color: #f9821E; margin-bottom: 20px;">REDEFINA SUA SENHA !</h4>
                    <h6 style="color: black; font-weight: normal;"> - Insira seu e-mail cadastrado</h6>
                    <h6 style="color: black; font-weight: normal;"> - Nesta sessão você pode alterar seu nome e senha</h6>
                    <h6 style="color: black; font-weight: normal;"> - Caso deseja alterar seu e-mail, será necessário realizar uma nova solicitação de Registro</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card" style="box-shadow: 5px 5px 5px rgba(0,0,139);">
                    <div class="card-header" style="background-color: #0046AD; color:white;">{{ __('Registro') }}</div>
                    <div class="card-body">
                        <form id="form_edit_registro" name="form_edit_registro" action="{{ route('registro.update',Auth::user()->id) }}" method="post" enctype="multipart/form-data">

                            @method('PUT')
                            @csrf
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" value="{{ Auth::user()->email }}" autocomplete="email" autofocus readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ Auth::user()->name }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Senha') }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirme a Senha') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="hidden" name="perfil" id="perfil" value="{{ Auth::user()->perfil }}">
                            </div>

                            <div class="form-group row mb-0" style="margin-top:30px;">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary" style="background-color:#f9821E; border-color:#f9821E;">
                                        {{ __('Redefinir Senha') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection