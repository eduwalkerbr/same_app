@extends('layouts.applogin')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="margin-bottom:30px;margin-top: 150px;">
        <div class="col-md-8 align-self-center">
            <div class="card">
                <div class="card-body">
                    <h4 style="font-weight: bolder; font-size: 18px; color: #f9821E; margin-bottom: 20px;">RECUPERAR SENHA !</h4>
                    <h6 style="color: black; font-weight: normal;"> - Insira seu e-mail cadastrado</h6>
                    <h6 style="color: black; font-weight: normal;"> - Você receberá um e-mail com as orientações para recuperação de sua senha</h6>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8 align-self-center">


            <div class="card" style="box-shadow: 5px 5px 5px rgba(0,0,139);">

                <div class="card-header" style="background-color: #0046AD; color:white;">{{ __('Redefinir senha') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" style="background-color:#f9821E; border-color:#f9821E;">
                                    {{ __('Enviar link de redefinição de senha') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection