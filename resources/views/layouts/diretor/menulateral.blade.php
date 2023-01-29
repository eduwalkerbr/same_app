<div class="col-2 fixed-top">
    <nav id="navbar-example3" class="h-100 flex-column align-items-stretch pe-4" style="margin-top:155px;margin-right:5px;">
        <nav class="nav nav-pills flex-column" style="background-color:rgba(54, 162, 235, 0.2);">
            <a id="link_escola" onclick="manipularLink('escola')" class="nav-link" href="#escola" style="font-size:14px;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:15px;padding-bottom:15px;padding-left:10px;padding-right:5px;">Média</a>
            <a id="link_disciplinas" class="nav-link" onclick="manipularLink('disciplinas')" href="#disciplinas" style="font-size:14px;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:15px;padding-bottom:15px;padding-left:10px;padding-right:5px;">Disciplinas</a>
            <div class="dropend">
            <a id="link_curriculardisciplina" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" onclick="manipularLink('curriculardisciplina')" href="#" style="font-size:14px;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:15px;padding-bottom:15px;padding-left:10px;padding-right:5px;">Anos Curriculares por <br> Disciplina</a>
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_curriculardisciplina" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('curriculardisciplina')" href="#curriculardisciplina" style="font-size:14px;font-weight:bold;color:#0046AD;border:none;padding-top:5;padding-bottom:5;">Dados Gerais</a></li>
                    <li class="nav-item dropdown"><a id="link_curriculardisciplinagrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('curriculardisciplina')" href="#curriculardisciplinagrafico" style="font-size:14px;font-weight:bold;color:#0046AD;border:none;padding-top:5;padding-bottom:5;">Gráfico</a></li>
                </ul>
            </div>
            <div class="dropend">
            <a id="link_turmadisciplina" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" onclick="manipularLink('turmadisciplina')" href="#" style="font-size:14px;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:15px;padding-bottom:15px;padding-left:10px;padding-right:5px;">Turmas por Disciplina</a>
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_turmadisciplina" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('turmadisciplina')" href="#turmadisciplina" style="font-size:14px;font-weight:bold;color:#0046AD;border:none;padding-top:5;padding-bottom:5;">Dados Gerais</a></li>
                    <li class="nav-item dropdown"><a id="link_turmadisciplinagrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('turmadisciplina')" href="#turmadisciplinagrafico" style="font-size:14px;font-weight:bold;color:#0046AD;border:none;padding-top:5;padding-bottom:5;">Gráfico</a></li>
                </ul>
            </div>
            <div class="dropend">
            <a id="link_habilidadeanodisciplina" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" onclick="manipularLink('habilidadeanodisciplina')" href="#" style="font-size:14px;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;border-bottom:none;padding-top:15px;padding-bottom:15px;padding-left:10px;padding-right:5px;">Habilidades por Disciplina e <br> Ano Curricular</a>
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_habilidadeanodisciplina" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('habilidadeanodisciplina')" href="#habilidadeanodisciplina" style="font-size:14px;font-weight:bold;color:#0046AD;border:none;padding-top:5;padding-bottom:5;">Dados Gerais</a></li>
                    <li class="nav-item dropdown"><a id="link_habilidadeanodisciplinagrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('habilidadeanodisciplina')" href="#habilidadeanodisciplinagrafico" style="font-size:14px;font-weight:bold;color:#0046AD;border:none;padding-top:5;padding-bottom:5;">Gráfico</a></li>
                </ul>
            </div> 
            <div class="dropend">
            <a id="link_habilidadeselecionadadisciplina" class="nav-link dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" onclick="manipularLink('habilidadeselecionadadisciplina')" href="#" style="font-size:14px;font-weight:bold;color:#0046AD;border: 0.1em solid #0046AD;border-radius:0;padding-top:10px;padding-bottom:10px;">Habilidade Selecionada por <br> Disciplina e Ano Curricular</a>    
                <ul class="dropdown-menu">
                    <li class="nav-item dropdown"><a id="link_habilidadeselecionadadisciplina" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('habilidadeselecionadadisciplina')" href="#habilidadeselecionadadisciplina" style="font-size:14px;font-weight:bold;color:#0046AD;border:none;padding-top:5;padding-bottom:5;">Dados Gerais</a></li>
                    @if(count($dados_base_habilidade_disciplina_grafico) > 1)
                    <li class="nav-item dropdown"><a id="link_habilidadeselecionadadisciplinagrafico" class="nav-link" onmouseover='this.style.backgroundColor="#0046AD";this.style.color="white"' onmouseout='this.style.backgroundColor=" white";this.style.color="#0046AD"' onclick="manipularLink('habilidadeselecionadadisciplina')" href="#habilidadeselecionadadisciplinagrafico" style="font-size:14px;font-weight:bold;color:#0046AD;border:none;padding-top:5;padding-bottom:5;">Gráfico</a></li>
                    @endif
                </ul>
            </div>    
        </nav>
    </nav>
</div>