@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 100px;">
  <div class="card text-center">
    <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
      <h5 class="card-title">Lista de Turmas</h5>
      <p class="card-text">Segue abaixo a lista de Turmas para Visualização e ou Edição.</p>
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
          <form id="form_filter" name="form_filter" action="{{ route('turma.filtrar') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-6">
                <div class="form-group">
                  <label for="TURMA">Turma</label>
                  <input type="text" class="form-control" id="TURMA" name="TURMA" placeholder="Turma" value="">
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
              <div class=" col-md-4">
                <div class="form-group">
                  <label for="municipios_id">Município</label>
                  <select class="form-control" id="municipios_id" name="municipios_id">
                    <option value=""></option>                    
                  </select>
                </div>
              </div>
            </div>
            <div class="row justify-content-begin" style="color:black;font-size:15px;">
              <div class=" col-md-6">
                <div class="form-group">
                  <label for="escolas_id">Escola</label>
                  <select class="form-control" id="escolas_id" name="escolas_id">
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
        <th scope="col" style="color: black;">Descrição</th>
        <th scope="col" style="color: black;">Escola</th>
        <th scope="col" style="color: black;">Município</th>
        <th scope="col" style="color: black;text-align:center;">Ano SAME</th>
        <th style="text-align:center; color: black;" scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($turmas as $turma)
      <tr>
        <th style="font-weight: normal;font-size:14px;" scope="row">{{$turma->id}}</th>
        <td style="font-weight: normal;font-size:14px;">{{$turma->DESCR_TURMA}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$turma->nome_escola}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$turma->nome_municipio}}</td>
        <td style="font-weight: normal;font-size:14px;text-align:center;">{{$turma->SAME}}</td>
        <td style="text-align:center;">
          <a href="{{ route('turma.create') }}">
            <button style="font-weight: normal;background-color:#f9821E;border-color:#f9821E;" class="btn-primary">Novo</button>
          </a>
          <a href="{{ route('turma.edit', ['id' => $turma->id, 'anosame' => $turma->SAME]) }}">
            <button style="font-weight: normal;background-color:black;border-color:black;" class="btn-primary">Editar</button>
          </a>
          @if(isset($turma) && $turma->status == 'Ativo')
          <a href="{{ route('turma.inativar',['id' => $turma->id, 'anosame' => $turma->SAME]) }}">
            <button style="font-weight: normal;" class="btn-danger">Inativar</button>
          </a>
          @else
          <a href="{{ route('turma.ativar',['id' => $turma->id, 'anosame' => $turma->SAME]) }}">
            <button style="font-weight: normal;" class="btn-danger">Ativar</button>
          </a>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="row justify-content-center">
    {{ $turmas->appends(Request::except('page'))->links() }}
  </div>

</div>
@endsection