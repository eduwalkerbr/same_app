@extends('layouts.appAutorizacao')

@section('content')
<div class="container" style="padding-top: 20px;margin-top: 100px; margin-bottom: 140px;box-shadow: 5px 5px 5px rgba(0,0, 139);background-color:white;">
    <div class="row justify-content-center" style="margin-bottom: 20px;">
        <div class="col-md-10">
            <h4 style="font-weight: bolder; font-size: 18px; color: #f9821E; margin-bottom: 20px;text-align:center;">Solicitações de Inclusão de Turma</h4>
            <p style="color: black;font-size: 13px; font-weight:bold;">Seguem abaixo as solicitações de inclusões de turma realizadas e que se encontram pendentes de avaliação.</p>
            <p style="color: black;font-size: 13px; font-weight:bold;">Para realizar a avaliação, basta clicar em "Avaliar" e seguir com o processo, aprovando ou negando a solicitação realizada.</p>
        </div>
    </div>
    @foreach($solicitacoes as $solicitacao)
    @php
    $tipoSolicitacao = $solicitacao->find($solicitacao->id)->relTiposSolicitacao;
    @endphp
    <div class="row justify-content-center">
        <div class="col-md-10" style="background-color: white;padding-top:13px;border: 1px solid white;">
            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                <div class="card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:rgba(0,0,139);font-weight:bold;">
                    <i class="fa-solid fa-user-group"></i> &emsp; {{$solicitacao->email ?? ''}}
                </div>
                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                    <p style="color:rgba(107,114,128);font-size: 14px;text-align:justify; font-weight: normal;line-height: 1.5;margin-bottom:20px;" class="card-text">{{$solicitacao->descricao ?? ''}}</p>

                </div>
                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:right;font-size:13px;">
                    <p style="color: black; font-weight: normal;line-height: 1.5;font-size: 13px;text-align:left;" class="card-text">Realizada em {{ \Carbon\Carbon::parse($solicitacao->updated_at)->format('d/m/y h:m:s')}}</p>
                    <a class=" btn btn-link" style="color:#f9821E;" href="{{ route('exibe_registro_usuario',$solicitacao->id) }}">
                        Avaliar &emsp;<i class="fa-solid fa-arrow-right"></i></i>

                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <div class="row justify-content-center">
        <div class="col-md-10" style="padding-top:10px;">
            <div style="margin-top: 20px; margin-bottom: 15px;" class="row justify-content-center">
                {{ $solicitacoes->links() }}
            </div>
        </div>
    </div>
    <!--<div class="col-md-5" style="background-color: #F5FFFA;padding-top:10px;margin-bottom:20px;">
            <img src="{{ asset('images/links_uteis/links.png') }}" class="card-img-top" alt="..." loading="lazy">
        </div>-->
</div>

@endsection