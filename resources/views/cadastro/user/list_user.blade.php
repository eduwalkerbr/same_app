@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 100px;">
  <div class="card text-center">
    <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
      <h5 class="card-title">Lista de Usuários</h5>
      <p class="card-text">Segue abaixo a lista de Usuários para Visualização e ou Edição.</p>
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
          <form id="form_filter_user" name="form_filter_user" action="{{ route('user.filtrar') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row justify-content-center" style="color:black;font-size:15px;">
              <div class=" col-md-6">
                <div class="form-group">
                  <label for="name">Nome</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Nome do Usuário" value="">
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="email">E-Mail</label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="E-mail do Usuário" value="">
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
        <th scope="col" style="color: black;">E-mail</th>
        <th style="text-align:center; color: black;" scope="col">Data</th>
        <th style="text-align:center; color: black;" scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($users as $user)
      <tr>
        <th style="font-weight: normal;font-size:14px;" scope="row">{{$user->id}}</th>
        <td style="font-weight: normal;font-size:14px;">{{$user->name}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$user->email}}</td>
        <td style="font-weight: normal; text-align:center;font-size:14px;">{{$user->updated_at->format('d/m/Y H:i:s')}}</td>
        <td style="text-align:center;">
          <a href="{{ route('user.create') }}">
            <button style="font-weight: normal;background-color:#f9821E;border-color:#f9821E;" class="btn-primary">Novo</button>
          </a>
          <a href="{{ route('user.edit', $user->id) }}">
            <button style="font-weight: normal;background-color:black;border-color:black;" class="btn-primary">Editar</button>
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="row justify-content-center">
    {{ $users->appends(Request::except('page'))->links() }}
  </div>

</div>
@endsection