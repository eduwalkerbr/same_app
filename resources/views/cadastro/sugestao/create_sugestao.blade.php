@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 150px;">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card text-center">
                <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
                    <h5 class="card-title">@if(isset($sugestao)) Visualização da @else Cadastro de @endif Sugestão</h5>
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
            @if(isset($sugestao))
            <form id="form_edit_sugestao" name="form_edit_sugestao" action="{{ route('sugestao.update',$sugestao->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
            @else
            <form id="form_sugestao" name="form_sugestao" action="{{ route('sugestao.store') }}" method="post" enctype="multipart/form-data">
            @endif
                    @csrf
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-12">
                            <div class="form-group">
                                <label for="nome">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome Autor Sugestão" value="{{ $sugestao->nome ?? old('nome')}}" required >
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="email">E-Mail</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="E-mail do Usuário" value="{{ $sugestao->email ?? old('email')}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-12">
                            <div class="form-group">
                                <label for="mensagem">Mensagem</label>
                                <textarea class="form-control" id="mensagem" name="mensagem" rows="6">{{ $sugestao->mensagem ?? old('mensagem')}}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="id_user" id="id_user" value="{{ $sugestao->status ?? '1' }}">
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">@if(isset($sugestao)) Ok @else Cadastrar @endif</button>
                                <a href="{{ route('lista_sugestoes') }}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

@endsection