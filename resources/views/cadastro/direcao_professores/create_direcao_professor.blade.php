@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 150px;">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card text-center">
                <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
                    <h5 class="card-title">@if(isset($direcao_professor)) Edição da @else Cadastro de @endif Direção Professor</h5>
                </div>
            </div>
        </div>
    </div>
    <div style="margin-top: 20px;" class="row justify-content-center">
        <div class="col-md-9">
            @if($errors->any())
            <div class="text-center mt-2 mb-2 p-2 alert-danger">
                @foreach($errors->all() as $erro)
                {{$erro}}<br>
                @endforeach
            </div>
            @endif
            @if(isset($direcao_professor))
            <form id="form_edit_direcao_professor" name="form_edit_direcao_professor" action="{{ route('direcao_professor.update',$direcao_professor->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
            @else
            <form id="form_direcao_professor" name="form_direcao_professor" action="{{ route('direcao_professor.store') }}" method="post" enctype="multipart/form-data">
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
                                    <option value="{{ $prev_selecionado->id ?? old('id_previlegio')}}">{{ $nome_prev_selecionado->name ?? ''}}</option>
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
                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="SAME">Ano SAME</label>
                                <select class="form-control" id="SAME" name="SAME" required>
                                    <option value="{{ $direcao_professor->SAME ?? old('SAME')}}">{{ $direcao_professor->SAME ?? old('SAME')}}</option>
                                    @if((isset($anosame) && $anosame[0]->status == 'Ativo') || empty($anosame))
                                    @foreach($anosativos as $anoativo)
                                    <option value="{{ $anoativo->descricao }}">{{ $anoativo->descricao ?? ''}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="municipios_id">Município</label>
                                <select class="form-control" id="municipios_id" name="municipios_id">
                                    @if(isset($direcao_professor) && isset($direcao_professor->id_municipio))
                                    <option value="{{ $direcao_professor->id_municipio.'_'.$direcao_professor->SAME ?? old('municipios_id')}}">{{ $direcao_professor->nome_municipio.' ('.$direcao_professor->SAME_escola.')' ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="escolas_id">Escola</label>
                                <select class="form-control" id="escolas_id" name="escolas_id" required>
                                    @if(isset($direcao_professor) && isset($direcao_professor->id_escola))
                                    <option value="{{ $direcao_professor->id_escola.'_'.$direcao_professor->SAME ?? old('escolas_id')}}">{{ $direcao_professor->nome_escola.' ('.$direcao_professor->SAME_escola.')' ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="turmas_id">Turma</label>
                                <select class="form-control" id="turmas_id" name="turmas_id">
                                    @if(isset($direcao_professor) && isset($direcao_professor->id_turma))
                                    <option value="{{ $direcao_professor->id_turma ?? old('turmas_id')}}">{{ $direcao_professor->nome_turma.' ('.$direcao_professor->SAME_turma.')'  ?? ''}}</option>
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
                                <a href="{{ route('lista_direcao_professor') }}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

@endsection