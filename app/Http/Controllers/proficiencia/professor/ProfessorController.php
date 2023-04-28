<?php

namespace App\Http\Controllers\proficiencia\professor;

use App\Http\Controllers\Controller;
use App\Http\Controllers\staticmethods\gerais\MethodsGerais;
use App\Http\Controllers\staticmethods\proficiencia\MethodsProfTurma;

class ProfessorController extends Controller
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
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
        $ano_same_selecionado = $anos_same[0]->SAME;

        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        $disciplinas = MethodsGerais::getDisciplinas();

        //------------------------------------------ Solicitações -----------------------------------------------------------------
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------- Municípios -----------------------------------------------------------------
        $municipios = MethodsGerais::getMunicipios($ano_same_selecionado);

        //------------------------------------------- Escolas -----------------------------------------------------------------
        $escolas = MethodsProfTurma::getEscolasProfessor($municipios[0]->id, null, $ano_same_selecionado);

        //------------------------------------------- Turmas -------------------------------------------------------------------
        $turmas = MethodsProfTurma::getTurmasProfessor($escolas[0]->id, $ano_same_selecionado);

        //Realiza a busca dos Destaques
        $destaques = MethodsGerais::getDestaques();

        //Busca as Questões
        $questoes = MethodsGerais::getQuestoes();

        //Busca as Legendas
        $legendas = MethodsGerais::getLegendas();

        //Define a turma selecionda
        $turma = $turmas[0]->id;

        //Seta os Anos a serem utilizados no seletor
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }
        //Define o Ano seleciondao
        $ano = substr(trim($turmas[0]->DESCR_TURMA), 0, 2);

        //Carrega Dados da Turma Selecionada
        $turma_selecionada = MethodsGerais::getTurmaSelecionada($turma, $ano_same_selecionado);

        //Carrega dados da Escola Selecionda
        $escola_selecionada = MethodsGerais::getEscolaSelecionada($turma_selecionada[0]->escolas_id, $ano_same_selecionado);

        //Carrega os dados do Município Selecionda
        $municipio_selecionado = MethodsGerais::getMunicipioSelecionado($turma_selecionada[0]->escolas_municipios_id, $ano_same_selecionado);

        //Carrega os dados da Disciplina Selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($disciplinas[0]->id);

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = MethodsGerais::getCriteriosQuestao($ano,$disciplina_selecionada[0]->id);

        //Busca dados Média Turma ---------------------------------------------------------------------------------------------------------------
        $dados_base_turma_grafico = MethodsProfTurma::estatisticaBaseTurma($turma,$ano,$ano_same_selecionado);
        $dados_comparacao_turma = MethodsProfTurma::estatisticaComparacaoTurma($turma,$ano,$ano_same_selecionado);
        //Divide em 2 por linha para gerar os cards
        $dados_base_turma = array_chunk($dados_base_turma_grafico, 2);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Tema na Disciplina -------------------------------------------------------------------------------------------------
        $dados_base_grafico = MethodsProfTurma::estatisticaBaseGrafico($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 4 por Linha para gerar os Cards
        $dados_base_tema = array_chunk($dados_base_grafico, 4);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Habilidade Disciplina ----------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_grafico = MethodsProfTurma::estatisticaHabilidadeDisciplinaGrafico($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_base = MethodsProfTurma::estatisticaAjustePercentualBase($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca as habilidades
        $habilidades = MethodsProfTurma::getHabilidadesProfessor($disciplina_selecionada[0]->id, $turma, $ano_same_selecionado);
        //Busca dados das Questões da Sessão Habilidade Disciplina
        $dados_base_habilidade_questao = MethodsProfTurma::estatisticaDisciplinaQuestao($turma, $disciplina_selecionada[0]->id, $habilidades[0]->id_habilidade);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Ano Habilidades ----------------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico = MethodsProfTurma::estatisticaHabilidadeDisciplinaAnoGrafico($escola_selecionada[0]->id, $disciplina_selecionada[0]->id,$ano[0],$ano_same_selecionado);
        $dados_ajuste_percentual = MethodsProfTurma::estatisticaAjustePercentual($escola_selecionada[0]->id, $disciplina_selecionada[0]->id,$anos[0],$ano_same_selecionado);      
        //Divide em 6 por linha para gerar os cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        //Busca dados Questões Habilidade
        $dados_base_habilidade_ano_questao = MethodsProfTurma::estatisticaHabilidadeAnoQuestao($escola_selecionada[0]->id, $disciplina_selecionada[0]->id,$anos[0],$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados da Habilidade Selecionada  
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($habilidades[0]->id_habilidade);

        //Busca dados Sessão Habilidade Selecionda ----------------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico_habilidade = MethodsProfTurma::estatisticaHabilidadeDisciplinaHabilidade($escola_selecionada[0]->id, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = MethodsProfTurma::estatisticaAjustePercentualAno($escola_selecionada[0]->id, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide em 6 por linha para gerar Cards
        $dados_base_habilidades_disciplina_habilidade = array_chunk($dados_base_habilidade_disciplina_grafico_habilidade, 6);
        //Busca dados questão habilidade individual
        $dados_base_habilidade_questao_habilidade = MethodsProfTurma::estatisticaBaseHabilidadeQuestaoHabilidade($escola_selecionada[0]->id, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade,$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------
        
        //Busca dados Sessão Questões Disciplinas -----------------------------------------------------------------------------------------------
        $dados_base_questao_grafico_disciplina = MethodsProfTurma::estatisticaQuestaoGraficoDisciplina($turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_questao = MethodsProfTurma::estatisticaAjustePercentualQuestao($turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_questao_disciplina = array_chunk($dados_base_questao_grafico_disciplina, 6);
        //Seta os Tipos de Questões
        $tipos_questoes = [];
        $contador = 0;
        for ($j = 0; $j < sizeof($dados_base_questao_grafico_disciplina); $j++) {
            if (!in_array($dados_base_questao_grafico_disciplina[$j]->tipo_questao, $tipos_questoes)) {
                $tipos_questoes[$contador] = $dados_base_questao_grafico_disciplina[$j]->tipo_questao;
                $contador++;
            }
        }
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Aluno --------------------------------------------------------------------------------------------------------------------------------------
        $dados_base_aluno_grafico_disciplina = MethodsProfTurma::estatisticaBaseAlunoGraficoDisciplina($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_aluno_disciplina = array_chunk($dados_base_aluno_grafico_disciplina, 6);
        //---------------------------------------------------------------------------------------------------------------------------------------------------------------

        $dados_base_habilidade_questao = MethodsProfTurma::getHabilidadesCriterios($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);

        $sessao_inicio = "turma";

        return view('proficiencia/professor/professor', compact(

            'solRegistro','solAltCadastral','solAddTurma','turmas','municipios','questoes','dados_base_turma_grafico','dados_base_turma','turma_selecionada','destaques',
            'dados_base_tema','dados_base_grafico','dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_questao_grafico_disciplina',
            'dados_base_questao_disciplina','escolas','disciplinas','disciplina_selecionada','escola_selecionada','municipio_selecionado','legendas',
            'dados_base_aluno_grafico_disciplina','dados_base_aluno_disciplina','anos','dados_base_habilidade_questao','ano','dados_base_habilidade_disciplina_ano_grafico',
            'dados_base_habilidades_ano_disciplina','dados_base_habilidade_ano_questao','criterios_questao','habilidades','habilidade_selecionada',
            'dados_base_habilidade_disciplina_grafico_habilidade','dados_base_habilidades_disciplina_habilidade','dados_base_habilidade_questao_habilidade','tipos_questoes',
            'dados_ajuste_percentual','dados_ajuste_percentual_ano','dados_ajuste_percentual_base','dados_ajuste_percentual_questao','anos_same','ano_same_selecionado','dados_comparacao_turma','sessao_inicio'));
    }
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
    public function exibirTurma($id, $id_disciplina, $id_escola, $ano_same)
    {
        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();

        $ano_same_selecionado = $ano_same;

        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        $disciplinas = MethodsGerais::getDisciplinas();
        //----------------------------------------------------------------------------------------------------------------------


        //------------------------------------------ Solicitações -----------------------------------------------------------------
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------- Municípios -----------------------------------------------------------------
        $municipios = MethodsGerais::getMunicipios($ano_same_selecionado);
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------- Turmas -------------------------------------------------------------------
        $turmas = MethodsProfTurma::getTurmasProfessor($id_escola, $ano_same_selecionado);
        if(!isset($turmas) || sizeof($turmas) == 0){
            $escolas = MethodsProfTurma::getEscolasProfessor($municipios[0]->id, null, $ano_same_selecionado);
            $turmas = MethodsProfTurma::getTurmasProfessor($escolas[0]->id, $ano_same_selecionado);
        }
        //----------------------------------------------------------------------------------------------------------------------

        $turma = $turmas[0]->id;
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if($turmas[$i]->id == $id){
                $turma = $id;   
            }    
        }
        $turma_selecionada = MethodsGerais::getTurmaSelecionada($turma, $ano_same_selecionado);

        //------------------------------------------- Escolas -----------------------------------------------------------------
        $escolas = MethodsProfTurma::getEscolasProfessor($turma_selecionada[0]->escolas_municipios_id, $id_escola, $ano_same_selecionado);
        //----------------------------------------------------------------------------------------------------------------------

        //Busca as Questões
        $questoes = MethodsGerais::getQuestoes();

        //Busca os Destaques
        $destaques = MethodsGerais::getDestaques();

        //Busca as Legendas
        $legendas = MethodsGerais::getLegendas();

        //Seta os Anos a serem utilizados no seletor
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o Ano seleciondao
        $ano = substr(trim($turma_selecionada[0]->DESCR_TURMA), 0, 2);

        //Carrega os dados da Disciplina Selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($id_disciplina);

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = MethodsGerais::getCriteriosQuestao($ano,$disciplina_selecionada[0]->id);

        //Carrega dados da Escola Selecionda
        $escola_selecionada = MethodsGerais::getEscolaSelecionada($turma_selecionada[0]->escolas_id, $ano_same_selecionado);

        //Carrega os dados do Município Selecionda
        $municipio_selecionado = MethodsGerais::getMunicipioSelecionado($turma_selecionada[0]->escolas_municipios_id, $ano_same_selecionado);

        //Busca dados Média Turma ---------------------------------------------------------------------------------------------------------------
        $dados_base_turma_grafico = MethodsProfTurma::estatisticaBaseTurma($turma,$ano,$ano_same_selecionado);
        $dados_comparacao_turma = MethodsProfTurma::estatisticaComparacaoTurma($turma,$ano,$ano_same_selecionado);
        //Divide em 2 por linha para gerar os cards
        $dados_base_turma = array_chunk($dados_base_turma_grafico, 2);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Tema na Disciplina -------------------------------------------------------------------------------------------------
        $dados_base_grafico = MethodsProfTurma::estatisticaBaseGrafico($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 4 por Linha para gerar os Cards
        $dados_base_tema = array_chunk($dados_base_grafico, 4);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca as habilidades
        $habilidades = MethodsProfTurma::getHabilidadesProfessor($disciplina_selecionada[0]->id, $turma, $ano_same_selecionado);

        //Busca dados Sessão Habilidade Disciplina ----------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_grafico = MethodsProfTurma::estatisticaHabilidadeDisciplinaGrafico($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_base = MethodsProfTurma::estatisticaAjustePercentualBase($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca dados das Questões da Sessão Habilidade Disciplina
        $dados_base_habilidade_questao = MethodsProfTurma::estatisticaDisciplinaQuestao($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Ano Habilidades ----------------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico = MethodsProfTurma::estatisticaHabilidadeDisciplinaAnoGrafico($id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
        $dados_ajuste_percentual = MethodsProfTurma::estatisticaAjustePercentual($id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);     
        //Divide em 6 por linha para gerar os cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        //Busca dados Questões Habilidade
        $dados_base_habilidade_ano_questao = MethodsProfTurma::estatisticaHabilidadeAnoQuestao($id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados da Habilidade Selecionada  
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($habilidades[0]->id_habilidade);

        //Busca dados Sessão Habilidade Selecionda ----------------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico_habilidade = MethodsProfTurma::estatisticaHabilidadeDisciplinaHabilidade($id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = MethodsProfTurma::estatisticaAjustePercentualAno($id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide em 6 por linha para gerar Cards
        $dados_base_habilidades_disciplina_habilidade = array_chunk($dados_base_habilidade_disciplina_grafico_habilidade, 6);
        //Busca dados questão habilidade individual
        $dados_base_habilidade_questao_habilidade = MethodsProfTurma::estatisticaBaseHabilidadeQuestaoHabilidade($id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade,$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------       

        //Busca dados Sessão Questões Disciplinas -----------------------------------------------------------------------------------------------
        $dados_base_questao_grafico_disciplina = MethodsProfTurma::estatisticaQuestaoGraficoDisciplina($turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_questao = MethodsProfTurma::estatisticaAjustePercentualQuestao($turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_questao_disciplina = array_chunk($dados_base_questao_grafico_disciplina, 6);
        //Seta os Tipos de Questões
        $tipos_questoes = [];
        $contador = 0;
        for ($j = 0; $j < sizeof($dados_base_questao_grafico_disciplina); $j++) {
            if (!in_array($dados_base_questao_grafico_disciplina[$j]->tipo_questao, $tipos_questoes)) {
                $tipos_questoes[$contador] = $dados_base_questao_grafico_disciplina[$j]->tipo_questao;
                $contador++;
            }
        }
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Aluno --------------------------------------------------------------------------------------------------------------------------------------
        $dados_base_aluno_grafico_disciplina = MethodsProfTurma::estatisticaBaseAlunoGraficoDisciplina($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_aluno_disciplina = array_chunk($dados_base_aluno_grafico_disciplina, 6);
        //---------------------------------------------------------------------------------------------------------------------------------------------------------------
        $dados_base_habilidade_questao = MethodsProfTurma::getHabilidadesCriterios($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);

        $sessao_inicio = "turma";

        return view('proficiencia/professor/professor', compact(
            'solRegistro','solAltCadastral','solAddTurma','turmas','municipios','questoes','destaques','dados_base_turma_grafico','dados_base_turma','turma_selecionada','dados_base_tema','dados_base_grafico',
            'dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_questao_grafico_disciplina','dados_base_questao_disciplina','escolas','disciplinas',
            'disciplina_selecionada','escola_selecionada','municipio_selecionado','legendas','dados_base_aluno_grafico_disciplina','dados_base_aluno_disciplina','anos',
            'dados_base_habilidade_questao','ano','dados_base_habilidade_disciplina_ano_grafico','dados_base_habilidades_ano_disciplina','dados_base_habilidade_ano_questao',
            'tipos_questoes','criterios_questao','habilidades','habilidade_selecionada','dados_base_habilidade_disciplina_grafico_habilidade','dados_base_habilidades_disciplina_habilidade',
            'dados_base_habilidade_questao_habilidade','dados_ajuste_percentual','dados_ajuste_percentual_ano','dados_ajuste_percentual_base','dados_ajuste_percentual_questao','anos_same',
            'ano_same_selecionado','dados_comparacao_turma','sessao_inicio'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirTurmaAno($id, $id_disciplina, $id_escola, $ano, $ano_same)
    { 

        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();

        $ano_same_selecionado = $ano_same;

        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        $disciplinas = MethodsGerais::getDisciplinas();
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------ Solicitações -----------------------------------------------------------------
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------- Municípios -----------------------------------------------------------------
        $municipios = MethodsGerais::getMunicipios($ano_same_selecionado);
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------- Turmas -------------------------------------------------------------------
        $turmas = MethodsProfTurma::getTurmasProfessor($id_escola, $ano_same_selecionado);
        if(!isset($turmas) || sizeof($turmas) == 0){
            $escolas = MethodsProfTurma::getEscolasProfessor($municipios[0]->id, null, $ano_same_selecionado);
            $turmas = MethodsProfTurma::getTurmasProfessor($escolas[0]->id, $ano_same_selecionado);
        }
        //----------------------------------------------------------------------------------------------------------------------

        $turma = $turmas[0]->id;
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if($turmas[$i]->id == $id){
                $turma = $id;   
            }    
        }

        $turma_selecionada = MethodsGerais::getTurmaSelecionada($turma, $ano_same_selecionado);

        //------------------------------------------- Escolas -----------------------------------------------------------------
        $escolas = MethodsProfTurma::getEscolasProfessor($turma_selecionada[0]->escolas_municipios_id, $id_escola, $ano_same_selecionado);
        //----------------------------------------------------------------------------------------------------------------------

        //Busca as Questões
        $questoes = MethodsGerais::getQuestoes();

        //Busca os Destaques
        $destaques = MethodsGerais::getDestaques();

        //Busca as Legendas
        $legendas = MethodsGerais::getLegendas();

        //Seta os Anos a serem utilizados no seletor
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o Ano seleciondao
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($id_disciplina);

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = MethodsGerais::getCriteriosQuestao($ano,$disciplina_selecionada[0]->id);

        //Carrega dados da Escola Selecionda
        $escola_selecionada = MethodsGerais::getEscolaSelecionada($turma_selecionada[0]->escolas_id, $ano_same_selecionado);

        //Carrega os dados do Município Selecionda
        $municipio_selecionado = MethodsGerais::getMunicipioSelecionado($turma_selecionada[0]->escolas_municipios_id, $ano_same_selecionado);

        //Busca dados Média Turma ---------------------------------------------------------------------------------------------------------------
        $dados_base_turma_grafico = MethodsProfTurma::estatisticaBaseTurma($turma,substr(trim($turma_selecionada[0]->DESCR_TURMA), 0, 2),$ano_same_selecionado);
        $dados_comparacao_turma = MethodsProfTurma::estatisticaComparacaoTurma($turma,substr(trim($turma_selecionada[0]->DESCR_TURMA), 0, 2),$ano_same_selecionado);
        //Divide em 2 por linha para gerar os cards
        $dados_base_turma = array_chunk($dados_base_turma_grafico, 2);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Tema na Disciplina -------------------------------------------------------------------------------------------------
        $dados_base_grafico = MethodsProfTurma::estatisticaBaseGrafico($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 4 por Linha para gerar os Cards
        $dados_base_tema = array_chunk($dados_base_grafico, 4);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca as habilidades
        $habilidades = MethodsProfTurma::getHabilidadesProfessor($disciplina_selecionada[0]->id, $turma, $ano_same_selecionado);
            
        //Busca dados Sessão Habilidade Disciplina ----------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_grafico = MethodsProfTurma::estatisticaHabilidadeDisciplinaGrafico($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_base = MethodsProfTurma::estatisticaAjustePercentualBase($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca dados das Questões da Sessão Habilidade Disciplina
        $dados_base_habilidade_questao = MethodsProfTurma::estatisticaDisciplinaQuestao($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Ano Habilidades ----------------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico = MethodsProfTurma::estatisticaHabilidadeDisciplinaAnoGrafico($id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
        $dados_ajuste_percentual = MethodsProfTurma::estatisticaAjustePercentual($id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);  
        //Divide em 6 por linha para gerar os cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        //Busca dados Questões Habilidade
        $dados_base_habilidade_ano_questao = MethodsProfTurma::estatisticaHabilidadeAnoQuestao($id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------
        
        $ano_selecionado = $ano;

        //Busca dados da Habilidade Selecionada   
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($habilidades[0]->id_habilidade);  

        //Busca dados Sessão Habilidade Selecionda ----------------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico_habilidade = MethodsProfTurma::estatisticaHabilidadeDisciplinaHabilidade($id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = MethodsProfTurma::estatisticaAjustePercentualAno($id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide em 6 por linha para gerar Cards
        $dados_base_habilidades_disciplina_habilidade = array_chunk($dados_base_habilidade_disciplina_grafico_habilidade, 6);
        //Busca dados questão habilidade individual
        $dados_base_habilidade_questao_habilidade = MethodsProfTurma::estatisticaBaseHabilidadeQuestaoHabilidade($id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade,$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Questões Disciplinas -----------------------------------------------------------------------------------------------
        $dados_base_questao_grafico_disciplina = MethodsProfTurma::estatisticaQuestaoGraficoDisciplina($turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_questao = MethodsProfTurma::estatisticaAjustePercentualQuestao($turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_questao_disciplina = array_chunk($dados_base_questao_grafico_disciplina, 6);
        //Seta os Tipos de Questões
        $tipos_questoes = [];
        $contador = 0;
        for ($j = 0; $j < sizeof($dados_base_questao_grafico_disciplina); $j++) {
            if (!in_array($dados_base_questao_grafico_disciplina[$j]->tipo_questao, $tipos_questoes)) {
                $tipos_questoes[$contador] = $dados_base_questao_grafico_disciplina[$j]->tipo_questao;
                $contador++;
            }
        }
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Aluno --------------------------------------------------------------------------------------------------------------------------------------
        $dados_base_aluno_grafico_disciplina = MethodsProfTurma::estatisticaBaseAlunoGraficoDisciplina($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_aluno_disciplina = array_chunk($dados_base_aluno_grafico_disciplina, 6);
        //---------------------------------------------------------------------------------------------------------------------------------------------------------------

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        if ($disciplina_selecionada[0]->id == 2) {
            $testeProfessor=31;
            $criterios_questaoAno = MethodsGerais::getCriteriosQuestaoAno($ano_selecionado[0]);
        } else {
            //Nos demais não existe esse filtro adicional
            $testeProfessor=32;
            $criterios_questaoAno = MethodsGerais::getCriterios();
        }

        $dados_base_habilidade_questao = MethodsProfTurma::getHabilidadesCriterios($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);

        $sessao_inicio = "habilidadeanodisciplina";

        return view('proficiencia/professor/professor', compact(
            'criterios_questaoAno','solRegistro','solAltCadastral','solAddTurma','turmas','municipios','questoes','destaques','dados_base_turma_grafico','dados_base_turma','turma_selecionada',
            'dados_base_tema','dados_base_grafico','dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_questao_grafico_disciplina',
            'dados_base_questao_disciplina','escolas','disciplinas','disciplina_selecionada','escola_selecionada','municipio_selecionado','legendas','dados_base_aluno_grafico_disciplina',
            'dados_base_aluno_disciplina','anos','dados_base_habilidade_questao','ano_selecionado','dados_base_habilidade_disciplina_ano_grafico','ano_selecionado','ano',
            'dados_base_habilidade_ano_questao','criterios_questao','tipos_questoes','habilidades','habilidade_selecionada','dados_base_habilidade_disciplina_grafico_habilidade',
            'dados_base_habilidades_disciplina_habilidade','dados_base_habilidade_questao_habilidade','dados_ajuste_percentual','dados_ajuste_percentual_ano','dados_ajuste_percentual_base',
            'dados_ajuste_percentual_questao','anos_same','ano_same_selecionado','dados_comparacao_turma','dados_base_habilidades_ano_disciplina','sessao_inicio'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
 

    public function exibirTurmaHabilidade($id, $id_disciplina, $id_escola, $ano, $id_habilidade, $ano_same)
    {
         //Busca os previlégios do Usuário Logado
         $previlegio = MethodsGerais::getPrevilegio();

         $ano_same_selecionado = $ano_same;
 
         //Listagem de Anos do SAME
         $anos_same = MethodsGerais::getAnosSAME();
 
         //----------------------------------------- Disciplinas ----------------------------------------------------------------
         $disciplinas = MethodsGerais::getDisciplinas();
         //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------ Solicitações -----------------------------------------------------------------
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------- Municípios -----------------------------------------------------------------
        $municipios = MethodsGerais::getMunicipios($ano_same_selecionado);
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------- Turmas -------------------------------------------------------------------
        $turmas = MethodsProfTurma::getTurmasProfessor($id_escola, $ano_same_selecionado);
        if(!isset($turmas) || sizeof($turmas) == 0){
            $escolas = MethodsProfTurma::getEscolasProfessor($municipios[0]->id, null, $ano_same_selecionado);
            $turmas = MethodsProfTurma::getTurmasProfessor($escolas[0]->id, $ano_same_selecionado);
         }
        //----------------------------------------------------------------------------------------------------------------------

        $turma = $turmas[0]->id;
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if($turmas[$i]->id == $id){
                $turma = $id;   
            }    
        }
        $turma_selecionada = MethodsGerais::getTurmaSelecionada($turma, $ano_same_selecionado);

        //------------------------------------------- Escolas -----------------------------------------------------------------
        $escolas = MethodsProfTurma::getEscolasProfessor($turma_selecionada[0]->escolas_municipios_id, $id_escola, $ano_same_selecionado);
        //----------------------------------------------------------------------------------------------------------------------

        //Busca as Questões
        $questoes = MethodsGerais::getQuestoes();

        //Busca os Destaques
        $destaques = MethodsGerais::getDestaques();

        //Busca as Legendas
        $legendas = MethodsGerais::getLegendas();

        //Seta os Anos a serem utilizados no seletor
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o Ano seleciondao
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($id_disciplina);

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = MethodsGerais::getCriteriosQuestao($ano,$disciplina_selecionada[0]->id);

        //Carrega dados da Escola Selecionda
        $escola_selecionada = MethodsGerais::getEscolaSelecionada($turma_selecionada[0]->escolas_id, $ano_same_selecionado);

        //Carrega os dados do Município Selecionada
        $municipio_selecionado = MethodsGerais::getMunicipioSelecionado($turma_selecionada[0]->escolas_municipios_id, $ano_same_selecionado);

        //Busca dados Média Turma ---------------------------------------------------------------------------------------------------------------
        $dados_base_turma_grafico = MethodsProfTurma::estatisticaBaseTurma($turma,substr(trim($turma_selecionada[0]->DESCR_TURMA), 0, 2),$ano_same_selecionado);
        $dados_comparacao_turma = MethodsProfTurma::estatisticaComparacaoTurma($turma,substr(trim($turma_selecionada[0]->DESCR_TURMA), 0, 2),$ano_same_selecionado);
        //Divide em 2 por linha para gerar os cards
        $dados_base_turma = array_chunk($dados_base_turma_grafico, 2);
        //---------------------------------------------------------------------------------------------------------------------------------------
   
        //Busca dados Sessão Tema na Disciplina -------------------------------------------------------------------------------------------------
        $dados_base_grafico = MethodsProfTurma::estatisticaBaseGrafico($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 4 por Linha para gerar os Cards
        $dados_base_tema = array_chunk($dados_base_grafico, 4);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca as habilidades
        $habilidades = MethodsProfTurma::getHabilidadesProfessor($disciplina_selecionada[0]->id, $turma, $ano_same_selecionado);

        //Busca dados da Habilidade Selecionada     
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($id_habilidade);

        //Busca dados Sessão Habilidade Disciplina ----------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_grafico = MethodsProfTurma::estatisticaHabilidadeDisciplinaGrafico($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_base = MethodsProfTurma::estatisticaAjustePercentualBase($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca dados das Questões da Sessão Habilidade Disciplina
        $dados_base_habilidade_questao = MethodsProfTurma::estatisticaDisciplinaQuestao($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Ano Habilidades ----------------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico = MethodsProfTurma::estatisticaHabilidadeDisciplinaAnoGrafico($id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);   
        $dados_ajuste_percentual = MethodsProfTurma::estatisticaAjustePercentual($id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        //Busca dados Questões Habilidade
        $dados_base_habilidade_ano_questao = MethodsProfTurma::estatisticaHabilidadeAnoQuestao($id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        $ano_selecionado = $ano;

        //Busca dados Sessão Habilidade Selecionda ----------------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico_habilidade = MethodsProfTurma::estatisticaHabilidadeDisciplinaHabilidade($id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = MethodsProfTurma::estatisticaAjustePercentualAno($id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide em 6 por linha para gerar Cards
        $dados_base_habilidades_disciplina_habilidade = array_chunk($dados_base_habilidade_disciplina_grafico_habilidade, 6);
        //Busca dados questão habilidade individual
        $dados_base_habilidade_questao_habilidade = MethodsProfTurma::estatisticaBaseHabilidadeQuestaoHabilidade($id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade,$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Questões Disciplinas -----------------------------------------------------------------------------------------------
        $dados_base_questao_grafico_disciplina = MethodsProfTurma::estatisticaQuestaoGraficoDisciplina($turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_questao = MethodsProfTurma::estatisticaAjustePercentualQuestao($turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_questao_disciplina = array_chunk($dados_base_questao_grafico_disciplina, 6);
        //Seta os Tipos de Questões
        $tipos_questoes = [];
        $contador = 0;
        for ($j = 0; $j < sizeof($dados_base_questao_grafico_disciplina); $j++) {
            if (!in_array($dados_base_questao_grafico_disciplina[$j]->tipo_questao, $tipos_questoes)) {
                $tipos_questoes[$contador] = $dados_base_questao_grafico_disciplina[$j]->tipo_questao;
                $contador++;
            }
        }
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Aluno --------------------------------------------------------------------------------------------------------------------------------------
        $dados_base_aluno_grafico_disciplina = MethodsProfTurma::estatisticaBaseAlunoGraficoDisciplina($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_aluno_disciplina = array_chunk($dados_base_aluno_grafico_disciplina, 6);
        //---------------------------------------------------------------------------------------------------------------------------------------------------------------
        
        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        if ($disciplina_selecionada[0]->id == 2) {
            $testeProfessor=41;
            $criterios_questaoAno = MethodsGerais::getCriteriosQuestaoAno($ano_selecionado[0]);
        } else {
             //Nos demais não existe esse filtro adicional
            $testeProfessor=42;
            $criterios_questaoAno = MethodsGerais::getCriterios();
        }

        $dados_base_habilidade_questao = MethodsProfTurma::getHabilidadesCriterios($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);

        $sessao_inicio = "habilidadeselecionadadisciplina";

        return view('proficiencia/professor/professor', compact(
            'criterios_questaoAno','solRegistro','solAltCadastral','solAddTurma','turmas','municipios','questoes','destaques','dados_base_turma_grafico','dados_base_turma','dados_comparacao_turma','ano',
            'turma_selecionada','dados_base_tema','dados_base_grafico','dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_questao_grafico_disciplina',
            'dados_base_questao_disciplina','escolas','disciplinas','disciplina_selecionada','escola_selecionada','municipio_selecionado','legendas','dados_base_aluno_grafico_disciplina',
            'dados_base_aluno_disciplina','anos','dados_base_habilidade_questao','ano_selecionado','dados_base_habilidade_disciplina_ano_grafico','dados_base_habilidades_ano_disciplina',
            'dados_base_habilidade_ano_questao','criterios_questao','tipos_questoes','habilidades','habilidade_selecionada','dados_base_habilidade_disciplina_grafico_habilidade',
            'dados_base_habilidades_disciplina_habilidade','dados_base_habilidade_questao_habilidade','dados_ajuste_percentual','dados_ajuste_percentual_ano','dados_ajuste_percentual_base',
            'dados_ajuste_percentual_questao','anos_same','ano_same_selecionado','sessao_inicio'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
     
     public function exibirTurmaAnoSame($id_disciplina, $id_escola, $ano_same)
     {
         //Busca os previlégios do Usuário Logado
         $previlegio = MethodsGerais::getPrevilegio();
 
         $ano_same_selecionado = $ano_same;
 
         //Listagem de Anos do SAME
         $anos_same = MethodsGerais::getAnosSAME();
 
         //----------------------------------------- Disciplinas ----------------------------------------------------------------
         $disciplinas = MethodsGerais::getDisciplinas();
         //----------------------------------------------------------------------------------------------------------------------
 
 
         //------------------------------------------ Solicitações -----------------------------------------------------------------
         $solRegistro = MethodsGerais::getSolicitacaoRegistro();
         $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
         $solAddTurma = MethodsGerais::getSolicitacaoTurma();
         //----------------------------------------------------------------------------------------------------------------------
 
         //------------------------------------------- Municípios -----------------------------------------------------------------
         $municipios = MethodsGerais::getMunicipios($ano_same_selecionado);
         //----------------------------------------------------------------------------------------------------------------------
 
         //------------------------------------------- Turmas -------------------------------------------------------------------
         $turmas = MethodsProfTurma::getTurmasProfessor($id_escola, $ano_same_selecionado);
         if(!isset($turmas) || sizeof($turmas) == 0){
            $escolas = MethodsProfTurma::getEscolasProfessor($municipios[0]->id, null, $ano_same_selecionado);
            $turmas = MethodsProfTurma::getTurmasProfessor($escolas[0]->id, $ano_same_selecionado);
         }
         //----------------------------------------------------------------------------------------------------------------------
 
         $turma = $turmas[0]->id;
         $turma_selecionada = MethodsGerais::getTurmaSelecionada($turma, $ano_same_selecionado);
 
         //------------------------------------------- Escolas -----------------------------------------------------------------
         $escolas = MethodsProfTurma::getEscolasProfessor($turma_selecionada[0]->escolas_municipios_id, $id_escola, $ano_same_selecionado);
         //----------------------------------------------------------------------------------------------------------------------
 
         //Busca as Questões
         $questoes = MethodsGerais::getQuestoes();
 
         //Busca os Destaques
         $destaques = MethodsGerais::getDestaques();
 
         //Busca as Legendas
         $legendas = MethodsGerais::getLegendas();
 
         //Seta os Anos a serem utilizados no seletor
         $anos = [];
         for ($i = 0; $i < sizeof($turmas); $i++) {
             if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                 $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
             }
         }
 
         //Define o Ano seleciondao
         $ano = substr(trim($turma_selecionada[0]->DESCR_TURMA), 0, 2);
 
         //Carrega os dados da Disciplina Selecionada
         $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($id_disciplina);
 
         //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
         $criterios_questao = MethodsGerais::getCriteriosQuestao($ano,$disciplina_selecionada[0]->id);
 
         //Carrega dados da Escola Selecionada
         $escola_selecionada = MethodsGerais::getEscolaSelecionada($turma_selecionada[0]->escolas_id, $ano_same_selecionado);
 
         //Carrega os dados do Município Selecionada
         $municipio_selecionado = MethodsGerais::getMunicipioSelecionado($turma_selecionada[0]->escolas_municipios_id, $ano_same_selecionado);
 
         //Busca dados Média Turma ---------------------------------------------------------------------------------------------------------------
         $dados_base_turma_grafico = MethodsProfTurma::estatisticaBaseTurma($turma,$ano,$ano_same_selecionado);
         $dados_comparacao_turma = MethodsProfTurma::estatisticaComparacaoTurma($turma,$ano,$ano_same_selecionado);
         //Divide em 2 por linha para gerar os cards
         $dados_base_turma = array_chunk($dados_base_turma_grafico, 2);
         //---------------------------------------------------------------------------------------------------------------------------------------
 
         //Busca dados Sessão Tema na Disciplina -------------------------------------------------------------------------------------------------
         $dados_base_grafico = MethodsProfTurma::estatisticaBaseGrafico($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
         //Divide em 4 por Linha para gerar os Cards
         $dados_base_tema = array_chunk($dados_base_grafico, 4);
         //---------------------------------------------------------------------------------------------------------------------------------------
 
         //Busca as habilidades
         $habilidades = MethodsProfTurma::getHabilidadesProfessor($disciplina_selecionada[0]->id, $turma, $ano_same_selecionado);
 
         //Busca dados Sessão Habilidade Disciplina ----------------------------------------------------------------------------------------------
         $dados_base_habilidade_disciplina_grafico = MethodsProfTurma::estatisticaHabilidadeDisciplinaGrafico($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
         $dados_ajuste_percentual_base = MethodsProfTurma::estatisticaAjustePercentualBase($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
         //Divide em 6 por linha para gerar os Cards
         $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
         //Busca dados das Questões da Sessão Habilidade Disciplina
         $dados_base_habilidade_questao = MethodsProfTurma::estatisticaDisciplinaQuestao($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
         //---------------------------------------------------------------------------------------------------------------------------------------
 
         //Busca dados Sessão Ano Habilidades ----------------------------------------------------------------------------------------------------
         $dados_base_habilidade_disciplina_ano_grafico = MethodsProfTurma::estatisticaHabilidadeDisciplinaAnoGrafico($id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);  
         $dados_ajuste_percentual = MethodsProfTurma::estatisticaAjustePercentual($id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado); 
         //Divide em 6 por linha para gerar os cards
         $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
         //Busca dados Questões Habilidade
         $dados_base_habilidade_ano_questao = MethodsProfTurma::estatisticaHabilidadeAnoQuestao($id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
         //---------------------------------------------------------------------------------------------------------------------------------------
 
         //Busca dados da Habilidade Selecionada  
         $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($habilidades[0]->id_habilidade);
 
         //Busca dados Sessão Habilidade Selecionda ----------------------------------------------------------------------------------------------    
         $dados_base_habilidade_disciplina_grafico_habilidade = MethodsProfTurma::estatisticaHabilidadeDisciplinaHabilidade($id_escola, $disciplina_selecionada[0]->id, 
         $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
         $dados_ajuste_percentual_ano = MethodsProfTurma::estatisticaAjustePercentualAno($id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
         //Divide em 6 por linha para gerar Cards
         $dados_base_habilidades_disciplina_habilidade = array_chunk($dados_base_habilidade_disciplina_grafico_habilidade, 6);
         //Busca dados questão habilidade individual
         $dados_base_habilidade_questao_habilidade = MethodsProfTurma::estatisticaBaseHabilidadeQuestaoHabilidade($id_escola, $disciplina_selecionada[0]->id, 
         $habilidade_selecionada[0]->id_habilidade,$ano_same_selecionado);
         //---------------------------------------------------------------------------------------------------------------------------------------       
 
         //Busca dados Sessão Questões Disciplinas -----------------------------------------------------------------------------------------------
         $dados_base_questao_grafico_disciplina = MethodsProfTurma::estatisticaQuestaoGraficoDisciplina($turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
         $dados_ajuste_percentual_questao = MethodsProfTurma::estatisticaAjustePercentualQuestao($turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
         //Divide em 6 por linha para gerar os Cards
         $dados_base_questao_disciplina = array_chunk($dados_base_questao_grafico_disciplina, 6);
         //Seta os Tipos de Questões
         $tipos_questoes = [];
         $contador = 0;
         for ($j = 0; $j < sizeof($dados_base_questao_grafico_disciplina); $j++) {
             if (!in_array($dados_base_questao_grafico_disciplina[$j]->tipo_questao, $tipos_questoes)) {
                 $tipos_questoes[$contador] = $dados_base_questao_grafico_disciplina[$j]->tipo_questao;
                 $contador++;
             }
         }
         //---------------------------------------------------------------------------------------------------------------------------------------
 
         //Busca dados Sessão Aluno --------------------------------------------------------------------------------------------------------------------------------------
         $dados_base_aluno_grafico_disciplina = MethodsProfTurma::estatisticaBaseAlunoGraficoDisciplina($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
         //Divide em 6 por linha para gerar os cards
         $dados_base_aluno_disciplina = array_chunk($dados_base_aluno_grafico_disciplina, 6);
         //---------------------------------------------------------------------------------------------------------------------------------------------------------------
         $dados_base_habilidade_questao = MethodsProfTurma::getHabilidadesCriterios($turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);

         $sessao_inicio = "turma";
 
         return view('proficiencia/professor/professor', compact(
             'solRegistro','solAltCadastral','solAddTurma','turmas','municipios','questoes','destaques','dados_base_turma_grafico','dados_base_turma','turma_selecionada','dados_base_tema','dados_base_grafico',
             'dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_questao_grafico_disciplina','dados_base_questao_disciplina','escolas','disciplinas',
             'disciplina_selecionada','escola_selecionada','municipio_selecionado','legendas','dados_base_aluno_grafico_disciplina','dados_base_aluno_disciplina','anos',
             'dados_base_habilidade_questao','ano','dados_base_habilidade_disciplina_ano_grafico','dados_base_habilidades_ano_disciplina','dados_base_habilidade_ano_questao',
             'tipos_questoes','criterios_questao','habilidades','habilidade_selecionada','dados_base_habilidade_disciplina_grafico_habilidade','dados_base_habilidades_disciplina_habilidade',
             'dados_base_habilidade_questao_habilidade','dados_ajuste_percentual','dados_ajuste_percentual_ano','dados_ajuste_percentual_base','dados_ajuste_percentual_questao','anos_same',
             'ano_same_selecionado','dados_comparacao_turma','sessao_inicio'));
     }

}
