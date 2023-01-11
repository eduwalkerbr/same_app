@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 150px;">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card text-center">
                <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
                    <h5 class="card-title">@if(isset($aluno)) Edição da @else Cadastro de @endif Aluno</h5>
                </div>
            </div>
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
            @if(isset($aluno))
            <form id="form_edit_aluno" name="form_edit_aluno" action="{{ route('aluno.update',$aluno->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @else
                <form id="form_aluno" name="form_aluno" action="{{ route('aluno.store') }}" method="post" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-8">
                            <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Aluno" value="{{ $aluno->nome ?? ''}}" required>
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="SAME">Ano SAME</label>
                                <select class="form-control" id="SAME" name="SAME" required>
                                    <option value="{{ $aluno->SAME ?? ''}}">{{ $aluno->SAME ?? ''}}</option>
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
                                <label for="turmas_escolas_municipios_id">Município</label>
                                <select class="form-control" id="turmas_escolas_municipios_id" name="turmas_escolas_municipios_id" required>
                                    @if(isset($aluno))
                                    <option value="{{ $aluno->id_municipio.'_'.$aluno->SAME_municipio ?? ''}}">{{ $aluno->nome_municipio.' ('.$aluno->SAME_municipio.')' ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($municipios as $municipio)
                                    <option value="{{ $municipio->id.'_'.$municipio->SAME }}">{{ $municipio->nome.' ('.$municipio->SAME.')' ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="turmas_escolas_id">Escola</label>
                                <select class="form-control" id="turmas_escolas_id" name="turmas_escolas_id" required>
                                    @if(isset($aluno))
                                    <option value="{{ $aluno->id_escola.'_'.$aluno->SAME_escola ?? ''}}">{{ $aluno->nome_escola.' ('.$aluno->SAME_escola.')' ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="turmas_id">Turma</label>
                                <select class="form-control" id="turmas_id" name="turmas_id" required>
                                    @if(isset($aluno))
                                    <option value="{{ $aluno->id_turma ?? ''}}">{{ $aluno->nome_turma.' ('.$aluno->SAME_turma.')' ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">@if(isset($aluno)) Editar @else Cadastrar @endif</button>
                                <a href="{{ route('lista_aluno') }}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

@endsection