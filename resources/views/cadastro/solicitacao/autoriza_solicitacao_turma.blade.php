@extends('layouts.appAutorizacao')

@section('content')
<div class="container" style="padding-top: 20px;margin-top:100px;margin-bottom: 5px;box-shadow: 5px 5px 5px rgba(0,0, 139);background-color:white;">
    <div class="row justify-content-center" style="margin-bottom: 20px;">
        <div class="col-md-10">
            <h4 style="font-weight: bolder; font-size: 18px; color: #f9821E; margin-bottom: 20px;text-align:center;">Solicitação de Inclusão de Turma</h4>
            <p style="color: black;text-align:center;font-weight:bold;">Seguem abaixo os dados preenchidos pelo usuário, na presente solicitação.
            </p>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10 align-self-center">
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header" style="background-color: #0046AD; color:white;">{{ __('Solicitação de Turma') }}</div>

                <div class="card-body">
                    <form id="form_solicitacao_turma" name="form_solicitacao_turma" action="{{ route('solicitacao_turma.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row justify-content-center" style="color:black;font-size:14px;">
                            <div class=" col-md-10">
                                <div class="form-group">
                                    <label for="name">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="E-mail" value="{{ $solicitacao->email }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center" style="color:black;font-size:14px;">
                            <div class=" col-md-10">
                                <div class="form-group">
                                    <label for="name">Descrição</label>
                                    <textarea class="form-control" id="descricao" name="descricao" rows="2" disabled>{{ $solicitacao->descricao }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center" style="color:black;font-size:14px;">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="id_sala">Turma</label>
                                    <select class="form-control" id="id_turma" name="id_turma" required disabeld>
                                        <option value="{{ $turma->id }}">{{ $turma->DESCR_TURMA.' ('.$turma->SAME.')'  ?? ''}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row justify-content-center" style="color:black;font-size:14px;">
                            <div class="col-md-10">
                                <div class="form-group">
                                    <label for="id_escola">Escola</label>
                                    <select class="form-control" id="id_escola" name="id_escola" required disabeld>
                                        <option value="{{ $escolas->id }}">{{ $escolas->nome.' ('.$escolas->SAME.')'  ?? ''}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="id_solicitacao" id="id_solicitacao" value="{{ $solicitacao->id }}">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="id_escola" id="id_escola" value="{{ $escolas->id }}">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="SAME" id="SAME" value="{{ $solicitacao->SAME }}">
                        </div>
                        @foreach($usuarios as $usuario)
                        <div class="form-group">
                            <input type="hidden" name="id_user" id="id_user" value="{{ $usuario->id }}">
                        </div>
                        @endforeach
                        <div class="row justify-content-center" style="color:black;font-size:14px;">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">Autorizar</button>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <a href="{{ route('negar_solicitacao',$solicitacao->id)}}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Negar</button></a>
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