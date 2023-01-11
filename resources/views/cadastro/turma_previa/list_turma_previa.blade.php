@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 100px;">
  <div class="card text-center">
    <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
      <h5 class="card-title">Lista de Turmas Prévias</h5>
      <p class="card-text">Segue abaixo a lista de Turmas Prévias para Visualização e ou Edição.</p>
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
          <form id="form_filter" name="form_filter" action="{{ route('turma_previa.filtrar') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-12">
                <div class="form-group">
                  <label for="email">E-mail Usuário</label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="E-mail do Usuário" value="">
                </div>
              </div>
            </div>
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-5">
                <div class="form-group">
                  <label for="id_escola">Escola</label>
                  <select class="form-control" id="id_escola" name="id_escola">
                    <option value=""></option>
                    @foreach($escolas as $escola)
                    <option value="{{ $escola->id }}">{{ $escola->nome.' ('.$escola->SAME.')' ?? ''}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class=" col-md-7">
                <div class="form-group">
                  <label for="id_turma">Turma</label>
                  <select class="form-control" id="id_turma" name="id_turma">
                    <option value=""></option>
                    @foreach($turmas as $turma)
                    <option value="{{ $turma->id }}">{{ $turma->TURMA.' ('.$turma->SAME.')' ?? ''}}</option>
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
        <th scope="col" style="color: black;">E-mail</th>
        <th scope="col" style="color: black;">Turma</th>
        <th scope="col" style="color: black;">Escola</th>
        <th style="text-align:center; color: black;" scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($turmasprevias as $turmaprevia)
      @php
      $turmas = $turmaprevia->find($turmaprevia->id)->relTurmas;
      $escolas = $turmaprevia->find($turmaprevia->id)->relEscolas;
      @endphp
      <tr>
        <th style="font-weight: normal;font-size:14px;" scope="row">{{$turmaprevia->id}}</th>
        <td style="font-weight: normal;font-size:14px;">{{$turmaprevia->email }}</td>
        <td style="font-weight: normal;font-size:14px;">@if(isset($turmas)) {{$turmas->DESCR_TURMA}} @endif</td>
        <td style="font-weight: normal;font-size:14px;">@if(isset($escolas)) {{$escolas->nome}} @endif</td>
        <td style="text-align:center;">
          <a href="{{ route('cadastro_turma_previa') }}">
            <button style="font-weight: normal;background-color:#f9821E;border-color:#f9821E;" class="btn-primary">Novo</button>
          </a>
          <a href="{{ route('turma_previa.edit', $turmaprevia->id) }}">
            <button style="font-weight: normal;background-color:black;border-color:black;" class="btn-primary">Editar</button>
          </a>
          @if(isset($turmaprevia) && $turmaprevia->ativo == true)
          <a href="{{ route('turma_previa.inativar',$turmaprevia->id) }}">
            <button style="font-weight: normal;" class="btn-danger">Inativar</button>
          </a>
          @else
          <a href="{{ route('turma_previa.ativar',$turmaprevia->id) }}">
            <button style="font-weight: normal;" class="btn-danger">Ativar</button>
          </a>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="row justify-content-center">
    {{ $turmasprevias->appends(Request::except('page'))->links() }}
  </div>

</div>
@endsection