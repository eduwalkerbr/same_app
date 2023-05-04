<?php
namespace App\Http\Controllers\comparativo\diretor;
use App\Http\Controllers\Controller;
use App\Http\Controllers\staticmethods\comparativo\MethodsGerais as ComparativoMethodsGerais;
use App\Http\Controllers\staticmethods\comparativo\MethodsProfEscola;
use App\Http\Controllers\staticmethods\gerais\MethodsGerais;
use App\Http\Controllers\staticmethods\proficiencia\MethodsProfEscola as ProficienciaMethodsProfEscola;

class DiretorComparativoController extends Controller
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
        $direcaoProfessor = ComparativoMethodsGerais::getDirecaoProfessor();

        //----------------------------------------- Escolas -------------------------------------------------------------------
        $escolas = MethodsProfEscola::getEscolasDiretor($previlegio[0]->municipios_id);

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
        $municipios = ComparativoMethodsGerais::getMunicipiosComparativo();

        //Identifica a escola selecionada
        $escola = $escolas[0]->id;

        //Busca as turmas da escola selecionda
        $turmas = MethodsProfEscola::getTurmasEscola($escola);

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
        $escola_selecionada = ComparativoMethodsGerais::getEscolaSelecionadaComparativo($escola);

        //Busca os dados do Município selecionado partindo da escola
        $municipio_selecionado = ComparativoMethodsGerais::getMunicipioSelecionadoComparativo($escola_selecionada[0]->municipios_id);

        //Busca os dados da Disciplina Selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($disciplinas[0]->id);

        //Buscas as Habilidades
        $habilidades = ProficienciaMethodsProfEscola::getHabilidadesEscola($disciplina_selecionada[0]->id, $escola_selecionada[0]->id);

        //Busca os dados da Habilidade Selecionda    
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($habilidades[0]->id_habilidade);

        //Busca todos os Critérios
        $criterios_questaoAll = MethodsGerais::getCriterios();

        //Busca dados Sessão de Disciplinas
        $dados_comp_grafico_disciplina = MethodsProfEscola::estatisticaDisciplinas($escola);
        $label_disc = $dados_comp_grafico_disciplina[0];
        $dados_disc = $dados_comp_grafico_disciplina[1];
        $itens_disc = $dados_comp_grafico_disciplina[2];
        $map_itens_disc = $dados_comp_grafico_disciplina[3];

        //Busca dados da Sessão de Temas
        $dados_comp_grafico_tema = MethodsProfEscola::estatisticaTemas($escola, $disciplina_selecionada[0]->id, $ano);
        $label_tema = $dados_comp_grafico_tema[0];
        $dados_tema = $dados_comp_grafico_tema[1];
        $itens_tema = $dados_comp_grafico_tema[2];
        $map_itens_tema = $dados_comp_grafico_tema[3];

        //Busca dados da Sessão de Ano Curricular Disciplina
        $dados_comp_grafico_curricular_disc = MethodsProfEscola::estatisticaCurricularDisciplina($escola, $disciplina_selecionada[0]->id);
        $label_curricular_disc = $dados_comp_grafico_curricular_disc[0];
        $dados_curricular_disc = $dados_comp_grafico_curricular_disc[1];
        $itens_curricular_disc = $dados_comp_grafico_curricular_disc[2];
        $map_itens_curricular_disc = $dados_comp_grafico_curricular_disc[3];

        //Busca dados da Sessão de Turma Disciplina
        $dados_comp_grafico_turma_disc = MethodsProfEscola::estatisticaTurmaDisciplina($escola, $disciplina_selecionada[0]->id);
        $label_turma_disc = $dados_comp_grafico_turma_disc[0];
        $dados_turma_disc = $dados_comp_grafico_turma_disc[1];
        $itens_turma_disc = $dados_comp_grafico_turma_disc[2];
        $map_itens_turma_disc = $dados_comp_grafico_turma_disc[3];

        //Busca dados da Sessão de Habilidade Ano Disciplina
        $dados_comp_grafico_han_ano_disc = MethodsProfEscola::estatisticaHabilidadeAnoDisciplina($escola, $disciplina_selecionada[0]->id, $ano);
        $label_hab_ano_disc = $dados_comp_grafico_han_ano_disc[0];
        $dados_hab_ano_disc = $dados_comp_grafico_han_ano_disc[1];
        $itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[2];
        $map_itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[3];
        $nome_hab = $dados_comp_grafico_han_ano_disc[4];

        $sessao_inicio = "municipio_comparativo";
  
        return view('comparativo/diretor/content/diretor', compact(
            'criterios_questaoAll','solRegistro','solAltCadastral','solAddTurma','turmas','escolas','municipios','destaques','escola_selecionada','sessao_inicio',
            'disciplinas','disciplina_selecionada','municipio_selecionado','legendas','anos','ano','habilidades','habilidade_selecionada','anos_same',
            'ano_same_selecionado','label_disc','dados_disc','label_tema','dados_tema','label_curricular_disc','dados_curricular_disc','label_turma_disc','dados_turma_disc',
            'itens_disc','map_itens_disc','itens_tema','map_itens_tema','itens_curricular_disc','map_itens_curricular_disc','itens_turma_disc','map_itens_turma_disc',
            'label_hab_ano_disc','dados_hab_ano_disc','itens_hab_ano_disc','map_itens_hab_ano_disc','nome_hab'    
        ));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirEscolaComparativo($id, $id_municipio, $id_disciplina, $sessao)
    {
        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();

        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
        $ano_same_selecionado = $anos_same[0]->SAME;

        //Listage, de Direção Professor utilizando Cache
        $direcaoProfessor = ComparativoMethodsGerais::getDirecaoProfessor();

        //------------------------------------------- Municípios -----------------------------------------------------------------
        $municipios = ComparativoMethodsGerais::getMunicipiosComparativo();

        //----------------------------------------- Escolas -------------------------------------------------------------------
        $escolas = MethodsProfEscola::getEscolasDiretor($id_municipio);
        if(!isset($escolas) || sizeof($escolas) == 0){
            $escolas = MethodsProfEscola::getEscolasDiretor($municipios[0]->id);
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
        $turmas = MethodsProfEscola::getTurmasEscola($escola);

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
        $escola_selecionada = ComparativoMethodsGerais::getEscolaSelecionadaComparativo($escola);

        //Busca os dados do Município selecionado partindo da escola
        $municipio_selecionado = ComparativoMethodsGerais::getMunicipioSelecionadoComparativo($escola_selecionada[0]->municipios_id);

        //Busca os dados da Disciplina Selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($id_disciplina);

        //Buscas as Habilidades
        $habilidades = ProficienciaMethodsProfEscola::getHabilidadesEscola($disciplina_selecionada[0]->id, $escola_selecionada[0]->id);

        //Busca os dados da Habilidade Selecionda    
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($habilidades[0]->id_habilidade);
        
        //Busca dados Sessão de Disciplinas
        $dados_comp_grafico_disciplina = MethodsProfEscola::estatisticaDisciplinas($escola);
        $label_disc = $dados_comp_grafico_disciplina[0];
        $dados_disc = $dados_comp_grafico_disciplina[1];
        $itens_disc = $dados_comp_grafico_disciplina[2];
        $map_itens_disc = $dados_comp_grafico_disciplina[3];

        //Busca dados da Sessão de Temas
        $dados_comp_grafico_tema = MethodsProfEscola::estatisticaTemas($escola, $disciplina_selecionada[0]->id, $ano);
        $label_tema = $dados_comp_grafico_tema[0];
        $dados_tema = $dados_comp_grafico_tema[1];
        $itens_tema = $dados_comp_grafico_tema[2];
        $map_itens_tema = $dados_comp_grafico_tema[3];

        //Busca dados da Sessão de Ano Curricular Disciplina
        $dados_comp_grafico_curricular_disc = MethodsProfEscola::estatisticaCurricularDisciplina($escola, $disciplina_selecionada[0]->id);
        $label_curricular_disc = $dados_comp_grafico_curricular_disc[0];
        $dados_curricular_disc = $dados_comp_grafico_curricular_disc[1];
        $itens_curricular_disc = $dados_comp_grafico_curricular_disc[2];
        $map_itens_curricular_disc = $dados_comp_grafico_curricular_disc[3];

        //Busca dados da Sessão de Turma Disciplina
        $dados_comp_grafico_turma_disc = MethodsProfEscola::estatisticaTurmaDisciplina($escola, $disciplina_selecionada[0]->id);
        $label_turma_disc = $dados_comp_grafico_turma_disc[0];
        $dados_turma_disc = $dados_comp_grafico_turma_disc[1];
        $itens_turma_disc = $dados_comp_grafico_turma_disc[2];
        $map_itens_turma_disc = $dados_comp_grafico_turma_disc[3];

        //Busca dados da Sessão de Habilidade Ano Disciplina
        $dados_comp_grafico_han_ano_disc = MethodsProfEscola::estatisticaHabilidadeAnoDisciplina($escola, $disciplina_selecionada[0]->id, $ano);
        $label_hab_ano_disc = $dados_comp_grafico_han_ano_disc[0];
        $dados_hab_ano_disc = $dados_comp_grafico_han_ano_disc[1];
        $itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[2];
        $map_itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[3];
        $nome_hab = $dados_comp_grafico_han_ano_disc[4];

        //Busca todos os Critérios
        $criterios_questaoAll = MethodsGerais::getCriterios();

        $sessao_inicio = "";
        $sessao_inicio = $sessao;

        return view('comparativo/diretor/content/diretor', compact(
            'criterios_questaoAll','solRegistro','solAltCadastral','solAddTurma','turmas','escolas','municipios','destaques','escola_selecionada','disciplinas','sessao_inicio',
            'disciplina_selecionada','municipio_selecionado','legendas','anos','ano','habilidades','habilidade_selecionada','anos_same','ano_same_selecionado','label_disc',
            'dados_disc','label_tema','dados_tema','label_curricular_disc','dados_curricular_disc','label_turma_disc','dados_turma_disc','itens_disc','map_itens_disc',
            'itens_tema','map_itens_tema','itens_curricular_disc','map_itens_curricular_disc','itens_turma_disc','map_itens_turma_disc','label_hab_ano_disc','dados_hab_ano_disc',
            'itens_hab_ano_disc','map_itens_hab_ano_disc','nome_hab'
        ));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirEscolaComparativoAno($id, $id_municipio, $id_disciplina, $ano, $sessao)
    {
        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();

        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
        $ano_same_selecionado = $anos_same[0]->SAME;

        //Listage, de Direção Professor utilizando Cache
        $direcaoProfessor = ComparativoMethodsGerais::getDirecaoProfessor();

        //------------------------------------------- Municípios -----------------------------------------------------------------
        $municipios = ComparativoMethodsGerais::getMunicipiosComparativo();

        //----------------------------------------- Escolas -------------------------------------------------------------------
        $escolas = MethodsProfEscola::getEscolasDiretor($id_municipio);
        if(!isset($escolas) || sizeof($escolas) == 0){
            $escolas = MethodsProfEscola::getEscolasDiretor($municipios[0]->id);
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

        //Busca as turmas da escola selecionada
        $turmas = MethodsProfEscola::getTurmasEscola($escola);

        //Seta os Anos a serem utilizados no Select
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }
        //Define o ano padrão do Select
        $ano = $ano;

        //Busca os dados da Escola Selecionada
        $escola_selecionada = ComparativoMethodsGerais::getEscolaSelecionadaComparativo($escola);

        //Busca os dados do Município selecionado partindo da escola
        $municipio_selecionado = ComparativoMethodsGerais::getMunicipioSelecionadoComparativo($escola_selecionada[0]->municipios_id);

        //Busca os dados da Disciplina Selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($id_disciplina);

        //Buscas as Habilidades
        $habilidades = ProficienciaMethodsProfEscola::getHabilidadesEscola($disciplina_selecionada[0]->id, $escola_selecionada[0]->id);   

        //Busca os dados da Habilidade Selecionda   
        $habilidade_selecionada = MethodsGerais::getHabilidadeSelecionada($habilidades[0]->id_habilidade); 
        
        //Busca dados Sessão de Disciplinas
        $dados_comp_grafico_disciplina = MethodsProfEscola::estatisticaDisciplinas($escola);
        $label_disc = $dados_comp_grafico_disciplina[0];
        $dados_disc = $dados_comp_grafico_disciplina[1];
        $itens_disc = $dados_comp_grafico_disciplina[2];
        $map_itens_disc = $dados_comp_grafico_disciplina[3];

        //Busca dados da Sessão de Temas
        $dados_comp_grafico_tema = MethodsProfEscola::estatisticaTemas($escola, $disciplina_selecionada[0]->id, $ano);
        $label_tema = $dados_comp_grafico_tema[0];
        $dados_tema = $dados_comp_grafico_tema[1];
        $itens_tema = $dados_comp_grafico_tema[2];
        $map_itens_tema = $dados_comp_grafico_tema[3];

        //Busca dados da Sessão de Ano Curricular Disciplina
        $dados_comp_grafico_curricular_disc = MethodsProfEscola::estatisticaCurricularDisciplina($escola, $disciplina_selecionada[0]->id);
        $label_curricular_disc = $dados_comp_grafico_curricular_disc[0];
        $dados_curricular_disc = $dados_comp_grafico_curricular_disc[1];
        $itens_curricular_disc = $dados_comp_grafico_curricular_disc[2];
        $map_itens_curricular_disc = $dados_comp_grafico_curricular_disc[3];

        //Busca dados da Sessão de Turma Disciplina
        $dados_comp_grafico_turma_disc = MethodsProfEscola::estatisticaTurmaDisciplina($escola, $disciplina_selecionada[0]->id);
        $label_turma_disc = $dados_comp_grafico_turma_disc[0];
        $dados_turma_disc = $dados_comp_grafico_turma_disc[1];
        $itens_turma_disc = $dados_comp_grafico_turma_disc[2];
        $map_itens_turma_disc = $dados_comp_grafico_turma_disc[3];

        //Busca dados da Sessão de Habilidade Ano Disciplina
        $dados_comp_grafico_han_ano_disc = MethodsProfEscola::estatisticaHabilidadeAnoDisciplina($escola, $disciplina_selecionada[0]->id, $ano);
        $label_hab_ano_disc = $dados_comp_grafico_han_ano_disc[0];
        $dados_hab_ano_disc = $dados_comp_grafico_han_ano_disc[1];
        $itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[2];
        $map_itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[3];
        $nome_hab = $dados_comp_grafico_han_ano_disc[4];

        //Busca todos os Critérios
        $criterios_questaoAll = MethodsGerais::getCriterios();

        $sessao_inicio = "";
        $sessao_inicio = $sessao;

        return view('comparativo/diretor/content/diretor', compact(
            'criterios_questaoAll','solRegistro','solAltCadastral','solAddTurma','turmas','escolas','municipios','destaques','escola_selecionada','disciplinas','sessao_inicio',
            'disciplina_selecionada','municipio_selecionado','legendas','anos','ano','habilidades','habilidade_selecionada','anos_same','ano_same_selecionado','label_disc',
            'dados_disc','label_tema','dados_tema','label_curricular_disc','dados_curricular_disc','label_turma_disc','dados_turma_disc','itens_disc','map_itens_disc',
            'itens_tema','map_itens_tema','itens_curricular_disc','map_itens_curricular_disc','itens_turma_disc','map_itens_turma_disc','label_hab_ano_disc','dados_hab_ano_disc',
            'itens_hab_ano_disc','map_itens_hab_ano_disc','nome_hab'
        ));
    }
}
