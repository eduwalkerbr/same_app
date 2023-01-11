@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 150px;">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card text-center">
                <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
                    <h5 class="card-title">@if(isset($habilidade)) Edição da @else Cadastro de @endif Habilidade</h5>
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
            @if(isset($habilidade))
            <form id="form_edit_habilidade" name="form_edit_habilidade" action="{{ route('habilidade.update',$habilidade->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @else
                <form id="form_habilidade" name="form_habilidade" action="{{ route('habilidade.store') }}" method="post" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-12">
                            <div class="form-group">
                                <label for="name">Descrição</label>
                                <input type="text" class="form-control" id="desc" name="desc" placeholder="Descrição da Habilidade" value="{{ $habilidade->desc ?? ''}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-12">
                            <div class="form-group">
                                <label for="name">Observação</label>
                                <textarea class="form-control" id="obs" name="obs" rows="2">{{ $habilidade->obs ?? ''}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="id_sala">Disciplina</label>
                                <select class="form-control" id="disciplinas_id" name="disciplinas_id" required>
                                    @if(isset($habilidade))
                                    @php
                                    $dis_selecionado = $habilidade->find($habilidade->id)->relDisciplinas;
                                    @endphp
                                    <option value="{{ $dis_selecionado->id ?? ''}}">{{ $dis_selecionado->desc ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($disciplinas as $disciplina)
                                    <option value="{{ $disciplina->id }}">{{ $disciplina->desc ?? ''}}</option>
                                    @endforeach
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
                                <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">@if(isset($habilidade)) Editar @else Cadastrar @endif</button>
                                <a href="{{ route('lista_habilidade') }}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

@endsection