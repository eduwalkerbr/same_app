   <!------------------------------------ Sessão inicial da Plataforma de Carrousel ------------------->
   <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" style="border: 1px solid white;">
    <div class=" carousel-inner">
        <!------------------------------------ Destaque Padrão ------------------->
        <div class="carousel-item active">
            <div class="card text-center">
                <div class="card-header" style="text-align: center;background-color: white; border-bottom:none;font-size:20px;color:rgba(0,0,139);font-weight:bold;">
                    Bem vindo(a)
                </div>
                <div class="card-body">
                    <!--
                        <h5 class="card-title" style="text-align: center;background-color: white; border-bottom:none;font-size:40px;color:rgba(0,0,139);font-weight:bold;">100%</h5>
                    -->
                    <h6 class="card-title" style="text-align: center;background-color: white; border-bottom:none;font-size:20px;color:rgba(0,0,139);font-weight:bold;">
                        Esta plataforma apresenta o resultado do processo de avaliação do seu município, escola, turma e aluno(a).
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
                        Estamos realizando ajustes e revisões na Plataforma, para melhorar sua experiência.awwwwww
                    </a>
                </div>
            </div>
        </div>
        <!------------------------------------ Destaques Cadastrados ------------------->
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

                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
<!------------------------------------ Sobre ------------------->
<!--
<div class="row justify-content-center" id="turma">
    <div class="col-md-6" style="background-color: white;padding-top:13px;border: 1px solid white;">
        <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
            <div class="card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:rgba(0,0,139);font-weight:bold;">
                <i class="fa-solid fa-house"></i> &emsp; Sobre Nós
            </div>
            <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;">Somos voltados a apurar dados e informar. Quer nos conhecer melhor, acesse o link abaixo.</p>
            </div>
            <div class="card-footer text-muted" style="background-color: white;padding-top: 0.3rem;padding-bottom: 0.3rem;border-top:none;text-align:right;font-size:13px;">
                <a class=" btn btn-link" style="color:#f9821E;" href="{{route('sobre.index')}}">
                    Saiba mais &emsp;<i class="fa-solid fa-arrow-right"></i></i>

                </a>
            </div>
        </div>
    </div>
    -->
    <!------------------------------------ Registro ------------------->
    <!--
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
-->