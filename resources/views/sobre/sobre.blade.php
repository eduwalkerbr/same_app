@extends('layouts.appSobre')

@section('content')
@if (session('status'))
<script>
    alert("{{ session('status') }}");
</script>

@endif
<div class="container" style="padding-top: 20px;margin-bottom: 5px;box-shadow: 5px 5px 5px rgba(0,0,139);background-color: white;margin-top: 100px;">
    <!------------------------------------ Sessão inicial da plataforma de Carrousel ------------------->
    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" style="border: 1px solid white;">
        <!------------------------------------ Destaque Padrão ------------------->
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
        </div>
    </div>

    <!------------------------------------ Sobre Apresentação------------------->
    <div class="row justify-content-center">
        <div class="col-md-12" style="background-color: white;margin-top:30px;margin-bottom:20px;border: 1px solid white;">
            <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                    <div class="card-header" style="text-align: justify;background-color: #f9821E; border-bottom:none;font-size:15px;color:white;font-weight:bold;">
                        <i class="fa-solid fa-memo-circle-info"></i> &emsp; Sobre Nós
                    </div>
                    <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                        <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                            SAME é um Sistema de Avaliação Municipal da Educação Básica, que tem por objetivo desenvolver uma Avaliação Diagnóstica de caráter censitária envolvendo todos os alunos das redes de educação matriculados entre o 2º e 9º anos do Ensino Fundamental, que sustente um planejamento dos processos educacionais para os próximos anos.<br>
                        </p>
                        <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                            Envolve duas áreas de conhecimento, Matemática e Português, a partir de uma prova organizada em questões de múltipla escolha e resposta construída ou produção textual.
                        </p>
                        <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">

                            <br>As questões da prova são organizadas a partir de Matrizes de Referências (ANA - Avaliação Nacional de Alfabetização e Matriz de Referência de Língua Portuguesa e Matemática do SAEB), identificando Habilidades/Descritores em cada ano e área de conhecimento.
                        </p>
                        <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:10px;">
                            <br>Os resultados, a partir do nível de proficiência (AVANÇADO, PROFICIENTE, BÁSICO, INSUFICIENTE) é organizado em uma escala e apresentado a partir desta plataforma, com escopos diferentes ao gestor municipal, gestor escolar e professor da turma, por disciplina.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>
@endsection