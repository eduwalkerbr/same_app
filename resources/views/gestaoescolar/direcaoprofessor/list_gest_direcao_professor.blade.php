@extends('layouts.appAutorizacao')

@section('content')
<div class="container" style="padding-top: 20px;margin-top: 100px; margin-bottom: 140px;box-shadow: 5px 5px 5px rgba(0,0, 139);background-color:white;">
  <div class="row justify-content-center" style="margin-bottom: 20px;">
    <div class="col-md-10">
      <h4 style="font-weight: bolder; font-size: 18px; color: #f9821E; margin-bottom: 20px;text-align:center;">Lista de Direção Professor</h4>
      <p style="color: black;text-align:center;font-weight:bold;">Segue abaixo a lista de Direção Professor para Visualização e ou Edição.</p>
    </div>
  </div>
  @csrf
  <br>
  <div class="accordion" id="accordionPanelsStayOpenExample">
    <div class="accordion-item">
      <h2 class="accordion-header" id="panelsStayOpen-headingOne">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne" style="background-color:white;color: #0046AD;font-weight:bold;font-size:16px;border: none;">
          Filtros de Consulta
        </button>
      </h2>
      <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingOne">
        <div class="accordion-body">
          <form id="form_filter" name="form_filter" action="{{ route('gest_direcao_professor.filtrar') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-12">
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
            </div>
            <div class="row justify-content-center" style="color:black;font-size:15px;">
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
              <div class=" col-md-4">
                <div class="form-group">
                  <label for="id_escola">Escola</label>
                  <select class="form-control" id="id_escola" name="id_escola">
                    <option value=""></option>
                    @foreach($escolas as $escola)
                    <option value="{{ $escola->id.'_'.$escola->SAME }}">{{ $escola->nome.' ('.$escola->SAME.')' ?? ''}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class=" col-md-6">
                <div class="form-group">
                  <label for="id_turma">Turma</label>
                  <select class="form-control" id="id_turma" name="id_turma">
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
      $turma = $direcao_professor->find($direcao_professor->id)->relTurmas;
      @endphp
      <tr>
        <th style="font-weight: normal;font-size:14px;" scope="row">{{$direcao_professor->id}}</th>
        <td style="font-weight: normal;font-size:14px;">{{$usuario->name}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$funcao->desc}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$direcao_professor->nome_escola ?? ''}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$turma->DESCR_TURMA ?? ''}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$direcao_professor->SAME ?? ''}}</td>
        <td style="text-align:center;">
          <!--<a href="{{ route('gest_cadastro_direcao_professor') }}">
            <button style="font-weight: normal;background-color:#f9821E;border-color:#f9821E;" class="btn-primary">Novo</button>
          </a>-->
          <a href="{{ route('gest_direcao_professor.edit', $direcao_professor->id) }}">
            <button style="font-weight: normal;background-color:black;border-color:black;" class="btn-primary">Editar</button>
          </a>
          <a href="{{ route('gest_direcao_professor.delete', $direcao_professor->id) }}" class="js-del">
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