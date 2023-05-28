@extends('layouts.appAutorizacao')

@section('content')
<div class="container" style="padding-top: 20px;margin-top: 100px; margin-bottom: 140px;box-shadow: 5px 5px 5px rgba(0,0, 139);background-color:white;">
    <div class="row justify-content-center" style="margin-bottom: 20px;">
        <div class="col-md-10">
            <h4 style="font-weight: bolder; font-size: 18px; color: #f9821E; margin-bottom: 20px;text-align:center;">@if(isset($direcao_professor)) Edição da @else Cadastro de @endif Direção Professor</h4>
            <p style="color: black;text-align:center;font-weight:bold;">Realize a adição dos Dados de Registro ou Cancele a operação e retorno a página de Listagem.</p>
        </div>
    </div>
    <div style="margin-top: 20px;" class="row justify-content-center">
        <div class="col-md-9">
            @if(isset($errors) && count($errors)>0)
            <div class="text-center mt-2 mb-2 p-2 alert-danger">
                @foreach($errors->all() as $erro)
                {{$erro}}<br>
                @endforeach
            </div>
            @endif
            <form id="form_edit_direcao_professor" name="form_edit_direcao_professor" action="{{ route('gest_direcao_professor.update',$direcao_professor->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @else
                <form id="form_direcao_professor" name="form_direcao_professor" action="{{ route('gest_direcao_professor.store') }}" method="post" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="id_sala">Previlégio</label>
                                <select class="form-control" id="id_previlegio" name="id_previlegio" required>
                                    @if(isset($direcao_professor))
                                    @php
                                    $prev_selecionado = $direcao_professor->find($direcao_professor->id)->relPrevilegios;
                                    $nome_prev_selecionado = $prev_selecionado->find($prev_selecionado->id)->relUsuarios;
                                    @endphp
                                    <option value="{{ $prev_selecionado->id ?? ''}}">{{ $nome_prev_selecionado->name ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($previlegios as $previlegio)
                                    @php
                                    $nome_us = $previlegio->find($previlegio->id)->relUsuarios;
                                    @endphp
                                    <option value="{{ $previlegio->id }}">{{ $nome_us->name ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="id_sala">Escola</label>
                                <select class="form-control" id="id_escola" name="id_escola" required>
                                    @if(isset($direcao_professor) && isset($direcao_professor->id_escola))
                                    <option value="{{ $direcao_professor->id_escola.'_'.$direcao_professor->SAME ?? ''}}">{{ $direcao_professor->nome_escola.' ('.$direcao_professor->SAME_escola.')' ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($escolas as $escola)
                                    <option value="{{ $escola->id.'_'.$escola->SAME }}">{{ $escola->nome.' ('.$escola->SAME.')' ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="SAME">Ano SAME</label>
                                <select class="form-control" id="SAME" name="SAME" required>
                                    <option value="{{ $direcao_professor->SAME ?? ''}}">{{ $direcao_professor->SAME ?? ''}}</option>
                                    @if((isset($anosame) && $anosame[0]->status == 'Ativo') || empty($anosame))
                                    @foreach($anosativos as $anoativo)
                                        <option value="{{ $anoativo->descricao }}">{{ $anoativo->descricao ?? ''}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="id_sala">Turma</label>
                                <select class="form-control" id="id_turma" name="id_turma">
                                    @if(isset($direcao_professor) && isset($direcao_professor->id_turma))
                                    <option value="{{ $direcao_professor->id_turma ?? ''}}">{{ $direcao_professor->nome_turma.' ('.$direcao_professor->SAME_turma.')'  ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="autorizou_users_id" id="autorizou_users_id" value="{{ Auth::user()->id }}">
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">@if(isset($direcao_professor)) Editar @else Cadastrar @endif</button>
                                <a href="{{ route('gest_direcao_professor.listar') }}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

@endsection