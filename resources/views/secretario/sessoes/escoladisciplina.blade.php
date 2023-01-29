<div class="row justify-content-center section">
    <!------------------------------------ Card Escola Disciplina Munícipio ------------------->
    <div class="card-deck" style="background-color: white;padding-top:16px;border: 1px solid white;">
        <div class="card text-center" style="box-shadow: 5px 5px 5px rgba(156,163,175);">
            <!-------------------------Título Cardio Escolas Disciplina Município Cards ------------------->
            <div class="card-header" style="text-align: justify;background-color: white; border-bottom:none;font-size:15px;color:black;font-weight:bold;">
                <i class="fa-solid fa-percent"></i> &emsp; Escolas do Munícipio na Disciplina de {{$disciplina_selecionada[0]->desc}}
            </div>
            <!-------------------------Título Cardio Escolas Disciplina Município Cards ------------------->
            <div class="card-body" style="padding-top:0.5rem;padding-bottom:0.5rem;">
                <p class="card-text" style="font-size:13px;color:black;border-bottom:none;text-align:justify;font-weight:bold;">Seguem abaixo as Proficiências por Escola na Disciplina de {{$disciplina_selecionada[0]->desc}}</p>
                <!------------------------------------ Cards ------------------->
                @foreach ($dados_base_escola_disciplina as $group)
                <div class="row justify-content-center" style="margin-top: -10px;">
                    @foreach ($group as $dados_base)
                    @foreach($legendas as $legenda)
                    @php
                    if(number_format($dados_base->percentual, 0, '.', '') >= $legenda->valor_inicial && number_format($dados_base->percentual, 0, '.', '') <= $legenda->valor_final){
                        $corFundo = $legenda->cor_fundo;
                        $corLetra = $legenda->cor_letra;
                        }
                        @endphp
                        @endforeach
                        <div class="col-md-3" style="margin-top:20px;border: 1px solid white;background-color:white;padding-left:0px;padding-right:0px;">
                            <div class="card text-center" style="background-color:{{$corFundo}};border: 1px solid #f0f8ff;box-shadow: 5px 5px 5px rgba(156,163,175);min-height: 130px;" data-bs-toggle="modal" data-bs-target="{{$dados_base->descricao}}">
                                <div class="card-header" style="text-align: center;background-color:{{$corFundo}}; border-bottom:none;font-size:12px;font-weight:bold;color:{{$corLetra}};padding-bottom:0.1em;">
                                    <a class=" btn btn-link" data-bs-toggle="tooltip" data-bs-placement="right" title="{{$dados_base->descricao}}" style="text-decoration:none;text-align: center;background-color:{{$corFundo}}; border-bottom:none;font-size:12px;font-weight:bold;color:{{$corLetra}};padding-bottom:0.1em;padding-top:0em;margin-right: 0;margin-left: 0;padding-left:0em;padding-right:0em;" href="#questao_grafico">
                                        {{$dados_base->sigla}} &ensp;<i class="fa-solid fa-circle-question"></i>
                                    </a>
                                </div>
                                <div class="card-body" style="padding-bottom:0em;padding-top:0.5em;">
                                    <h5 class="card-title" style="text-align: center;background-color:{{$corFundo}}; border-radius:50%;border-bottom:none;font-size:25px;color:{{$corLetra}};font-weight:bold;"><?php echo number_format($dados_base->percentual, 0, '.', '') ?>%</h5>
                                </div>
                                <div class="card-footer text-muted" style="background-color: {{$corFundo}};padding-top: 0rem;padding-bottom: 0rem;border-top:none;text-align:center;line-heigth: 0em;">
                                    <p style="color:{{$corLetra}};font-size:12px;margin-bottom: 0.5em;">
                                        {{$dados_base->descricao}}

                                    </p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                </div>
                @endforeach
                <!------------------------------------ Cards ------------------->

                <p style="color:rgba(107,114,128);font-size: 13px;text-align:justify;margin-top:30px;">
                    * Os presentes dados representam o percentual de proficiência por Escola do Município na Disciplina de {{$disciplina_selecionada[0]->desc}}.<br>* Caso deseje visualizar o nome completo da Escola, ponha o mouse sobre o ícone &ensp;<i class="fa-solid fa-circle-question"></i>
                </p>
                <!------------------------------------ Legenda ------------------->
                @php
                    if(count($dados_base_grafico_escola) > 1)
                        $id_sessao_escola = 'escolasdisciplinagrafico';    
                    else      
                        $id_sessao_escola = 'curriculardisciplina';   
                @endphp
                <div class="row justify-content-center" style="margin-top:15px;" id="{{$id_sessao_escola}}">
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
                <p style="color:black;font-size: 12px;text-align:right;margin-top:10px;font-weight:bold;">Fonte: Dados oriundos de bases internas do SAME ({{strval($ano_same_selecionado)}}).</p>
                <!------------------------------------ Legenda ------------------->
            </div>

        </div>
    </div>
    <!------------------------------------ Card Escola Disciplina Munícipio ------------------->
</div>