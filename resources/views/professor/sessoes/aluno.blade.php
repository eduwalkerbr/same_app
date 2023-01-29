<div class="row justify-content-center">
    <div class="card-deck" style="background-color: white;padding-bottom:16px;border: 1px solid white;">
        <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
            <div class="card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black;font-weight:bold;">
                <i class="fa-solid fa-percent"></i> &emsp; Alunos da Disciplina de {{$disciplina_selecionada[0]->desc}}
            </div>
            <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Seguem abaixo as Proficiências por Aluno na Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                @php
                $count = 0;
                @endphp
                <!------------------------------------ Cards ------------------->
                @foreach ($dados_base_aluno_disciplina as $group)
                <div class="row justify-content-center" style="margin-top: -10px;">
                    @foreach ($group as $dados_base)
                    @foreach($legendas as $legenda)
                    @php
                    if(number_format($dados_base->percentual_aluno, 0, '.', '') >= $legenda->valor_inicial && number_format($dados_base->percentual_aluno, 0, '.', '') <= $legenda->valor_final){
                        $count = $count + 1;
                        $corFundo = $legenda->cor_fundo;
                        $corLetra = $legenda->cor_letra;
                        }
                        @endphp
                        @endforeach
                        <!------------------------------------ Card Individual ------------------->
                        <div class="col-md-2" style="margin-top:20px;border: 1px solid white;background-color:white;padding-left:0px;padding-right:0px;">
                            <div class="card text-center" style="background-color:{{$corFundo}};border: 1px solid #f0f8ff;box-shadow: 5px 5px 5px rgba(156,163,175);" data-bs-toggle="modal" data-bs-target="#{{$dados_base->sigla_aluno}}">
                                <div class="card-header" style="text-align: center;background-color:{{$corFundo}}; border-bottom:none;font-size:12px;font-weight:bold;color:{{$corLetra}};padding-bottom:0.1em;">
                                    <a class=" btn btn-link" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_base->nome_aluno}}" style="text-decoration:none;text-align: center;background-color:{{$corFundo}}; border-bottom:none;font-size:12px;font-weight:bold;color:{{$corLetra}};padding-bottom:0.1em;padding-top:0em;margin-right: 0;margin-left: 0;padding-left:0em;padding-right:0em;" href="#questao_grafico">
                                        {{$dados_base->sigla_aluno}} &ensp;<i class="fa-solid fa-circle-question"></i>
                                    </a>
                                </div>
                                <div class="card-body" style="padding-bottom:0em;padding-top:0.5em;">
                                    <h5 class="card-title" style="text-align: center;background-color:{{$corFundo}}; border-radius:50%;border-bottom:none;font-size:20px;color:{{$corLetra}};font-weight:bold;"><?php echo number_format($dados_base->percentual_aluno, 0, '.', '') ?>%</h5>
                                </div>
                                <div class="card-footer text-muted" style="background-color: {{$corFundo}};padding-top: 0rem;padding-bottom: 0rem;border-top:none;text-align:center;line-heigth: 0em;">
                                    <p style="color:{{$corLetra}};font-size:8px;margin-bottom: 0.5em;padding-right:0;padding-left:0;">
                                        @php
                                        $previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
                                        @endphp
                                        @if($previlegio->funcaos_id == 7 || Auth::user()->perfil == 'Administrador')
                                        {{$dados_base->nome_aluno_abreviado}}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------ Card Individual ------------------->

                        <!-- The Modal Questions -->
                        <div class="modal" id="{{$dados_base->sigla_aluno}}">
                            <div class="modal-dialog modal-lg modal-lg modal-dialog-scrollable">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header" style="background-color:#0046AD;">
                                        <h4 class="modal-title" style="background-color:#0046AD; color:white;font-size:13px;font-weight:bold;">
                                            @php
                                            $previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
                                            @endphp
                                            @if($previlegio->funcaos_id == 7 || Auth::user()->perfil == 'Administrador')
                                            {{$dados_base->nome_aluno}}
                                            @else
                                            {{$dados_base->sigla_aluno}}
                                            @endif
                                        </h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <!-- Modal Header -->

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <div class="row justify-content-center">
                                            <div class="col-md-12" style="background-color: white;border: 1px solid white;">
                                                <p style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Seguem abaixo informações relativas ao aluno selecionado: </p>
                                                <nav>
                                                    <div class="nav nav-tabs" id="nav-tab-novo" role="tablist" style="border-bottom: 1px solid #f9821e;">
                                                        <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true" style="color:#f9821E;">Dados Detalhados</a>
                                                    </div>
                                                </nav>
                                                <div class="tab-content" id="nav-tabContent">
                                                    <!-- Dados Modal Aluno -->
                                                    <div class="row justify-content-center">
                                                        <div class="col-md-6" id="fotogrande">
                                                            <p style="text-align:justify;margin-top:10px;font-size:12px;"><b>Resposta: </b>{{$dados_base->respostaDoAluno}}</p>
                                                        </div>
                                                        <div class="col-md-6" id="fotogrande">
                                                            <p style="text-align:justify;margin-top:10px;font-size:12px;"><b>Status da Prova: </b>@php
                                                                if($dados_base->presenca <= 1){ echo 'Não Realizou a Prova' ; } else { echo 'Realizou a Prova' ; } @endphp </p>
                                                        </div>
                                                    </div>
                                                    <div class="row justify-content-center">
                                                        <div class="col-md-6" id="fotogrande">
                                                            <p style="text-align:justify;font-size:12px;"><b>Gabarito: </b>{{$dados_base->gabarito_prova}}</p>
                                                        </div>
                                                        <div class="col-md-6" id="fotogrande">
                                                            <p style="text-align:justify;font-size:12px;"><b>Pontuação: </b>{{$dados_base->pontuacao}}</p>
                                                        </div>
                                                    </div>
                                                    <!-- Dados Modal Aluno -->
                                                    <div style="margin-top:5px;" class="row justify-content-center">
                                                        <!-- Tabela Modal Aluno -->
                                                        <table class="table caption-top">
                                                            <thead>
                                                                <tr style="font-size:12px;text-align:center;">
                                                                    <th scope="col">Questão</th>
                                                                    <th scope="col">Resposta Aluno</th>
                                                                    <th scope="col">Gabarito</th>
                                                                    <th scope="col">Status</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @for ($i = 0; $i < strlen($dados_base->gabarito_prova); $i++)
                                                                    <tr style="font-size:12px;vertical-align:initial;text-align:center;">
                                                                        <th style="text-align:center;font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">Q{{$i+1}}</th>
                                                                        <td style="font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:black;"><?php echo substr($dados_base->respostaDoAluno, $i, 1) ?></td>
                                                                        <td style="font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:black;"><?php echo substr($dados_base->gabarito_prova, $i, 1) ?></td>
                                                                        <td style="font-weight: normal;font-size:12px;padding: 0.1em;vertical-align:middle;color:black;">
                                                                            <?php $status = '<i class="fa-solid fa-square-xmark" style="color:red;" data-bs-toggle="modal" data-bs-target="#Q' . ($i + 1) . '"></i>';
                                                                            if (substr($dados_base->gabarito_prova, $i, 1) == substr($dados_base->respostaDoAluno, $i, 1)) $status = '<i class="fa-solid fa-square-check" style="color:green;" data-bs-toggle="modal" data-bs-target="#Q' . ($i + 1) . '"></i>';
                                                                            echo $status ?>
                                                                        </td>
                                                                    </tr>
                                                                    @endfor
                                                            </tbody>
                                                        </table>
                                                        <!-- Tabela Modal Aluno -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <button type="button" style="background-color: #f9821E;border:none;" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                    </div>
                                    <!-- Modal footer -->
                                </div>
                            </div>
                        </div>
                        <!-- The Modal Questions -->
                        @endforeach
                </div>
                @endforeach
                <!------------------------------------ Cards ------------------->
                <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">
                    * Os presentes dados representam o percentual de proficiência por Aluno na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Caso deseje visualizar as informações detalhadas do aluno, clique sobre o ícone &ensp;<i class="fa-solid fa-circle-question"></i>
                </p>
                <!------------------------------------ Legenda ------------------->
                <div class="row justify-content-center" style="margin-top:15px;" id="alunosgrafico">
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