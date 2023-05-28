@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 150px;">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card text-center">
                <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
                    <h5 class="card-title">@if(isset($tipo_solicitacao)) Edição da @else Cadastro de @endif Tipos de Solicitação</h5>
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
            @if(isset($tipo_solicitacao))
            <form id="form_edit_tipo_solicitacao" name="form_edit_tipo_solicitacao" action="{{ route('tipo_solicitacao.update',$tipo_solicitacao->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
            @else
            <form id="form_tipo_solicitacao" name="form_tipo_solicitacao" action="{{ route('tipo_solicitacao.store') }}" method="post" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-12">
                            <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Tipo de Solicitação" value="{{ $tipo_solicitacao->nome ?? old('nome')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">@if(isset($tipo_solicitacao)) Editar @else Cadastrar @endif</button>
                                <a href="{{ route('lista_tipo_solicitacao') }}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

@endsection