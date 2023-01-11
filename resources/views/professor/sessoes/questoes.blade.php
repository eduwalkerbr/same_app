<div class="row justify-content-center">
    <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
        <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
            <!------------------------------------ Título Card ------------------->
            <div class="card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black;font-weight:bold;">
                <i class="fa-solid fa-percent"></i> &emsp; Questões {{$tipo_questao}} de {{$disciplina_selecionada[0]->desc}}
            </div>
            <!------------------------------------ Título Card ------------------->
            <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Seguem abaixo as Proficiências por Questão {{$tipo_questao}} na Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                <!------------------------------------ Cards ------------------->
                @foreach ($dados_base_questao_disciplina as $group)
                <div class="row justify-content-center" style="margin-top: -10px;">
                    @foreach ($group as $dados_base)
                    @if($dados_base->tipo_questao == 'Objetivas')
                    @foreach($legendas as $legenda)
                    @php
                    if(number_format($dados_base->percentual_questao, 0, '.', '') >= $legenda->valor_inicial && number_format($dados_base->percentual_questao, 0, '.', '') <= $legenda->valor_final){
                        $corFundo = $legenda->cor_fundo;
                        $corLetra = $legenda->cor_letra;
                        }
                        @endphp
                        @endforeach
                        <!-- Card Individual -->
                        <div class="col-md-2" style="margin-top:20px;border: 1px solid white;background-color:white;padding-left:0px;padding-right:0px;">
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
                        <!-- Card Individual -->

                        <!-- The Modal Questions -->
                        <div class="modal" id="{{$dados_base->sigla_questao}}">
                            <div class="modal-dialog modal-lg modal-lg modal-dialog-scrollable">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header" style="background-color:#0046AD;">
                                        <h4 class="modal-title" style="background-color:#0046AD; color:white;font-size:13px;font-weight:bold;">{{$dados_base->nome_questao}}</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <!-- Modal Header -->

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <div class="row justify-content-center">
                                            <div class="col-md-12" style="background-color: white;border: 1px solid white;">
                                                <p style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Seguem abaixo informações relativas a questão selecionada: </p>
                                                <!-- Acordions de Questões -->
                                                <div class="accordion" id="accordionExample">
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingOne">
                                                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="background-color:white;color: #f9821e;font-size:14px;border: none;">
                                                                Dados Detalhados
                                                            </button>
                                                        </h2>
                                                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <!-- Dados Cabeçalho Acordion -->
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

                                                                @if($dados_base->tipo_questao == 'Objetivas')
                                                                <div class="row justify-content-center" style="font-size: 12px;">
                                                                    <div class="col-md-12" style="background-color: white;border: 1px solid white;">
                                                                        <p style="text-align:justify;"><b>Resposta correta: </b>{{$dados_base->correta}}</p>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                <!-- Dados Cabeçalho Acordion -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!------------------------------------ Imagem Questão ------------------->
                                                <div style="margin-top:5px;" class="row justify-content-center">
                                                    <div class="col-md-12" id="fotogrande">
                                                        <img id="foto_extensa" style="background-color: white;" src="{{ asset('storage/'.$dados_base->imagem_questao) }}" width="70%" height="70%" class="d-inline-block align-center img-fluid" alt="" loading="lazy">
                                                    </div>
                                                </div>
                                                <!------------------------------------ Imagem Questão ------------------->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Modal body -->
                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button type="button" style="background-color: #f9821E;border:none;" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    </div>
                                    <!-- Modal footer -->
                                </div>
                            </div>
                        </div>
                        <!-- The Modal Questões Habilidade -->
                        @endif
                        @endforeach
                </div>
                @endforeach
                <!------------------------------------ Cards ------------------->
                <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                    * Os presentes dados representam o percentual de proficiência por Questão na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Caso deseje visualizar as informações detalhadas da questão, clique sobre o ícone &ensp;<i class="fa-solid fa-circle-question"></i>
                </p>
                <!------------------------------------ Legenda ------------------->
                <div class="row justify-content-center" style="margin-top:15px;">
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
                <!------------------------------------ Legenda ------------------->
                <p style="color:black;font-size: 12px;text-align:right;;margin-top:15px;margin-bottom:5px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
            </div>
            <!------------------------------------ Navegação ------------------->
            <div class="card-footer text-muted" style="background-color: white;padding-top: 0em;padding-bottom: 0.3rem;border-top:none;text-align:justify;" id="proximoquestoesobjetivas">
                <div class="row justify-content-center">
                    @if(count($dados_base_habilidade_disciplina_grafico_habilidade) > 1)
                    <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                        <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#habilidadedisciplinahabilidade">
                            Voltar para Gráfico Habilidades em {{$disciplina_selecionada[0]->desc}} no transcorrer dos Anos &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>
                        </a>
                    </div>
                    @else
                    <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                        <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#graficohabilidadeanodisciplina">
                            Habilidade Selecionada em {{$disciplina_selecionada[0]->desc}} no transcorrer dos Anos &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>
                        </a>
                    </div>
                    @endif
                    @if(count($tipos_questoes) == 1)
                    <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                        <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#proximoquestoesobjetivas">
                            Gráfico Questões em {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>
                        </a>
                    </div>
                    @else
                    <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                        <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#proximoquestoesobjetivas">
                            Questões {{$tipos_questoes[1]}} em {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            <!------------------------------------ Navegação ------------------->
        </div>
    </div>
</div>