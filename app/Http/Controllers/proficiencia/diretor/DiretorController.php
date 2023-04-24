<?php
namespace App\Http\Controllers\proficiencia\diretor;
use App\Http\Controllers\Controller;
use App\Http\Controllers\staticmethods\gerais\MethodsGerais;
use App\Http\Controllers\staticmethods\proficiencia\MethodsProfEscola;

class DiretorController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();

        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
        $ano_same_selecionado = $anos_same[0]->SAME;

        //Listage, de Direção Professor utilizando Cache
        $direcaoProfessor = MethodsProfEscola::getDirecaoProfessor($ano_same_selecionado);

        //----------------------------------------- Escolas -------------------------------------------------------------------
        $escolas = MethodsProfEscola::getEscolasDiretor($previlegio[0]->municipios_id, $ano_same_selecionado);

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        $disciplinas = MethodsGerais::getDisciplinas();

        //------------------------------------------ Solicitações -----------------------------------------------------------------
        //Caso seja Gestor busca todas solicitações em aberto do munícipio
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();

        //Busca as Legendas
        $legendas = MethodsGerais::getLegendas();

        //Realiza a busca dos Destaques
        $destaques = MethodsGerais::getDestaques();

        //------------------------------------------- Municípios -----------------------------------------------------------------
        $municipios = MethodsGerais::getMunicipios($ano_same_selecionado);

        //Identifica a escola selecionada
        $escola = $escolas[0]->id;

        //Busca as turmas da escola selecionda
        $turmas = MethodsProfEscola::getTurmasEscola($escola, $ano_same_selecionado);

        //Seta os Anos a serem utilizados no Select
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o ano padrão do Select
        $ano = substr(trim($turmas[0]->DESCR_TURMA), 0, 2);

        //Carrega os dados da Escola Selecionda
        $escola_selecionada = MethodsGerais::getEscolaSelecionada($escola, $ano_same_selecionado);

        //Busca os dados do Município selecionado partindo da escola
        $municipio_selecionado = MethodsGerais::getMunicipioSelecionado($escola_selecionada[0]->municipios_id, $ano_same_selecionado);

        //Busca os dados da Disciplina Selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($disciplinas[0]->id);

        //Busca dados Sessão Base de Escola --------------------------------------------------------------
        $dados_base_escola = MethodsProfEscola::estatisticaEscola($escola, $ano_same_selecionado);
        //Divide em 2 por linha para gerar os Cards
        $dados_base_escola = array_chunk($dados_base_escola, 2);
        //Busca dados Sessão Base de Escola --------------------------------------------------------------

        //Busca dados da Sessão de Comparativo entre a Escola Selecionado e as Demais --------------------
        $dados_comparacao_escola = MethodsProfEscola::estatisticaComparacaoEscola($escola, $ano_same_selecionado);
        //Busca dados da Sessão de Comparativo entre a Escola Selecionado e as Demais --------------------

        //Busca Dados para Sessão de Proficiência da Escola pela Disciplina ------------------------------
        $dados_base_grafico_disciplina = MethodsProfEscola::estatisticaGraficoDisciplina($escola, $ano_same_selecionado);
        //Divide em 4 por linha para Gerar os Cards
        $dados_base_disciplina = array_chunk($dados_base_grafico_disciplina, 4);
        //Busca Dados para Sessão de Proficiência da Escola pela Disciplina ------------------------------

        //Busca Dados para Sessão de Ano Curricular Disciplina -------------------------------------------
        $dados_base_anos_disciplina_grafico = MethodsProfEscola::estatisticaDisciplinaGrafico($escola,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_anos_disciplina = array_chunk($dados_base_anos_disciplina_grafico, 6);
        //Busca Dados para Sessão de Ano Curricular Disciplina -------------------------------------------

        //Busca os dados para Sessão de Turmas da Escola na Disciplina -----------------------------------
        $dados_base_turmas_disciplina_grafico = MethodsProfEscola::estatisticaTurmaDisciplinaGrafico($escola,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_turmas_disciplina = array_chunk($dados_base_turmas_disciplina_grafico, 6);
        //Busca os dados para Sessão de Turmas da Escola na Disciplina -----------------------------------

        //Busca Dados da Sessão de Habilidades por Ano da Escola na Disciplina ---------------------------
        $dados_base_habilidade_ano_disciplina_grafico = MethodsProfEscola::estatisticaHabilidadeDisciplinaGrafico($escola, $disciplina_selecionada[0]->id, $anos[0], $ano_same_selecionado);
        $dados_ajuste_percentual = MethodsProfEscola::estatisticaAjustePercentual($escola, $disciplina_selecionada[0]->id, $anos[0], $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_ano_disciplina_grafico, 6);
        //Busca dados das Questões para gerar os Modais de Habilidade por Ano
        $dados_base_habilidade_ano_questao = MethodsProfEscola::estatisticaHabilidadeAnoQuestao($escola, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //Busca Dados da Sessão de Habilidades por Ano da Escola na Disciplina ---------------------------

        //Buscas as Habilidades
        $habilidades = MethodsProfEscola::getHabilidadesEscola($disciplina_selecionada[0]->id, $escola_selecionada[0]->id);   

        //Busca os dados da Habilidade Selecionda    
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($habilidades[0]->id_habilidade);

        //Busca Dados da Sessão de Habilidade Individual pelos Anos --------------------------------------   
        $dados_base_habilidade_disciplina_grafico = MethodsProfEscola::estatisticaEscolaDisciplinaHabilidade($escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = MethodsProfEscola::estatisticaPercentualAno($escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide em 6 por linha para Gerar os Cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca os dados das Questões para Gerar os Modais de Habilidade Individual
        $dados_base_habilidade_questao = MethodsProfEscola::estatisticaHabilidadeQuestao($escola, $disciplina_selecionada[0]->id,$habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);     
        //Busca Dados da Sessão de Habilidade Individual pelos Anos --------------------------------------   

        //Busca os Critérios de Acordo com Ano e Disciplina
        $criterios_questao = MethodsGerais::getCriteriosQuestao($ano, $disciplina_selecionada[0]->id);

       //Busca todos os Critérios
       $criterios_questaoAll = MethodsGerais::getCriterios();

       $sessao_inicio = "escola";
  
        return view('proficiencia/diretor/diretor', compact(
            'criterios_questaoAll','solRegistro','solAltCadastral','solAddTurma','turmas','escolas','municipios','destaques','dados_base_escola','dados_comparacao_escola',
            'escola_selecionada','dados_base_grafico_disciplina','dados_base_disciplina','dados_base_anos_disciplina_grafico','dados_base_anos_disciplina',
            'dados_base_turmas_disciplina_grafico','dados_base_turmas_disciplina','disciplinas','disciplina_selecionada','municipio_selecionado','legendas',
            'dados_base_habilidade_ano_disciplina_grafico','dados_base_habilidades_ano_disciplina','anos','ano','dados_base_habilidade_ano_questao','habilidades',
            'habilidade_selecionada','dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_habilidade_questao','dados_ajuste_percentual',
            'dados_ajuste_percentual_ano','criterios_questao','anos_same','ano_same_selecionado','sessao_inicio'));
    }
    //

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirEscola($id, $id_municipio, $id_disciplina, $ano_same)
    {
        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();

        $ano_same_selecionado = $ano_same;

        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();

        //Listage, de Direção Professor utilizando Cache
        $direcaoProfessor = MethodsProfEscola::getDirecaoProfessor($ano_same_selecionado);

        //------------------------------------------- Municípios -----------------------------------------------------------------
        $municipios = MethodsGerais::getMunicipios($ano_same_selecionado);

        //----------------------------------------- Escolas -------------------------------------------------------------------
        $escolas = MethodsProfEscola::getEscolasDiretor($id_municipio, $ano_same_selecionado);
        if(!isset($escolas) || sizeof($escolas) == 0){
            $escolas = MethodsProfEscola::getEscolasDiretor($municipios[0]->id, $ano_same_selecionado);
        }

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        $disciplinas = MethodsGerais::getDisciplinas();

        //------------------------------------------ Solicitações -----------------------------------------------------------------
        //Caso seja Gestor busca todas solicitações em aberto do munícipio
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();
        //----------------------------------------------------------------------------------------------------------------------

        //Busca as Legendas
        $legendas = MethodsGerais::getLegendas();

        //Realiza a busca dos Destaques
        $destaques = MethodsGerais::getDestaques();

        //Identifica a escola selecionada
        $escola = $escolas[0]->id;
        for ($i = 0; $i < sizeof($escolas); $i++) {
            if($escolas[$i]->id == $id){
                $escola = $id;    
            }    
        }

        //Busca as turmas da escola selecionda
        $turmas = MethodsProfEscola::getTurmasEscola($escola, $ano_same_selecionado);

        //Seta os Anos a serem utilizados no Select
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }
        //Define o ano padrão do Select
        $ano = substr(trim($turmas[0]->DESCR_TURMA), 0, 2);

        //Busca os dados da Escola Selecionda
        $escola_selecionada = MethodsGerais::getEscolaSelecionada($escola, $ano_same_selecionado);

        //Busca os dados do Município selecionado partindo da escola
        $municipio_selecionado = MethodsGerais::getMunicipioSelecionado($escola_selecionada[0]->municipios_id, $ano_same_selecionado);

        //Busca os dados da Disciplina Selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($id_disciplina);

        //Busca dados Sessão Base de Escola --------------------------------------------------------------
        $dados_base_escola = MethodsProfEscola::estatisticaEscola($escola, $ano_same_selecionado);
        //Divide em 2 por linha para gerar os Cards
        $dados_base_escola = array_chunk($dados_base_escola, 2);
        //Busca dados Sessão Base de Escola --------------------------------------------------------------

        //Busca dados da Sessão de Comparativo entre a Escola Selecionado e as Demais --------------------
        $dados_comparacao_escola = MethodsProfEscola::estatisticaComparacaoEscola($escola, $ano_same_selecionado);
        //Busca dados da Sessão de Comparativo entre a Escola Selecionado e as Demais --------------------

        //Busca Dados para Sessão de Proficiência da Escola pela Disciplina ------------------------------
        $dados_base_grafico_disciplina = MethodsProfEscola::estatisticaGraficoDisciplina($escola, $ano_same_selecionado);
        //Divide em 4 por linha para Gerar os Cards
        $dados_base_disciplina = array_chunk($dados_base_grafico_disciplina, 4);
        //Busca Dados para Sessão de Proficiência da Escola pela Disciplina ------------------------------

        //Busca Dados para Sessão de Ano Curricular Disciplina -------------------------------------------
        $dados_base_anos_disciplina_grafico = MethodsProfEscola::estatisticaDisciplinaGrafico($escola,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_anos_disciplina = array_chunk($dados_base_anos_disciplina_grafico, 6);
        //Busca Dados para Sessão de Ano Curricular Disciplina -------------------------------------------

        //Busca os dados para Sessão de Turmas da Escola na Disciplina -----------------------------------
        $dados_base_turmas_disciplina_grafico = MethodsProfEscola::estatisticaTurmaDisciplinaGrafico($escola,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_turmas_disciplina = array_chunk($dados_base_turmas_disciplina_grafico, 6);
        //Busca os dados para Sessão de Turmas da Escola na Disciplina -----------------------------------

        //Busca Dados da Sessão de Habilidades por Ano da Escola na Disciplina ---------------------------
        $dados_base_habilidade_ano_disciplina_grafico = MethodsProfEscola::estatisticaHabilidadeDisciplinaGrafico($escola, $disciplina_selecionada[0]->id, $anos[0], $ano_same_selecionado);
        $dados_ajuste_percentual = MethodsProfEscola::estatisticaAjustePercentual($escola, $disciplina_selecionada[0]->id, $anos[0], $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_ano_disciplina_grafico, 6);
        //Busca dados das Questões para gerar os Modais de Habilidade por Ano
        $dados_base_habilidade_ano_questao = MethodsProfEscola::estatisticaHabilidadeAnoQuestao($escola, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //Busca Dados da Sessão de Habilidades por Ano da Escola na Disciplina ---------------------------

        //Buscas as Habilidades
        $habilidades = MethodsProfEscola::getHabilidadesEscola($disciplina_selecionada[0]->id, $escola_selecionada[0]->id);

        //Busca os dados da Habilidade Selecionda    
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($habilidades[0]->id_habilidade);

        //Busca Dados da Sessão de Habilidade Individual pelos Anos --------------------------------------   
        $dados_base_habilidade_disciplina_grafico = MethodsProfEscola::estatisticaEscolaDisciplinaHabilidade($escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = MethodsProfEscola::estatisticaPercentualAno($escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide em 6 por linha para Gerar os Cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca os dados das Questões para Gerar os Modais de Habilidade Individual
        $dados_base_habilidade_questao = MethodsProfEscola::estatisticaHabilidadeQuestao($escola, $disciplina_selecionada[0]->id,$habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);      
        //Busca Dados da Sessão de Habilidade Individual pelos Anos --------------------------------------         

        //Busca os Critérios de Acordo com Ano e Disciplina
        $criterios_questao = MethodsGerais::getCriteriosQuestao($ano, $disciplina_selecionada[0]->id);

        //Busca todos os Critérios
        $criterios_questaoAll = MethodsGerais::getCriterios();

        $sessao_inicio = "escola";

        return view('proficiencia/diretor/diretor', compact(
            'criterios_questaoAll','solRegistro','solAltCadastral','solAddTurma','turmas','escolas','municipios','destaques','dados_base_escola','dados_comparacao_escola',
            'escola_selecionada','dados_base_grafico_disciplina','dados_base_disciplina','dados_base_anos_disciplina_grafico','dados_base_anos_disciplina','dados_base_turmas_disciplina_grafico',
            'dados_base_turmas_disciplina','disciplinas','disciplina_selecionada','municipio_selecionado','legendas','dados_base_habilidade_ano_disciplina_grafico','dados_base_habilidades_ano_disciplina',
            'anos','ano','dados_base_habilidade_ano_questao','habilidades','habilidade_selecionada','dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_habilidade_questao',
            'dados_ajuste_percentual','dados_ajuste_percentual_ano','criterios_questao','anos_same','ano_same_selecionado','sessao_inicio'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirEscolaAno($id, $id_municipio, $id_disciplina, $ano, $ano_same)
    {
        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();

        $ano_same_selecionado = $ano_same;

        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
        
        //Listage, de Direção Professor utilizando Cache
        $direcaoProfessor = MethodsProfEscola::getDirecaoProfessor($ano_same_selecionado);

        //------------------------------------------- Municípios -----------------------------------------------------------------
        $municipios = MethodsGerais::getMunicipios($ano_same_selecionado);
        
        //----------------------------------------- Escolas -------------------------------------------------------------------
        $escolas = MethodsProfEscola::getEscolasDiretor($id_municipio, $ano_same_selecionado);
        if(!isset($escolas) || sizeof($escolas) == 0){
            $escolas = MethodsProfEscola::getEscolasDiretor($municipios[0]->id, $ano_same_selecionado);
        }

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        $disciplinas = MethodsGerais::getDisciplinas();

        //------------------------------------------ Solicitações -----------------------------------------------------------------
        //Caso seja Gestor busca todas solicitações em aberto do munícipio
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();
        //----------------------------------------------------------------------------------------------------------------------

        //Busca as Legendas
        $legendas = MethodsGerais::getLegendas();

        //Realiza a busca dos Destaques
        $destaques = MethodsGerais::getDestaques();

        //Identifica a escola selecionada
        $escola = $escolas[0]->id;
        for ($i = 0; $i < sizeof($escolas); $i++) {
            if($escolas[$i]->id == $id){
                $escola = $id;    
            }    
        }

        //Busca as turmas da escola selecionda
        $turmas = MethodsProfEscola::getTurmasEscola($escola, $ano_same_selecionado);

        //Seta os Anos a serem utilizados no Select
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Busca os dados da Escola Selecionda
        $escola_selecionada = MethodsGerais::getEscolaSelecionada($escola, $ano_same_selecionado);

        //Busca os dados do Município selecionado partindo da escola
        $municipio_selecionado = MethodsGerais::getMunicipioSelecionado($escola_selecionada[0]->municipios_id, $ano_same_selecionado);

        //Busca os dados da Disciplina Selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($id_disciplina);

        //Busca dados Sessão Base de Escola --------------------------------------------------------------
        $dados_base_escola = MethodsProfEscola::estatisticaEscola($escola, $ano_same_selecionado);
        //Divide em 2 por linha para gerar os Cards
        $dados_base_escola = array_chunk($dados_base_escola, 2);
        //Busca dados Sessão Base de Escola --------------------------------------------------------------

        //Busca dados da Sessão de Comparativo entre a Escola Selecionado e as Demais --------------------
        $dados_comparacao_escola = MethodsProfEscola::estatisticaComparacaoEscola($escola, $ano_same_selecionado);
        //Busca dados da Sessão de Comparativo entre a Escola Selecionado e as Demais --------------------

        //Busca Dados para Sessão de Proficiência da Escola pela Disciplina ------------------------------
        $dados_base_grafico_disciplina = MethodsProfEscola::estatisticaGraficoDisciplina($escola, $ano_same_selecionado);
        //Divide em 4 por linha para Gerar os Cards
        $dados_base_disciplina = array_chunk($dados_base_grafico_disciplina, 4);
        //Busca Dados para Sessão de Proficiência da Escola pela Disciplina ------------------------------

        //Busca Dados para Sessão de Ano Curricular Disciplina -------------------------------------------
        $dados_base_anos_disciplina_grafico = MethodsProfEscola::estatisticaDisciplinaGrafico($escola,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_anos_disciplina = array_chunk($dados_base_anos_disciplina_grafico, 6);
        //Busca Dados para Sessão de Ano Curricular Disciplina -------------------------------------------

        //Busca os dados para Sessão de Turmas da Escola na Disciplina -----------------------------------
        $dados_base_turmas_disciplina_grafico = MethodsProfEscola::estatisticaTurmaDisciplinaGrafico($escola,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_turmas_disciplina = array_chunk($dados_base_turmas_disciplina_grafico, 6);
        //Busca os dados para Sessão de Turmas da Escola na Disciplina -----------------------------------
      
        //Busca Dados da Sessão de Habilidades por Ano da Escola na Disciplina ---------------------------
        $dados_base_habilidade_ano_disciplina_grafico = MethodsProfEscola::estatisticaHabilidadeDisciplinaGrafico($escola, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        $dados_ajuste_percentual = MethodsProfEscola::estatisticaAjustePercentual($escola, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_ano_disciplina_grafico, 6);
        //Busca dados das Questões para gerar os Modais de Habilidade por Ano
        $dados_base_habilidade_ano_questao = MethodsProfEscola::estatisticaHabilidadeAnoQuestao($escola, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //Busca Dados da Sessão de Habilidades por Ano da Escola na Disciplina ---------------------------

        //Buscas as Habilidades
        $habilidades = MethodsProfEscola::getHabilidadesEscola($disciplina_selecionada[0]->id, $escola_selecionada[0]->id);

        //Busca os dados da Habilidade Selecionda    
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($habilidades[0]->id_habilidade);
  
        //Busca Dados da Sessão de Habilidade Individual pelos Anos --------------------------------------   
        $dados_base_habilidade_disciplina_grafico = MethodsProfEscola::estatisticaEscolaDisciplinaHabilidade($escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = MethodsProfEscola::estatisticaPercentualAno($escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide em 6 por linha para Gerar os Cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca os dados das Questões para Gerar os Modais de Habilidade Individual
        $dados_base_habilidade_questao = MethodsProfEscola::estatisticaHabilidadeQuestao($escola, $disciplina_selecionada[0]->id,$habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);      
        //Busca Dados da Sessão de Habilidade Individual pelos Anos --------------------------------------         

        //Busca os Critérios de Acordo com Ano e Disciplina
        $criterios_questao = MethodsGerais::getCriteriosQuestao($ano, $disciplina_selecionada[0]->id);

        //Busca todos os Critérios
        $criterios_questaoAll = MethodsGerais::getCriterios();

        $sessao_inicio = "habilidadeanodisciplina";

        return view('proficiencia/diretor/diretor', compact(
            'criterios_questaoAll','solRegistro','solAltCadastral','solAddTurma','turmas','escolas','municipios','destaques','dados_base_escola','dados_comparacao_escola','escola_selecionada',
            'dados_base_grafico_disciplina','dados_base_disciplina','dados_base_anos_disciplina_grafico','dados_base_anos_disciplina','dados_base_turmas_disciplina_grafico','dados_base_turmas_disciplina',
            'disciplinas','disciplina_selecionada','municipio_selecionado','legendas','dados_base_habilidade_ano_disciplina_grafico','dados_base_habilidades_ano_disciplina','anos','ano',
            'dados_base_habilidade_ano_questao','habilidades','habilidade_selecionada','dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_habilidade_questao',
            'dados_ajuste_percentual','dados_ajuste_percentual_ano','criterios_questao','anos_same','ano_same_selecionado','sessao_inicio'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirEscolaHabilidade($id, $id_municipio, $id_disciplina, $ano, $id_habilidade, $ano_same)
    {
        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();

        $ano_same_selecionado = $ano_same;

        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
        
        //Listage, de Direção Professor utilizando Cache
        $direcaoProfessor = MethodsProfEscola::getDirecaoProfessor($ano_same_selecionado);

        //------------------------------------------- Municípios -----------------------------------------------------------------
        $municipios = MethodsGerais::getMunicipios($ano_same_selecionado);

        //----------------------------------------- Escolas -------------------------------------------------------------------
        $escolas = MethodsProfEscola::getEscolasDiretor($id_municipio, $ano_same_selecionado);
        if(!isset($escolas) || sizeof($escolas) == 0){
            $escolas = MethodsProfEscola::getEscolasDiretor($municipios[0]->id, $ano_same_selecionado);
        }

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        $disciplinas = MethodsGerais::getDisciplinas();

        //------------------------------------------ Solicitações -----------------------------------------------------------------
        //Caso seja Gestor busca todas solicitações em aberto do munícipio
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();
        //----------------------------------------------------------------------------------------------------------------------

        //Busca as Legendas
        $legendas = MethodsGerais::getLegendas();

        //Realiza a busca dos Destaques
        $destaques = MethodsGerais::getDestaques();

        //Identifica a escola selecionada
        $escola = $escolas[0]->id;
        for ($i = 0; $i < sizeof($escolas); $i++) {
            if($escolas[$i]->id == $id){
                $escola = $id;    
            }    
        }

        //Busca as turmas da escola selecionda
        $turmas = MethodsProfEscola::getTurmasEscola($escola, $ano_same_selecionado);

        //Seta os Anos a serem utilizados no Select
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Busca os dados da Escola Selecionda
        $escola_selecionada = MethodsGerais::getEscolaSelecionada($escola, $ano_same_selecionado);

        //Busca os dados do Município selecionado partindo da escola
        $municipio_selecionado = MethodsGerais::getMunicipioSelecionado($escola_selecionada[0]->municipios_id, $ano_same_selecionado);

        //Busca os dados da Disciplina Selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($id_disciplina);

        //Busca dados Sessão Base de Escola --------------------------------------------------------------
        $dados_base_escola = MethodsProfEscola::estatisticaEscola($escola, $ano_same_selecionado);
        //Divide em 2 por linha para gerar os Cards
        $dados_base_escola = array_chunk($dados_base_escola, 2);
        //Busca dados Sessão Base de Escola --------------------------------------------------------------

        //Busca dados da Sessão de Comparativo entre a Escola Selecionado e as Demais --------------------
        $dados_comparacao_escola = MethodsProfEscola::estatisticaComparacaoEscola($escola, $ano_same_selecionado);
        //Busca dados da Sessão de Comparativo entre a Escola Selecionado e as Demais --------------------

        //Busca Dados para Sessão de Proficiência da Escola pela Disciplina ------------------------------
        $dados_base_grafico_disciplina = MethodsProfEscola::estatisticaGraficoDisciplina($escola, $ano_same_selecionado);
        //Divide em 4 por linha para Gerar os Cards
        $dados_base_disciplina = array_chunk($dados_base_grafico_disciplina, 4);
        //Busca Dados para Sessão de Proficiência da Escola pela Disciplina ------------------------------

        //Busca Dados para Sessão de Ano Curricular Disciplina -------------------------------------------
        $dados_base_anos_disciplina_grafico = MethodsProfEscola::estatisticaDisciplinaGrafico($escola,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_anos_disciplina = array_chunk($dados_base_anos_disciplina_grafico, 6);
        //Busca Dados para Sessão de Ano Curricular Disciplina -------------------------------------------

        //Busca os dados para Sessão de Turmas da Escola na Disciplina -----------------------------------
        $dados_base_turmas_disciplina_grafico = MethodsProfEscola::estatisticaTurmaDisciplinaGrafico($escola,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_turmas_disciplina = array_chunk($dados_base_turmas_disciplina_grafico, 6);
        //Busca os dados para Sessão de Turmas da Escola na Disciplina -----------------------------------
      
        //Busca Dados da Sessão de Habilidades por Ano da Escola na Disciplina ---------------------------
        $dados_base_habilidade_ano_disciplina_grafico = MethodsProfEscola::estatisticaHabilidadeDisciplinaGrafico($escola, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        $dados_ajuste_percentual = MethodsProfEscola::estatisticaAjustePercentual($escola, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_ano_disciplina_grafico, 6);
        //Busca dados das Questões para gerar os Modais de Habilidade por Ano
        $dados_base_habilidade_ano_questao = MethodsProfEscola::estatisticaHabilidadeAnoQuestao($escola, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //Busca Dados da Sessão de Habilidades por Ano da Escola na Disciplina ---------------------------

        //Buscas as Habilidades
        $habilidades = MethodsProfEscola::getHabilidadesEscola($disciplina_selecionada[0]->id, $escola_selecionada[0]->id);

        //Busca os dados da Habilidade Selecionda    
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($id_habilidade);

        //Busca Dados da Sessão de Habilidade Individual pelos Anos --------------------------------------   
        $dados_base_habilidade_disciplina_grafico = MethodsProfEscola::estatisticaEscolaDisciplinaHabilidade($escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = MethodsProfEscola::estatisticaPercentualAno($escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide em 6 por linha para Gerar os Cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca os dados das Questões para Gerar os Modais de Habilidade Individual
        $dados_base_habilidade_questao = MethodsProfEscola::estatisticaHabilidadeQuestao($escola, $disciplina_selecionada[0]->id,$habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);       
        //Busca Dados da Sessão de Habilidade Individual pelos Anos --------------------------------------         
        
        //Busca os Critérios de Acordo com Ano e Disciplina
        $criterios_questao = MethodsGerais::getCriteriosQuestao($ano, $disciplina_selecionada[0]->id);

        $dados_base_habilidade_questao = MethodsProfEscola::getHabilidadeQuestaoCriterio($dados_base_habilidade_questao);

        $sessao_inicio = "habilidadeselecionadadisciplina";

        return view('proficiencia/diretor/diretor', compact(
            'solRegistro','solAltCadastral','solAddTurma','turmas','escolas','municipios','destaques','dados_base_escola','dados_comparacao_escola','escola_selecionada',
            'dados_base_grafico_disciplina','dados_base_disciplina','dados_base_anos_disciplina_grafico','dados_base_anos_disciplina','dados_base_turmas_disciplina_grafico',
            'dados_base_turmas_disciplina','disciplinas','disciplina_selecionada','municipio_selecionado','legendas','dados_base_habilidade_ano_disciplina_grafico',
            'dados_base_habilidades_ano_disciplina','anos','ano','dados_base_habilidade_ano_questao','habilidades','habilidade_selecionada','dados_base_habilidade_disciplina_grafico',
            'dados_base_habilidades_disciplina','dados_base_habilidade_questao','dados_ajuste_percentual','dados_ajuste_percentual_ano','criterios_questao','anos_same','ano_same_selecionado','sessao_inicio'));
    }
}
