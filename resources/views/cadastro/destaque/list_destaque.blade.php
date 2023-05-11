@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 100px;">
  <div class="card text-center">
    <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
      <h5 class="card-title">Lista de Destaques</h5>
      <p class="card-text">Segue abaixo a lista de Destaques para Visualização e ou Edição.</p>
    </div>
  </div>
  @csrf
  <br>
  <table class="table responsive-md">
    <thead class="thead-light">
      <tr>
        <th scope="col" style="color: black;">id</th>
        <th scope="col" style="color: black;width:550px;">Título</th>
        <th scope="col" style="color: black;">Conteúdo</th>
        <th style="text-align:center; color: black;" scope="col">Data</th>
        <th style="text-align:center; color: black;" scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($destaques as $destaque)
      <tr>
        <th style="font-weight: normal;font-size:14px;" scope="row">{{$destaque->id}}</th>
        <td style="font-weight: normal;font-size:14px;width:550px;">{{$destaque->titulo}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$destaque->conteudo}}</td>
        <td style="font-weight: normal; text-align:center;font-size:14px;">{{$destaque->updated_at->format('d/m/Y H:i:s')}}</td>
        <td style="text-align:center;">
          <a href="{{ route('destaque.create') }}">
            <button style="font-weight: normal;background-color:#f9821E;border-color:#f9821E;" class="btn-primary">Novo</button>
          </a>
          <a href="{{ route('destaque.edit', $destaque->id) }}">
            <button style="font-weight: normal;background-color:black;border-color:black;" class="btn-primary">Editar</button>
          </a>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="row justify-content-center">
    {{ $destaques->links() }}
  </div>

</div>
@endsection