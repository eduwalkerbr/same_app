<div class="col-2 fixed-top" style="margin-left:-10px;">
    <nav id="navbar-example3" class="h-100 flex-column align-items-stretch pe-4" style="margin-top:180px;margin-right:15px;">
        <nav class="nav nav-pills flex-column">
            <a id="link_turma" onclick="manipularLink('turma')" class="nav-link" href="#turma" style="font-size:90%;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:5%;padding-bottom:5%;white-space:normal;">Média</a>
            <div class="dropend">
                <a id="link_temas" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" onclick="manipularLink('temas')" href="" style="font-size:90%;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:5%;padding-bottom:5%;white-space:normal;">@if($disciplina_selecionada[0]->id == 1) Temas @else Eixos/Temas @endif por Disciplina</a>
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_temas" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('temas')" href="#temas" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Dados Gerais</a></li>
                    <li class="nav-item dropdown"><a id="link_temasgrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('temas')" href="#temasgrafico" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Gráfico</a></li>
                </ul>
            </div>
            <div class="dropend">
                <a id="link_habilidadedisciplina" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false" onclick="manipularLink('habilidadedisciplina')" href="" style="font-size:90%;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:5%;padding-bottom:5%;white-space:normal;">Habilidades por Disciplina</a>
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_habilidadedisciplina" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('habilidadedisciplina')" href="#habilidadedisciplina" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Dados Gerais</a></li>
                    <li class="nav-item dropdown"><a id="link_habilidadedisciplinagrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('habilidadedisciplina')" href="#habilidadedisciplinagrafico" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Gráfico</a></li>
                </ul>
            </div>
            <div class="dropend">
                <a id="link_habilidadeanodisciplina" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" onclick="manipularLink('habilidadeanodisciplina')" href="" style="font-size:90%;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:5%;padding-bottom:5%;white-space:normal;">Habilidades por Disciplina e Ano Curricular</a>
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_habilidadeanodisciplina" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('habilidadeanodisciplina')" href="#habilidadeanodisciplina" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Dados Gerais</a></li>
                    <li class="nav-item dropdown"><a id="link_habilidadeanodisciplinagrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('habilidadeanodisciplina')" href="#habilidadeanodisciplinagrafico" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Gráfico</a></li>
                </ul>
            </div>
            <div class="dropend">
                <a id="link_habilidadeselecionadadisciplina" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" onclick="manipularLink('habilidadeselecionadadisciplina')" href="#" style="font-size:90%;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:5%;padding-bottom:5%;white-space:normal;">Habilidade Selecionada por Disciplina e Ano Curricular</a>
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_habilidadeselecionadadisciplina" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('habilidadeselecionadadisciplina')" href="#habilidadeselecionadadisciplina" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Dados Gerais</a></li>
                    @if(count($dados_base_habilidade_disciplina_grafico_habilidade) > 1)
                    <li class="nav-item dropdown"><a id="link_habilidadeselecionadadisciplinagrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('habilidadeselecionadadisciplina')" href="#habilidadeselecionadadisciplinagrafico" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Gráfico</a></li>
                    @endif
                </ul>
            </div>
            <div class="dropend">
                @php
                $previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
                @endphp
                @if($previlegio->funcaos_id == 7 || Auth::user()->perfil == 'Administrador')
                <a id="link_questoesobjetivas" onclick="manipularLink('questoesobjetivas')" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="#" style="font-size:90%;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:5%;padding-bottom:5%;white-space:normal;">Questões por Disciplina</a>
                @else
                <a id="link_questoesobjetivas" onclick="manipularLink('questoesobjetivas')" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="#" style="font-size:90%;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;padding-top:5%;padding-bottom:5%;white-space:normal;">Questões por Disciplina</a>
                @endif
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_questoesobjetivas" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('questoesobjetivas')" href="#questoesobjetivas" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Questões Objetivas</a></li>
                    @if(count($tipos_questoes) > 1)
                    <li class="nav-item dropdown"><a id="link_questoesdif1" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('questoesobjetivas')" href="#questoesdif1" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">{{$tipos_questoes[1]}}</a></li>
                    @endif
                    @if(count($tipos_questoes) > 2)
                    <li class="nav-item dropdown"><a id="link_questoesdif2" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('questoesobjetivas')" href="#questoesdif2" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">{{$tipos_questoes[2]}}</a></li>
                    @endif
                    <li class="nav-item dropdown"><a id="link_questoesgrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('questoesobjetivas')" href="#questoesgrafico" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Gráfico</a></li>
                </ul>
            </div>
            @php
            $previlegio = Auth::user()->find(Auth::user()->id)->relPrevilegio;
            @endphp
            @if($previlegio->funcaos_id == 7 || Auth::user()->perfil == 'Administrador')
            <div class="dropend">
                <a id="link_alunos" onclick="manipularLink('alunos')" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" href="#" style="font-size:90%;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;padding-top:5%;padding-bottom:5%;white-space:normal;">Alunos</a>
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_alunos" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('alunos')" href="#alunos" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Dados Gerais</a></li>
                    <li class="nav-item dropdown"><a id="link_alunosgrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('alunos')" href="#alunosgrafico" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Gráfico</a></li>
                </ul>
            </div>
            @endif
        </nav>
    </nav>
</div>