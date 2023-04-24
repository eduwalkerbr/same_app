<div class="row justify-content-center">
    <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
        <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
            <!------------------------------------ Título Card ------------------->
            <div class="card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black;font-weight:bold;">
                <i class="fa-solid fa-percent"></i> &emsp; Habilidade {{$habilidade_selecionada[0]->sigla_habilidade}} em {{$disciplina_selecionada[0]->desc}} no transcorrer dos Anos
            </div>
            <!------------------------------------ Título Card ------------------->
            <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Seguem abaixo as Proficiências na Habilidade {{$habilidade_selecionada[0]->nome_habilidade}} em {{$disciplina_selecionada[0]->desc}} no transcorrer dos Anos</p>
                <div class="row justify-content-center">
                    <div class="col-md-12" style="background-color: white;border: 1px solid white;">
                        <!------------------------------------ Menu Habilidade ------------------->
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb" style="background-color: white;border:none;color:#0046AD;padding: 0.1em 0.5em;">
                                <li class="breadcrumb-item">
                                <li class="nav-item dropdown nav-fill">
                                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" style="color:black;font-size: 13px;border: 1px solid black;border-radius: 10px;"><i class="fa-brands fa-wpforms"></i>&emsp;@php $hab_nome = substr($habilidade_selecionada[0]->nome_habilidade,0,150);@endphp {{$hab_nome}}</a>
                                    <ul class="dropdown-menu navbar-nav-scroll" style="max-height:220px;">
                                        @foreach($habilidades as $habilidade)
                                        <li><a class="dropdown-item" style="color:black;font-size: 13px;" href="{{ route('professor.exibirTurmaHabilidade', ['id' => $turma_selecionada[0]->id, 'id_disciplina' => $disciplina_selecionada[0]->id, 'id_escola' => $escola_selecionada[0]->id,'ano' => $ano[0],'id_habilidade' => $habilidade->id_habilidade, 'ano_same' => $ano_same_selecionado]) }}">
                                                @php $hab_nome = substr($habilidade->nome_habilidade,0,150);@endphp @if(strlen($habilidade->nome_habilidade)>149) {{$hab_nome}}... @else {{$hab_nome}} @endif
                                            </a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ol>
                        </nav>
                        <!------------------------------------ Menu Habilidade ------------------->
                    </div>
                </div>
                @php
                $count = 0;
                @endphp
                <!------------------------------------ Cards ------------------->
                @foreach ($dados_base_habilidades_disciplina_habilidade as $group)
                <div class="row justify-content-center" style="margin-top: -10px;">
                    @foreach ($group as $dados_base)
                    @foreach($legendas as $legenda)
                    @php
                    if(number_format($dados_base->percentual_habilidade, 0, '.', '') >= $legenda->valor_inicial && number_format($dados_base->percentual_habilidade, 0, '.', '') <= $legenda->valor_final){
                        $corFundo = $legenda->cor_fundo;
                        $corLetra = $legenda->cor_letra;
                        }
                        @endphp
                        @endforeach
                        @php
                        $count++;
                        @endphp
                        <!------------------------------------ Card Individual ------------------->
                        @if($dados_base->tipo_questao != 'Objetivas')
                        <div class="col-md-2" style="margin-top:20px;border: 1px solid white;background-color:white;padding-left:0px;padding-right:0px;" data-bs-toggle="tooltip" data-bs-placement="right" title="Este Percentual corresponde ao Nível de Avaliação que representa a maior parte dos Alunos.">
                            <div class="card text-center" style="background-color:white;border: 3px solid {{$dados_base->cor}};box-shadow: 7px 7px 7px {{$dados_base->cor}};" data-bs-toggle="modal" data-bs-target="#HABILIDADE{{$count}}">
                                <div class="card-header" style="text-align: center;background-color:white; border-bottom:none;font-size:12px;font-weight:bold;color:rgba(75,85,99);padding-bottom:0.1em;">
                                    <a class=" btn btn-link" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_base->nome_habilidade}}" style="text-decoration:none;text-align: center;background-color:white; border-bottom:none;font-size:12px;font-weight:bold;color:rgba(75,85,99);padding-bottom:0.1em;padding-top:0em;margin-right: 0;margin-left: 0;padding-left:0em;padding-right:0em;" href="#habilidade_grafico">
                                        {{$dados_base->sigla_ano}} &ensp;<i class="fa-solid fa-circle-question"></i>
                                    </a>
                                </div>
                                <div class="card-body" style="padding-bottom:0em;padding-top:0.5em;">
                                    <h5 class="card-title" style="text-align: center;background-color:white; border-radius:50%;border-bottom:none;font-size:20px;color:rgba(75,85,99);font-weight:bold;"><?php echo number_format($dados_base->percentual_habilidade, 0, '.', '') ?>% **</h5>
                                </div>
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0rem;padding-bottom: 0rem;border-top:none;text-align:center;line-heigth: 0em;" id="habilidade_grafico_matematica">
                                    <p style="color:rgba(75,85,99);font-size:12px;margin-bottom: 0.5em;">
                                        {{$dados_base->nome_disciplina}}

                                    </p>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-md-2" style="margin-top:20px;border: 1px solid white;background-color:white;padding-left:0px;padding-right:0px;">
                            <div class="card text-center" style="background-color:{{$corFundo}};border: 1px solid #f0f8ff;box-shadow: 5px 5px 5px rgba(156,163,175);" data-bs-toggle="modal" data-bs-target="#HABILIDADE{{$count}}">
                                <div class="card-header" style="text-align: center;background-color:{{$corFundo}}; border-bottom:none;font-size:12px;font-weight:bold;color:{{$corLetra}};padding-bottom:0.1em;">
                                    <a class=" btn btn-link" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_base->nome_habilidade}}" style="text-decoration:none;text-align: center;background-color:{{$corFundo}}; border-bottom:none;font-size:12px;font-weight:bold;color:{{$corLetra}};padding-bottom:0.1em;padding-top:0em;margin-right: 0;margin-left: 0;padding-left:0em;padding-right:0em;" href="#habilidade_grafico">
                                        {{$dados_base->sigla_ano}} &ensp;<i class="fa-solid fa-circle-question"></i>
                                    </a>
                                </div>
                                <div class="card-body" style="padding-bottom:0em;padding-top:0.5em;">
                                    <h5 class="card-title" style="text-align: center;background-color:{{$corFundo}}; border-radius:50%;border-bottom:none;font-size:20px;color:{{$corLetra}};font-weight:bold;"><?php echo number_format($dados_base->percentual_habilidade, 0, '.', '') ?>%</h5>
                                </div>
                                <div class="card-footer text-muted" style="background-color: {{$corFundo}};padding-top: 0rem;padding-bottom: 0rem;border-top:none;text-align:center;line-heigth: 0em;" id="habilidade_grafico_matematica">
                                    <p style="color:{{$corLetra}};font-size:12px;margin-bottom: 0.5em;">
                                        {{$dados_base->sigla_habilidade}}

                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                        <!------------------------------------ Card Individual ------------------->

                        <!-- The Modal Questões Habilidade -->
                        <div class="modal" id="HABILIDADE{{$count}}">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header" style="background-color:#0046AD;">
                                        <h4 class="modal-title" style="background-color:#0046AD; color:white;font-size:13px;font-weight:bold;">{{$dados_base->nome_habilidade}}</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <!-- Modal Header -->

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <div class="row justify-content-center">
                                            <div class="col-md-12" style="background-color: white;border: 1px solid white;">
                                                <!-- Cabeçalho Modal Card -->
                                                <p style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Seguem abaixo informações relativas a Habilidade selecionada: </p>
                                                <div class="row justify-content-center">
                                                    <div class="col-md-12" id="fotogrande">
                                                        <p style="text-align:justify;font-size:12px;"><b>Disciplina: </b>{{$dados_base->nome_disciplina}}</p>
                                                    </div>
                                                </div>
                                                <!-- Cabeçalho Modal Card -->
                                                <div class="row justify-content-center">
                                                    <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                                                        <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                                            <!-- Título Modal Card -->
                                                            <div class="card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:13px;color:black;font-weight:bold;">
                                                                <i class="fa-solid fa-percent"></i> &emsp; Percentual Individual das Questões relacionadas a presente Habilidade
                                                            </div>
                                                            <!-- Título Modal Card -->
                                                            <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                                                                <div class="row justify-content-center">
                                                                    @foreach ($dados_base_habilidade_questao_habilidade as $dados_questao)
                                                                    @if($dados_questao->id_habilidade == $dados_base->id_habilidade && $dados_questao->ano == $dados_base->ano && $dados_questao->tipo_questao == $dados_base->tipo_questao)
                                                                    @foreach($legendas as $legenda)
                                                                    @php
                                                                    if(number_format($dados_questao->percentual_habilidade, 0, '.', '') >= $legenda->valor_inicial && number_format($dados_questao->percentual_habilidade, 0, '.', '') <= $legenda->valor_final){
                                                                        $corFundoQuestao = $legenda->cor_fundo;
                                                                        $corLetraQuestao = $legenda->cor_letra;
                                                                        }
                                                                        @endphp
                                                                        @endforeach
                                                                        <!-- Card Individual Interno Modal -->
                                                                        @if($dados_base->tipo_questao != 'Objetivas')
                                                                        <div class="col-md-3" style="margin-top:20px;border: 1px solid white;background-color:white;padding-left:0px;padding-right:0px;">
                                                                            <div class="card text-center" style="background-color:white;border: 3px solid {{$dados_base->cor}};box-shadow: 7px 7px 7px {{$dados_base->cor}};">
                                                                                <div class=" card-header" style="text-align: center;background-color:white; border-bottom:none;font-size:12px;font-weight:bold;color:rgba(75,85,99);padding-bottom:0.1em;">
                                                                                    <a class=" btn btn-link" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_base->nome_habilidade}}" style="text-decoration:none;text-align: center;background-color:white; border-bottom:none;font-size:12px;font-weight:bold;color:rgba(75,85,99);padding-bottom:0.1em;padding-top:0em;margin-right: 0;margin-left: 0;padding-left:0em;padding-right:0em;">
                                                                                        {{$dados_questao->ano}}º Ano&emsp; <i class="fa-solid fa-circle-question"></i>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="card-body" style="padding-bottom:0em;padding-top:0.5em;">
                                                                                    <h5 class="card-title" style="text-align: center;background-color:white; border-radius:50%;border-bottom:none;font-size:20px;color:rgba(75,85,99);font-weight:bold;"><?php echo number_format($dados_questao->percentual_habilidade, 0, '.', '') ?>%</h5>
                                                                                </div>
                                                                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0rem;padding-bottom: 0rem;border-top:none;text-align:center;line-heigth: 0em;" id="habilidade_grafico_matematica">
                                                                                    <p style="color:rgba(75,85,99);font-size:12px;margin-bottom: 0.5em;">
                                                                                        {{$dados_questao->desc_questao}}

                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @else
                                                                        <div class="col-md-3" style="margin-top:20px;border: 1px solid white;background-color:white;padding-left:0px;padding-right:0px;">
                                                                            <div class="card text-center" style="background-color:{{$corFundoQuestao}};border: 1px solid #f0f8ff;box-shadow: 5px 5px 5px rgba(156,163,175);">
                                                                                <div class=" card-header" style="text-align: center;background-color:{{$corFundoQuestao}}; border-bottom:none;font-size:12px;font-weight:bold;color:{{$corLetraQuestao}};padding-bottom:0.1em;">
                                                                                    <a class=" btn btn-link" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_base->nome_habilidade}}" style="text-decoration:none;text-align: center;background-color:{{$corFundoQuestao}}; border-bottom:none;font-size:12px;font-weight:bold;color:{{$corLetraQuestao}};padding-bottom:0.1em;padding-top:0em;margin-right: 0;margin-left: 0;padding-left:0em;padding-right:0em;">
                                                                                        {{$dados_questao->ano}}º Ano&emsp; <i class="fa-solid fa-circle-question"></i>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="card-body" style="padding-bottom:0em;padding-top:0.5em;">
                                                                                    <h5 class="card-title" style="text-align: center;background-color:{{$corFundoQuestao}}; border-radius:50%;border-bottom:none;font-size:20px;color:{{$corLetraQuestao}};font-weight:bold;"><?php echo number_format($dados_questao->percentual_habilidade, 0, '.', '') ?>%</h5>
                                                                                </div>
                                                                                <div class="card-footer text-muted" style="background-color: {{$corFundoQuestao}};padding-top: 0rem;padding-bottom: 0rem;border-top:none;text-align:center;line-heigth: 0em;" id="habilidade_grafico_matematica">
                                                                                    <p style="color:{{$corLetraQuestao}};font-size:12px;margin-bottom: 0.5em;">
                                                                                        {{$dados_questao->desc_questao}}

                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                        <!-- Card Individual Interno Modal -->
                                                                        @endif
                                                                        @endforeach

                                                                </div>
                                                            </div>
                                                            <div class="card-footer text-muted" style="background-color: white;padding-top: 0em;padding-bottom: 0.3rem;border-top:none;text-align:justify;" id="turmasportugues">
                                                                <div class="row justify-content-center">
                                                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">
                                                                        * Os presentes percentuais acima, correspondem ao valor individual de percentual de cada uma das questões que compõe a Habilidade e por consequência, o percentual da habilidade.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <!-- Acordions de Questões -->
                                                            <div class="accordion" id="HAH{{$dados_base->sigla_habilidade}}">
                                                                @foreach ($dados_base_habilidade_questao_habilidade as $dados_questao)
                                                                <!-- Acordion Individual de Questão -->
                                                                @if($dados_questao->id_habilidade == $dados_base->id_habilidade && $dados_questao->ano == $dados_base->ano && $dados_questao->tipo_questao == $dados_base->tipo_questao)
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header" id="QAH{{$dados_questao->desc_questao}}">
                                                                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#QAH{{$dados_questao->id_questao}}" aria-expanded="false" aria-controls="collapseOne" style="background-color:white;color: #f9821e;font-size:14px;border: none;">
                                                                            {{$dados_questao->desc_questao}}
                                                                        </button>
                                                                    </h2>
                                                                    <div id="QAH{{$dados_questao->id_questao}}" class="accordion-collapse collapse" aria-labelledby="QHH{{$dados_questao->desc_questao}}" data-bs-parent="#HAH{{$dados_base->sigla_habilidade}}">
                                                                        <div class="accordion-body">
                                                                            <!-- Dados Cabeçalho Acordion -->
                                                                            <div class="row justify-content-center" style="font-size: 12px;">
                                                                                <div class="col-md-6" style="background-color: white;border: 1px solid white;">
                                                                                    <p style="text-align:justify;"><b>Tema: </b>{{$dados_questao->nome_tema}}</p>
                                                                                </div>
                                                                                <div class="col-md-6" style="background-color: white;border: 1px solid white;">
                                                                                    <p style="text-align:justify;"><b>Tipo de Questão: </b>{{$dados_questao->tipo_questao}}</p>
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
                                                                                    <p style="text-align:justify;"><b>Resposta correta: </b>{{$dados_questao->correta}}</p>
                                                                                </div>
                                                                            </div>
                                                                            @endif
                                                                            <!-- Dados Cabeçalho Acordion -->

                                                                            <!------------------------------------ Questões Tabelas ------------------->
                                                                            @php
                                                                            $dados_tabela=$dados_ajuste_percentual_ano;
                                                                            @endphp
                                                                            @include('layouts/_gerais.questaoTabelas')
                                                                            <!------------------------------------ Questões Tabelas ------------------->

                                                                            <!------------------------------------ Imagem Questão ------------------->
                                                                            <div style="margin-top:5px;" class="row justify-content-center">
                                                                                <div class="col-md-12" id="fotogrande">
                                                                                    <img id="foto_extensa" style="background-color: white;" src="{{ asset('storage/'.$dados_questao->imagem_questao) }}" width="70%" height="70%" class="d-inline-block align-center img-fluid" alt="" loading="lazy">
                                                                                </div>
                                                                            </div>
                                                                            <!------------------------------------ Imagem Questão ------------------->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                @endif
                                                                <!-- Acordion Individual de Questão -->
                                                                @endforeach
                                                            </div>
                                                            <!-- Acordions de Questões -->
                                                        </div>
                                                    </div>
                                                </div>
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
                        <!-- The Modal de Questões de Cada Habilidade -->
                        @endforeach
                        <!------------------------------------ Cards ------------------->
                </div>
                @endforeach
                <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">
                    * Os presentes dados representam o percentual de proficiência da Habilidade selecionada, na Disciplina de {{$disciplina_selecionada[0]->desc}} da presente Turma no transcorrer dos Anos.
                    <br>* Caso deseje visualizar o nome completo da habilidade, adicione o mouse sobre o ícone &ensp;<i class="fa-solid fa-circle-question"></i>

                    <br>** Este Percentual corresponde ao Nível de Avaliação que representa a maior parte dos Alunos, sendo a cor da borda o nível de proficiência conforme a legenda abaixo.

                </p>
                <!------------------------------------ Legenda ------------------->
                @php
                    if(intval(count($dados_base_habilidade_disciplina_grafico_habilidade)) > 1)
                        $id_sessao_habilidade = 'habilidadeselecionadadisciplinagrafico';    
                    else      
                        $id_sessao_habilidade = 'questoesobjetivas';   
                @endphp
                <div class="row justify-content-center" style="margin-top:15px;" id="{{$id_sessao_habilidade}}">
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
                <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;margin-bottom:0;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>

            </div>
        </div>

    </div>
</div>