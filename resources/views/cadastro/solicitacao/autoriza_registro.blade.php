@extends('layouts.appAutorizacao')

@section('content')
<div class="container" style="padding-top: 20px;margin-top:100px;margin-bottom: 5px;box-shadow: 5px 5px 5px rgba(0,0, 139);background-color:white;">
    <div class="row justify-content-center" style="margin-bottom: 20px;">
        <div class="col-md-10">
            <h4 style="font-weight: bolder; font-size: 18px; color: #f9821E; margin-bottom: 20px;text-align:center;">Solicitação de Registro de Usuário</h4>
            <p style="color: black;text-align:center;font-weight: bold;">Seguem abaixo os dados preenchidos pelo usuário, na presente solicitação.</p>
            @if(isset($turmasprevias) && count($turmasprevias) > 1)
            <p style="color: red;text-align:center;font-size: 14px;">
                Além da Turma solicitada, segue a listagem de turmas previamente cadastradas para o presente usuário, de forma que podem ser selecionadas as que se deseja habilitar o Acesso ao Usuário.
            </p>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-10 align-self-center">
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header" style="background-color: #0046AD; color:white;">{{ __('Registro de Usuário') }}</div>

                <div class="card-body">
                    <form id="form_autorizacao_registro" name="form_autorizacao_registro" action="{{ route('solicitacao_registro.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <div class="row justify-content-center" style="color:black;font-size:13px;">
                            <div class=" col-md-5">
                                <div class="form-group">
                                    <label for="name">Nome</label>
                                    <input style="color:black;font-size:13px;" type=" text" class="form-control" id="nome" name="nome" placeholder="Nome" value="{{ $solicitacao->name }}" disabled>
                                </div>
                            </div>
                            <div class=" col-md-5">
                                <div class="form-group">
                                    <label for="name">E-mail</label>
                                    <input style="color:black;font-size:13px;" type=" email" class="form-control" id="email" name="email" placeholder="E-mail" value="{{ $solicitacao->email }}" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row justify-content-center" style="color:black;font-size:13px;">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="id_funcao">Função</label>

                                    <select class="form-control" id="id_funcao" name="id_funcao">
                                        <option style="color:black;font-size:13px;" value=" {{ $funcao->id }}">{{ $funcao->desc ?? ''}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="id_funcao">Munícipio</label>

                                    <select class="form-control" id="id_municipio" name="id_municipio">
                                        <option style="color:black;font-size:13px;" value=" {{ $municipio->id }}">{{ $municipio->nome.' ('.$municipio->SAME.')' ?? ''}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        @if(isset($escola))
                        <div class="row justify-content-center" style="color:black;font-size:13px;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id_funcao">Escola</label>

                                    <select class="form-control" id="id_escola" name="id_escola">
                                        <option style="color:black;font-size:13px;" value=" {{ $escola->id }}">{{ $escola->nome.' ('.$escola->SAME.')' ?? ''}}</option>
                                    </select>
                                </div>
                            </div>
                            @if(isset($funcao) && $funcao->desc == 'Professor(a)')
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="id_funcao">Turma</label>

                                    <select class="form-control" id="id_turma" name="id_turma">
                                        <option style="color:black;font-size:13px;" value=" {{ $turma->id }}">{{ $turma->DESCR_TURMA.' ('.$turma->SAME.')' ?? ''}}</option>
                                    </select>
                                </div>
                            </div>
                            @endif
                        </div>
                        @endif
                        @if(isset($turmasprevias) && count($turmasprevias) > 1)
                        <div class="row justify-content-center" style="color:black;font-size:13px;">
                            <div class="col-md-10">
                                <p>Turmas Préviamente Cadastradas</p>
                            </div>
                        </div>

                        <div class="row justify-content-center" style="color:black;font-size:13px;">
                            <div class="col-md-10">
                                <table class="table responsive-md">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col" style="color: black;">id</th>
                                            <th scope="col" style="color: black;">E-mail</th>
                                            <th scope="col" style="color: black;">Turma</th>
                                            <th scope="col" style="color: black;">Escola</th>
                                            <th style="text-align:center; color: black;" scope="col">Seletor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($turmasprevias as $turmaprevia)
                                        @php
                                        $turmas = $turmaprevia->find($turmaprevia->id)->relTurmas;
                                        $escolas = $turmaprevia->find($turmaprevia->id)->relEscolas;
                                        @endphp
                                        <tr>
                                            <th style="font-weight: normal;font-size:14px;" scope="row">{{$turmaprevia->id}}</th>
                                            <td style="font-weight: normal;font-size:14px;">{{$turmaprevia->email }}</td>
                                            <td style="font-weight: normal;font-size:14px;">@if(isset($turmas)) {{$turmas->DESCR_TURMA}} @endif</td>
                                            <td style="font-weight: normal;font-size:14px;">@if(isset($escolas)) {{$escolas->nome}} @endif</td>
                                            <td style="text-align:center;">
                                                <input type="checkbox" name="selected_values[]" value="{{$turmas->id}}">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                        <div class="form-group">
                            <input type="hidden" name="autorizou_users_id" id="autorizou_users_id" value="{{ Auth::user()->id }}">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="id_solicitacao" id="id_solicitacao" value="{{ $solicitacao->id }}">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="SAME" id="SAME" value="{{ $solicitacao->SAME }}">
                        </div>
                        @foreach($usuarios as $usuario)
                        <div class="form-group">
                            <input type="hidden" name="id_user" id="id_user" value="{{ $usuario->id }}">
                        </div>
                        @endforeach
                        <div class="row justify-content-center" style="color:black;font-size:13px;">
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