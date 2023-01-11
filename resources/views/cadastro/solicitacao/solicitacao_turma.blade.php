@extends('layouts.appAutorizacao')

@section('content')
<div class="container" style="padding-top: 20px;margin-top:100px;margin-bottom: 5px;box-shadow: 5px 5px 5px rgba(0,0, 139);background-color:white;">
    <div class="row justify-content-center" style="margin-bottom: 20px;">
        <div class="col-md-10">
            <h4 style="font-weight: bolder; font-size: 18px; color: #f9821E; margin-bottom: 20px;text-align:center;">Solicitação de Inclusão de Turma</h4>
            <p style="color: black;text-align:center;font-weight:bold;">Preencha os dados abaixo, informando a turma e descrevendo a razão da solicitação.
            </p>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10 align-self-center">
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header" style="background-color: #0046AD; color:white;">{{ __('Solicitação de Turma') }}</div>

                <div class="card-body">
                    <form id="form_solicitacao_turma" name="form_solicitacao_turma" action="{{ route('registro_complementar') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row justify-content-center" style="color:black;font-size:14px;">
                            <div class=" col-md-12">
                                <div class="form-group">
                                    <label for="name">Descrição</label>
                                    <textarea class="form-control" id="descricao" name="descricao" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center" style="color:black;font-size:14px;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="id_sala">Escola</label>
                                    <select class="form-control" id="id_escola" name="id_escola" required>
                                        <option value=""></option>
                                        @foreach($escolas as $escola)
                                        <option value="{{ $escola->id.'_'.$escola->SAME }}">{{ $escola->nome.' ('.$escola->SAME.')'  ?? ''}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center" style="color:black;font-size:14px;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="id_sala">Turma</label>
                                    <select class="form-control" id="id_turma" name="id_turma" required>
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="email" id="email" value="{{ Auth::user()->email }}">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="id_tipo_solicitacao" id="id_tipo_solicitacao" value="3">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="id_municipio" id="id_municipio" value="{{ $escolas[0]->municipios_id }}">
                        </div>
                        <div class="row justify-content-center" style="color:black;font-size:14px;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">Solicitar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection