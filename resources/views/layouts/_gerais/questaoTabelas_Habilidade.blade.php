@php
    $resposta_A = 0;
    $resposta_B = 0;
    $resposta_C = 0;
    $resposta_D = 0;
@endphp
@if($dados_questao->tipo_questao != 'Objetivas')
    @foreach ($dados_tabela as $ajuste)
        @php
            if($ajuste->id_habilidade == $dados_questao->id_habilidade && $ajuste->id_questao == $dados_questao->id_questao){
                if($ajuste->resposta == 'A'){
                    $resposta_A = $ajuste->qtd;
                } else
                if($ajuste->resposta == 'B'){
                    $resposta_B = $ajuste->qtd;
                } else
                if($ajuste->resposta == 'C'){
                    $resposta_C = $ajuste->qtd;
                } else
                if($ajuste->resposta == 'D'){
                    $resposta_D = $ajuste->qtd;
                }
            }
        @endphp
    @endforeach

    <div class="row justify-content-start" style="font-size: 12px;">
      <p style="text-align:left;margin-bottom:4px;"><b>Quantitativo de Respostas da Questão: </b></p>
    </div>
    <div class="row justify-content-center">

        <table class="table caption-top table-striped">
            <thead>
                <tr style="font-size:12px;vertical-align:initial;text-align:center;">
                    <td style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">
                        <a class="list-group-item list-group-item-action" aria-current="true" style="padding-top:0.5em;padding-bottom:0px;" data-bs-toggle="tooltip" data-bs-placement="right" title="Níveis de proficiência especificos, tendo por base a pontuação total obtida pelos alunos">
                            <div class="d-flex w-100 justify-content-between">
                                <h3 class="mb-1" style="font-size:12px;color:#000000;font-weight:bold;">
                                    Nível de proficiência Geral
                                </h3>
                            </div>
                        </a>
                    </td>
                    <td style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">
                        <a class="list-group-item list-group-item-action" aria-current="true" style="padding-top:0.5em;padding-bottom:0px;" data-bs-toggle="tooltip" data-bs-placement="right" title="Quantidade de alunos que responderam está questão!">
                            <div class="d-flex w-100 justify-content-between">
                                <h3 class="mb-1" style="font-size:12px;color:#000000;font-weight:bold;">
                                    Qtd alunos
                                </h3>
                            </div>
                        </a>
                    </td>                                                                                 
                </tr>
            </thead>
            <tbody>                                              
                <tr style="font-size:12px;vertical-align:initial;text-align:center;">
                    <td style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">
                        <a class="list-group-item list-group-item-action" aria-current="true" style="padding-top:0.5em;padding-bottom:0px;" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_questao->Obs_A}}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1" style="font-size:12px;color:#000000;font-weight:bold;">
                                    {{$dados_questao->nome_A}}
                                </h6>
                            </div>
                        </a>
                    </td>   
                    <td style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">
                        <a class="list-group-item list-group-item-action" aria-current="true" style="padding-top:0.5em;padding-bottom:0px;" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_questao->Obs_A}}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1" style="font-size:12px;color:#000000;font-weight:bold;">
                                    {{$resposta_A}}
                                </h6>
                            </div>
                        </a>
                    </td>  
                </tr>
                <tr style="font-size:12px;vertical-align:initial;text-align:center;">
                    <td style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">
                        <a class="list-group-item list-group-item-action" aria-current="true" style="padding-top:0.5em;padding-bottom:0px;" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_questao->Obs_B}}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1" style="font-size:12px;color:#000000;font-weight:bold;">
                                    {{$dados_questao->nome_B}}
                                </h6>
                            </div>
                        </a>
                    </td>   
                    <td style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">
                        <a class="list-group-item list-group-item-action" aria-current="true" style="padding-top:0.5em;padding-bottom:0px;" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_questao->Obs_B}}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1" style="font-size:12px;color:#000000;font-weight:bold;">
                                    {{$resposta_B}}
                                </h6>
                            </div>
                        </a>
                    </td>  
                </tr>
                <tr style="font-size:12px;vertical-align:initial;text-align:center;">
                    <td style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">
                        <a class="list-group-item list-group-item-action" aria-current="true" style="padding-top:0.5em;padding-bottom:0px;" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_questao->Obs_C}}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1" style="font-size:12px;color:#000000;font-weight:bold;">
                                    {{$dados_questao->nome_C}}
                                </h6>
                            </div>
                        </a>
                    </td>   
                    <td style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">
                        <a class="list-group-item list-group-item-action" aria-current="true" style="padding-top:0.5em;padding-bottom:0px;" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_questao->Obs_C}}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1" style="font-size:12px;color:#000000;font-weight:bold;">
                                    {{$resposta_C}}
                                </h6>
                            </div>
                        </a>
                    </td>  
                </tr>
                <tr style="font-size:12px;vertical-align:initial;text-align:center;">
                    <td style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">
                        <a class="list-group-item list-group-item-action" aria-current="true" style="padding-top:0.5em;padding-bottom:0px;" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_questao->Obs_D}}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1" style="font-size:12px;color:#000000;font-weight:bold;">
                                    {{$dados_questao->nome_D}}
                                </h6>
                            </div>
                        </a>
                    </td>   
                    <td style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">
                        <a class="list-group-item list-group-item-action" aria-current="true" style="padding-top:0.5em;padding-bottom:0px;" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_questao->Obs_D}}">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1" style="font-size:12px;color:#000000;font-weight:bold;">
                                    {{$resposta_D}}
                                </h6>
                            </div>
                        </a>
                    </td>  
                </tr>
        
            </tbody>
        </table>
    </div>
@endif