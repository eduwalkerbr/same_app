<?php

namespace App\Http\Controllers\proficiencia\secretario;
use App\Http\Controllers\Controller;
use App\Http\Controllers\staticmethods\gerais\MethodsGerais;
use App\Http\Controllers\staticmethods\proficiencia\MethodsProfMunicipio;

use function Symfony\Component\String\b;

class SecretarioController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Adiciona autenticação para Acesso a Página
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * Método para disponibilização de página Inicial
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();

        //Define o primeiro Ano SAME como padrão
        $ano_same_selecionado = $anos_same[0]->SAME;

        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();

        //Lista os Munícipios pelo Ano SAME
        $municipios = MethodsGerais::getMunicipios($ano_same_selecionado);

        //Lista as Disciplinas
        $disciplinas = MethodsGerais::getDisciplinas();

        //Busca as Sugestões
        $sugestoes = MethodsGerais::getSugestoes();

        //Busca as Solicitações de Acordo o tipo de Perfil do Usuário
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();

        //Busca as Legendas
        $legendas = MethodsGerais::getLegendas();

        //Busca os destaques
        $destaques = MethodsGerais::getDestaques();

        //Define o Munícipio Selecionado como padrão sendo o primeiro
        $municipio = $municipios[0]->id;

        //Busca as escola ativas do município e Ano SAME
        $escolas = MethodsProfMunicipio::getEscolasMunicipio($municipio, $ano_same_selecionado);

        //Busca as turmas ativas do municípios e Ano SAME
        $turmas = MethodsProfMunicipio::getTurmasMunicipio($municipio, $ano_same_selecionado);
        
        //Seta os Anos a serem utilizados na listagem baseado nas turmas listadas, evitando duplicações
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o primeiro ano da listagem como padrão
        $ano = substr(trim($turmas[0]->DESCR_TURMA), 0, 2);

        //Carrega os dados do Munícipio Selecionado
        $municipio_selecionado = MethodsGerais::getMunicipioSelecionado($municipio, $ano_same_selecionado);

        //Carrega os dados da Disciplina Selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($disciplinas[0]->id);

        //Carrega os dados da Escola Selecionada
        $escola_selecionada = MethodsGerais::getEscolaSelecionada($escolas[0]->id, $ano_same_selecionado);

        //Reseta o nome da escola selecionada
        $escola_selecionada[0]->nome = null;

        //---------------- Dados para a Sessão Proficiência Disciplina -----------------------------------------------------------------------------------------
        $dados_base_grafico_disciplina = MethodsProfMunicipio::estatisticaDisciplinas($municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_disciplina = array_chunk($dados_base_grafico_disciplina, 4);
        //---------------- Dados para a Sessão Proficiência Disciplina ----------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------
        $dados_base_grafico_escola = MethodsProfMunicipio::estatisticaEscola($municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola = array_chunk($dados_base_grafico_escola, 4);
        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------
        $dados_base_grafico_escola_disciplina = MethodsProfMunicipio::estatisticaEscolaDisciplina($municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola_disciplina = array_chunk($dados_base_grafico_escola_disciplina, 4);
        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------
        $dados_base_anos_disciplina_grafico = MethodsProfMunicipio::estatisticaAnoDisciplinas($municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_anos_disciplina = array_chunk($dados_base_anos_disciplina_grafico, 6);
        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico = MethodsProfMunicipio::estatisticaHabilidadeDisciplinaAno($municipio, $disciplina_selecionada[0]->id, $anos[0], $ano_same_selecionado);
        $dados_ajuste_percentual = MethodsProfMunicipio::estatisticaAjustePercentual($municipio, $disciplina_selecionada[0]->id, $anos[0], $ano_same_selecionado);
        //Divide em 6 registros por linha para gerar os Cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        // Dados das questões das Habilidades Selecionadas por Ano
        $dados_base_habilidade_ano_questao = MethodsProfMunicipio::estatisticaHabilidadeAnoQuestao($municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------

        //Busca as Habilidades pela Disciplina e Munícipio
        $habilidades = MethodsProfMunicipio::getHabilidades($disciplina_selecionada[0]->id, $municipio);

        //Busca habilidade selecionada
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($habilidades[0]->id_habilidade);

        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico = MethodsProfMunicipio::estatisticaHabilidadeSelecionadaDisciplina($municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = MethodsProfMunicipio::estatisticaAjustePercentualAno($municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca dados das Questões das Habilidades
        $dados_base_habilidade_questao = MethodsProfMunicipio::estatisticaHabilidadeQuestao($municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------  

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = MethodsGerais::getCriteriosQuestao($ano, $disciplina_selecionada[0]->id);

        $turmas = null;

        $sessao_inicio = "municipio";
              
        return view('proficiencia/secretario/secretario', compact(
            'solRegistro','solAltCadastral','solAddTurma','sugestoes','turmas','escolas','municipios','destaques','municipio_selecionado','dados_base_grafico_disciplina',
            'dados_base_disciplina','dados_base_grafico_escola','dados_base_escola','dados_base_grafico_escola_disciplina','dados_base_escola_disciplina','disciplinas',
            'disciplina_selecionada','legendas','dados_base_anos_disciplina_grafico','dados_base_anos_disciplina','escola_selecionada','anos','ano','dados_base_habilidade_disciplina_ano_grafico',
            'dados_base_habilidades_ano_disciplina','dados_base_habilidade_ano_questao','habilidades','habilidade_selecionada','dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina',
            'dados_base_habilidade_questao','dados_ajuste_percentual','dados_ajuste_percentual_ano','criterios_questao','anos_same','ano_same_selecionado','sessao_inicio'));
    }
    //

    /**
     * Show the application dashboard.
     * Método para disponibilização da página após seleção de um município e ou disciplina diferente
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirMunicipio($id, $id_disciplina, $ano_same)
    {
        //Seta o Ano SAME pelo parâmetro passado
        $ano_same_selecionado = $ano_same;

        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();
        
        //Lista os Munícipios pelo Ano SAME Selecionado
        $municipios = MethodsGerais::getMunicipios($ano_same_selecionado);

        //Seta o Munícipio Padrão
        $municipio = $municipios[0]->id;

        //Caso esteja na listagem substitui pelo informado
        for ($i = 0; $i < sizeof($municipios); $i++) {
            if($municipios[$i]->id == $id){
                $municipio = $id;    
            }    
        }

        //Lista as Disciplinas
        $disciplinas = MethodsGerais::getDisciplinas();

        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();

        //Busca as Sugestões
        $sugestoes = MethodsGerais::getSugestoes();

        //Caso seja Gestor busca as solicitações de seu munícipio
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();

        //Busca as Legendas
        $legendas = MethodsGerais::getLegendas();

        //Busca os destaques
        $destaques = MethodsGerais::getDestaques();

        //Busca as escola ativas do município e Ano SAME Selecionado
        $escolas = MethodsProfMunicipio::getEscolasMunicipio($municipio, $ano_same_selecionado);

        //Busca as turmas ativas do municípios e Ano SAME Selecionado
        $turmas = MethodsProfMunicipio::getTurmasMunicipio($municipio, $ano_same_selecionado);

        //Seta os Anos a serem utilizados na listagem
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o primeiro ano da listagem como padrão
        $ano = substr(trim($turmas[0]->DESCR_TURMA), 0, 2);

        //Carrega os dados do município selecionado
        $municipio_selecionado = MethodsGerais::getMunicipioSelecionado($municipio, $ano_same_selecionado);

        //Carrega os dados da disciplina selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($id_disciplina);

        //Carrega os dados da escola selecionada
        $escola_selecionada = MethodsGerais::getEscolaSelecionada($escolas[0]->id, $ano_same_selecionado);

        //Reseta o nome da escola selecionada
        $escola_selecionada[0]->nome = null;
        
        //---------------- Dados para a Sessão Proficiência Disciplina -----------------------------------------------------------------------------------------
        $dados_base_grafico_disciplina = MethodsProfMunicipio::estatisticaDisciplinas($municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_disciplina = array_chunk($dados_base_grafico_disciplina, 4);
        //---------------- Dados para a Sessão Proficiência Disciplina ----------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------
        $dados_base_grafico_escola = MethodsProfMunicipio::estatisticaEscola($municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola = array_chunk($dados_base_grafico_escola, 4);
        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------
        $dados_base_grafico_escola_disciplina = MethodsProfMunicipio::estatisticaEscolaDisciplina($municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola_disciplina = array_chunk($dados_base_grafico_escola_disciplina, 4);
        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------
        $dados_base_anos_disciplina_grafico = MethodsProfMunicipio::estatisticaAnoDisciplinas($municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_anos_disciplina = array_chunk($dados_base_anos_disciplina_grafico, 6);
        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------

       //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico = MethodsProfMunicipio::estatisticaHabilidadeDisciplinaAno($municipio, $disciplina_selecionada[0]->id, $anos[0], $ano_same_selecionado);
        $dados_ajuste_percentual = MethodsProfMunicipio::estatisticaAjustePercentual($municipio, $disciplina_selecionada[0]->id, $anos[0], $ano_same_selecionado);
        //Divide em 6 registros por linha para gerar os Cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        // Dados das questões das Habilidades Selecionadas por Ano
        $dados_base_habilidade_ano_questao = MethodsProfMunicipio::estatisticaHabilidadeAnoQuestao($municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------

        //Busca as Habilidades pela Disciplina e Munícipio
        $habilidades = MethodsProfMunicipio::getHabilidades($disciplina_selecionada[0]->id, $municipio);

        //Busca habilidade selecionada
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($habilidades[0]->id_habilidade);

        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico = MethodsProfMunicipio::estatisticaHabilidadeSelecionadaDisciplina($municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = MethodsProfMunicipio::estatisticaAjustePercentualAno($municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca dados das Questões das Habilidades
        $dados_base_habilidade_questao = MethodsProfMunicipio::estatisticaHabilidadeQuestao($municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------  

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = MethodsGerais::getCriteriosQuestao($ano, $disciplina_selecionada[0]->id);

        $turmas = null;

        $sessao_inicio = "municipio";

        return view('proficiencia/secretario/secretario', compact(

            'solRegistro','solAltCadastral','solAddTurma','sugestoes','turmas','escolas','municipios','destaques','municipio_selecionado','dados_base_grafico_disciplina',
            'dados_base_disciplina','dados_base_grafico_escola','dados_base_escola','dados_base_grafico_escola_disciplina','dados_base_escola_disciplina','disciplinas',
            'disciplina_selecionada','legendas','dados_base_anos_disciplina_grafico','dados_base_anos_disciplina','escola_selecionada',
            'anos','ano','dados_base_habilidade_disciplina_ano_grafico','dados_base_habilidades_ano_disciplina','dados_base_habilidade_ano_questao','habilidades','habilidade_selecionada',
            'dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_habilidade_questao','dados_ajuste_percentual','dados_ajuste_percentual_ano',
            'criterios_questao','anos_same','ano_same_selecionado','sessao_inicio'));
    }

    /**
     * Show the application dashboard.
     * Método para disponibilização da página após a seleção de um ano
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirMunicipioAno($id, $id_disciplina, $ano, $ano_same)
    {
        //Seta o Ano SAME Selecionado pelo parâmetro passado
        $ano_same_selecionado = $ano_same;

        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();

         //Lista os Munícipios pelo Ano SAME Selecionado
         $municipios = MethodsGerais::getMunicipios($ano_same_selecionado);

         //Seta o Munícipio Padrão
         $municipio = $municipios[0]->id;
 
         //Caso esteja na listagem substitui pelo informado
         for ($i = 0; $i < sizeof($municipios); $i++) {
             if($municipios[$i]->id == $id){
                 $municipio = $id;    
             }    
         }

        //Lista as Disciplinas
        $disciplinas = MethodsGerais::getDisciplinas();

        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();

        //Busca as Sugestões
        $sugestoes = MethodsGerais::getSugestoes();

        //Caso seja Gestor busca as solicitações de seu munícipio
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();

        //Busca as Legendas
        $legendas = MethodsGerais::getLegendas();

        //Busca os destaques
        $destaques = MethodsGerais::getDestaques();

        //Busca as escola ativas do município e Ano SAME Selecionado
        $escolas = MethodsProfMunicipio::getEscolasMunicipio($municipio, $ano_same_selecionado);

        //Busca as turmas ativas do municípios e Ano SAME Selecionado
        $turmas = MethodsProfMunicipio::getTurmasMunicipio($municipio, $ano_same_selecionado);

        //Seta os Anos a serem utilizados na listagem
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Carrega os dados do município selecionado
        $municipio_selecionado = MethodsGerais::getMunicipioSelecionado($municipio, $ano_same_selecionado);

        //Carrega os dados da disciplina selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($id_disciplina);

        //Carrega os dados da escola selecionada
        $escola_selecionada = MethodsGerais::getEscolaSelecionada($escolas[0]->id, $ano_same_selecionado);

        //Reseta o nome da escola selecionada
        $escola_selecionada[0]->nome = null;

        //---------------- Dados para a Sessão Proficiência Disciplina -----------------------------------------------------------------------------------------
        $dados_base_grafico_disciplina = MethodsProfMunicipio::estatisticaDisciplinas($municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_disciplina = array_chunk($dados_base_grafico_disciplina, 4);
        //---------------- Dados para a Sessão Proficiência Disciplina ----------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------
        $dados_base_grafico_escola = MethodsProfMunicipio::estatisticaEscola($municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola = array_chunk($dados_base_grafico_escola, 4);
        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------
        $dados_base_grafico_escola_disciplina = MethodsProfMunicipio::estatisticaEscolaDisciplina($municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola_disciplina = array_chunk($dados_base_grafico_escola_disciplina, 4);
        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------
        $dados_base_anos_disciplina_grafico = MethodsProfMunicipio::estatisticaAnoDisciplinas($municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_anos_disciplina = array_chunk($dados_base_anos_disciplina_grafico, 6);
        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico = MethodsProfMunicipio::estatisticaHabilidadeDisciplinaAno($municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        $dados_ajuste_percentual = MethodsProfMunicipio::estatisticaAjustePercentual($municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //Divide em 6 registros por linha para gerar os Cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        // Dados das questões das Habilidades Selecionadas por Ano
        $dados_base_habilidade_ano_questao = MethodsProfMunicipio::estatisticaHabilidadeAnoQuestao($municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------

        //Busca as Habilidades pela Disciplina e Munícipio
        $habilidades = MethodsProfMunicipio::getHabilidades($disciplina_selecionada[0]->id, $municipio);

        //Busca habilidade selecionada
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($habilidades[0]->id_habilidade);

        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico = MethodsProfMunicipio::estatisticaHabilidadeSelecionadaDisciplina($municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = MethodsProfMunicipio::estatisticaAjustePercentualAno($municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca dados das Questões das Habilidades
        $dados_base_habilidade_questao = MethodsProfMunicipio::estatisticaHabilidadeQuestao($municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------  

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = MethodsGerais::getCriteriosQuestao($ano, $disciplina_selecionada[0]->id);

        $turmas = null;

        $sessao_inicio = "habilidadeanodisciplina";

        return view('proficiencia/secretario/secretario', compact(
            
            'solRegistro','solAltCadastral','solAddTurma','sugestoes','turmas','escolas','municipios','destaques','municipio_selecionado','dados_base_grafico_disciplina','dados_base_disciplina',
            'dados_base_grafico_escola','dados_base_escola','dados_base_grafico_escola_disciplina','dados_base_escola_disciplina','disciplinas','disciplina_selecionada','legendas',
            'dados_base_anos_disciplina_grafico','dados_base_anos_disciplina','escola_selecionada','anos','ano','dados_base_habilidade_disciplina_ano_grafico','dados_base_habilidades_ano_disciplina',
            'dados_base_habilidade_ano_questao','habilidades','habilidade_selecionada','dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_habilidade_questao',
            'dados_ajuste_percentual','dados_ajuste_percentual_ano','criterios_questao','anos_same','ano_same_selecionado','sessao_inicio'));
    }

    /**
     * Show the application dashboard.
     * Método para disponibilização da página após seleção de uma habilidade
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirMunicipioHabilidade($id, $id_disciplina, $ano, $id_habilidade, $ano_same)
    {
        //Seta o Ano SAME Selecionado pelo parâmetro passado
        $ano_same_selecionado = $ano_same;

        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio(); 

        //Lista os Munícipios pelo Ano SAME Selecionado
        $municipios = MethodsGerais::getMunicipios($ano_same_selecionado);

        //Seta o Munícipio Padrão
        $municipio = $municipios[0]->id;
 
        //Caso esteja na listagem substitui pelo informado
        for ($i = 0; $i < sizeof($municipios); $i++) {
            if($municipios[$i]->id == $id){
                $municipio = $id;    
            }    
        }

        //Lista as Disciplinas
        $disciplinas = MethodsGerais::getDisciplinas();

        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();

        //Busca as Sugestões
        $sugestoes = MethodsGerais::getSugestoes();
 
        //Caso seja Gestor busca as solicitações de seu munícipio
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();
 
        //Busca as Legendas
        $legendas = MethodsGerais::getLegendas();

        //Busca os destaques
        $destaques = MethodsGerais::getDestaques();

        //Busca as escola ativas do município pelo Ano SAME Selecionado
        $escolas = MethodsProfMunicipio::getEscolasMunicipio($municipio, $ano_same_selecionado);

        //Busca as turmas ativas do municípios pelo Ano SAME Selecionado
        $turmas = MethodsProfMunicipio::getTurmasMunicipio($municipio, $ano_same_selecionado);
 
         //Seta os Anos a serem utilizados na listagem
         $anos = [];
         for ($i = 0; $i < sizeof($turmas); $i++) {
             if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                 $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
             }
         }
 
        //Carrega os dados do município selecionado
        $municipio_selecionado = MethodsGerais::getMunicipioSelecionado($municipio, $ano_same_selecionado);

        //Carrega os dados da disciplina selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($id_disciplina);

        //Carrega os dados da escola selecionada
        $escola_selecionada = MethodsGerais::getEscolaSelecionada($escolas[0]->id, $ano_same_selecionado);

        //Reseta o nome da escola selecionada
        $escola_selecionada[0]->nome = null;
 
        //---------------- Dados para a Sessão Proficiência Disciplina -----------------------------------------------------------------------------------------
        $dados_base_grafico_disciplina = MethodsProfMunicipio::estatisticaDisciplinas($municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_disciplina = array_chunk($dados_base_grafico_disciplina, 4);
        //---------------- Dados para a Sessão Proficiência Disciplina ----------------------------------------------------------------------------------------
 
        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------
        $dados_base_grafico_escola = MethodsProfMunicipio::estatisticaEscola($municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola = array_chunk($dados_base_grafico_escola, 4);
        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------
 
        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------
        $dados_base_grafico_escola_disciplina = MethodsProfMunicipio::estatisticaEscolaDisciplina($municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola_disciplina = array_chunk($dados_base_grafico_escola_disciplina, 4);
        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------
 
        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------
        $dados_base_anos_disciplina_grafico = MethodsProfMunicipio::estatisticaAnoDisciplinas($municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_anos_disciplina = array_chunk($dados_base_anos_disciplina_grafico, 6);
        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------
 
        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico = MethodsProfMunicipio::estatisticaHabilidadeDisciplinaAno($municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        $dados_ajuste_percentual = MethodsProfMunicipio::estatisticaAjustePercentual($municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //Divide em 6 registros por linha para gerar os Cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        // Dados das questões das Habilidades Selecionadas por Ano
        $dados_base_habilidade_ano_questao = MethodsProfMunicipio::estatisticaHabilidadeAnoQuestao($municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------
 
        //Busca as Habilidades pela Disciplina e Munícipio
        $habilidades = MethodsProfMunicipio::getHabilidades($disciplina_selecionada[0]->id, $municipio);

        //Busca habilidade selecionada
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($id_habilidade);
 
        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico = MethodsProfMunicipio::estatisticaHabilidadeSelecionadaDisciplina($municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = MethodsProfMunicipio::estatisticaAjustePercentualAno($municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca dados das Questões das Habilidades
        $dados_base_habilidade_questao = MethodsProfMunicipio::estatisticaHabilidadeQuestao($municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------  

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = MethodsGerais::getCriteriosQuestao($ano, $disciplina_selecionada[0]->id);

        // Nova definição das Habilidades com os Critérios
        $dados_base_habilidade_questao = MethodsGerais::getHabilidadesCriterios($dados_base_habilidade_questao);

        $turmas = null;

        $sessao_inicio = "habilidadeselecionadadisciplina";
         
        return view('proficiencia/secretario/secretario', compact(
            'solRegistro','solAltCadastral','solAddTurma','sugestoes','turmas','escolas','municipios','destaques','municipio_selecionado','dados_base_grafico_disciplina',
            'dados_base_disciplina','dados_base_grafico_escola','dados_base_escola','dados_base_grafico_escola_disciplina','dados_base_escola_disciplina','disciplinas',
            'disciplina_selecionada','legendas','dados_base_anos_disciplina_grafico','dados_base_anos_disciplina','escola_selecionada','anos','ano',
            'dados_base_habilidade_disciplina_ano_grafico','dados_base_habilidades_ano_disciplina','dados_base_habilidade_ano_questao','habilidades','habilidade_selecionada',
            'dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_habilidade_questao','dados_ajuste_percentual','dados_ajuste_percentual_ano' 
            ,'criterios_questao','anos_same','ano_same_selecionado','sessao_inicio'));
    }
}



