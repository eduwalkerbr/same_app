<div class="col-2 fixed-top" style="margin-left:-10px;">
    <nav id="navbar-example3" class="h-100 flex-column align-items-stretch pe-4" style="margin-top:180px;margin-right:15px;">
        <nav class="nav nav-pills flex-column">
            <a id="link_municipio" onclick="manipularLink('municipio')" class="nav-link" href="#municipio" style="font-size:90%;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:5%;padding-bottom:5%;white-space:normal;">Disciplinas</a>
            <div class="dropend">
            <a id="link_escolas" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" onclick="manipularLink('escolas')" href="#" style="font-size:90%;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:5%;padding-bottom:5%;margin-bottom:5%;white-space:normal;">Escolas</a>    
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_escolas" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('escolas')" href="#escolas" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Dados Gerais</a></li>
                    @if(count($dados_base_grafico_escola) > 1)
                    <li class="nav-item dropdown"><a id="link_escolasgrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('escolas')" href="#escolasgrafico" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Gráfico</a></li>
                    @endif
                </ul>
            </div>
            <div class="dropend">
            <a id="link_escolasdisciplina" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" onclick="manipularLink('escolasdisciplina')" href="#" style="font-size:90%;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:5%;padding-bottom:5%;white-space:normal;">Escolas por Disciplina</a>
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_escolasdisciplina" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('escolasdisciplina')" href="#escolasdisciplina" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Dados Gerais</a></li>
                    @if(count($dados_base_grafico_escola) > 1)
                    <li class="nav-item dropdown"><a id="link_escolasdisciplinagrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('escolasdisciplina')" href="#escolasdisciplinagrafico" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Gráfico</a></li>
                    @endif
                </ul>
            </div>
            <div class="dropend">
            <a id="link_curriculardisciplina" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" onclick="manipularLink('curriculardisciplina')" href="#" style="font-size:90%;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:5%;padding-bottom:5%;white-space:normal;">Anos Curriculares por Disciplina</a>
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_curriculardisciplina" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('curriculardisciplina')" href="#curriculardisciplina" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Dados Gerais</a></li>
                    <li class="nav-item dropdown"><a id="link_curriculardisciplinagrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('curriculardisciplina')" href="#curriculardisciplinagrafico" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Gráfico</a></li>
                </ul>
            </div>
            <div class="dropend">
            <a id="link_habilidadeanodisciplina" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" onclick="manipularLink('habilidadeanodisciplina')" href="#" style="font-size:90%;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:5%;padding-bottom:5%;white-space:normal;">Habilidades por Disciplina e Ano Curricular</a>
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_habilidadeanodisciplina" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('habilidadeanodisciplina')" href="#habilidadeanodisciplina" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Dados Gerais</a></li>
                    <li class="nav-item dropdown"><a id="link_habilidadeanodisciplinagrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('habilidadeanodisciplina')" href="#habilidadeanodisciplinagrafico" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Gráfico</a></li>
                </ul>
            </div>
            <div class="dropend">
            <a id="link_habilidadeselecionadadisciplina" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" onclick="manipularLink('habilidadeselecionadadisciplina')" href="#" style="font-size:90%;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;padding-top:5%;padding-bottom:5%;white-space:normal;">Habilidade Selecionada por Disciplina e Ano Curricular</a>    
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_habilidadeselecionadadisciplina" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('habilidadeselecionadadisciplina')" href="#habilidadeselecionadadisciplina" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Dados Gerais</a></li>
                    @if(count($dados_base_habilidade_disciplina_grafico) > 1)
                    <li class="nav-item dropdown"><a id="link_habilidadeselecionadadisciplinagrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('habilidadeselecionadadisciplina')" href="#habilidadeselecionadadisciplinagrafico" style="font-size:90%;font-weight:bold;color:#0046AD;border:none;padding-top:5%;padding-bottom:5%;">Gráfico</a></li>
                    @endif
                </ul>
            </div>
        </nav>
    </nav>
</div>