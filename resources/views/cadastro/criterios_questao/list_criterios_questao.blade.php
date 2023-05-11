@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 100px;">
  <div class="card text-center">
    <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
      <h5 class="card-title">Lista de Critérios das Questões</h5>
      <p class="card-text">Segue abaixo a lista de Critérios das Questões para Visualização e ou Edição.</p>
    </div>
  </div>
  @csrf
  <br>
  <div class="accordion" id="accordionPanelsStayOpenExample">
    <div class="accordion-item">
      <h2 class="accordion-header" id="panelsStayOpen-headingOne">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne" style="background-color:white;color: #f9821e;font-weight:bold;font-size:16px;border: none;">
          Filtros de Consulta
        </button>
      </h2>
      <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingOne">
        <div class="accordion-body">
          <form id="form_filter" name="form_filter" action="{{ route('criterios_questao.filtrar') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-8">
                <div class="form-group">
                  <label for="nome">Nome</label>
                  <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Critério de Questão" value="{{ $request->nome ?? ''}}">
                </div>
              </div>
              <div class=" col-md-4">
                <div class="form-group">
                  <label for="id_disciplina">Disciplina</label>
                  <select class="form-control" id="id_disciplina" name="id_disciplina">
                    <option value=""></option>
                    @foreach($disciplinas as $disciplina)
                    <option value="{{ $disciplina->id }}">{{ $disciplina->desc ?? ''}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-8">
                <div class="form-group">
                  <label for="id_tipo_questao">Tipo de Questão</label>
                  <select class="form-control" id="id_tipo_questao" name="id_tipo_questao">
                    <option value=""></option>
                    @foreach($tipoquestaos as $tipoquestao)
                    <option value="{{ $tipoquestao->id }}">{{ $tipoquestao->titulo ?? ''}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class=" col-md-4">
                <div class="form-group">
                  <label for="ano">Ano</label>
                  <input type="number" class="form-control" id="ano" name="ano" placeholder="Ano" value="">
                </div>
              </div>
            </div>
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class="col-md-12">
                <div class="form-group">
                  <button type="submit" style="background-color: #f9821E;border-color:#f9821E;" class="btn btn-primary">Filtrar</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <table class="table responsive-md">
    <thead class="thead-light">
      <tr>
        <th scope="col" style="color: black;">id</th>
        <th scope="col" style="color: black;">Nome</th>
        <th scope="col" style="color: black;">Tipo Questão</th>
        <th scope="col" style="color: black;">Disciplina</th>
        <th style="text-align:center; color: black;" scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($criterios_questao as $criterio)
      @php
      $disciplina = $criterio->find($criterio->id)->relDisciplinas;
      $tiposquestao = $criterio->find($criterio->id)->relTipoQuestaos;
      @endphp
      <tr>
        <th style="font-weight: normal;font-size:14px;" scope="row">{{$criterio->id}}</th>
        <td style="font-weight: normal;font-size:14px;">{{$criterio->nome }}</td>
        <td style="font-weight: normal;font-size:14px;">{{$tiposquestao->titulo}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$disciplina->desc}}</td>
        <td style="text-align:center;">
          <a href="{{ route('criterios_questao.create') }}">
            <button style="font-weight: normal;background-color:#f9821E;border-color:#f9821E;" class="btn-primary">Novo</button>
          </a>
          <a href="{{ route('criterios_questao.edit', $criterio->id) }}">
            <button style="font-weight: normal;background-color:black;border-color:black;" class="btn-primary">Editar</button>
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="row justify-content-center">
    {{ $criterios_questao->appends(Request::except('page'))->links() }}
  </div>

</div>
@endsection