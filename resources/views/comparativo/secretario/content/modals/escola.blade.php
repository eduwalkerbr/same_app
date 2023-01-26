<!-- The Modal de Dados Tabelas Escola -->
<div class="modal" id="mod_escola">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header" style="background-color:#0046AD;">
                <h4 class="modal-title" style="background-color:#0046AD; color:white;font-size:15px;font-weight:bold;">Comparativo de {{$municipio_selecionado[0]->nome}} entre as Escolas</h4>
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
                                            Escola
                                        </td>
                                        @foreach($label_escola as $label_escola_item)
                                        <td style="text-align:center;font-weight: normal;padding: 0.1em;vertical-align:middle;color:white;font-weight:bold;" scope="row">
                                            {{$label_escola_item}}
                                        </td>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($itens_escola as $item_escola)
                                    <tr style="font-size:14px;vertical-align:initial;text-align:center;">
                                        <td style="text-align:left;font-weight: normal;padding: 0.1em;vertical-align:middle;color:black;font-weight:bold;" scope="row">
                                            {{$item_escola}}
                                        </td>
                                        @foreach($map_itens_escola as $map_item)
                                        @php
                                        $valor = "Ausente";
                                        if(array_key_exists(trim($item_escola),$map_item)){
                                            if($map_item[trim($item_escola)] != 'Ausente'){
                                                $valor = $map_item[trim($item_escola)];
                                            }
                                        }
                                        @endphp
                                        @if($valor == 'Ausente')
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
<!-- The Modal de Dados Tabelas Escola -->