@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 100px;">
  <div class="card text-center">
    <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
      <h5 class="card-title">Lista de Gabritos de Prova</h5>
      <p class="card-text">Segue abaixo a lista de Gabaritos de Prova para Visualização e ou Edição.</p>
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
          <form id="form_filter" name="form_filter" action="{{ route('prova_gabarito.filtrar') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-8">
                <div class="form-group">
                  <label for="DESCR_PROVA">Descrição</label>
                  <input type="text" class="form-control" id="DESCR_PROVA" name="DESCR_PROVA" placeholder="Descrição da Prova" value="">
                </div>
              </div>
              <div class=" col-md-4">
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
            </div>
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-8">
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
        <th scope="col" style="color: black;">Prova</th>
        <th scope="col" style="color: black;">Gabarito</th>
        <th scope="col" style="color: black;">Disciplina</th>
        <th style="text-align:center; color: black;" scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($prova_gabaritos as $prova_gabarito)
      @php
      $disciplina = $prova_gabarito->find($prova_gabarito->id)->relDisciplinas;
      @endphp
      <tr>
        <th style="font-weight: normal;font-size:14px;" scope="row">{{$prova_gabarito->id}}</th>
        <td style="font-weight: normal;font-size:14px;">{{$prova_gabarito->DESCR_PROVA }}</td>
        <td style="font-weight: normal;font-size:14px;">{{$prova_gabarito->gabarito}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$disciplina->desc}}</td>
        <td style="text-align:center;">
          <a href="{{ route('cadastro_prova_gabarito') }}">
            <button style="font-weight: normal;background-color:#f9821E;border-color:#f9821E;" class="btn-primary">Novo</button>
          </a>
          <a href="{{ route('prova_gabarito.edit', $prova_gabarito->id) }}">
            <button style="font-weight: normal;background-color:black;border-color:black;" class="btn-primary">Editar</button>
          </a>
          @if(isset($prova_gabarito) && $prova_gabarito->status == 1)
          <a href="{{ route('prova_gabarito.inativar',$prova_gabarito->id) }}">
            <button style="font-weight: normal;" class="btn-danger">Inativar</button>
          </a>
          @else
          <a href="{{ route('prova_gabarito.ativar',$prova_gabarito->id) }}">
            <button style="font-weight: normal;" class="btn-danger">Ativar</button>
          </a>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="row justify-content-center">
    {{ $prova_gabaritos->appends(Request::except('page'))->links() }}
  </div>

</div>
@endsection