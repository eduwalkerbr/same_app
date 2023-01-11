@extends('layouts.appProfessorHabilidade')

@section('content')
@if (session('status'))
<script>
    alert("{{ session('status') }}");
</script>

@endif
<div class="container" style="padding-top: 20px;margin-bottom: 5px;box-shadow: 5px 5px 5px rgba(0,0,139);background-color: white;margin-top: 110px;" id="turma">

    <!------------------------------------ Sessão inicial da plataforma de Carrousel ------------------->
    @include('layouts/_gerais.bemVindo')

    <div class="row justify-content-center">
        <div class="col-md-12" style="background-color: white;margin-top:30px;border: 1px solid white;">
            <nav>
                <div class="nav nav-tabs" id="nav-tab-novo" role="tablist" style="border-bottom: 1px solid #f9821e;">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true" style="color:#f9821E;">Proficiência</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <!------------------------------------ Média Turma ------------------->
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">

                            @include('professor/sessoes.turma');

                            <!------------------------------------ Gráfico Média Turma ------------------->
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Comparativo Percentual entre Profciiência da Turma e Média de Proficiência das Turmas do {{$ano[0]}}º Ano</p>
                                    <div class="chartCard col-md-10">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoTurma"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">
                                        * O presente gráfico representa um Comparativo entre a Proficiência da Turma em questão, comparada a Média de Proficiência calculada entre as Turmas do {{$ano[0]}}º Ano.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:30px;font-weight:bold;">Fonte: Dados oriundos de bases internas do Município ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:right;" id="tema_detalhado">
                                    <a class=" btn btn-link" style="color:#f9821E;font-size:13px;" href="#tema_detalhado">
                                        Seguir para @if($disciplina_selecionada[0]->id == 1) Temas @else Eixos/Temas @endif na Disciplina de {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>
                                    </a>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                            </div>
                            <!------------------------------------ Gráfico Média Turma ------------------->
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------------------ Sessão Média Turma ---------------------------------------------------------------------->

                    <!------------------------------------ Tema Disciplina ------------------->
                    @include('professor/sessoes.temadisciplina');
                    <!------------------------------------------------------------------- Sessão Tema Disciplina -------------------------------------------------------------------->

                    <!------------------------------------ Gráfico Tema Disciplina ------------------->
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Proficiências por @if($disciplina_selecionada[0]->id == 1) Tema @else Eixo/Tema @endif na Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoTema"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                        * O presente gráfico representa o percentual de Proficiências por @if($disciplina_selecionada[0]->id == 1) Temas @else Eixos/Temas @endif da Turma selecionada na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:0px;margin-bottom:5px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:justify;" id="habilidade">
                                    <div class="row justify-content-center">
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#tema_detalhado">
                                                Voltar para Gráfico @if($disciplina_selecionada[0]->id == 1) Temas @else Eixos/Temas @endif na Disciplina de {{$disciplina_selecionada[0]->desc}}&emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>

                                            </a>
                                        </div>
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#habilidade">
                                                Seguir para Habilidades {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>

                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                            </div>
                        </div>
                    </div>
                    <!------------------------------------ Sessão Tema Disciplina Gráfico ------------------------------------------------------>

                    <!------------------------------------ Habilidade Disciplina ------------------->
                    @include('professor/sessoes.habilidadesdisciplina');
                    <!------------------------------------ Habilidades Disciplina ------------------->

                    <!------------------------------------ Gráfico Habilidade Disciplina ------------------->
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Proficiências por Habilidade na Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoHabilidadeDisciplina"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                        * O presente gráfico representa o percentual de Proficiências por Habilidades da Turma selecionada, na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:justify;" id="questaomatematica">
                                    <div class="row justify-content-center">
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#habilidade">
                                                Voltar para Habilidades em {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>

                                            </a>
                                        </div>
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#questaomatematica">
                                                Seguir para Habilidades em {{$disciplina_selecionada[0]->desc}} por Ano &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>

                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                            </div>
                        </div>
                    </div>
                    <!------------------------------------ Gráfico Habilidade Disciplina ------------------->

                    <!-- Início Sessão de Habilidades Anos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    @include('professor/sessoes.habilidade');
                    <!-- Fim Sessão de Habilidades Anos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->

                    <!------------------------------------ Habilidade Disciplina Ano Gráfico ------------------->
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Proficiências por Habilidade na Disciplina de {{$disciplina_selecionada[0]->desc}} no {{$ano_selecionado[0]}}º Ano </p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoHabilidadeDisciplinaAno"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                        * O presente gráfico representa o percentual de Proficiência por Habilidade da Turma selecionda, na Disciplina de {{$disciplina_selecionada[0]->desc}} no {{$ano_selecionado[0]}}º Ano.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:justify;" id="graficohabilidadeanodisciplina">
                                    <div class="row justify-content-center">
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#questaomatematica">
                                                Voltar para Habilidades em {{$disciplina_selecionada[0]->desc}} nos Anos &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>
                                            </a>
                                        </div>
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#graficohabilidadeanodisciplina">
                                                Habilidade Selecionada em {{$disciplina_selecionada[0]->desc}} no transcorrer dos Anos &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                            </div>
                        </div>
                    </div>
                    <!------------------------------------ Habilidade Disciplina Ano Gráfico ------------------->

                    <!-- Início Sessão de Habilidade Selecionada Anos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    @include('professor/sessoes.habilidadeselecionada');
                    <!-- Fim Sessão de Habilidade Selecionada Anos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->

                    <!------------------------------------ Habilidade Individual Gráfico ------------------->
                    @if(count($dados_base_habilidade_disciplina_grafico_habilidade) > 1)
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Proficiência da Habilidade selecionada na Disciplina de {{$disciplina_selecionada[0]->desc}} no transcorrer dos Anos </p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoHabilidadeDisciplinaHabilidade"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">
                                        * O presente gráfico representa o percentual de Proficiência da Habilidade selecionada, na Disciplina de {{$disciplina_selecionada[0]->desc}} da presente Turma no transcorrer dos Anos.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:30px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:justify;" id="questoesobjetivas">
                                    <div class="row justify-content-center">
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#graficohabilidadeanodisciplina">
                                                Habilidade Selecionada em {{$disciplina_selecionada[0]->desc}} no transcorrer dos Anos &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>

                                            </a>
                                        </div>
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#questoesobjetivas">
                                                Questões Objetivas em {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>

                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                            </div>
                        </div>
                    </div>
                    @endif
                    <!------------------------------------ Habilidade Individual Gráfico ------------------->

                    <!-- Início Sessão de Questões --------------------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    @php
                    $conttq = 0;
                    @endphp
                    @foreach ($tipos_questoes as $tipo_questao)
                    @php
                    $conttq++;
                    @endphp
                    <!------------------------------------ Card Questões Objetivas ------------------->
                    @if($tipo_questao == 'Objetivas')
                    @include('professor/sessoes.questoes');
                    @else
                    <!------------------------------------ Card Questões Não Objetivas ------------------->
                    <!------------------------------------ Questões Diferenciadas ------------------->
                    @include('professor/_parciais.questoesDiferenciadas')
                    <!------------------------------------ Questões Diferenciadas ------------------->
                    @endif
                    @endforeach
                    <!------------------------------------ Questão Disciplina Gráfico ------------------->
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;padding-bottom:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Proficiências por Questão na Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoQuestoesDisciplina"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                        * O presente gráfico representa o percentual de Proficiências por Questão na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Ponha o mouse sobre as Colunas do Gráfico ou clique nas mesmas para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:15px;margin-bottom:15px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:justify;" id="aluno">
                                    <div class="row justify-content-center">
                                        <!------------------------------------------------------- Voltar ---------------------------------------------------------------------->
                                        @if((count($tipos_questoes) == 1) && (count($dados_base_habilidade_disciplina_grafico_habilidade) > 1))
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#questoesobjetivas">
                                                Voltar para Questões Objetivas em {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>

                                            </a>
                                        </div>
                                        @endif

                                        @if((count($tipos_questoes) == 1) && (count($dados_base_habilidade_disciplina_grafico_habilidade) == 1))
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#habilidadedisciplinahabilidade">
                                                Voltar para Questões Objetivas em {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>

                                            </a>
                                        </div>
                                        @endif

                                        @if(count($tipos_questoes) == 2)
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#proximoquestoesobjetivas">
                                                Questões {{$tipos_questoes[1]}} em {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>

                                            </a>
                                        </div>
                                        @endif

                                        @if(count($tipos_questoes) == 3)
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#{{$tipos_questoes[1]}}">
                                                Questões {{$tipos_questoes[2]}} em {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>

                                            </a>
                                        </div>
                                        @endif
                                        <!------------------------------------------------------- Voltar ---------------------------------------------------------------------->
                                        @php
                                        $previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
                                        @endphp
                                        @if($previlegio->funcaos_id == 7 || Auth::user()->perfil == 'Administrador')
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#aluno">
                                                Alunos em {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>

                                            </a>
                                        </div>
                                        @else
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#turma">
                                                Voltar para o Início &emsp;<i class="fa-solid fa-door-closed"></i>

                                            </a>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                            </div>
                        </div>
                    </div>
                    <!------------------------------------ Gráfico Questões Disciplina ------------------->

                    <!-- Início Sessão de Alunos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    @php
                    $previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
                    @endphp
                    @if($previlegio->funcaos_id == 7 || Auth::user()->perfil == 'Administrador')
                    @include('professor/sessoes.aluno');
                    <!-- Fim Sessão de Alunos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->

                    <!------------------------------------ Aluno Disciplina Gráfico ------------------->
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-bottom:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Proficiência por Aluno na Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoAlunosDisciplina"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">
                                        * O presente gráfico representa o percentual de Proficiência por Aluno na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Ponha o mouse sobre as Colunas do Gráfico ou clique nas mesmas para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:30px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:justify;">
                                    <div class="row justify-content-center">
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#aluno">
                                                Voltar para Alunos {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>
                                            </a>
                                        </div>
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#turma">
                                                Voltar para o Início &emsp;<i class="fa-solid fa-door-closed"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection