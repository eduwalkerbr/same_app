<!------------------------------------ Questões Diferenciadas ------------------->
<div class="row justify-content-center">
    <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
        <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
            <div class="card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black;font-weight:bold;">
                <i class="fa-solid fa-percent"></i> &emsp; Questões {{$tipo_questao}} de {{$disciplina_selecionada[0]->desc}}
            </div>
            <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Seguem abaixo as Proficiências por Questão {{$tipo_questao}} na Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                <div class="row justify-content-center" style="margin-top: 2px;">
                    <div class="col-md-6" style="border: 1px solid white;background-color:white;padding-left:10px;padding-right:0px;">
                        <p style="black;font-size: 13px;text-align:justify;margin-top:0px;">
                            @if (($tipo_questao=="Produção Textual") OR ($tipo_questao=="Produção textual"))
                            As questões de <b> {{$tipo_questao}} </b> avaliadas a partir de critérios, os quais geraram níveis de proficiência
                            especificos, tendo por base a pontuação total obtida pelos alunos. Abaixo ficam explicitados os níveis de
                            proficiência, sua relação com as alternativas das questões e respectiva pontuação:
                            @else
                            @if ($tipo_questao=="Escrita da Palavra")
                            As questões de <b> {{$tipo_questao}} </b> consideraram quatro aspectos, se o aluno escreveu a palavra, ou as palavras, corretamente, foneticamente, incorretamente
                            ou não escreveu. Nas questões em que o aluno deveria grafar mais de uma palavra, foi considerada a forma de escrita predominante, para fins de avaliação.
                            @else
                            As questões de <b> {{$tipo_questao}} </b> envolvem a resolução de situações-problema. Na correção foi considerado se o aluno respondeu corretamente,
                            se na resolução houve erro de procedimento ou erro de resultado ou se a questão não foi respondida- foi deixada em branco.
                            @endif
                            @endif
                        </p>




                        <!------------------------------------ Critérios ------------------->
                        @php
                        $cont = 0;
                        @endphp
                        <div class="list-group">
                            @foreach ($criterios_questao as $criterio)
                            @php
                            $tiposquestao = $criterio->find($criterio->id)->relTipoQuestaos;
                            @endphp
                            @if(($tiposquestao->titulo == $tipo_questao)&&($cont < 4)) <a class="list-group-item list-group-item-action" aria-current="true" style="padding-top:0.5em;padding-bottom:0px;" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$criterio->obs}}">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1" style="font-size:14px;color:#f9821e;font-weight:bold;">{{$criterio->nome}}</h6>
                                    <small><i class="fa-solid fa-circle-question"></i></small>
                                </div>
                                <p class="mb-1" style="font-size:12px;text-align:justify;">{{$criterio->descricao}}</p>
                                </a>
                                @php
                                $cont = $cont + 1;
                                @endphp
                                @endif
                                @endforeach
                        </div>
                    </div>



                    <div class="col-md-6" style="border: 1px solid white;background-color:white;padding-left:0px;padding-right:0px;">

                        @foreach ($dados_base_questao_disciplina as $group)
                        <div class="row justify-content-center" style="margin-top: -3px;">
                            <!------------------------------------ Cards ------------------->
                            @foreach ($group as $dados_base)
                            @if($dados_base->tipo_questao == $tipo_questao)
                            @foreach($legendas as $legenda)
                            @php
                            if(number_format($dados_base->percentual_questao, 0, '.', '') >= $legenda->valor_inicial && number_format($dados_base->percentual_questao, 0, '.', '') <= $legenda->valor_final){
                                $corFundo = $legenda->cor_fundo;
                                $corLetra = $legenda->cor_letra;
                                }
                                @endphp
                                @endforeach
                                @if($dados_base->tipo_questao != 'Objetivas')
                                <div class="col-md-4" style="margin-top:20px;border: 1px solid white;background-color:white;padding-left:0px;padding-right:0px;">
                                    <div class="card text-center" style="background-color:white;border: 3px solid {{$dados_base->cor}};box-shadow: 7px 7px 7px {{$dados_base->cor}};" data-bs-toggle="modal" data-bs-target="#{{$dados_base->sigla_questao}}">
                                        <div class="card-header" style="text-align: center;background-color:white; border-bottom:none;font-size:12px;font-weight:bold;color:rgba(75,85,99);padding-bottom:0.1em;">
                                            <a class=" btn btn-link" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_base->nome_questao}}" style="text-decoration:none;text-align: center;background-color:white; border-bottom:none;font-size:12px;font-weight:bold;color:rgba(75,85,99);padding-bottom:0.1em;padding-top:0em;margin-right: 0;margin-left: 0;padding-left:0em;padding-right:0em;" href="#questao_grafico">
                                                {{$dados_base->sigla_questao}} &ensp;<i class="fa-solid fa-circle-question"></i>
                                            </a>
                                        </div>
                                        <div class="card-body" style="padding-bottom:0em;padding-top:0.5em;">
                                            <h5 class="card-title" style="text-align: center;background-color:white; border-radius:50%;border-bottom:none;font-size:20px;color:rgba(75,85,99);font-weight:bold;"><?php echo number_format($dados_base->percentual_questao, 0, '.', '') ?>%</h5>
                                        </div>
                                        <div class="card-footer text-muted" style="background-color: white;padding-top: 0rem;padding-bottom: 0rem;border-top:none;text-align:center;line-heigth: 0em;">
                                            <p style="color:rgba(75,85,99);font-size:9px;margin-bottom: 0.5em;">
                                                {{$dados_base->nome_questao}}

                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="col-md-4" style="margin-top:20px;border: 1px solid white;background-color:white;padding-left:0px;padding-right:0px;">
                                    <div class="card text-center" style="background-color:{{$corFundo}};border: 1px solid #f0f8ff;box-shadow: 5px 5px 5px rgba(156,163,175);" data-bs-toggle="modal" data-bs-target="#{{$dados_base->sigla_questao}}">
                                        <div class="card-header" style="text-align: center;background-color:{{$corFundo}}; border-bottom:none;font-size:12px;font-weight:bold;color:{{$corLetra}};padding-bottom:0.1em;">
                                            <a class=" btn btn-link" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_base->nome_questao}}" style="text-decoration:none;text-align: center;background-color:{{$corFundo}}; border-bottom:none;font-size:12px;font-weight:bold;color:{{$corLetra}};padding-bottom:0.1em;padding-top:0em;margin-right: 0;margin-left: 0;padding-left:0em;padding-right:0em;" href="#questao_grafico">
                                                {{$dados_base->sigla_questao}} &ensp;<i class="fa-solid fa-circle-question"></i>
                                            </a>
                                        </div>
                                        <div class="card-body" style="padding-bottom:0em;padding-top:0.5em;">
                                            <h5 class="card-title" style="text-align: center;background-color:{{$corFundo}}; border-radius:50%;border-bottom:none;font-size:20px;color:{{$corLetra}};font-weight:bold;"><?php echo number_format($dados_base->percentual_questao, 0, '.', '') ?>%</h5>
                                        </div>
                                        <div class="card-footer text-muted" style="background-color: {{$corFundo}};padding-top: 0rem;padding-bottom: 0rem;border-top:none;text-align:center;line-heigth: 0em;">
                                            <p style="color:{{$corLetra}};font-size:9px;margin-bottom: 0.5em;">
                                                {{$dados_base->nome_questao}}

                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                <!-- The Modal Questions -->
                                <div class="modal" id="{{$dados_base->sigla_questao}}">
                                    <div class="modal-dialog modal-lg modal-lg modal-dialog-scrollable">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header" style="background-color:#0046AD;">
                                                <h4 class="modal-title" style="background-color:#0046AD; color:white;font-size:13px;font-weight:bold;">{{$dados_base->nome_questao}}</h4>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">

                                                <div class="row justify-content-center">
                                                    <div class="col-md-12" style="background-color: white;border: 1px solid white;">
                                                        <p style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Seguem abaixo informações relativas a questão selecionada: </p>


                                                        <div class="accordion" id="accordionExample">
                                                            <div class="accordion-item">
                                                                <h2 class="accordion-header" id="headingOne">
                                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="background-color:white;color: #f9821e;font-size:14px;border: none;">
                                                                        Dados Detalhados
                                                                    </button>
                                                                </h2>
                                                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                                    <div class="accordion-body">
                                                                        <div class="row justify-content-center" style="font-size: 12px;">
                                                                            <div class="col-md-6" style="background-color: white;border: 1px solid white;">
                                                                                <p style="text-align:justify;"><b>Tema: </b>{{$dados_base->nome_tema}}</p>
                                                                            </div>
                                                                            <div class="col-md-6" style="background-color: white;border: 1px solid white;">
                                                                                <p style="text-align:justify;"><b>Tipo de Questão: </b>{{$dados_base->tipo_questao}}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="row justify-content-center" style="font-size: 12px;">
                                                                            <div class="col-md-12" style="background-color: white;border: 1px solid white;">
                                                                                <p style="text-align:justify;"><b>Habilidade: </b>{{$dados_base->nome_habilidade}}</p>
                                                                            </div>
                                                                        </div>

                                                                        @php
                                                                        $resposta_A = 0;
                                                                        $resposta_B = 0;
                                                                        $resposta_C = 0;
                                                                        $resposta_D = 0;
                                                                        $cont =0;
                                                                        @endphp
                                                                        @if($dados_base->tipo_questao != 'Objetivas')
                                                                        @foreach ($dados_ajuste_percentual_questao as $ajuste)
                                                                        @php
                                                                        if($ajuste->id_questao == $dados_base->id_questao){
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
                                                                            <table class="table caption-top">
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
                                                                                    @php
                                                                                    $cont=0;
                                                                                    $resposta = 0;
                                                                                    @endphp
                                                                                    @foreach ($criterios_questao as $criterio)
                                                                                    @php
                                                                                    $tiposquestao = $criterio->find($criterio->id)->relTipoQuestaos;
                                                                                    @endphp
                                                                                    @if (($tiposquestao->titulo == $tipo_questao)&&($cont <4)) <tr style="font-size:12px;vertical-align:initial;text-align:center;">
                                                                                        <td style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">
                                                                                            <a class="list-group-item list-group-item-action" aria-current="true" style="padding-top:0.5em;padding-bottom:0px;" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$criterio->obs}}">
                                                                                                <div class="d-flex w-100 justify-content-between">
                                                                                                    <h6 class="mb-1" style="font-size:12px;color:#000000;font-weight:bold;">
                                                                                                        {{$criterio->nome}}
                                                                                                    </h6>
                                                                                                </div>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">
                                                                                            <a class="list-group-item list-group-item-action" aria-current="true" style="padding-top:0.5em;padding-bottom:0px;" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$criterio->obs}}">
                                                                                                <div class="d-flex w-100 justify-content-between">
                                                                                                    <h6 class="mb-1" style="font-size:12px;color:#000000;font-weight:bold;">
                                                                                                        @php
                                                                                                        $cont++;
                                                                                                        if($cont== 1){
                                                                                                        $resposta = $resposta_A;
                                                                                                        } else
                                                                                                        if($cont == 2){
                                                                                                        $resposta = $resposta_B;
                                                                                                        } else
                                                                                                        if($cont == 3){
                                                                                                        $resposta = $resposta_C;
                                                                                                        } else
                                                                                                        if($cont == 4){
                                                                                                        $resposta = $resposta_D;
                                                                                                        }
                                                                                                        @endphp
                                                                                                        {{$resposta}}
                                                                                                    </h6>
                                                                                                </div>
                                                                                            </a>
                                                                                        </td>
                                                                                        </tr>
                                                                                        @endif
                                                                                        @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        @endif

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div style="margin-top:5px;" class="row justify-content-center">
                                                            <div class="col-md-12" id="fotogrande">
                                                                <img id="foto_extensa" style="background-color: white;" src="{{ asset('storage/'.$dados_base->imagem_questao) }}" width="70%" height="70%" class="d-inline-block align-center img-fluid" alt="" loading="lazy">
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal footer -->
                                            <div class="modal-footer">
                                                <button type="button" style="background-color: #f9821E;border:none;" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
                <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">
                    * Os presentes dados representam o percentual de proficiência por Questão {{$tipo_questao}} na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Caso deseje visualizar as informações detalhadas de cada sessão da plataforma, adicione o mouse sobre o ícone &ensp;<i class="fa-solid fa-circle-question"></i>
                </p>
                <!------------------------------------ Legenda ------------------->
                @php
                if(count($tipos_questoes) == $conttq)
                $id_sessao_questao_dif = 'questoesgrafico';
                else if(count($tipos_questoes) > 2)
                $id_sessao_questao_dif = 'questoesdif2';
                else
                $id_sessao_questao_dif = 'questoesgrafico';
                @endphp
                <div class="row justify-content-center" style="margin-top:15px;" id="{{$id_sessao_questao_dif}}">
                    <div class="col-md-6" style="border: 1px solid white;background-color:white;">
                        <div class="row justify-content-center">
                            @foreach($legendas as $legenda)
                            <div class="col-md-3" style="border: 1px solid white;background-color:white;" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$legenda->descricao}}">
                                <p style="color:black;font-size: 11px;text-align:center;font-weight:normal;"><i class="fa-solid fa-cube" style="color:{{$legenda->cor_fundo}};"></i>&nbsp;<b>{{$legenda->exibicao}}</b><br>{{$legenda->titulo}}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;margin-bottom:0;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
            </div>
        </div>
    </div>
</div>