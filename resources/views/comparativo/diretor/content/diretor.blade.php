@extends('comparativo/diretor/estrutura.appDiretor')

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

    <div class="row justify-content-center" id="escola_comparativo">
        <div class="col-md-12" style="background-color: white;margin-top:30px;border: 1px solid white;">
            <nav>
                <div class="nav nav-tabs" id="nav-tab-novo" role="tablist" style="border-bottom: 1px solid #f9821e;">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true" style="color:#f9821E;">Comparativo</a>
                </div>
            </nav>

            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

                    <!------------------------------------ Sessão Escola por Disciplina Gráfico ------------------->
                    <div class="row justify-content-center section" style="margin-bottom: 15px;">
                        <!---------------------------- Card Escola por Disciplina Gráfico ------------------->
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!---------------------------- Título Escola por Disciplina Gráfico ------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores Disciplinas
                                </div>
                                <!---------------------------- Título Escola por Disciplina Gráfico ------------------->
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
                                    <button id="button_disc_{{$label_disc_item}}" onclick="manipularAno('{{$label_disc_item}}')" style="margin-right:5px;margin-top:5px;color:white;background-color:#f9821E;border-color:#f9821E;" class="btn btn-primary btn-sm"><i class="fa-solid fa-minus"></i> &ensp;{{$label_disc_item}}</button>
                                    @endforeach
                                    <button id="" data-bs-toggle="modal" data-bs-target="#mod_disciplina" onclick="" style="margin-left:100px;margin-top:5px;font-size:14px;font-weight:bold;" class="btn btn-outline-dark btn-sm"><i class="fa-solid fa-table-list"></i> &ensp; Dados Tabelados</button>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                        * O presente gráfico representa o Percentual de Proficiência da Escola entre as Disciplinas no trancorrer dos Anos SAME.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:5px;font-weight:bold;" id="graficocurricular">Fonte: Dados oriundos de bases internas do SAME.</p>
                                </div>

                                @include('comparativo/diretor/content/modals.disciplina')

                            </div>
                        </div>
                        <!---------------------------- Card Escola por Disciplina Gráfico ------------------->
                    </div>

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
                                            <div class="col-md-12" style="background-color: white;border: 1px solid white;margin-bottom:-10px;margin-top: -15px;">
                                                <nav aria-label="breadcrumb">
                                                    <ol class="breadcrumb" style="background-color: white;border:none;color:#0046AD;padding: 0em 0em;">
                                                        <li class="breadcrumb-item">
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" style="color:black;font-size: 13px;border: 1px solid black;border-radius: 10px;"><i class="fa-brands fa-wpforms"></i>&emsp;{{$disciplina_selecionada[0]->desc}}</a>
                                                            <ul class="dropdown-menu navbar-nav-scroll" style="max-height:220px;">
                                                                @foreach($disciplinas as $disciplina)
                                                                <li><a class="dropdown-item" style="color:black;font-size: 13px;" href="{{ route('diretor_comparativo.exibirEscolaComparativo', ['id' => $escola_selecionada[0]->id, 'id_municipio' => $municipio_selecionado[0]->id, 'id_disciplina' => $disciplina->id, 'sessao' => 'graficocurricular']) }}">{{ $disciplina->desc}}</a></li>
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
                                    <button id="button_curricular_disc_{{$label_curricular_disc_item}}" onclick="manipularAnoCurricularDisciplina('{{$label_curricular_disc_item}}')" style="margin-right:5px;margin-top:0px;color:white;background-color:#f9821E;border-color:#f9821E;" class="btn btn-primary btn-sm"><i class="fa-solid fa-minus"></i> &ensp;{{$label_curricular_disc_item}}</button>
                                    @endforeach
                                    <button id="" data-bs-toggle="modal" data-bs-target="#mod_curricular_disc" onclick="" style="margin-left:100px;margin-top:5px;font-size:14px;font-weight:bold;" class="btn btn-outline-dark btn-sm"><i class="fa-solid fa-table-list"></i> &ensp; Dados Tabelados</button>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                        * O presente gráfico representa o Percentual de Proficiência da Escola entre os Anos Curriculares na Disciplina de {{$disciplina_selecionada[0]->desc}} no trancorrer dos Anos SAME.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:0px;font-weight:bold;" id="graficotema">Fonte: Dados oriundos de bases internas do SAME.</p>
                                </div>

                                @include('comparativo/diretor/content/modals.curricular_disciplina')

                            </div>
                        </div>
                        <!---------------------------- Card Curricular Disciplina Comparativo Gráfico ------------------->
                    </div>
                    <!------------------------------------ Sessão Curricular Disciplina Comparativo Gráfico ------------------->

                    <!------------------------------------ Sessão Tema Comparativo ------------------->
                    <div class="row justify-content-center section" style="margin-bottom: 15px;">
                        <!---------------------------- Card Tema Comparativo Gráfico ------------------->
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <!---------------------------- Título Tema Comparativo Gráfico------------------->
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores Temas em {{$disciplina_selecionada[0]->desc}} no Transcorrer dos Anos
                                </div>
                                <!---------------------------- Título Tema Comparativo ------------------->
                                <div class="card-body" style="padding-top:1rem;padding-bottom:0.5rem;background-color:white;">
                                    <div class="chartCard col-md-12">
                                        <div class="row justify-content-start">
                                            <div class="col-md-2" style="background-color: white;border: 1px solid white;margin-bottom:-10px;margin-top: -15px;">
                                                <nav aria-label="breadcrumb">
                                                    <ol class="breadcrumb" style="background-color: white;border:none;color:#0046AD;padding: 0em 0em;">
                                                        <li class="breadcrumb-item">
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" style="color:black;font-size: 13px;border: 1px solid black;border-radius: 10px;"><i class="fa-brands fa-wpforms"></i>&emsp;{{$ano[0]}}º Ano</a>
                                                            <ul class="dropdown-menu navbar-nav-scroll" style="max-height:220px;">
                                                                @foreach($anos as $ano_principal)
                                                                <li><a class="dropdown-item" style="color:black;font-size: 13px;" href="{{ route('diretor_comparativo.exibirEscolaComparativoAno', ['id' => $escola_selecionada[0]->id,'id_municipio' => $municipio_selecionado[0]->id, 'id_disciplina' => $disciplina_selecionada[0]->id, 'ano' => $ano_principal[0], 'sessao' => 'graficotema']) }}">{{ $ano_principal[0]}}º Ano</a></li>
                                                                @endforeach
                                                            </ul>
                                                        </li>

                                                    </ol>
                                                </nav>
                                            </div>
                                            <div class="col-md-2" style="background-color: white;border: 1px solid white;margin-bottom:-10px;margin-top: -15px;">
                                                <nav aria-label="breadcrumb">
                                                    <ol class="breadcrumb" style="background-color: white;border:none;color:#0046AD;padding: 0em 0em;">
                                                        <li class="breadcrumb-item">
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" style="color:black;font-size: 13px;border: 1px solid black;border-radius: 10px;"><i class="fa-brands fa-wpforms"></i>&emsp;{{$disciplina_selecionada[0]->desc}}</a>
                                                            <ul class="dropdown-menu">
                                                                @foreach($disciplinas as $disciplina)
                                                                <li><a class="dropdown-item" style="color:black;font-size: 13px;" href="{{ route('diretor_comparativo.exibirEscolaComparativoAno', ['id' => $escola_selecionada[0]->id,'id_municipio' => $municipio_selecionado[0]->id, 'id_disciplina' => $disciplina->id, 'ano' => $ano[0], 'sessao' => 'graficotema']) }}">{{ $disciplina->desc}}</a></li>
                                                                @endforeach
                                                            </ul>
                                                        </li>

                                                    </ol>
                                                </nav>
                                            </div>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                        <div class="chartBox">
                                            <canvas id="graficoTema"></canvas>
                                        </div>
                                        <!------------------------------------ Gráfico ------------------->
                                    </div>
                                    @foreach($label_tema as $label_tema_item)
                                    <button id="button_tema_{{$label_tema_item}}" onclick="manipularAnoTema('{{$label_tema_item}}')" style="margin-right:5px;margin-top:5px;color:white;background-color:#f9821E;border-color:#f9821E;" class="btn btn-primary btn-sm"><i class="fa-solid fa-minus"></i> &ensp;{{$label_tema_item}}</button>
                                    @endforeach
                                    <button id="" data-bs-toggle="modal" data-bs-target="#mod_tema" onclick="" style="margin-left:100px;margin-top:5px;font-size:14px;font-weight:bold;" class="btn btn-outline-dark btn-sm"><i class="fa-solid fa-table-list"></i> &ensp; Dados Tabelados</button>
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                        * O presente gráfico representa o Percentual de Proficiência da Escola em relação aos Temas na Disciplina de {{$disciplina_selecionada[0]->desc}} no {{$ano[0]}}º Ano, entre os Anos SAME.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                    </p>
                                    <p style="color:black;font-size: 12px;text-align:right;margin-top:5px;font-weight:bold;" id="graficohabilidade">Fonte: Dados oriundos de bases internas do SAME.</p>
                                </div>

                                @include('comparativo/diretor/content/modals.tema')

                            </div>
                        </div>
                        <!---------------------------- Card Tema Comparativo Gráfico ------------------->
                    </div>
                    <!------------------------------------ Sessão Tema Comparativo Gráfico ------------------->
                </div>

                <!------------------------------------ Sessão Turma Disciplina Comparativo Gráfico ------------------->

                <!------------------------------------ Sessão Habilidade Ano Disciplina Comparativo ------------------->
                <div class="row justify-content-center section" style="margin-bottom: 15px;">
                    <!---------------------------- Card Habilidade Ano Disciplina Comparativo Gráfico ------------------->
                    <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                        <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                            <!---------------------------- Título Habilidade Ano Disciplina Comparativo Gráfico------------------->
                            <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores Habilidades em {{$disciplina_selecionada[0]->desc}} no Transcorrer dos Anos
                            </div>
                            <!---------------------------- Título Habilidade Ano Disciplina Comparativo ------------------->
                            <div class="card-body" style="padding-top:1rem;padding-bottom:0.5rem;background-color:white;">
                                <div class="chartCard col-md-12">
                                    <div class="row justify-content-start">
                                        <div class="col-md-2" style="background-color: white;border: 1px solid white;margin-bottom:-10px;margin-top: -15px;">
                                            <nav aria-label="breadcrumb">
                                                <ol class="breadcrumb" style="background-color: white;border:none;color:#0046AD;padding: 0em 0em;">
                                                    <li class="breadcrumb-item">
                                                    <li class="nav-item dropdown">
                                                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" style="color:black;font-size: 13px;border: 1px solid black;border-radius: 10px;"><i class="fa-brands fa-wpforms"></i>&emsp;{{$ano[0]}}º Ano</a>
                                                        <ul class="dropdown-menu navbar-nav-scroll" style="max-height:220px;">
                                                            @foreach($anos as $ano_principal)
                                                            <li><a class="dropdown-item" style="color:black;font-size: 13px;" href="{{ route('diretor_comparativo.exibirEscolaComparativoAno', ['id' => $escola_selecionada[0]->id,'id_municipio' => $municipio_selecionado[0]->id, 'id_disciplina' => $disciplina_selecionada[0]->id, 'ano' => $ano_principal[0], 'sessao' => 'graficohabilidade']) }}">{{ $ano_principal[0]}}º Ano</a></li>
                                                            @endforeach
                                                        </ul>
                                                    </li>

                                                </ol>
                                            </nav>
                                        </div>
                                        <div class="col-md-2" style="background-color: white;border: 1px solid white;margin-bottom:-10px;margin-top: -15px;">
                                            <nav aria-label="breadcrumb">
                                                <ol class="breadcrumb" style="background-color: white;border:none;color:#0046AD;padding: 0em 0em;">
                                                    <li class="breadcrumb-item">
                                                    <li class="nav-item dropdown">
                                                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" style="color:black;font-size: 13px;border: 1px solid black;border-radius: 10px;"><i class="fa-brands fa-wpforms"></i>&emsp;{{$disciplina_selecionada[0]->desc}}</a>
                                                        <ul class="dropdown-menu">
                                                            @foreach($disciplinas as $disciplina)
                                                            <li><a class="dropdown-item" style="color:black;font-size: 13px;" href="{{ route('diretor_comparativo.exibirEscolaComparativoAno', ['id' => $escola_selecionada[0]->id,'id_municipio' => $municipio_selecionado[0]->id, 'id_disciplina' => $disciplina->id, 'ano' => $ano[0], 'sessao' => 'graficohabilidade']) }}">{{ $disciplina->desc}}</a></li>
                                                            @endforeach
                                                        </ul>
                                                    </li>

                                                </ol>
                                            </nav>
                                        </div>
                                    </div>
                                    <!------------------------------------ Gráfico ------------------->
                                    <div class="chartBox">
                                        <canvas id="graficoHabilidadeAnoDisciplina"></canvas>
                                    </div>
                                    <!------------------------------------ Gráfico ------------------->
                                </div>
                                @foreach($label_hab_ano_disc as $label_hab_ano_disc_item)
                                <button id="button_hab_ano_disc_{{$label_hab_ano_disc_item}}" onclick="manipularHabilidadeAnoDisciplina('{{$label_hab_ano_disc_item}}')" style="margin-right:5px;margin-top:0px;color:white;background-color:#f9821E;border-color:#f9821E;" class="btn btn-primary btn-sm"><i class="fa-solid fa-minus"></i> &ensp;{{$label_hab_ano_disc_item}}</button>
                                @endforeach
                                <button id="" data-bs-toggle="modal" data-bs-target="#mod_hab_ano_disc" onclick="" style="margin-left:100px;margin-top:5px;font-size:14px;font-weight:bold;" class="btn btn-outline-dark btn-sm"><i class="fa-solid fa-table-list"></i> &ensp; Dados Tabelados</button>
                                <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                                    * O presente gráfico representa o Percentual de Proficiência da Escola entre as Habilidades na Disciplina de {{$disciplina_selecionada[0]->desc}} no {{$ano[0]}}º Ano, no trancorrer dos Anos SAME.<br>* Ponha o mouse sobre as Colunas do Gráfico para visualizar dados detalhados.
                                </p>
                                <p style="color:black;font-size: 12px;text-align:right;margin-top:-10px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME.</p>
                            </div>

                            @include('comparativo/diretor/content/modals.habilidade_ano_disciplina')

                        </div>
                    </div>
                    <!---------------------------- Card Habilidade Ano Disciplina Comparativo Gráfico ------------------->
                </div>
                <!------------------------------------ Sessão Habilidade Ano Disciplina Comparativo Gráfico ------------------->

            </div>
        </div>
    </div>
</div>

@endsection