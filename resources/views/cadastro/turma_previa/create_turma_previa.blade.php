@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 150px;">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card text-center">
                <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
                    <h5 class="card-title">@if(isset($turmaprevia)) Edição da @else Cadastro de @endif Turma Prévia</h5>
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
            @if(isset($turmaprevia))
            <form id="form_edit_turma_previa" name="form_edit_turma_previa" action="{{ route('turma_previa.update',$turmaprevia->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @else
                <form id="form_turma_previa" name="form_turma_previa" action="{{ route('turma_previa.store') }}" method="post" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-12">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="E-mail" value="{{ $turmaprevia->email ?? ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="id_escola">Escola</label>
                                <select class="form-control" id="id_escola" name="id_escola" required>
                                    @if(isset($turmaprevia))
                                    @php
                                    $escola_selecionada = $turmaprevia->find($turmaprevia->id)->relEscolas;
                                    @endphp
                                    <option value="{{ $escola_selecionada->id ?? ''}}">{{ $escola_selecionada->nome.' ('.$escola_selecionada->SAME.')' ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($escolas as $escola)
                                    <option value="{{ $escola->id }}">{{ $escola->nome.' ('.$escola->SAME.')' ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="id_turma">Turma</label>
                                <select class="form-control" id="id_turma" name="id_turma" required>
                                    @if(isset($turmaprevia))
                                    @php
                                    $turma_selecionada = $turmaprevia->find($turmaprevia->id)->relTurmas;
                                    @endphp
                                    <option value="{{ $turma_selecionada->id ?? ''}}">{{ $turma_selecionada->DESCR_TURMA.' ('.$turma_selecionada->SAME.')' ?? ''}}</option>
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
                    <div class="form-group">
                        <input type="hidden" name="ativo" id="ativo" value="1">
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">@if(isset($turmaprevia)) Editar @else Cadastrar @endif</button>
                                <a href="{{ route('lista_turma_previa') }}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

@endsection