@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 150px;">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card text-center">
                <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
                    <h5 class="card-title">@if(isset($criterio_questao)) Edição da @else Cadastro de @endif Critérios de Questões</h5>
                    <p style="color:white;font-weight:bold;">Critérios da Disciplina de Português devem ter o preenchimento do Ano realizado.</p>
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
            @if(isset($criterio_questao))
            <form id="form_edit_criterios_questao" name="form_edit_criterios_questao" action="{{ route('criterios_questao.update',$criterio_questao->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
            @else
            <form id="form_criterios_questao" name="form_criterios_questao" action="{{ route('criterios_questao.store') }}" method="post" enctype="multipart/form-data">
            @endif
                    @csrf
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-12">
                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Critério" value="{{ $criterio_questao->nome ?? old('nome')}}">
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-12">
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="2">{{ $criterio_questao->descricao ?? old('descricao')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_disciplina">Disciplina</label>
                                <select class="form-control" id="id_disciplina" name="id_disciplina" required>
                                    @if(isset($criterio_questao))
                                    @php
                                    $disc_selecionado = $criterio_questao->find($criterio_questao->id)->relDisciplinas;
                                    @endphp
                                    <option value="{{ $disc_selecionado->id ?? old('id_disciplina')}}">{{ $disc_selecionado->desc ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($disciplinas as $disciplina)
                                    <option value="{{ $disciplina->id }}">{{ $disciplina->desc ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_tipo_questao">Tipo de Questão</label>
                                <select class="form-control" id="id_tipo_questao" name="id_tipo_questao" required>
                                    @if(isset($criterio_questao))
                                    @php
                                    $tipo_selecionado = $criterio_questao->find($criterio_questao->id)->relTipoQuestaos;
                                    @endphp
                                    <option value="{{ $tipo_selecionado->id ?? old('id_tipo_questao')}}">{{ $tipo_selecionado->titulo ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($tipoquestaos as $tipoquestao)
                                    <option value="{{ $tipoquestao->id }}">{{ $tipoquestao->titulo ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-12">
                            <div class="form-group">
                                <label for="obs">Observação</label>
                                <textarea class="form-control" id="obs" name="obs" rows="3">{{ $criterio_questao->obs ?? old('obs')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-12">
                            <div class="form-group">
                                <label for="ano">Ano</label>
                                <input type="number" class="form-control" id="ano" name="ano" placeholder="Ano" value="{{ $criterio_questao->ano ?? old('ano')}}">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">@if(isset($criterio_questao)) Editar @else Cadastrar @endif</button>
                                <a href="{{ route('lista_criterios_questao') }}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

@endsection