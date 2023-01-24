<!-- The Modal de Dados Tabelas Habilidade Ano Disciplina -->
<div class="modal" id="mod_hab_ano_disc">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header" style="background-color:#0046AD;">
                <h4 class="modal-title" style="background-color:#0046AD; color:white;font-size:15px;font-weight:bold;">Comparativo de {{$municipio_selecionado[0]->nome}} nas Habilidades em {{$disciplina_selecionada[0]->desc}} no {{$ano[0]}}º Ano</h4>
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
                                            Habilidade
                                        </td>
                                        @foreach($label_hab_ano_disc as $label_hab_ano_disc_item)
                                        <td style="text-align:center;font-weight: normal;padding: 0.1em;vertical-align:middle;color:white;font-weight:bold;" width="10%" scope="row">
                                            {{$label_hab_ano_disc_item}}
                                        </td>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < sizeof($itens_hab_ano_disc); $i++)
                                    <tr style="font-size:14px;vertical-align:initial;text-align:center;">
                                        <td style="text-align:left;font-weight: normal;padding: 0.1em;vertical-align:middle;color:black;font-weight:bold;" scope="row">
                                            {{$nome_hab[$i]}}
                                        </td>
                                        @foreach($map_itens_hab_ano_disc as $map_item)
                                        @php
                                        $valor = "Ausente";
                                        if(array_key_exists(trim($itens_hab_ano_disc[$i]),$map_item)){
                                            if($map_item[trim($itens_hab_ano_disc[$i])] != '00.0000'){
                                                $valor = $map_item[trim($itens_hab_ano_disc[$i])];
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
                                    @endfor
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
<!-- The Modal de Dados Tabelas Habilidade Ano Disciplina -->