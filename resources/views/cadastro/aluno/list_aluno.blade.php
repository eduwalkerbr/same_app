@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 100px;">
  <div class="card text-center">
    <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
      <h5 class="card-title">Lista de Alunos</h5>
      <p class="card-text">Segue abaixo a lista de Alunos para Visualização e ou Edição.</p>
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
          <form id="form_filter" name="form_filter" action="{{ route('aluno.filtrar') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-5">
                <div class="form-group">
                  <label for="nome">Nome</label>
                  <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do Aluno" value="{{ $request->nome ?? ''}}">
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
              <div class=" col-md-5">
                <div class="form-group">
                  <label for="turmas_escolas_municipios_id">Município</label>
                  <select class="form-control" id="turmas_escolas_municipios_id" name="turmas_escolas_municipios_id">
                    <option value=""></option>                    
                  </select>
                </div>
              </div>
            </div>
            <div class="row justify-content-begin" style="color:black;font-size:15px;">
              <div class=" col-md-5">
                <div class="form-group">
                  <label for="turmas_escolas_id">Escola</label>
                  <select class="form-control" id="turmas_escolas_id" name="turmas_escolas_id">
                    <option value=""></option>
                  </select>
                </div>
              </div>
              <div class=" col-md-5">
                <div class="form-group">
                  <label for="turmas_id">Turma</label>
                  <select class="form-control" id="turmas_id" name="turmas_id">
                    <option value=""></option>
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
        <th scope="col" style="color: black;width:500px;">Nome</th>
        <th scope="col" style="color: black;">Turma</th>
        <th scope="col" style="color: black;">Escola</th>
        <th scope="col" style="color: black;">SAME</th>
        <th style="text-align:center; color: black;" scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($alunos as $aluno)
      <tr>
        <th style="font-weight: normal;font-size:14px;" scope="row">{{$aluno->id}}</th>
        <td style="font-weight: normal;font-size:14px;width:500px;">{{$aluno->nome}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$aluno->nome_turma}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$aluno->nome_escola}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$aluno->SAME}}</td>
        <td style="text-align:center;">
          <a href="{{ route('aluno.create') }}">
            <button style="font-weight: normal;background-color:#f9821E;border-color:#f9821E;" class="btn-primary">Novo</button>
          </a>
          <a href="{{ route('aluno.edit', ['id' => $aluno->id, 'anosame' => $aluno->SAME]) }}">
            <button style="font-weight: normal;background-color:black;border-color:black;" class="btn-primary">Editar</button>
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="row justify-content-center">
    {{ $alunos->appends(Request::except('page'))->links() }}
  </div>

</div>
@endsection