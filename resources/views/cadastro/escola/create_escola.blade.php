@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 150px;">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card text-center">
                <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
                    <h5 class="card-title">@if(isset($escola)) Edição da @else Cadastro de @endif Escola</h5>
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
            @if(isset($escola))
            <form id="form_edit_escola" name="form_edit_escola" action="{{ route('escola.update',$escola->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
            @else
            <form id="form_escola" name="form_escola" action="{{ route('escola.store') }}" method="post" enctype="multipart/form-data">
                    @endif
            @csrf
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-10">
                            <div class="form-group">
                                <label for="name">Nome</label>
                                <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome da Escola" value="{{ $escola->nome ?? old('nome')}}" required>
                            </div>
                        </div>
                        <div class=" col-md-2">
                            <div class="form-group">
                                <label for="SAME">Ano SAME</label>
                                <select class="form-control" id="SAME" name="SAME" required>
                                    <option value="{{ $escola->SAME ?? old('SAME')}}">{{ $escola->SAME ?? old('SAME')}}</option>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="id_sala">Município</label>
                                <select class="form-control" id="municipios_id" name="municipios_id" required>
                                    @if(isset($escola))
                                    <option value="{{ $escola->municipios_id ?? old('municipios_id')}}">{{ $escola->nome_municipio.' ('.$escola->SAME_municipio.')' ?? old('municipios_id')}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="perfil">Status</label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="{{ $escola->status ?? old('status')}}">{{ $escola->status ?? old('status')}}</option>
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
                                <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">@if(isset($escola)) Editar @else Cadastrar @endif</button>
                                <a href="{{ route('lista_escola') }}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

@endsection