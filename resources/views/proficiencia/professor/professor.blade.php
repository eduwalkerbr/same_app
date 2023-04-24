@extends('layouts.appProfessor')

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

                    <!------------------------------------------------------------------------------------------ Sessão Média Turma ---------------------------------------------------------------------->
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">

                            @include('proficiencia/professor/sessoes.turma');

                            <!------------------------------------ Card Gráfico Média Turma ------------------->
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Comparativo Percentual entre Proficiência da Turma e Média de Proficiência das Turmas do {{$ano[0]}}º Ano</p>
                                    <div class="chartCard col-md-10">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoTurma"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;" id="temas">
                                        * O presente gráfico representa um Comparativo entre a Proficiência da Turma em questão, comparada a Média de Proficiência calculada entre as Turmas do {{$ano[0]}}º Ano.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:30px;font-weight:bold;">Fonte: Dados oriundos de bases internas do Município ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                            </div>
                            <!------------------------------------ Card Gráfico Média Escola ------------------->
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------------------ Sessão Média Turma ---------------------------------------------------------------------->

                    <!------------------------------------------------------------------- Sessão Tema Disciplina -------------------------------------------------------------------->
                    @include('proficiencia/professor/sessoes.temadisciplina');
                    <!------------------------------------------------------------------- Sessão Tema Disciplina -------------------------------------------------------------------->

                    <!------------------------------------ Sessão Tema Disciplina Gráfico ------------------------------------------------------>
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
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;" id="habilidadedisciplina">
                                        * O presente gráfico representa o percentual de Proficiências por @if($disciplina_selecionada[0]->id == 1) Temas @else Eixos/Temas @endif da Turma selecionada na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:0px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------------------------ Sessão Tema Disciplina Gráfico ------------------------------------------------------>

                    <!------------------------------------ Habilidades Disciplina ------------------->
                    @include('proficiencia/professor/sessoes.habilidadesdisciplina');
                    <!------------------------------------ Habilidades Disciplina ------------------->

                    <!------------------------------------ Gráfico Habilidade Disciplina ------------------->
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Proficiências por Habilidade na Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoHabilidadeDisciplina"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;" id="habilidadeanodisciplina">
                                        * O presente gráfico representa o percentual de Proficiências por Habilidade da Turma selecionda, na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------------------------ Gráfico Habilidade Disciplina ------------------->

                    <!-- Início Sessão de Habilidades Anos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    @include('proficiencia/professor/sessoes.habilidade');
                    <!-- Início Sessão de Habilidades Anos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->

                    <!------------------------------------ Gráfico Habilidade Disciplina Ano ------------------->
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Proficiências por Habilidade na Disciplina de {{$disciplina_selecionada[0]->desc}} no {{$ano[0]}}º Ano </p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoHabilidadeDisciplinaAno"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;" id="habilidadeselecionadadisciplina">
                                        * O presente gráfico representa o percentual de Proficiências por Habilidade da Turma selecionada, na Disciplina de {{$disciplina_selecionada[0]->desc}} no {{$ano[0]}}º Ano.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;margin-bottom:0px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------------------------ Gráfico Habilidade Disciplina Ano ------------------->

                    <!-- Início Sessão de Habilidade Selecionada Anos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    @include('proficiencia/professor/sessoes.habilidadeselecionada');
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
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;" id="questoesobjetivas">
                                        * O presente gráfico representa o percentual de Proficiência da Habilidade selecionada, na Disciplina de {{$disciplina_selecionada[0]->desc}} da presente Turma no transcorrer dos Anos.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:30px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
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
                    @include('proficiencia/professor/sessoes.questoes');
                    <!------------------------------------ Card Questões Objetivas ------------------->
                    @else
                    <!------------------------------------ Questões Diferenciadas ------------------->
                    @include('proficiencia/professor/_parciais.questoesDiferenciadas')
                    <!------------------------------------ Questões Diferenciadas ------------------->
                    @endif
                    @endforeach

                    <!------------------------------------ Gráfico Questões Disciplina ------------------->
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
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;" id="alunos">
                                        * O presente gráfico representa o percentual de Proficiências por Questão na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Ponha o mouse sobre as Colunas do Gráfico ou clique nas mesmas para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:15px;margin-bottom:15px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------------------------ Gráfico Questões Disciplina ------------------->

                    <!-- Início Sessão de Alunos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    @php
                    $previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
                    @endphp
                    @if($previlegio->funcaos_id == 7 || Auth::user()->perfil == 'Administrador')
                    @include('proficiencia/professor/sessoes.aluno');
                    @endif
                    <!-- Fim Sessão de Alunos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->

                    <!------------------------------------ Gráfico Aluno Disciplina ------------------->
                    @php
                    $previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
                    @endphp
                    @if($previlegio->funcaos_id == 7 || Auth::user()->perfil == 'Administrador')
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-bottom:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Proficiências por Aluno na Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoAlunosDisciplina"></canvas>
                                        </div>
                                        <!------------------------------------ gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                        * O presente gráfico representa o percentual de Proficiências por Aluno na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Ponha o mouse sobre as Colunas do Gráfico ou clique nas mesmas para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
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