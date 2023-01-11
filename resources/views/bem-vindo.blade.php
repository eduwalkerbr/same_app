@extends('layouts.app')

@section('content')
@if (session('status'))
<script>
    alert("{{ session('status') }}");
</script>
@endif
<div class="container" style="padding-top: 20px;margin-bottom: 5px;box-shadow: 5px 5px 5px rgba(0,0,139);background-color: white;margin-top: 100px;">
    <!------------------------------------ Sessão inicial da Plataforma de Carrousel ------------------->
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
                        <!--
                        <p class="card-text" style="font-size:16px;color:color:rgba(0,0,139);">
                            Esta plataforma apresenta o resultado do processo de avaliação do seu municipio, escola, turma, aluno(a)
                        </p>
                        <p style="font-size:14px;color:rgba(156,163,175);">Fonte: Próprias</p>
                         -->
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
    <div class="row justify-content-center">
        <div class="col-md-12" style="background-color: white;margin-top:30px;margin-bottom:20px;border: 1px solid white;">

            <div class="row justify-content-center">
                <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
                    <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
                        <div class="card-header" style="text-align: justify;background-color: #f9821E; border-bottom:none;font-size:15px;color:white;font-weight:bold;">
                            <i class="fa-solid fa-door-open"></i> &emsp; Orientações para Utilização da plataforma
                        </div>
                        <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                            <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Informativo da plataforma</p>

                            <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">
                                Olá, tudo bem?<br><br>
                                Você está realizando seu primeiro acesso a Plataforma SAME. Se você chegou até aqui, já realizou a solicitação de registro, e ela se encontra no momento, em avaliação.
                                <br>Assim que a avaliação estiver completa, você será informado de que foi aprovada, e seu próximo acesso irá lhe proporcionar um gama de dados, de acordo com a sua função definida no momento de seu registro.
                                <br>Até lá, seguem algumas possibilidade para você:
                                <br> - Conforme os links acima, você pode se informar mais sobre Nós, reiterando quem somos, nossa finalidade e objetivos.
                                <br> - Ainda em relação aos links acima, proceder com uma nova solicitação de registro, lembrando que o e-mail precisa ser diferente.
                                <br>
                                <br>Além disso, vamos aproveitar para informar você sobre o funcionamento da plataforma.
                                <br> - A plataforma compreende um compilado de dados, nos mais diversos âmbitos, resultantes das avaliações do Same. Os dados
                                estarão expressos diretamente em forma percentual, seguidos de gráficos para representá-los.
                                <br> - Nós gráficos ainda existe a possibilidade de colocar o mouse sobre as colunas, para visualizar dados mais detalhados.
                                <br> - Quantos as legendas, segue abaixo uma representação das legendas, com suas definições e cores, além de uma descrição mais detalhada ao adicionar o mouse sobre o item da legenda que se deseja obter mais informações.
                                <br> - Após as legendas, existe ainda a possibilidade de que você nos disponibilize avaliações, ou realize sugestões a nossa plataforma, visando a melhoria do mesmo.
                            </p>
                            <!------------------------------------ Legenda ------------------->
                            <div class="row justify-content-center" style="margin-top:15px;">
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
                            <p style="color:black;font-size: 13px;text-align:justify;margin-top:10px;">Com isto, acreditamos que tenhamos passado informações que serão úteis em seu acesso registrado, que logo estará disponível.<br> Obrigado!</p>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection