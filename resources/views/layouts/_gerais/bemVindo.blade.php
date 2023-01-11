    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel" style="border: 1px solid white;">
    <div class=" carousel-inner">
        <!------------------------------------ Destaque Padrão ------------------->
        <div class="carousel-item active">
            <div class="card text-center">
                <div class="card-header" style="text-align: center;background-color: white; border-bottom:none;font-size:25px;color:rgba(0,0,139);font-weight:bold;">
                    Bem vindo(a)
                </div>
                <div class="card-body">
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
