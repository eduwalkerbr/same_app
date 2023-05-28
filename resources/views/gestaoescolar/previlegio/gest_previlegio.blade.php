@extends('layouts.appAutorizacao')

@section('content')
<div class="container" style="padding-top: 20px;margin-top: 100px; margin-bottom: 140px;box-shadow: 5px 5px 5px rgba(0,0, 139);background-color:white;">
    <div class="row justify-content-center" style="margin-bottom: 20px;">
        <div class="col-md-10">
            <h4 style="font-weight: bolder; font-size: 18px; color: #f9821E; margin-bottom: 20px;text-align:center;">@if(isset($previlegio)) Edição da @else Cadastro de @endif Privilégio</h4>
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
            <form id="form_edit_previlegio" name="form_edit_previlegio" action="{{ route('gest_previlegio.update',$previlegio->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @else
                <form id="form_previlegio" name="form_previlegio" action="{{ route('gest_previlegio.store') }}" method="post" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="id_sala">Usuário</label>
                                <select class="form-control" id="users_id" name="users_id" required>
                                    @if(isset($previlegio))
                                    @php
                                    $us_selecionado = $previlegio->find($previlegio->id)->relUsuarios;
                                    @endphp
                                    <option value="{{ $us_selecionado->id ?? ''}}">{{ $us_selecionado->name ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}">{{ $usuario->name ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="SAME">Ano SAME</label>
                                <select class="form-control" id="SAME" name="SAME" required>
                                    <option value="{{ $previlegio->SAME ?? ''}}">{{ $previlegio->SAME ?? ''}}</option>
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
                                <label for="id_sala">Município</label>
                                <select class="form-control" id="municipios_id" name="municipios_id" required>
                                    @if(isset($previlegio))
                                    <option value="{{ $previlegio->id_municipio ?? ''}}">{{ $previlegio->nome_municipio.' ('.$previlegio->SAME_municipio.')' ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($municipios as $municipio)
                                    <option value="{{ $municipio->id }}">{{ $municipio->nome.' ('.$municipio->SAME.')' ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="id_sala">Função</label>
                                <select class="form-control" id="funcaos_id" name="funcaos_id" required>
                                    @if(isset($previlegio))
                                    @php
                                    $fun_selecionado = $previlegio->find($previlegio->id)->relFuncaos;
                                    @endphp
                                    <option value="{{ $fun_selecionado->id ?? ''}}">{{ $fun_selecionado->desc ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($funcaos as $funcao)
                                    <option value="{{ $funcao->id }}">{{ $funcao->desc ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="status" id="status" value="1">
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="autorizou_users_id" id="autorizou_users_id" value="{{ Auth::user()->id }}">
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">@if(isset($previlegio)) Editar @else Cadastrar @endif</button>
                                <a href="{{ route('gest_previlegio.listar') }}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

@endsection