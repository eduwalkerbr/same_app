@extends('layouts.appSecretario')

@section('content')
@if (session('status'))
<script>
    alert("{{ session('status') }}");
</script>

@endif
<div class="container" style="padding-top: 20px;margin-bottom: 5px;box-shadow: 5px 5px 5px rgba(0,0,139);background-color: white;margin-top: 110px;" id="municipio">
    <!------------------------------------ Sessão inicial da plataforma de Carrousel ------------------->
    @include('layouts/_gerais.bemVindo')  

    <!------------------------------------ Sessão de Seleção de Município, Escola, Turma e Disciplina ------------------->

    <div class="row justify-content-center">
        <div class="col-md-12" style="background-color: white;margin-top:30px;border: 1px solid white;">
            <nav>
                <div class="nav nav-tabs" id="nav-tab-novo" role="tablist" style="border-bottom: 1px solid #f9821e;">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true" style="color:#f9821E;">Proficiência</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

                    <!------------------------------------ Sessão Disciplina Município ------------------->
                    <div class="row justify-content-center section">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">

                            <!---- Card Disciplinas Município ---->
                            @include('secretario/sessoes.disciplina')
                            <!---- Card Disciplinas Município ---->

                            <!------------------------------------ Sessão Disciplina Município Gráfico ------------------->
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">

                                <!---- Título Disciplina Município Gráfico ---->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!---- Título Disciplina Município Gráfico ---->

                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Proficiência do Município entre as Disciplinas</p>
                                    <div class="chartCard col-md-10">

                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoDisciplina"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->

                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">
                                        * O presente gráfico representa o Percentual de Proficiência do Município em relação as Disciplinas.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>

                                    <!---- Rodapé Disciplina Município Gráfico ---->
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:30px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                    <!---- Rodapé Disciplina Município Gráfico ---->

                                </div>

                            </div>
                            <!------------------------------------ Sessão Disciplina Município Gráfico ------------------->
                        </div>
                    </div>
                    
                    <!------------------------------------ Sessão Disciplina Município ------------------->
  
                    <!------------------------------------ Sessão Escolas Município ------------------->
                    @include('secretario/sessoes.escola')
                    <!------------------------------------ Sessão Escolas Município ------------------->

                    <!------------------------------------ Sessão Escola Município Gráfico que Exibe quando tem mais de um Item ------------------->
                    @if(count($dados_base_grafico_escola) > 1)
                    <div class="row justify-content-center section">

                        <!------------------------------------ Card Escola Município Gráfico ------------------->
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------- Título Card Escola Munícipio Gráfico ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------- Título Card Escola Munícipio Gráfico ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Proficiência das Escolas do Munícipio</p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoEscola"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;" id="escolasdisciplina">
                                        * O presente gráfico representa o Percentual de Proficiência das Escolas do Município.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:30px;font-weight:bold;" >Fonte: Dados oriundos de bases internas do Município ({{strval($ano_same_selecionado)}}).</p>
                                </div>

                            </div>
                        </div>
                        <!------------------------------------ Card Escola Município Gráfico ------------------->

                    </div>
                    @endif
                    <!------------------------------------ Sessão Escola Município Gráfico que Exibe quando tem mais de um Item ------------------->

                    <!------------------------------------ Sessão Escolas Disciplina Município ------------------->
                    @include('secretario/sessoes.escoladisciplina')
                    <!------------------------------------ Sessão Escolas Disciplina Município ------------------->

                    <!------------------------------------ Sessão Escola Disciplina Município Gráfico, exibe quando tem mais de um Item ------------------->
                    @if(count($dados_base_grafico_escola) > 1)
                    <div class="row justify-content-center section">
                        <!-----------------------------Card Escola Disciplina Munícipio Gráfico ------------------->
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!----------------------------Título Escola Disciplina Munícipio Gráfico ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!----------------------------Título Escola Disciplina Munícipio Gráfico ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Profiência das Escolas do Munícipio da Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoEscolaDisciplina"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;" id="curriculardisciplina">
                                        * O presente gráfico representa o Percentual de Proficiência das Escolas do Município na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:30px;font-weight:bold;">Fonte: Dados oriundos de bases internas do Município ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                            </div>
                        </div>
                        <!-----------------------------Card Escola Disciplina Munícipio Gráfico ------------------->
                    </div>
                    @endif
                    <!------------------------------------ Sessão Escola Disciplina Município Gráfico, exibe quando tem mais de um Item ------------------->

                    <!------------------------------------ Sessão Ano Curricular Disciplina ------------------->
                    @include('secretario/sessoes.anodisciplina')
                    <!------------------------------------ Sessão Ano Curricular Disciplina ------------------->

                    <!------------------------------------ Sessão Ano Curricular Disciplina Gráfico ------------------->
                    <div class="row justify-content-center section">
                        <!---------------------------- Card Ano Curricular Disciplina Gráfico ------------------->
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!---------------------------- Título Ano Curricular Disciplina Gráfico ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!---------------------------- Título Ano Curricular Disciplina Gráfico ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Proficiência do Município entre os Anos Curriculares da Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoAnoDisciplina"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;" id="habilidadeanodisciplina">
                                        * O presente gráfico representa o Percentual de Proficiência do Município em cada Ano Curricular na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;font-weight:bold;">Fonte: Dados oriundos de bases internas do Município ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                            </div>
                        </div>
                        <!---------------------------- Card Ano Curricular Disciplina Gráfico ------------------->
                    </div>
                    <!------------------------------------ Sessão Ano Curricular Disciplina Gráfico ------------------->

                    <!-- Início Sessão de Habilidades Anos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    @include('secretario/sessoes.habilidade')
                    <!------------------------------------ Fim Sessão Habilidades Disciplina Anos ----------------------------------------------------------------------------->

                    <!------------------------------------ Sessão Habilidades Disciplina Anos Gráfico ------------------->
                    <div class="row justify-content-center">
                        <!----------------------- Card Habilidades Disciplina Anos Gráfico ------------------->
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!----------------------- Título Habilidades Disciplina Anos Gráfico ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!----------------------- Título Habilidades Disciplina Anos Gráfico ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Percentual de Proficiência por Habilidade na Disciplina de {{$disciplina_selecionada[0]->desc}} no {{$ano[0]}}º Ano </p>
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoHabilidadeDisciplinaAno"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;" id="habilidadeselecionadadisciplina">
                                        * O presente gráfico representa o percentual de Proficiência por Habilidades da Turma selecionada, na Disciplina de {{$disciplina_selecionada[0]->desc}} no {{$ano[0]}}º Ano.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                            </div>
                        </div>
                        <!----------------------- Card Habilidades Disciplina Anos Gráfico ------------------->
                    </div>
                    <!------------------------------------ Sessão Habilidades Disciplina Anos Gráfico ------------------->
                    
                    <!-- Fim Sessão de Habilidades Anos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->

                    <!-- Início Sessão de Habilidade Selecionada Anos ---------------------------------------------------------------------------------------------------------------------------------------------------------------->
                    @include('secretario/sessoes.habilidadeselecionada')
                    <!-- Fim Sessão de Habilidade Selecionada Anos ----------------------------------------------------------->

                    <!------------------------------------ Sessão Habilidade Individual Disciplina Gráfico ------------------->
                    @if(count($dados_base_habilidade_disciplina_grafico) > 1)
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-bottom:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!------------------------------------ Título Habilidade Individual Disciplina Gráfico  ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!------------------------------------ Título Habilidade Individual Disciplina Gráfico  ------------------->
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
                                        * O presente gráfico representa o percentual de Proficiência da Habilidade selecionada, na Disciplina de {{$disciplina_selecionada[0]->desc}} no transcorrer dos Anos.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    <!-- Fim Sessão de Seleção de Habilidades ---------------------------------------------------------------------------------------------------------------------------------------------------------------->

                </div>
            </div>
        </div>

    </div>
</div>

@endsection