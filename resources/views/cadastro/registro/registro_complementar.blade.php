@extends('layouts.appRegistro')

@section('content')
<div class="container">
    <div class="row justify-content-center" style="margin-top: 110px;">
        <div class="col-md-8 align-self-center">
            <div class="card">
                <div class="card-body">
                    <h4 style="font-weight: bolder; font-size: 18px; color: #f9821E; margin-bottom: 20px;">FINALIZANDO SEU REGISTRO !</h4>
                    <h6 style="color: black; font-weight: bold;"> - Complemente seu registro com informações de localização</h6>
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
                        <div class=" form-group row">
                            <label for="municipio" class="col-md-4 col-form-label text-md-right">{{ __('Município') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" id="id_municipio" name="id_municipio" required>
                                    <option value=""></option>
                                    @foreach($municipios as $municipio)
                                    <option value="{{ $municipio->id.'_'.$municipio->SAME }}">{{ $municipio->nome.' ('.$municipio->SAME.')'  ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if(isset($funcao) && ($funcao->desc != 'Secretaria'))
                        <div class="form-group row">
                            <label for="id_escola" class="col-md-4 col-form-label text-md-right">{{ __('Escola') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" id="id_escola" name="id_escola" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        @endif

                        @if(isset($funcao) && $funcao->desc == 'Professor(a)')
                        <div class="form-group row">
                            <label for="id_turma" class="col-md-4 col-form-label text-md-right">{{ __('Turma') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" id="id_turma" name="id_turma" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <input type="hidden" name="name" id="name" value="{{ $data_base['name'] }}">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="descricao" id="descricao" value="Solicitação de Registro de Usuário">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="email" id="email" value="{{ $data_base['email'] }}">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="password" id="password" value="{{ $data_base['senha'] }}">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="id_funcao" id="id_funcao" value="{{ $funcao->id }}">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="id_tipo_solicitacao" id="id_tipo_solicitacao" value="1">
                        </div>
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary" style="background-color:#f9821E; border-color:#f9821E;">
                                    {{ __('Finalizar Registro') }}
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