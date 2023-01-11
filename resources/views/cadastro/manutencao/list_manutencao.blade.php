@extends('cadastro.cadastro')

@section('content')
<div class="container" style="margin-top: 100px;">
  <div class="card text-center">
    <div class="card-body" style="background-color: #0046AD; color: white;box-shadow: 5px 5px 5px rgba(0,0, 139);">
      <h5 class="card-title">Rotinas de Manutenção</h5>
      <p class="card-text">Segue abaixo as Rotinas de Manutenção do Site.</p>
    </div>
  </div>
  @csrf
  <br>
  <div class="accordion" id="accordionDadosUnificados">
    <div class="accordion-item">
      <h2 class="accordion-header" id="panelsStayHeading-DadosUnificados">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-DadosUnificados" aria-expanded="true" aria-controls="panelsStayOpen-DadosUnificados" style="background-color:white;color: #f9821e;font-weight:bold;font-size:16px;border: none;">
          Dados Unificados
        </button>
      </h2>
      <div id="panelsStayOpen-DadosUnificados" class="accordion-collapse collapse" aria-labelledby="panelsStayHeading-DadosUnificados">
        <div class="accordion-body">
          <form id="form_filter" name="form_filter" action="" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
            </div>
            <div class="row justify-content-begin" style="color:black;font-size:15px;">
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('dados_unificados.limpar') }}"><button type="button" class="btn btn-dark btn-sm">Limpar Dados Unificados</button></a>
                </div>
              </div>
            </div>
            <hr style="color: #0046AD;size:20px;">
            <div class="row justify-content-begin" style="color:black;font-size:15px;">
              <div class="col-md-5">
                <div class="form-group">
                  <a href="{{ route('dados_unificados.carregar') }}"><button type="button" class="btn btn-outline-primary btn-sm">Carregar Dados Unificados</button></a>
                </div>
              </div>
            </div>  
          </form>
        </div>
      </div>
    </div>
  </div>
  <br>
  <div class="accordion" id="accordionPanelsStayOpenExample">
    <div class="accordion-item">
      <h2 class="accordion-header" id="panelsStayOpen-headingOne">
        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne" style="background-color:white;color: #f9821e;font-weight:bold;font-size:16px;border: none;">
          Caches
        </button>
      </h2>
      <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingOne">
        <div class="accordion-body">
          <form id="form_filter" name="form_filter" action="" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
              <input type="hidden" name="id_user" id="id_user" value="{{ Auth::user()->id }}">
            </div>
            <div class="row justify-content-begin" style="color:black;font-size:15px;">
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.limpar') }}"><button type="button" class="btn btn-dark btn-sm">Limpar Cache</button></a>
                </div>
              </div>
            </div>
            <hr style="color: #0046AD;size:20px;">
            <div class="row justify-content-begin" style="color:black;font-size:15px;">
              <div class="col-md-2">
                <div class="form-group">
                  <a href="{{ route('cache.municipio_dados_base') }}"><button type="button" class="btn btn-outline-primary btn-sm">Dados Base Município</button></a>
                </div>
              </div>
              <div class="col-md-4">
                <div class="form-group">
                  <a href="{{ route('cache.municipio_hab_ano_mat') }}"><button type="button" class="btn btn-outline-primary btn-sm"> Anos Habilidade Matemática Município</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.municipio_hab_ano_port') }}"><button type="button" class="btn btn-outline-primary btn-sm">Anos Habilidade Português Município</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.municipio_ano_hab') }}"><button type="button" class="btn btn-outline-primary btn-sm">Habilidade Anos Município</button></a>
                </div>
              </div>
            </div>
            <hr style="color: #0046AD;size:20px;">
            <div class="row justify-content-begin" style="color:black;font-size:15px;">
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.escola_dados_base') }}"><button type="button" class="btn btn-outline-danger btn-sm">Dados Base Escola</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.media_escola') }}"><button type="button" class="btn btn-outline-danger btn-sm">Média Escolas</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.escola_comp_disc') }}"><button type="button" class="btn btn-outline-danger btn-sm">Comparativo e Disciplina Escola</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.escola_ano_cur_turma') }}"><button type="button" class="btn btn-outline-danger btn-sm">Ano Curricular e Turmas Escola</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.escola_anos_hab_port') }}"><button type="button" class="btn btn-outline-danger btn-sm">Anos Habilidade Português Escola</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.escola_anos_hab_mat') }}"><button type="button" class="btn btn-outline-danger btn-sm">Anos Habilidade Matemática Escola</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.escola_hab_anos_port') }}"><button type="button" class="btn btn-outline-danger btn-sm">Habilidade Anos Português Escola</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.escola_hab_anos_mat') }}"><button type="button" class="btn btn-outline-danger btn-sm">Habilidade Anos Matemática Escola</button></a>
                </div>
              </div>
            </div>
            <hr style="color: #0046AD;size:20px;">
            <div class="row justify-content-begin" style="color:black;font-size:15px;">
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.turma_dados_base') }}"><button type="button" class="btn btn-outline-secondary btn-sm">Dados Base Turma</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.media_turma') }}"><button type="button" class="btn btn-outline-secondary btn-sm">Média Turmas</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.turma_tema') }}"><button type="button" class="btn btn-outline-secondary btn-sm">Temas Turma</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.turma_hab_mat') }}"><button type="button" class="btn btn-outline-secondary btn-sm">Habilidade Matemática Turma</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.turma_hab_port') }}"><button type="button" class="btn btn-outline-secondary btn-sm">Habilidade Português Turma</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.turma_hab_ano_mat') }}"><button type="button" class="btn btn-outline-secondary btn-sm">Habilidade Anos Matemática Turma</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.turma_hab_ano_port') }}"><button type="button" class="btn btn-outline-secondary btn-sm">Habilidade Anos Português Turma</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.turma_hab_sel_mat') }}"><button type="button" class="btn btn-outline-secondary btn-sm">Hab. Selec. Matemática Turma</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.turma_hab_sel_port') }}"><button type="button" class="btn btn-outline-secondary btn-sm">Hab. Selec. Português Turma</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.turma_quest_mat') }}"><button type="button" class="btn btn-outline-secondary btn-sm">Questões Matemática Turma</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.turma_quest_port') }}"><button type="button" class="btn btn-outline-secondary btn-sm">Questões Português Turma</button></a>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <a href="{{ route('cache.turma_alunos') }}"><button type="button" class="btn btn-outline-secondary btn-sm">Alunos Turma</button></a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection