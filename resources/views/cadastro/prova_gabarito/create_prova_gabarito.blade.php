@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 150px;">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card text-center">
                <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
                    <h5 class="card-title">@if(isset($prova_gabarito)) Edição da @else Cadastro de @endif Gabarito de Prova</h5>
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
            @if(isset($prova_gabarito))
            <form id="form_edit_prova_gabarito" name="form_edit_prova_gabarito" action="{{ route('prova_gabarito.update',$prova_gabarito->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @else
                <form id="form_prova_gabarito" name="form_prova_gabarito" action="{{ route('prova_gabarito.store') }}" method="post" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-8">
                            <div class="form-group">
                                <label for="DESCR_PROVA">Descrição</label>
                                <input type="text" class="form-control" id="DESCR_PROVA" name="DESCR_PROVA" placeholder="Descrição da Prova" value="{{ $prova_gabarito->DESCR_PROVA ?? ''}}">
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="SAME">Ano SAME</label>
                                <select class="form-control" id="SAME" name="SAME" required>
                                    <option value="{{ $prova_gabarito->SAME ?? ''}}">{{ $prova_gabarito->SAME ?? ''}}</option>
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
                        <div class=" col-md-12">
                            <div class="form-group">
                                <label for="gabarito">Gabarito</label>
                                <input type="text" class="form-control" id="gabarito" name="gabarito" placeholder="Gabarito" value="{{ $prova_gabarito->gabarito ?? ''}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-6">
                            <div class="form-group">
                                <label for="ano">Ano</label>
                                <input type="number" class="form-control" id="ano" name="ano" placeholder="Ano" value="{{ $prova_gabarito->ano ?? ''}}" required>
                            </div>
                        </div>
                        <div class=" col-md-6">
                            <div class="form-group">
                                <label for="qtd">Quantidade</label>
                                <input type="number" class="form-control" id="qtd" name="qtd" placeholder="Quantidade" value="{{ $prova_gabarito->qtd ?? ''}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="disciplinas_id">Disciplina</label>
                                <select class="form-control" id="disciplinas_id" name="disciplinas_id" required>
                                    @if(isset($prova_gabarito))
                                    @php
                                    $disc_selecionado = $prova_gabarito->find($prova_gabarito->id)->relDisciplinas;
                                    @endphp
                                    <option value="{{ $disc_selecionado->id ?? ''}}">{{ $disc_selecionado->desc ?? ''}}</option>
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
                    <div class="form-group">
                        <input type="hidden" name="status" id="status" value="1">
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">@if(isset($prova_gabarito)) Editar @else Cadastrar @endif</button>
                                <a href="{{ route('lista_prova_gabarito') }}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

@endsection