@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 100px;">
  <div class="card text-center">
    <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
      <h5 class="card-title">Lista de Questões</h5>
      <p class="card-text">Segue abaixo a lista de Questões para Visualização e ou Edição.</p>
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
          <form id="form_filter" name="form_filter" action="{{ route('questao.filtrar') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-5">
                <div class="form-group">
                  <label for="desc">Descrição</label>
                  <input type="text" class="form-control" id="desc" name="desc" placeholder="Descrição da Questão" value="">
                </div>
              </div>
              <div class=" col-md-2">
                <div class="form-group">
                  <label for="SAME">Ano SAME</label>
                  <select class="form-control" id="SAME" name="SAME">
                    <option value=""></option>
                    @foreach($anossame as $anosame)
                    <option value="{{ $anosame->descricao }}">{{ $anosame->descricao ?? ''}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class=" col-md-3">
                <div class="form-group">
                  <label for="num_questao">Número</label>
                  <input type="number" class="form-control" id="num_questao" name="num_questao" placeholder="Número da Questão" value="">
                </div>
              </div>
              <div class=" col-md-2">
                <div class="form-group">
                  <label for="ano">Ano</label>
                  <input type="number" class="form-control" id="ano" name="ano" placeholder="Ano" value="">
                </div>
              </div>
            </div>
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-3">
                <div class="form-group">
                  <label for="disciplinas_id">Disciplina</label>
                  <select class="form-control" id="disciplinas_id" name="disciplinas_id">
                    <option value=""></option>
                    @foreach($disciplinas as $disciplina)
                    <option value="{{ $disciplina->id }}">{{ $disciplina->desc ?? ''}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class=" col-md-2">
                <div class="form-group">
                  <label for="modelo">Modelo</label>
                  <input type="text" class="form-control" id="modelo" name="modelo" placeholder="Modelo" value="">
                </div>
              </div>
              <div class=" col-md-2">
                <div class="form-group">
                  <label for="tipo">Tipo</label>
                  <select class="form-control" id="tipo" name="tipo">
                    <option value=""></option>
                    @foreach($tipoquestaos as $tipo)
                    <option value="{{ $tipo->titulo }}">{{ $tipo->titulo ?? ''}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class=" col-md-5">
                <div class="form-group">
                  <label for="temas_id">Tema</label>
                  <select class="form-control" id="temas_id" name="temas_id">
                    <option value=""></option>
                    @foreach($temas as $tema)
                    <option value="{{ $tema->id }}">{{ $tema->desc ?? ''}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-6">
                <div class="form-group">
                  <label for="habilidades_id">Habilidade</label>
                  <select class="form-control" id="habilidades_id" name="habilidades_id">
                    <option value=""></option>
                    @foreach($habilidades as $habilidade)
                    <option value="{{ $habilidade->id }}">{{ $habilidade->desc ?? ''}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class=" col-md-6">
                <div class="form-group">
                  <label for="prova_gabaritos_id">Prova</label>
                  <select class="form-control" id="prova_gabaritos_id" name="prova_gabaritos_id">
                    <option value=""></option>
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
        <th scope="col" style="color: black;">Descrição</th>
        <th scope="col" style="color: black;">Disciplina</th>
        <th scope="col" style="color: black;">Ano</th>
        <th scope="col" style="color: black;">Modelo</th>
        <th scope="col" style="color: black;">Tema</th>
        <th scope="col" style="color: black;">Habilidade</th>
        <th style="text-align:center; color: black;" scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($questaos as $questao)
      @php
      $disciplina = $questao->find($questao->id)->relDisciplinas;
      @endphp
      <tr>
        <th style="font-weight: normal;font-size:14px;" scope="row">{{$questao->id}}</th>
        <td style="font-weight: normal;font-size:14px;">{{$questao->desc }}</td>
        <td style="font-weight: normal;font-size:14px;">{{$disciplina->desc}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$questao->ano }}</td>
        <td style="font-weight: normal;font-size:14px;">{{$questao->modelo }}</td>
        <td style="font-weight: normal;font-size:14px;">{{$questao->nome_tema}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$questao->nome_habilidade}}</td>
        <td style="text-align:center;">
          <a href="{{ route('questao.create') }}">
            <button style="font-weight: normal;background-color:#f9821E;border-color:#f9821E;" class="btn-primary">Novo</button>
          </a>
          <a href="{{ route('questao.edit', ['id' => $questao->id, 'anosame' => $questao->SAME]) }}">
            <button style="font-weight: normal;background-color:black;border-color:black;" class="btn-primary">Editar</button>
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="row justify-content-center">
    {{ $questaos->appends(Request::except('page'))->links() }}
  </div>

</div>
@endsection