@extends('layouts.appDiretorAno')

@section('content')
@if (session('status'))
<script>
    alert("{{ session('status') }}");
</script>

@endif
<div class="container" style="padding-top: 20px;margin-bottom: 5px;box-shadow: 5px 5px 5px rgba(0,0,139);background-color: white;margin-top: 110px;" id="escola">


    <!------------------------------------ Sessão inicial da plataforma de Carrousel ------------------->
    @include('layouts/_gerais.bemVindo')

    <!------------------------------------------------------------------------------------------ Sessão Seleção Munícipio, Escola, Turma e Disciplina ---------------------------------------------------------------------->

    <div class="row justify-content-center">
        <div class="col-md-12" style="background-color: white;margin-top:30px;border: 1px solid white;">
            <nav>
                <div class="nav nav-tabs" id="nav-tab-novo" role="tablist" style="border-bottom: 1px solid #f9821e;">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true" style="color:#f9821E;">Proficiência</a>
                </div>
            </nav>
            <!------------------------------------------------------------------------------------------ Sessão Médica Escola ---------------------------------------------------------------------->
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">

                            @include('diretor/sessoes.escola')

                            <!------------------------------------ Gráfico Média Escola ------------------->
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Comparativo Percentual entre Profiência da Escola selecionada e Média de Proficiência das Escolas do Município</p>
                                    <div class="chartCard col-md-10">
                                        <!------------------------------------ gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoEscola"></canvas>
                                        </div>
                                        <!------------------------------------ gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;" id="disciplinas">
                                        * O presente gráfico representa um Comparativo entre a Proficiência da Escola em questão, comparada a Média de Proficiência calculada entre as Escolas do Município.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:30px;font-weight:bold;">Fonte: Dados oriundos de bases internas do Município (2021).</p>
                                </div>
                            </div>
                            <!------------------------------------ Gráfico Média Escola ------------------->
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------------------ Sessão Média Escola ---------------------------------------------------------------------->

                    <!------------------------------------------------------------------------------------------ Sessão Disciplina Escola ---------------------------------------------------------------------->
                    <div class="row justify-content-center">
                        <!------------------------------------ Card Disciplinas ------------------->
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">

                            @include('diretor/sessoes.disciplina')

                            <!------------------------------------ Gráfico Escolas Disciplinas ------------------->
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card Gráfico ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card Gráfico ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Profiência da Escola entre as Disciplinas</p>
                                    <div class="chartCard col-md-10">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoDisciplina"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;" id="curriculardisciplina">
                                        * O presente gráfico representa o Percentual de Proficiência da Escola em relação as Disciplinas.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:30px;font-weight:bold;">Fonte: Dados oriundos de bases internas do Município ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                            </div>
                            <!------------------------------------ Card Gráfico Escola Disciplinas ------------------->
                        </div>
                        <!------------------------------------ Card Disciplinas ------------------->
                    </div>

                    <!------------------------------------------------------------------------------------------ Sessão Ano Curricular Disciplina Cards ---------------------------------------------------------------------->
                    @include('diretor/sessoes.anodisciplina')
                    <!------------------------------------------------------------------------------------------ Sessão Ano Curricular Disciplina Cards ---------------------------------------------------------------------->

                    <!------------------------------------------------------------------------------------------ Sessão Ano Curricular Disciplina Gráfico ---------------------------------------------------------------------->
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Profiência da Escola entre os Anos Curriculares da Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoAnoDisciplina"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;" id="turmadisciplina">
                                        * O presente gráfico representa o Percentual de Proficiência da Escola em cada Ano Curricular na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;">Fonte: Dados oriundos de bases internas do Município ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------ Card Ano Curricular Disciplina Gráfico ------------------->
                    </div>
                    <!------------------------------------------------------------------------------------------ Sessão Ano Curricular Disciplina Gráfico ---------------------------------------------------------------------->

                    <!------------------------------------------------------------------------------------------ Sessão Turma Disciplina Cards ---------------------------------------------------------------------->
                    @include('diretor/sessoes.turmadisciplina')
                    <!------------------------------------------------------------------------------------------ Sessão Turma Disciplina Cards ---------------------------------------------------------------------->

                    <!------------------------------------------------------------------------------------------ Sessão Turma Disciplina Gráfico ---------------------------------------------------------------------->
                    <div class="row justify-content-center">
                        <!------------------------------------ Card Turma Disciplina ------------------->
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Profiência da Escola entre as Turmas da Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoTurmaDisciplina"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;" id="habilidadeanodisciplina">
                                        * O presente gráfico representa o Percentual de Proficiência da Escola entre as Turmas na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;font-weight:bold;">Fonte: Dados oriundos de bases internas do Município ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------ Card Turma Disciplina ------------------->
                    </div>
                    <!------------------------------------------------------------------------------------------ Sessão Turma Disciplina Gráfico ---------------------------------------------------------------------->

                    <!------------------------------------------------------------------------------------------ Sessão Habilidades na Disciplina por Ano Cards ---------------------------------------------------------------------->
                    @include('diretor/sessoes.habilidade')
                    <!------------------------------------------------------------------------------------------ Sessão Habilidades na Disciplina por Ano Cards ---------------------------------------------------------------------->

                    <!------------------------------------------------------------------------------------------ Sessão Habilidades na Disciplina por Ano Gráfico ---------------------------------------------------------------------->
                    <div class="row justify-content-center">
                        <!------------------------------------ Card Habilidades na Disciplina por Ano Gráfico ------------------->
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Card Habilidades na Disciplina por Ano Gráfico ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Card Habilidades na Disciplina por Ano Gráfico ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Proficiência por Habilidade na Disciplina de {{$disciplina_selecionada[0]->desc}} no {{$ano[0]}}º Ano </p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoHabilidadeDisciplina"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;" id="habilidadeselecionadadisciplina">
                                        * O presente gráfico representa o percentual de Proficiência por Habilidades da Escola selecionda, na Disciplina de {{$disciplina_selecionada[0]->desc}} no {{$ano[0]}}º Ano.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------ Card Habilidades na Disciplina por Ano Gráfico ------------------->
                    </div>
                    <!------------------------------------------------------------------------------------------ Sessão Habilidades na Disciplina por Ano Gráfico ---------------------------------------------------------------------->

                    <!-- Início Sessão de Habilidade Selecionada Anos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    @include('diretor/sessoes.habilidadeselecionada')
                    <!-- Início Sessão de Habilidade Selecionada Anos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->

                    <!------------------------------------------------------------------------------------------ Sessão Habilidades na Disciplina Gráfico ---------------------------------------------------------------------->
                    <!---- Apenas é exibido quando listada mais de uma habilidade, por isso atentar para as regras de navegação no fim da sessão ----------------------------------->
                    @if(count($dados_base_habilidade_disciplina_grafico) > 1)
                    <div class="row justify-content-center">
                        <!------------------------------------ Card Habilidades na Disciplina Gráfico ------------------->
                        <div class="card-deck" style="background-color: white;padding-bottom:16px;border: 1px solid white;">
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
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                        * O presente gráfico representa o percentual de Proficiência da Habilidade selecionada, na Disciplina de {{$disciplina_selecionada[0]->desc}} da presente Escola no transcorrer dos Anos.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                            </div>
                        </div>
                        <!------------------------------------ Card Habilidades na Disciplina Gráfico ------------------->
                    </div>
                    @endif
                    <!-- Fim Sessão de Seleção de Habilidades ---------------------------------------------------------------------------------------------------------------------------------------------------------------->

                </div>
            </div>
        </div>
    </div>
</div>
@endsection