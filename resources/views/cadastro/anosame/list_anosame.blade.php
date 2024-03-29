@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 100px;">
  <div class="card text-center">
    <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
      <h5 class="card-title">Lista de Anos SAME</h5>
      <p class="card-text">Segue abaixo a lista de Anos SAME para Visualização e ou Edição.</p>
    </div>
  </div>
  @csrf
  <br>
  <table class="table responsive-md">
    <thead class="thead-light">
      <tr>
        <th scope="col" style="color: black;">id</th>
        <th scope="col" style="color: black;">Descrição</th>
        <th scope="col" style="color: black;">Status</th>
        <th style="text-align:center; color: black;" scope="col">Ações</th>
      </tr>
    </thead>
    <tbody>
      @foreach($anosSame as $anosame)
      <tr>
        <th style="font-weight: normal;font-size:14px;" scope="row">{{$anosame->id}}</th>
        <td style="font-weight: normal;font-size:14px;">{{$anosame->descricao}}</td>
        <td style="font-weight: normal;font-size:14px;">{{$anosame->status}}</td>
        <td style="text-align:center;">
          <a href="{{ route('anosame.create') }}">
            <button style="font-weight: normal;background-color:#f9821E;border-color:#f9821E;" class="btn-primary">Novo</button>
          </a>
          <a href="{{ route('anosame.edit', $anosame->id) }}">
            <button style="font-weight: normal;background-color:black;border-color:black;" class="btn-primary">Editar</button>
          </a>
          @if(isset($anosame) && $anosame->status == 'Ativo')
          <a href="{{ route('anosame.inativar',$anosame->id) }}">
            <button style="font-weight: normal;" class="btn-danger">Inativar</button>
          </a>
          @else
          <a href="{{ route('anosame.ativar',$anosame->id) }}">
            <button style="font-weight: normal;" class="btn-danger">Ativar</button>
          </a>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>

  <div class="row justify-content-center">
    {{ $anosSame->links() }}
  </div>

</div>
@endsection