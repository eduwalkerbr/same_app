@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 150px;">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card text-center">
                <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
                    <h5 class="card-title">@if(isset($questao)) Edição da @else Cadastro de @endif Questão</h5>
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
            @if(isset($questao))
            <form id="form_edit_questao" name="form_edit_questao" action="{{ route('questao.update',$questao->id) }}" method="post" enctype="multipart/form-data">
                @method('PUT')
                @else
                <form id="form_questao" name="form_questao" action="{{ route('questao.store') }}" method="post" enctype="multipart/form-data">
                    @endif
                    @csrf
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-8">
                            <div class="form-group">
                                <label for="desc">Descrição</label>
                                <input type="text" class="form-control" id="desc" name="desc" placeholder="Descrição da Questão" value="{{ $questao->desc ?? ''}}">
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="SAME">Ano SAME</label>
                                <select class="form-control" id="SAME" name="SAME" required>
                                    <option value="{{ $questao->SAME ?? ''}}">{{ $questao->SAME ?? ''}}</option>
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
                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="num_questao">Número</label>
                                <input type="number" class="form-control" id="num_questao" name="num_questao" placeholder="Número da Questão" value="{{ $questao->num_questao ?? ''}}" required>
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="ano">Ano</label>
                                <input type="number" class="form-control" id="ano" name="ano" placeholder="Ano" value="{{ $questao->ano ?? ''}}" required>
                            </div>
                        </div>
                        <div class=" col-md-4">
                            <div class="form-group">
                                <label for="correta">Correta</label>
                                <input type="text" class="form-control" id="correta" name="correta" placeholder="Correta" value="{{ $questao->correta ?? ''}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class=" col-md-9">
                            <div class="form-group">
                                <label for="obs">Observação</label>
                                <input type="text" class="form-control" id="obs" name="obs" placeholder="Observação da Questão" value="{{ $questao->obs ?? ''}}">
                            </div>
                        </div>
                        <div class=" col-md-3">
                            <div class="form-group">
                                <label for="modelo">Modelo</label>
                                <input type="text" class="form-control" id="modelo" name="modelo" placeholder="Modelo" value="{{ $questao->modelo ?? ''}}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="disciplinas_id">Disciplina</label>
                                <select class="form-control" id="disciplinas_id" name="disciplinas_id" required>
                                    @if(isset($questao))
                                    @php
                                    $disc_selecionado = $questao->find($questao->id)->relDisciplinas;
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tipo">Tipo de Questão</label>
                                <select class="form-control" id="tipo" name="tipo" required>
                                    @if(isset($questao))
                                    <option value="{{ $questao->tipo ?? ''}}">{{ $questao->tipo ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($tipoquestaos as $tipoquestao)
                                    <option value="{{ $tipoquestao->titulo }}">{{ $tipoquestao->titulo ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="temas_id">Tema</label>
                                <select class="form-control" id="temas_id" name="temas_id" required>
                                    @if(isset($questao))
                                    @php
                                    $tema_selecionado = $questao->find($questao->id)->relTemas;
                                    @endphp
                                    <option value="{{ $tema_selecionado->id ?? ''}}">{{ $tema_selecionado->desc ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($temas as $tema)
                                    <option value="{{ $tema->id }}">{{ $tema->desc ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prova_gabaritos_id">Provas</label>
                                <select class="form-control" id="prova_gabaritos_id" name="prova_gabaritos_id" required>
                                    @if(isset($questao))
                                    <option value="{{ $questao->id_prova_gabarito ?? ''}}">{{ $questao->nome_prova_gabarito ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($provas_gabaritos as $prova_gabarito)
                                    <option value="{{ $prova_gabarito->id }}">{{ $prova_gabarito->DESCR_PROVA ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="habilidades_id">Habilidade</label>
                                <select class="form-control" id="habilidades_id" name="habilidades_id" required>
                                    @if(isset($questao))
                                    @php
                                    $hab_selecionado = $questao->find($questao->id)->relHabilidades;
                                    @endphp
                                    <option value="{{ $hab_selecionado->id ?? ''}}">{{ $hab_selecionado->desc ?? ''}}</option>
                                    @else
                                    <option value=""></option>
                                    @endif
                                    @foreach($habilidades as $habilidade)
                                    <option value="{{ $habilidade->id }}">{{ $habilidade->desc ?? ''}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @if(isset($questao->imagem))
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="image">Imagem da Questão</label>
                                    <input type="file" class="form-control-file" id="image" name="image" value="{{ $questao->imagem ?? ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="image">Imagem Selecionada</label>
                            </div>
                            <div class="form-group">
                                <img src="{{ asset('storage/'.$questao->imagem) }}" alt="..." width="150px" height="80px">
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="form-group">
                        <div class="form-group">
                            <label for="image">Imagem da Questão</label>
                            <input type="file" class="form-control-file" id="image" name="image" value="{{ $questao->imagem ?? ''}}">
                        </div>
                    </div>
                    @endif
                    <div class="form-group">
                        <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
                    </div>
                    <div class="row justify-content-center" style="color:black;font-size:15px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">@if(isset($questao)) Editar @else Cadastrar @endif</button>
                                <a href="{{ route('lista_questao') }}"><button type="button" style="background-color: black;border-color:black;" class="btn btn-primary">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </form>
            </form>
        </div>
    </div>
</div>

@endsection