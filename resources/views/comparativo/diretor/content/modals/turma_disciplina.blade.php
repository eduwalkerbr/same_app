<!-- The Modal de Dados Tabelas Turma Disciplina -->
<div class="modal" id="mod_turma_disc">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header" style="background-color:#0046AD;">
                <h4 class="modal-title" style="background-color:#0046AD; color:white;font-size:15px;font-weight:bold;">Comparativo das Turmas da Escola {{$escola_selecionada[0]->nome}} na Disciplina de {{$disciplina_selecionada[0]->desc}}</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <!-- Modal Header -->

            <!-- Modal body -->
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-md-12" style="background-color: white;border: 1px solid white;">
                        <p style="font-size:15px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Seguem abaixo os dados detalhados em Tabela: </p>
                        <div class="row justify-content-center">
                            <table class="table caption-top table-striped">
                                <thead class="table-dark">
                                    <tr style="font-size:15px;vertical-align:initial;text-align:left;">
                                        <td style="text-align:center;font-weight: normal;padding: 0.1em;vertical-align:middle;color:white;font-weight:bold;" scope="row">
                                            Turma
                                        </td>
                                        @foreach($label_turma_disc as $label_turma_disc_item)
                                        <td style="text-align:center;font-weight: normal;padding: 0.1em;vertical-align:middle;color:white;font-weight:bold;" scope="row">
                                            {{$label_turma_disc_item}}
                                        </td>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($itens_turma_disc as $item_turma_disc)
                                    <tr style="font-size:14px;vertical-align:initial;text-align:center;">
                                        <td style="text-align:left;font-weight: normal;padding: 0.1em;vertical-align:middle;color:black;font-weight:bold;" scope="row">
                                            {{$item_turma_disc}}
                                        </td>
                                        @foreach($map_itens_turma_disc as $map_item)
                                        @php
                                        $valor = "Sem Valor";
                                        if(array_key_exists(trim($item_turma_disc),$map_item)){
                                            if($map_item[trim($item_turma_disc)] != '00.0000'){
                                                $valor = $map_item[trim($item_turma_disc)];
                                            }
                                        }
                                        @endphp
                                        @if($valor == 'Sem Valor')
                                        <td style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:red;font-weight:bold;" scope="row">
                                            {{$valor}}
                                        </td>
                                        @else
                                        <td style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">
                                            {{$valor}}%
                                        </td>
                                        @endif
                                        @endforeach
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal body -->

            <!-- Modal footer -->
            <div class="modal-footer">
                <button type="button" style="background-color: #f9821E;border:none;" class="btn btn-danger" data-bs-dismiss="modal">Fechar</button>
            </div>
            <!-- Modal footer -->
        </div>
    </div>
</div>
<!-- The Modal de Dados Tabelas Turma Disciplina -->