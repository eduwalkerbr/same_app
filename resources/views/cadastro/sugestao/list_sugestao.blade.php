@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 100px;">
  <div class="card text-center">
    <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
      <h5 class="card-title">Lista de Sugestões</h5>
      <p class="card-text">Segue abaixo a lista de Temas para Visualização e ou Edição.</p>
    </div>
  </div>
  @csrf
  <br>
  <table class="table responsive-md">
    <thead class="thead-light">
      <tr>
        <th scope="col" style="color: black;">id</th>
        <th scope="col" style="color: black;width:550px;">Nome</th>
        <th scope="col" style="color: black;">E-mail</th>
        <th style="text-align:center; color: black;" scope="col">Data</th>
      </tr>
    </thead>
    <tbody>
      @foreach($sugestoes as $sugestao)
      <tr>
        <th style="font-weight: normal;font-size:14px;" scope="row">{{$sugestao->id}}</th>
        <td style="font-weight: normal;font-size:14px;width:550px;">{{$sugestao->nome}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$sugestao->email}}</td>
        <td style="font-weight: normal; text-align:center;font-size:14px;">{{$sugestao->updated_at->format('d/m/Y H:i:s')}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
  <div class="row justify-content-center">
    {{ $sugestoes->links() }}
  </div>

</div>
@endsection