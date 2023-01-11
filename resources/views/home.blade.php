@extends('layouts.appHome')

@section('content')
@if (session('status'))
<script>
    alert("{{ session('status') }}");
</script>

@endif
<div class="container" style="padding-top: 20px;margin-bottom: 5px;box-shadow: 5px 5px 5px rgba(0,0,139);background-color: white;margin-top: 100px;">
    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" style="border: 1px solid white;">
        <div class=" carousel-inner">
            <div class="carousel-item active">
                <div class="card text-center">
                    <div class="card-header" style="text-align: center;background-color: white; border-bottom:none;font-size:25px;color:rgba(0,0,139);font-weight:bold;">
                        Bem vindo(a)
                    </div>
                    <div class="card-body">
                        <!--
                            <h5 class="card-title" style="text-align: center;background-color: white; border-bottom:none;font-size:40px;color:rgba(0,0,139);font-weight:bold;">100%</h5>
                        -->
                        <h6 class="card-title" style="text-align: center;background-color: white; border-bottom:none;font-size:20px;color:rgba(0,0,139);font-weight:bold;">
                            Esta plataforma apresenta o resultado do processo de avaliação do seu município, escola, turma, aluno(a).
                        </h6>
    
                    </div>
                    <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;">
                        <a class=" btn btn-link" style="color:#f9821E;" href="">
                            Estamos realizando ajustes e revisões na Plataforma, para melhorar sua experiência.
                        </a>
                    </div>
                </div>
            </div>
            @foreach($destaques as $destaque)
            <div class="carousel-item">
                <div class="card text-center">
                    <div class="card-header" style="text-align: center;background-color: white; border-bottom:none;font-size:20px;color:rgba(0,0,139);font-weight:bold;">
                        {{$destaque->titulo}}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title" style="text-align: center;background-color: white; border-bottom:none;font-size:40px;color:rgba(0,0,139);font-weight:bold;">{{$destaque->conteudo}}</h5>
                        <p class="card-text" style="font-size:16px;color:black;">{{$destaque->descricao}}</p>
                        <p style="font-size:14px;color:rgba(156,163,175);">Fonte: {{$destaque->fonte}}</p>
                    </div>
                    <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;">
                        <a class=" btn btn-link" style="color:#f9821E;" href="">
                            Saiba mais &emsp;<i class="fa-solid fa-arrow-right"></i></i>

                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6" style="background-color: white;padding-top:13px;border: 1px solid white;">
            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                <div class="card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:rgba(0,0,139);font-weight:bold;">
                    <i class="fa-solid fa-house"></i> &emsp; Sobre Nós - Home
                </div>
                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;">Somos voltados a apurar dados e informar. Quer nos conhecer melhor, acesse o link abaixo.</p>
                </div>
                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:right;font-size:13px;">
                    <a class=" btn btn-link" style="color:#f9821E;" href="">
                        Saiba mais &emsp;<i class="fa-solid fa-arrow-right"></i></i>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6" style="background-color: white;padding-top:13px;border: 1px solid white;">
            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                <div class="card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:16px;color:#f9821E;font-weight:bold;">
                    <i class="fa-solid fa-user-check"></i> &emsp; Registre-se
                </div>
                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;">Quer uma experiência mais personalizada na plataforma, faça seu registro, clicando no link abaixo.</p>
                </div>
                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:right;font-size:13px;">
                    <a class=" btn btn-link" style="color:#f9821E;" href="{{ route('registro_base.index') }}">
                        Saiba mais &emsp;<i class="fa-solid fa-arrow-right"></i></i>

                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12" style="background-color: white;margin-top:10px;border: 1px solid white;">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb" style="background-color: #0046AD;margin-top:30px;border: 1px solid #0046AD;color:white;box-shadow: 5px 5px 5px rgba(0,0,139);padding: 0.1em 0.5em;">
                    <li class="breadcrumb-item">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" style="color:white;">Munícipio<i style="color:white;" class="fa-solid fa-slash-forward"></i></a>
                        <ul class="dropdown-menu">
                            @foreach($municipios as $municipio)
                            <li><a class="dropdown-item" style="color:black;" href="#">{{ $municipio->nome ?? ''}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    <i style="color:white;" class="fa-solid fa-slash-forward"></i>
                    </li>
                    <li class="breadcrumb-item">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" style="color:white;">Escola</a>
                        <ul class="dropdown-menu">
                            @foreach($escolas as $escola)
                            <li><a class="dropdown-item" style="color:black;" href="#">{{ $escola->nome ?? ''}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    </li>
                    <li class="breadcrumb-item">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" style="color:white;">Turma</a>
                        <ul class="dropdown-menu">
                            @foreach($turmas as $turma)
                            <li><a class="dropdown-item" style="color:black;" href="#">{{ $turma->DESCR_TURMA ?? ''}}</a></li>
                            @endforeach
                        </ul>
                    </li>
                    </li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12" style="background-color: white;margin-top:30px;border: 1px solid white;">
            <nav>
                <div class="nav nav-tabs" id="nav-tab-novo" role="tablist" style="border-bottom: 1px solid #f9821e;">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true" style="color:#f9821E;">Dados Gerais</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false" style="color:#f9821E;">Provas</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <div class="card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black;font-weight:bold;">
                                    <i class="fa-solid fa-city"></i> &emsp; {{$municipio_selecionado[0]->nome." / ".$municipio_selecionado[0]->uf}}
                                </div>
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;">Seguem abaixo alguns dados quantitativos relacionado ao Município.</p>
                                    @foreach ($dados_base_municipio as $group)
                                    <div class="row justify-content-center">
                                        @foreach ($group as $dados_base)
                                        <div class="col-md-6" style="margin-top:30px;border: 1px solid white;background-color:white;">
                                            <div class="card text-center" style="background-color:#f0f8ff;border: 1px solid #f0f8ff;">
                                                <div class="card-header" style="text-align: center;background-color:#f0f8ff; border-bottom:none;font-size:15px;font-weight:bold;color:rgba(0,0,139);padding-bottom:0.1em;">
                                                    {{$dados_base->descricao}}
                                                </div>
                                                <div class="card-body" style="padding-bottom:0.5em;padding-top:0.5em;">
                                                    <h5 class="card-title" style="text-align: center;background-color: #f0f8ff; border-radius:50%;border-bottom:none;font-size:32px;color:rgba(0,0,139);font-weight:bold;color:rgba(0,0,139);">{{$dados_base->qtd}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">Fonte: Dados oriundos de bases internas do Município (2022).</p>
                                </div>
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:right;">
                                </div>
                            </div>
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Aproveitamento por ano Escolar</p>
                                    <div class="chartCard col-md-10">
                                        <div class="chartBox">
                                            <canvas id="graficoAno"></canvas>
                                        </div>
                                    </div>

                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">Fonte: Dados oriundos de bases internas do Município (2022).<br>Caso deseje visualizar mais informações, acesse a sessão de questões abaixo.</p>
                                </div>
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:right;">
                                    <a class=" btn btn-link" style="color:#f9821E;font-size:13px;" href="">
                                        Saiba mais &emsp;<i class="fa-solid fa-arrow-right"></i></i>

                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <div class="row justify-content-center">
                        <div class="col-md-12" style="background-color: white;margin-top:20px;border: 1px solid white;">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb" style="background-color: white;border:none;color:#0046AD;padding: 0.1em 0.5em;">
                                    <li class="breadcrumb-item">
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" style="color:black;font-size: 13px;border: 1px solid black;border-radius: 10px;"><i class="fa-brands fa-wpforms"></i>&emsp;Prova</a>
                                        <ul class="dropdown-menu">
                                            @foreach($provas as $prova)
                                            <li><a class="dropdown-item" style="color:black;font-size: 13px;" href="#">{{ $prova->DESCR_PROVA ?? ''}}</a></li>
                                            @endforeach
                                        </ul>
                                    </li>

                                </ol>
                            </nav>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Acertos por Questão</p>
                                    <div class="chartCard col-md-10">
                                        <div class="chartBox">
                                            <canvas id="graficoProva"></canvas>
                                        </div>
                                    </div>

                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">Fonte: Dados oriundos de bases internas do Município (2022).<br>Caso deseje visualizar mais informações, acesse o link abaixo.</p>
                                </div>
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:right;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-question"></i> &emsp; Questões
                                </div>
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;margin-bottom:40px;">Segue abaixo a Listagem das Questões da Referente Prova, com a possibilidade de exibição de dados complementares.</p>
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="background-color:white;color: #0046AD;font-size:15px;border: 2px solid #0046AD;">
                                                    {{$prova_selecionada[0]->nome}}
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <table class="table caption-top">
                                                        <thead>
                                                            <tr style="font-size:12px;">
                                                                <th scope="col">Questão</th>
                                                                <th scope="col">Respostas A</th>
                                                                <th scope="col">Respostas B</th>
                                                                <th scope="col">Respostas C</th>
                                                                <th scope="col">Respostas D</th>
                                                                <th scope="col">Branco/Inválidos</th>
                                                                <th scope="col">Total Acertos</th>
                                                                <th scope="col">% Acertos</th>
                                                                <th scope="col">Ações</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($dados_base_qestoes as $dados_base_qestao)
                                                            <tr style="font-size:12px;vertical-align:initial;">
                                                                <th style="font-weight: normal;font-size:12px;padding: 0em;vertical-align:middle;color:rgba(107,114,128);font-weight:bold;" scope="row">{{$dados_base_qestao->nome}}</th>
                                                                <td style="font-weight: normal;font-size:12px;padding: 0em;vertical-align:middle;color:rgba(107,114,128);">{{$dados_base_qestao->resp_A}}</td>
                                                                <td style="font-weight: normal;font-size:12px;padding: 0em;vertical-align:middle;color:rgba(107,114,128);">{{$dados_base_qestao->resp_B}}</td>
                                                                <td style="font-weight: normal;font-size:12px;padding: 0em;vertical-align:middle;color:rgba(107,114,128);">{{$dados_base_qestao->resp_C}}</td>
                                                                <td style="font-weight: normal;font-size:12px;padding: 0em;vertical-align:middle;color:rgba(107,114,128);">{{$dados_base_qestao->resp_D}}</td>
                                                                <td style="font-weight: normal;font-size:12px;padding: 0em;vertical-align:middle;color:rgba(107,114,128);">{{$dados_base_qestao->branco_invalido}}</td>
                                                                <td style="font-weight: normal;font-size:12px;padding: 0em;vertical-align:middle;color:rgba(107,114,128);">{{$dados_base_qestao->acertos}}</td>
                                                                <td style="font-weight: normal;font-size:12px;padding: 0em;vertical-align:middle;margin-top: 5px;color:rgba(107,114,128);"><?php echo number_format($dados_base_qestao->percentual_acertos, 2, '.', '') ?>%</td>
                                                                <td style="text-align:center;">
                                                                    <a class=" btn btn-link" style="color:#f9821E;font-size:13px;padding: 0em;" href="">
                                                                        Ver mais &emsp;<i class="fa-solid fa-eye"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>


                                        <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">Fonte: Dados oriundos de bases internas do Município (2022).</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-center">
                        <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <div class="card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black;font-weight:bold;">
                                    <i class="fa-brands fa-wpforms"></i>&emsp; {{$prova_selecionada[0]->nome}}
                                </div>
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;"><b>Disciplina:</b> {{$prova_selecionada[0]->disciplina}}</p>
                                    @foreach ($dados_base_provas_chunk as $group)
                                    <div class="row justify-content-center">
                                        @foreach ($group as $dados_base_prova)
                                        <div class="col-md-6" style="margin-top:30px;border: 1px solid white;background-color:white;">
                                            <div class="card text-center" style="background-color:#f0f8ff;border: 1px solid #f0f8ff;">
                                                <div class="card-header" style="text-align: center;background-color:#f0f8ff; border-bottom:none;font-size:15px;font-weight:bold;color:rgba(0,0,139);padding-bottom:0.1em;">
                                                    {{$dados_base_prova->descricao}}
                                                </div>
                                                <div class="card-body" style="padding-bottom:0.5em;padding-top:0.5em;">
                                                    <h5 class="card-title" style="text-align: center;background-color: #f0f8ff; border-radius:50%;border-bottom:none;font-size:32px;color:rgba(0,0,139);font-weight:bold;color:rgba(0,0,139);">{{$dados_base_prova->qtd_dados}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endforeach
                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">Fonte: Dados oriundos de bases internas do Município (2022).</p>
                                </div>
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:right;">
                                </div>
                            </div>
                            <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <div class=" card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black#f9821E;font-weight:bold;">
                                    <i class="fa-solid fa-square-poll-vertical"></i> &emsp; Indicadores
                                </div>
                                <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;background-color:white;">
                                    <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Dados Gerais de Prova</p>
                                    <div class="chartCard col-md-10">
                                        <div class="chartBox">
                                            <canvas id="graficoGeralProva"></canvas>
                                        </div>
                                    </div>

                                    <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">Fonte: Dados oriundos de bases internas do Município (2022).</p>
                                </div>
                                <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:right;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
    @endsection