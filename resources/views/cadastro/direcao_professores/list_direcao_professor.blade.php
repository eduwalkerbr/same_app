@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 100px;">
  <div class="card text-center">
    <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
      <h5 class="card-title">Lista de Direção Professor</h5>
      <p class="card-text">Segue abaixo a lista de Direção Professor para Visualização e ou Edição.</p>
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
          <form id="form_filter" name="form_filter" action="{{ route('direcao_professor.filtrar') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-5">
                <div class="form-group">
                  <label for="users_id">Usuário</label>
                  <select class="form-control" id="users_id" name="users_id">
                    <option value=""></option>
                    @foreach($usuarios as $usuario)
                    <option value="{{ $usuario->id }}">{{ $usuario->name ?? ''}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class=" col-md-2">
                <div class="form-group">
                  <label for="SAME">Anos SAME</label>
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
                  <label for="municipios_id">Município</label>
                  <select class="form-control" id="municipios_id" name="municipios_id">
                    <option value=""></option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-6">
                <div class="form-group">
                  <label for="escolas_id">Escola</label>
                  <select class="form-control" id="escolas_id" name="escolas_id">
                    <option value=""></option>
                  </select>
                </div>
              </div>
              <div class=" col-md-6">
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
        <th scope="col" style="color: black;">Usuário</th>
        <th scope="col" style="color: black;">Previlégio</th>
        <th scope="col" style="color: black;">Escola</th>
        <th scope="col" style="color: black;">Turma</th>
        <th scope="col" style="color: black;">Ano SAME</th>
        <th style="text-align:center; color: black;" scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($direcao_professores as $direcao_professor)
      @php
      $previlegio = $direcao_professor->find($direcao_professor->id)->relPrevilegios;
      $funcao = $previlegio->find($previlegio->id)->relFuncaos;
      $usuario = $previlegio->find($previlegio->id)->relUsuarios;
      @endphp
      <tr>
        <th style="font-weight: normal;font-size:14px;" scope="row">{{$direcao_professor->id}}</th>
        <td style="font-weight: normal;font-size:14px;">{{$usuario->name}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$funcao->desc}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$direcao_professor->nome_escola ?? ''}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$direcao_professor->nome_turma ?? ''}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$direcao_professor->SAME ?? ''}}</td>
        <td style="text-align:center;">
          <a href="{{ route('direcao_professor.create') }}">
            <button style="font-weight: normal;background-color:#f9821E;border-color:#f9821E;" class="btn-primary">Novo</button>
          </a>
          <a href="{{ route('direcao_professor.edit', $direcao_professor->id) }}">
            <button style="font-weight: normal;background-color:black;border-color:black;" class="btn-primary">Editar</button>
          </a>
          <a href="{{ route('direcao_professor.destroy', $direcao_professor->id) }}" class="js-del">
            <button style="font-weight: normal;" class="btn-danger">Deletar</button>
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="row justify-content-center">
    {{ $direcao_professores->appends(Request::except('page'))->links() }}
  </div>

</div>
@endsection