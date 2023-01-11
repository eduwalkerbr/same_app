<div class="row justify-content-center">
    <!------------------------------------ Card Turma Disciplina ------------------->
    <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
        <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
            <!------------------------------------ Título Card ------------------->
            <div class="card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black;font-weight:bold;">
                <i class="fa-solid fa-percent"></i> &emsp; Turmas na Disciplina de {{$disciplina_selecionada[0]->desc}}
            </div>
            <!------------------------------------ Título Card ------------------->
            <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Seguem abaixo as Proficiências por Turma na Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                <!------------------------------------ Cards ------------------->
                @foreach ($dados_base_turmas_disciplina as $group)
                <div class="row justify-content-center">

                    @foreach ($group as $dados_base)
                    @foreach($legendas as $legenda)
                    @php
                    if(number_format($dados_base->percentual, 0, '.', '') >= $legenda->valor_inicial && number_format($dados_base->percentual, 0, '.', '') <= $legenda->valor_final){
                        $corFundo = $legenda->cor_fundo;
                        $corLetra = $legenda->cor_letra;
                        }
                        @endphp
                        @endforeach
                        <div class="col-md-2" style="margin-top:20px;border: 1px solid white;background-color:white;padding-left:0px;padding-right:0px;">
                            <div class="card text-center" style="background-color:{{$corFundo}};border: 1px solid #f0f8ff;box-shadow: 5px 5px 5px rgba(156,163,175);">
                                <div class="card-header" style="text-align: center;background-color:{{$corFundo}}; border-bottom:none;font-size:13px;font-weight:bold;color:{{$corLetra}};padding-bottom:0.1em;">
                                    {{$dados_base->descricao}}
                                </div>
                                <div class="card-body" style="padding-bottom:0.5em;padding-top:0.5em;">
                                    <h5 class="card-title" style="text-align: center;background-color:{{$corFundo}}; border-radius:50%;border-bottom:none;font-size:25px;color:{{$corLetra}};font-weight:bold;"><?php echo number_format($dados_base->percentual, 0, '.', '') ?>%</h5>
                                </div>
                            </div>
                        </div>
                        @endforeach
                </div>
                @endforeach
                <!------------------------------------ Cards ------------------->

                <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">
                    * Os presentes dados representam o percentual de proficiência da Escola na Disciplina de {{$disciplina_selecionada[0]->desc}}, por Turmas.
                </p>
                <!------------------------------------ Legenda ------------------->
                <div class="row justify-content-center" style="margin-top:10px;">
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
                <!------------------------------------ Legenda ------------------->
                <p style="color:black;font-size: 12px;text-align:right;margin-top:20px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>


            </div>
            <!------------------------------------ Navegação ------------------->
            <div class="card-footer text-muted" style="background-color: white;padding-top: 0em;padding-bottom: 0.3rem;border-top:none;text-align:justify;" id="graficoturmamatematica">
                <div class="row justify-content-center">
                    <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:left;">
                        <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#grafico_matematica">
                            Voltar para Gráfico Ano Curricular {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-up-short-wide"></i>

                        </a>
                    </div>
                    <div class="col-md-6" style="background-color: white;border: 1px solid white;text-align:right;">
                        <a class=" btn btn-link" style="color:#f9821E;font-size:13px;text-decoration:none;" href="#graficoturmamatematica">
                            Gráfico Turmas {{$disciplina_selecionada[0]->desc}} &emsp;<i class="fa-solid fa-arrow-down-short-wide"></i>

                        </a>
                    </div>
                </div>
            </div>
            <!------------------------------------ Navegação ------------------->
        </div>
    </div>
    <!------------------------------------ Card Turma Disciplina ------------------->
</div>