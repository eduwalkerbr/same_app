@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 150px;">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card text-center">
                <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
                    <h5 class="card-title">@if(isset($turma)) Edição da @else Cadastro de @endif Turma</h5>
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
            @if(isset($turma))
            <form id="form_edit_turma" name="form_edit_turma" action="{{ route('turma.update',$turma->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @else
                <form id="form_turma" name="form_turma" action="{{ route('turma.store') }}" method="post" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-9">
                            <div class="form-group">
                                <label for="name">Descrição</label>
                                <input type="text" class="form-control" id="DESCR_TURMA" name="DESCR_TURMA" placeholder="Descrição da Turma" value="{{ $turma->DESCR_TURMA ?? ''}}" required>
                            </div>
                        </div>
                        <div class=" col-md-3">
                            <div class="form-group">
                                <label for="SAME">Ano SAME</label>
                                <select class="form-control" id="SAME" name="SAME" required>
                                    <option value="{{ $turma->SAME ?? ''}}">{{ $turma->SAME ?? ''}}</option>
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
                        <div class=" col-md-6">
                            <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="TURMA" name="TURMA" placeholder="Nome da Turma" value="{{ $turma->TURMA ?? ''}}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_sala">Município</label>
                                <select class="form-control" id="escolas_municipios_id" name="escolas_municipios_id" required>
                                    @if(isset($turma))
                                    <option value="{{ $turma->id_municipio ?? ''}}">{{ $turma->nome_municipio.' ('.$turma->SAME_municipio.')' ?? ''}}</option>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_sala">Escola</label>
                                <select class="form-control" id="escolas_id" name="escolas_id" required>
                                    @if(isset($turma))
                                    <option value="{{ $turma->id_escola ?? ''}}">{{ $turma->nome_escola.' ('.$turma->SAME_escola.')' ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($escolas as $escola)
                                    <option value="{{ $escola->id }}">{{ $escola->nome.' ('.$escola->SAME.')' ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="perfil">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="{{ $turma->status ?? ''}}">{{ $turma->status ?? ''}}</option>
                                    <option>Ativo</option>
                                    <option>Inativo</option>
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
                                <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">@if(isset($turma)) Editar @else Cadastrar @endif</button>
                                <a href="{{ route('lista_turma') }}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

@endsection