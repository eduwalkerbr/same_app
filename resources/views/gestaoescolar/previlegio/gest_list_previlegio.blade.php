@extends('layouts.appAutorizacao')

@section('content')
<div class="container" style="padding-top: 20px;margin-top: 100px; margin-bottom: 140px;box-shadow: 5px 5px 5px rgba(0,0, 139);background-color:white;">
  <div class="row justify-content-center" style="margin-bottom: 20px;">
        <div class="col-md-10">
            <h4 style="font-weight: bolder; font-size: 18px; color: #f9821E; margin-bottom: 20px;text-align:center;">Lista de Privilégios</h4>
            <p style="color: black;text-align:center;font-weight:bold;">Segue abaixo a lista de Privilégios para Visualização e ou Edição.</p>
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
          <form id="form_filter" name="form_filter" action="{{ route('gest_previlegio.filtrar') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-8">
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
                  <label for="municipios_id">Municípios</label>
                  <select class="form-control" id="municipios_id" name="municipios_id">
                    <option value=""></option>          
                    @foreach($municipios as $municipio)
                    <option value="{{ $municipio->id }}">{{ $municipio->nome.' ('.$municipio->SAME.')' ?? ''}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class=" col-md-4">
                <div class="form-group">
                  <label for="funcaos_id">Função</label>
                  <select class="form-control" id="funcaos_id" name="funcaos_id">
                    <option value=""></option>          
                    @foreach($funcaos as $funcao)
                    <option value="{{ $funcao->id }}">{{ $funcao->desc ?? ''}}</option>
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
        <th scope="col" style="color: black;">Usuário</th>
        <th scope="col" style="color: black;">E-Mail</th>
        <th scope="col" style="color: black;">Função</th>
        <th scope="col" style="color: black;text-align:center">Ano SAME</th>
        <th style="text-align:center; color: black;" scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($previlegios as $previlegio)
      @php
      $funcao = $previlegio->find($previlegio->id)->relFuncaos;
      $usuario = $previlegio->find($previlegio->id)->relUsuarios;
      @endphp
      <tr>
        <th style="font-weight: normal;font-size:14px;" scope="row">{{$previlegio->id}}</th>
        <td style="font-weight: normal;font-size:14px;">{{$usuario->name}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$usuario->email}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$funcao->desc}}</td>
        <td style="font-weight: normal;font-size:14px;text-align:center">{{$previlegio->SAME}}</td>
        <td style="text-align:center;width:150px;">
          <a href="{{ route('gest_previlegio.edit', $previlegio->id) }}">
            <button style="font-weight: normal;background-color:black;border-color:black;" class="btn-primary">Editar</button>
          </a>
          @if(isset($previlegio) && $previlegio->status == 1)
          <a href="{{ route('gest_previlegio.inativar',$previlegio->id) }}">
            <button style="font-weight: normal;" class="btn-danger">Inativar</button>
          </a>
          @else
          <a href="{{ route('gest_previlegio.ativar',$previlegio->id) }}">
            <button style="font-weight: normal;" class="btn-danger">Ativar</button>
          </a>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="row justify-content-center">
    {{ $previlegios->appends(Request::except('page'))->links() }}
  </div>

</div>
@endsection