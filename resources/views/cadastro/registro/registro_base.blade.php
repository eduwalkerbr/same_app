@extends('layouts.appRegistro')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="margin-top: 110px;">
        <div class="col-md-8 align-self-center">
            <div class="card">
                <div class="card-body">
                    <h4 style="font-weight: bolder; font-size: 18px; color: #f9821E; margin-bottom: 20px;">INICIANDO SEU REGISTRO !</h4>
                    <h6 style="color: black; font-weight: bold;"> - Insira os dados Iniciais de Registro</h6>
                    <h6 style="color: black; font-weight: bold;"> - Selecione a Função</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center" style="margin-top: 40px;">
        <div class="col-md-8 align-self-center">
            <div class="card" style="box-shadow: 5px 5px 5px rgba(0,0,139);">
                <div class="card-header" style="background-color: #0046AD; color:white;">{{ __('Registro') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('registro_complementar') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Nome') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
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

                        <div class="form-group row">
                            <label for="id_funcao" class="col-md-4 col-form-label text-md-right">{{ __('Função') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" id="id_funcao" name="id_funcao" required>
                                    <option value=""></option>
                                    @foreach($funcoes as $funcao)
                                    <option value="{{ $funcao->id }}">{{ $funcao->desc ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4" style="padding-left: 35px;">
                                <input type="checkbox" class="form-check-input" id="exampleCheck1" required>
                                <label class="form-check-label" for="exampleCheck1"><a style="text-decoration: none;text-align: center;color:#0046AD;" href="{{ asset('termo/Termo.pdf') }}" target="_blank">
                                    <i class="fa-solid fa-book-bookmark"></i>Aceito o Termo de Confidencialidade e Sigilo do usuário de Usuário
                                    </a>
                                </label>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" style="background-color:#f9821E; border-color:#f9821E;">
                                    {{ __('Próxima Etapa') }}
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