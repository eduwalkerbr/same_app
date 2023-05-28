@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 150px;">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card text-center">
                <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
                    <h5 class="card-title">@if(isset($legenda)) Edição da @else Cadastro de @endif Legenda</h5>
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
            @if(isset($legenda))
                <form id="form_edit_legenda" name="form_edit_legenda" action="{{ route('legenda.update',$legenda->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @else
                <form id="form_legenda" name="form_legenda" action="{{ route('legenda.store') }}" method="post" enctype="multipart/form-data">
                @endif
                    @csrf
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-12">
                            <div class="form-group">
                                <label for="titulo">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título da Legenda" value="{{ $legenda->titulo ?? old('titulo')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-12">
                            <div class="form-group">
                                <label for="descricao">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="2">{{ $legenda->descricao ?? old('descricao')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="exibicao">Exibição</label>
                                <input type="text" class="form-control" id="exibicao" name="exibicao" placeholder="Exibição da Legenda" value="{{ $legenda->exibicao ?? old('exibicao')}}" required>
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="cor_fundo">Cor Fundo</label>
                                <input type="text" class="form-control" id="cor_fundo" name="cor_fundo" placeholder="Cor de Fundo da Legenda" value="{{ $legenda->cor_fundo ?? old('cor_fundo')}}" required>
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="cor_letra">Cor Letra</label>
                                <input type="text" class="form-control" id="cor_letra" name="cor_letra" placeholder="Cor de Letra da Legenda" value="{{ $legenda->cor_letra ?? old('cor_letra')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-6">
                            <div class="form-group">
                                <label for="valor_inicial">Valor Inicial</label>
                                <input type="number" class="form-control" id="valor_inicial" name="valor_inicial" placeholder="Valor Inicial" value="{{ $legenda->valor_inicial ?? old('valor_inicial')}}" required>
                            </div>
                        </div>
                        <div class=" col-md-6">
                            <div class="form-group">
                                <label for="valor_final">Ano</label>
                                <input type="number" class="form-control" id="valor_final" name="valor_final" placeholder="Valor Final" value="{{ $legenda->valor_final ?? old('valor_final')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">@if(isset($legenda)) Editar @else Cadastrar @endif</button>
                                <a href="{{ route('lista_legenda') }}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

@endsection