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
        <div class="col-md-12" style="background-color: white;margin-top:30px;border: 1px solid white;">
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
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores Disciplinas
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
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0em;padding-bottom: 0.3rem;border-top:none;text-align:justify;margin-top:-5px;" id="graficotema">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12" style="background-color: white;border: 1px solid white;text-align:center;">
                                            <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#graficotema">
                                                Temas do Município de {{$municipio_selecionado[0]->nome}} &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!------------------------------------ Navegação ------------------->
                        </div>
                    </div>
                    <!---------------------------- Card Município por Disciplina Gráfico ------------------->
                </div>
                <!------------------------------------ Sessão Município por Disciplina Gráfico ------------------->

                <!------------------------------------ Sessão Tema Comparativo ------------------->
                <div class="row justify-content-center section" style="margin-bottom: 15px;">
                    <!---------------------------- Card Tema Comparativo Gráfico ------------------->
                    <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                        <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                            <!---------------------------- Título Tema Comparativo Gráfico------------------->
                            <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores Temas
                            </div>
                            <!---------------------------- Título Tema Comparativo ------------------->
                            <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                <div class="chartCard col-md-12">
                                    <!------------------------------------ Gráfico ------------------->
                                    <div class="chartBox">
                                        <canvas id="graficoTema"></canvas>
                                    </div>
                                    <!------------------------------------ Gráfico ------------------->
                                </div>
                                @foreach($label_tema as $label_tema_item)
                                <button id="button_tema_{{$label_tema_item}}" onclick="manipularAnoTema('{{$label_tema_item}}')" style="margin-left:20px;margin-right:20px;margin-top:5px;" class="btn btn-dark btn-sm">{{$label_tema_item}}</button>
                                @endforeach
                                <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                    * O presente gráfico representa o Percentual de Proficiência do Município entre os Temas no trancorrer dos Anos SAME.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                </p>
                                <p style="color:black;font-size: 12px;text-align:right;margin-top:5px;font-weight:bold;">Fonte: Dados oriundos de bases internas dos Municípios.</p>
                            </div>
                            <!------------------------------------ Navegação ------------------->
                            <div class="card-footer text-muted" style="background-color: white;padding-top: 0em;padding-bottom: 0.3rem;border-top:none;text-align:justify;margin-top:-5px;" id="graficoescola">
                                <div class="row justify-content-center">
                                    <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                                        <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#municipio_comparativo">
                                            Voltar para Disciplinas em {{$municipio_selecionado[0]->nome}} &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>
                                        </a>
                                    </div>
                                    <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                                        <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#graficoescola">
                                            Escolas de {{$municipio_selecionado[0]->nome}} &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!------------------------------------ Navegação ------------------->
                        </div>
                    </div>
                    <!---------------------------- Card Tema Comparativo Gráfico ------------------->
                </div>
                <!------------------------------------ Sessão Tema Comparativo Gráfico ------------------->

                <!------------------------------------ Sessão Escolas Comparativo ------------------->
                <div class="row justify-content-center section" style="margin-bottom: 15px;">
                    <!---------------------------- Card Escolas Comparativo Gráfico ------------------->
                    <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                        <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                            <!---------------------------- Título Escolas Comparativo Gráfico------------------->
                            <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores Escolas
                            </div>
                            <!---------------------------- Título Escolas Comparativo ------------------->
                            <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                <div class="chartCard col-md-12">
                                    <!------------------------------------ Gráfico ------------------->
                                    <div class="chartBox">
                                        <canvas id="graficoEscola"></canvas>
                                    </div>
                                    <!------------------------------------ Gráfico ------------------->
                                </div>
                                @foreach($label_escola as $label_escola_item)
                                <button id="button_escola_{{$label_escola_item}}" onclick="manipularAnoEscola('{{$label_escola_item}}')" style="margin-left:20px;margin-right:20px;margin-top:5px;" class="btn btn-dark btn-sm">{{$label_escola_item}}</button>
                                @endforeach
                                <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                    * O presente gráfico representa o Percentual de Proficiência do Município entre as Escolas no trancorrer dos Anos SAME.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                </p>
                                <p style="color:black;font-size: 12px;text-align:right;margin-top:5px;font-weight:bold;">Fonte: Dados oriundos de bases internas dos Municípios.</p>
                            </div>
                            <!------------------------------------ Navegação ------------------->
                            <div class="card-footer text-muted" style="background-color: white;padding-top: 0em;padding-bottom: 0.3rem;border-top:none;text-align:justify;margin-top:-5px;" id="graficoescoladisciplina">
                                <div class="row justify-content-center">
                                    <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                                        <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#graficotema">
                                            Voltar para Temas em {{$municipio_selecionado[0]->nome}} &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>

                                        </a>
                                    </div>
                                    <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                                        <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#graficoescoladisciplina">
                                            Escolas em {{$municipio_selecionado[0]->nome}} na Disciplina de {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!------------------------------------ Navegação ------------------->
                        </div>
                    </div>
                    <!---------------------------- Card Escolas Comparativo Gráfico ------------------->
                </div>
                <!------------------------------------ Sessão Escolas Disciplina Comparativo Gráfico ------------------->

                <!------------------------------------ Sessão Escolas Disciplina Comparativo ------------------->
                <div class="row justify-content-center section" style="margin-bottom: 15px;">
                    <!---------------------------- Card Escolas Disciplina Comparativo Gráfico ------------------->
                    <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                        <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                            <!---------------------------- Título Escolas Disciplina Comparativo Gráfico------------------->
                            <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores Escolas na Disciplina de {{$disciplina_selecionada[0]->desc}}
                            </div>
                            <!---------------------------- Título Escolas Disciplina Comparativo ------------------->
                            <div class="card-body" style="padding-top:1rem;padding-bottom:0.5rem;background-color:white;">
                                <div class="chartCard col-md-12">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12" style="background-color: white;border: 1px solid white;margin-bottom:-10px;margin-top: -10px;">
                                            <nav aria-label="breadcrumb">
                                                <ol class="breadcrumb" style="background-color: white;border:none;color:#0046AD;padding: 0em 0em;">
                                                    <li class="breadcrumb-item">
                                                    <li class="nav-item dropdown">
                                                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" style="color:black;font-size: 13px;border: 1px solid black;border-radius: 10px;"><i class="fa-brands fa-wpforms"></i>&emsp;{{$disciplina_selecionada[0]->desc}}</a>
                                                        <ul class="dropdown-menu">
                                                            @foreach($disciplinas as $disciplina)
                                                            <li><a class="dropdown-item" style="color:black;font-size: 13px;" href="{{ route('secretario_comparativo.exibirMunicipioComparativo', ['id' => $municipio_selecionado[0]->id, 'id_disciplina' => $disciplina->id, 'sessao' => 'graficoescoladisciplina']) }}">{{ $disciplina->desc}}</a></li>
                                                            @endforeach
                                                        </ul>
                                                    </li>

                                                </ol>
                                            </nav>
                                        </div>
                                    </div>
                                    <!------------------------------------ Gráfico ------------------->
                                    <div class="chartBox">
                                        <canvas id="graficoEscolaDisciplina"></canvas>
                                    </div>
                                    <!------------------------------------ Gráfico ------------------->
                                </div>
                                @foreach($label_escola_disc as $label_escola_disc_item)
                                <button id="button_escola_disc_{{$label_escola_disc_item}}" onclick="manipularAnoEscolaDisciplina('{{$label_escola_disc_item}}')" style="margin-left:20px;margin-right:20px;margin-top:0px;" class="btn btn-dark btn-sm">{{$label_escola_disc_item}}</button>
                                @endforeach
                                <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                    * O presente gráfico representa o Percentual de Proficiência do Município entre as Escolas na Disciplina de {{$disciplina_selecionada[0]->desc}} no trancorrer dos Anos SAME.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                </p>
                                <p style="color:black;font-size: 12px;text-align:right;margin-top:0px;font-weight:bold;">Fonte: Dados oriundos de bases internas dos Municípios.</p>
                            </div>
                            <!------------------------------------ Navegação ------------------->
                            <div class="card-footer text-muted" style="background-color: white;padding-top: 0em;padding-bottom: 0.3rem;border-top:none;text-align:justify;margin-top:-10px;" id="graficocurriculardisciplina">
                                <div class="row justify-content-center">
                                    <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                                        <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#graficoescola">
                                            Voltar para Temas do Município de {{$municipio_selecionado[0]->nome}} &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>
                                        </a>
                                    </div>
                                    <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                                        <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#graficocurriculardisciplina">
                                            Anos Curriculares em {{$municipio_selecionado[0]->nome}} na Disciplina de {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!------------------------------------ Navegação ------------------->
                        </div>
                    </div>
                    <!---------------------------- Card Curricular Disciplina Comparativo Gráfico ------------------->
                </div>
                <!------------------------------------ Sessão Curricular Disciplina Comparativo Gráfico ------------------->

                <!------------------------------------ Sessão Curricular Disciplina Comparativo ------------------->
                <div class="row justify-content-center section" style="margin-bottom: 15px;">
                    <!---------------------------- Card Curricular Disciplina Comparativo Gráfico ------------------->
                    <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                        <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                            <!---------------------------- Título Curricular Disciplina Comparativo Gráfico------------------->
                            <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores Anos Curriculares na Disciplina de {{$disciplina_selecionada[0]->desc}}
                            </div>
                            <!---------------------------- Título Curricular Disciplina Comparativo ------------------->
                            <div class="card-body" style="padding-top:1rem;padding-bottom:0.5rem;background-color:white;">
                                <div class="chartCard col-md-12">
                                    <div class="row justify-content-center">
                                        <div class="col-md-12" style="background-color: white;border: 1px solid white;margin-bottom:-10px;margin-top: -10px;">
                                            <nav aria-label="breadcrumb">
                                                <ol class="breadcrumb" style="background-color: white;border:none;color:#0046AD;padding: 0em 0em;">
                                                    <li class="breadcrumb-item">
                                                    <li class="nav-item dropdown">
                                                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" style="color:black;font-size: 13px;border: 1px solid black;border-radius: 10px;"><i class="fa-brands fa-wpforms"></i>&emsp;{{$disciplina_selecionada[0]->desc}}</a>
                                                        <ul class="dropdown-menu">
                                                            @foreach($disciplinas as $disciplina)
                                                            <li><a class="dropdown-item" style="color:black;font-size: 13px;" href="{{ route('secretario_comparativo.exibirMunicipioComparativo', ['id' => $municipio_selecionado[0]->id, 'id_disciplina' => $disciplina->id, 'sessao' => 'graficocurriculardisciplina']) }}">{{ $disciplina->desc}}</a></li>
                                                            @endforeach
                                                        </ul>
                                                    </li>

                                                </ol>
                                            </nav>
                                        </div>
                                    </div>
                                    <!------------------------------------ Gráfico ------------------->
                                    <div class="chartBox">
                                        <canvas id="graficoCurricularDisciplina"></canvas>
                                    </div>
                                    <!------------------------------------ Gráfico ------------------->
                                </div>
                                @foreach($label_curricular_disc as $label_curricular_disc_item)
                                <button id="button_curricular_disc_{{$label_curricular_disc_item}}" onclick="manipularAnoCurricularDisciplina('{{$label_curricular_disc_item}}')" style="margin-left:20px;margin-right:20px;margin-top:0px;" class="btn btn-dark btn-sm">{{$label_curricular_disc_item}}</button>
                                @endforeach
                                <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                    * O presente gráfico representa o Percentual de Proficiência do Município entre os Anos Curriculares na Disciplina de {{$disciplina_selecionada[0]->desc}} no trancorrer dos Anos SAME.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                </p>
                                <p style="color:black;font-size: 12px;text-align:right;margin-top:0px;font-weight:bold;">Fonte: Dados oriundos de bases internas dos Municípios.</p>
                            </div>
                            <!------------------------------------ Navegação ------------------->
                            <div class="card-footer text-muted" style="background-color: white;padding-top: 0em;padding-bottom: 0.3rem;border-top:none;text-align:justify;margin-top:-10px;" id="fim">
                                <div class="row justify-content-center">
                                    <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                                        <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#graficoescoladisciplina">
                                        Voltar para Escolas em {{$municipio_selecionado[0]->nome}} na Disciplina de {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>
                                        </a>
                                    </div>
                                    <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                                        <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#graficohabilidadeanodisciplina">
                                            Habilidade Selecionada em {{$disciplina_selecionada[0]->nome}} &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <!------------------------------------ Navegação ------------------->
                        </div>
                    </div>
                    <!---------------------------- Card Curricular Disciplina Comparativo Gráfico ------------------->
                </div>
                <!------------------------------------ Sessão Curricular Disciplina Comparativo Gráfico ------------------->


            </div>
        </div>
    </div>

</div>
</div>

@endsection