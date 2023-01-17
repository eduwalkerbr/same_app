@extends('comparativo\secretario\estrutura.appSecretario')

@section('content')
@if (session('status'))
<script>
    alert("{{ session('status') }}");
</script>

@endif
<div class="container" style="padding-top: 20px;margin-bottom: 5px;box-shadow: 5px 5px 5px rgba(0,0,139);background-color: white;margin-top: 110px;">
    <!------------------------------------ Sessão inicial da plataforma de Carrousel ------------------->
    @include('layouts/_gerais.bemVindo')  

    <!------------------------------------ Sessão de Seleção de Município, Escola, Turma e Disciplina ------------------->

    <div class="row justify-content-center" id="municipio_comparativo">
        <div class="col-md-12" style="background-color: white;margin-top:30px;border: 1px solid white;" >
            <nav>
                <div class="nav nav-tabs" id="nav-tab-novo" role="tablist" style="border-bottom: 1px solid #f9821e;">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true" style="color:#f9821E;">Comparativo</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

                    <!------------------------------------ Sessão Município por Disciplina Gráfico ------------------->
                    <div class="row justify-content-center section" style="margin-bottom: 15px;">
                        <!---------------------------- Card Município por Disciplina Gráfico ------------------->
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!---------------------------- Título Município por Disciplina Gráfico ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <!---------------------------- Título Município por Disciplina Gráfico ------------------->
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <!--<p class="card-text" style="font-size:14px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Gráfico Comparativo de Proficiência do Município entre Disciplinas nos Anos SAME</p>-->
                                    <div class="chartCard col-md-12">
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoDisciplina"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    @foreach($label_disc as $label_disc_item)
                                    <button id="button_disc_{{$label_disc_item}}" onclick="manipularAno('{{$label_disc_item}}')" style="margin-left:20px;margin-right:20px;margin-top:5px;" class="btn btn-dark btn-sm">{{$label_disc_item}}</button>
                                    @endforeach
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                        * O presente gráfico representa o Percentual de Proficiência do Município entre as disciplinas no trancorrer dos Anos SAME.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:5px;font-weight:bold;">Fonte: Dados oriundos de bases internas dos Municípios.</p>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0em;padding-bottom: 0.3rem;border-top:none;text-align:justify;margin-top:-5px;" id="fim">
                                    <div class="row justify-content-end">
                                        
                                        <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#fim">
                                                Habilidades em nos Anos &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>

                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!------------------------------------ Navegação ------------------->
                            </div>
                        </div>
                        <!---------------------------- Card Município por Disciplina Gráfico ------------------->
                    </div>
                    <!------------------------------------ Sessão Município por Disciplina Gráfico ------------------->

                </div>
            </div>
        </div>

    </div>
</div>

@endsection