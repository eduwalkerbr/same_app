<?php

namespace App\Http\Controllers\comparativo\secretario;

use App\Http\Controllers\Controller;
use App\Http\Controllers\staticmethods\comparativo\MethodsGerais as ComparativoMethodsGerais;
use App\Http\Controllers\staticmethods\comparativo\MethodsProfMunicipio;
use App\Http\Controllers\staticmethods\gerais\MethodsGerais;

class SecretarioComparativoController extends Controller
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
        $ano_same_selecionado = $anos_same[0]->SAME;

        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();

        //Lista os Munícipios
        $municipios = ComparativoMethodsGerais::getMunicipiosComparativo();

        //Lista as Disciplinas
        $disciplinas = MethodsGerais::getDisciplinas();

        //Busca as Sugestões
        $sugestoes = MethodsGerais::getSugestoes();

        //Caso seja Gestor busca as solicitações de seu munícpio
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();

        //Busca os destaques
        $destaques = MethodsGerais::getDestaques();

        //Busca o munícipio selecionado
        $municipio = $municipios[0]->id;

        //Busca as escola ativas do município
        $escolas = MethodsProfMunicipio::getEscolasMunicipio($municipio);

        //Busca as turmas ativas do municípios
        $turmas = MethodsProfMunicipio::getTurmasMunicipio($municipio);
        
        //Seta os Anos a serem utilizados na listagem
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o primeiro ano da listagem como padrão
        $ano = substr(trim($turmas[0]->DESCR_TURMA), 0, 2);

        //Define o município selecionado
        $municipio_selecionado = ComparativoMethodsGerais::getMunicipioSelecionadoComparativo($municipio);

        //Define a disciplina selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($disciplinas[0]->id);

        //Define a escola selecionada
        $escola_selecionada = ComparativoMethodsGerais::getEscolaSelecionadaComparativo($escolas[0]->id);

        //Reseta o nome da escola selecionada
        $escola_selecionada[0]->nome = null;

        //Busca as Habilidades pela Disciplina e Munícipio
        $habilidades = MethodsProfMunicipio::getHabilidades($disciplina_selecionada, $municipio);

        //Busca dados da Sessão de Disciplina
        $dados_comp_grafico_disciplina = MethodsProfMunicipio::estatisticaDisciplinas($municipio);
        $label_disc = $dados_comp_grafico_disciplina[0];
        $dados_disc = $dados_comp_grafico_disciplina[1];
        $itens_disc = $dados_comp_grafico_disciplina[2];
        $map_itens_disc = $dados_comp_grafico_disciplina[3];

        //Busca dados da Sessão de Temas
        $dados_comp_grafico_tema = MethodsProfMunicipio::estatisticaTemas($municipio, $disciplina_selecionada[0]->id, $ano);
        $label_tema = $dados_comp_grafico_tema[0];
        $dados_tema = $dados_comp_grafico_tema[1];
        $itens_tema = $dados_comp_grafico_tema[2];
        $map_itens_tema = $dados_comp_grafico_tema[3];

        //Busca dados da Sessão de Escolas
        $dados_comp_grafico_escola = MethodsProfMunicipio::estatisticaEscolas($municipio);
        $label_escola = $dados_comp_grafico_escola[0];
        $dados_escola = $dados_comp_grafico_escola[1];
        $itens_escola = $dados_comp_grafico_escola[2];
        $map_itens_escola = $dados_comp_grafico_escola[3];

        //Busca dados da Sessão de Escolas Disciplina
        $dados_comp_grafico_escola_disc = MethodsProfMunicipio::estatisticaEscolasDisciplina($municipio, $disciplina_selecionada[0]->id);
        $label_escola_disc = $dados_comp_grafico_escola_disc[0];
        $dados_escola_disc = $dados_comp_grafico_escola_disc[1];
        $itens_escola_disc = $dados_comp_grafico_escola_disc[2];
        $map_itens_escola_disc = $dados_comp_grafico_escola_disc[3];

        //Busca dados da Sessão de Ano Curricular Disciplina
        $dados_comp_grafico_curricular_disc = MethodsProfMunicipio::estatisticaCurricularDisciplina($municipio, $disciplina_selecionada[0]->id);
        $label_curricular_disc = $dados_comp_grafico_curricular_disc[0];
        $dados_curricular_disc = $dados_comp_grafico_curricular_disc[1];
        $itens_curricular_disc = $dados_comp_grafico_curricular_disc[2];
        $map_itens_curricular_disc = $dados_comp_grafico_curricular_disc[3];

        //Busca dados da Sessão de Habilidade Ano Disciplina
        $dados_comp_grafico_han_ano_disc = MethodsProfMunicipio::estatisticaHabilidadeAnoDisciplina($municipio, $disciplina_selecionada[0]->id, $ano);
        $label_hab_ano_disc = $dados_comp_grafico_han_ano_disc[0];
        $dados_hab_ano_disc = $dados_comp_grafico_han_ano_disc[1];
        $itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[2];
        $map_itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[3];
        $nome_hab = $dados_comp_grafico_han_ano_disc[4];

        $sessao_inicio = "municipio_comparativo";
              
        return view('comparativo/secretario/content/secretario', compact(
            'solRegistro','solAltCadastral','solAddTurma','sugestoes','escolas','municipios','destaques','municipio_selecionado','disciplinas','itens_tema','map_itens_tema',
            'disciplina_selecionada','escola_selecionada','anos','ano','habilidades','anos_same','ano_same_selecionado','label_disc','dados_disc','itens_disc','map_itens_disc',
            'label_tema','dados_tema','label_escola','dados_escola','label_escola_disc','dados_escola_disc','sessao_inicio','label_curricular_disc',
            'dados_curricular_disc','itens_curricular_disc','map_itens_curricular_disc','itens_escola_disc','map_itens_escola_disc','itens_escola','map_itens_escola',
            'label_hab_ano_disc','dados_hab_ano_disc','itens_hab_ano_disc','map_itens_hab_ano_disc','nome_hab'
        ));
    }

    /**
     * Show the application dashboard.
     * Método para disponibilização de página Inicial
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirMunicipioComparativo($id, $id_disciplina, $sessao)
    {
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
        $ano_same_selecionado = $anos_same[0]->SAME;

        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();

        //Lista os Munícipios
        $municipios = ComparativoMethodsGerais::getMunicipiosComparativo();

        //Lista as Disciplinas
        $disciplinas = MethodsGerais::getDisciplinas();

        //Busca as Sugestões
        $sugestoes = MethodsGerais::getSugestoes();

        //Caso seja Gestor busca as solicitações de seu munícpio
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();

        //Busca os destaques
        $destaques = MethodsGerais::getDestaques();

        //Busca o munícipio selecionado
        $municipio = $id;

        //Busca as escola ativas do município
        $escolas = MethodsProfMunicipio::getEscolasMunicipio($municipio);

        //Busca as turmas ativas do municípios
        $turmas = MethodsProfMunicipio::getTurmasMunicipio($municipio);

        //Seta os Anos a serem utilizados na listagem
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o primeiro ano da listagem como padrão
        $ano = substr(trim($turmas[0]->DESCR_TURMA), 0, 2);

        //Define o município selecionado
        $municipio_selecionado = ComparativoMethodsGerais::getMunicipioSelecionadoComparativo($municipio);

        //Define a disciplina selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($id_disciplina);

        //Define a escola selecionada
        $escola_selecionada = ComparativoMethodsGerais::getEscolaSelecionadaComparativo($escolas[0]->id);

        //Reseta o nome da escola selecionada
        $escola_selecionada[0]->nome = null;

        //Busca as Habilidades pela Disciplina e Munícipio
        $habilidades = MethodsProfMunicipio::getHabilidades($disciplina_selecionada[0]->id, $municipio);

        //Busca dados Sessão de Disciplinas
        $dados_comp_grafico_disciplina = MethodsProfMunicipio::estatisticaDisciplinas($municipio);
        $label_disc = $dados_comp_grafico_disciplina[0];
        $dados_disc = $dados_comp_grafico_disciplina[1];
        $itens_disc = $dados_comp_grafico_disciplina[2];
        $map_itens_disc = $dados_comp_grafico_disciplina[3];

        //Busca dados da Sessão de Temas
        $dados_comp_grafico_tema = MethodsProfMunicipio::estatisticaTemas($municipio, $disciplina_selecionada[0]->id, $ano);
        $label_tema = $dados_comp_grafico_tema[0];
        $dados_tema = $dados_comp_grafico_tema[1];
        $itens_tema = $dados_comp_grafico_tema[2];
        $map_itens_tema = $dados_comp_grafico_tema[3];

        //Busca dados da Sessão de Escolas
        $dados_comp_grafico_escola = MethodsProfMunicipio::estatisticaEscolas($municipio);
        $label_escola = $dados_comp_grafico_escola[0];
        $dados_escola = $dados_comp_grafico_escola[1];
        $itens_escola = $dados_comp_grafico_escola[2];
        $map_itens_escola = $dados_comp_grafico_escola[3];

        //Busca dados da Sessão de Escolas Disciplina
        $dados_comp_grafico_escola_disc = MethodsProfMunicipio::estatisticaEscolasDisciplina($municipio, $disciplina_selecionada[0]->id);
        $label_escola_disc = $dados_comp_grafico_escola_disc[0];
        $dados_escola_disc = $dados_comp_grafico_escola_disc[1];
        $itens_escola_disc = $dados_comp_grafico_escola_disc[2];
        $map_itens_escola_disc = $dados_comp_grafico_escola_disc[3];

        //Busca dados da Sessão de Ano Curricular Disciplina
        $dados_comp_grafico_curricular_disc = MethodsProfMunicipio::estatisticaCurricularDisciplina($municipio, $disciplina_selecionada[0]->id);
        $label_curricular_disc = $dados_comp_grafico_curricular_disc[0];
        $dados_curricular_disc = $dados_comp_grafico_curricular_disc[1];
        $itens_curricular_disc = $dados_comp_grafico_curricular_disc[2];
        $map_itens_curricular_disc = $dados_comp_grafico_curricular_disc[3];

        //Busca dados da Sessão de Habilidade Ano Disciplina
        $dados_comp_grafico_han_ano_disc = MethodsProfMunicipio::estatisticaHabilidadeAnoDisciplina($municipio, $disciplina_selecionada[0]->id, $ano);
        $label_hab_ano_disc = $dados_comp_grafico_han_ano_disc[0];
        $dados_hab_ano_disc = $dados_comp_grafico_han_ano_disc[1];
        $itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[2];
        $map_itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[3];
        $nome_hab = $dados_comp_grafico_han_ano_disc[4];

        $sessao_inicio = "";
        $sessao_inicio = $sessao;
              
        return view('comparativo/secretario/content/secretario', compact(
            'solRegistro','solAltCadastral','solAddTurma','sugestoes','escolas','municipios','destaques','municipio_selecionado','disciplinas','itens_tema','map_itens_tema',
            'disciplina_selecionada','escola_selecionada','anos','ano','habilidades','anos_same','ano_same_selecionado','label_disc','dados_disc','itens_disc','map_itens_disc',
            'label_tema','dados_tema','label_escola','dados_escola','label_escola_disc','dados_escola_disc','sessao_inicio','label_curricular_disc','itens_escola','map_itens_escola',
            'dados_curricular_disc','itens_escola_disc','map_itens_escola_disc','itens_curricular_disc','map_itens_curricular_disc','label_hab_ano_disc','dados_hab_ano_disc',
            'itens_hab_ano_disc','map_itens_hab_ano_disc','nome_hab'
        ));
    }

    /**
     * Show the application dashboard.
     * Método para disponibilização de página Inicial
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirMunicipioComparativoAno($id, $id_disciplina, $ano, $sessao)
    {
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
        $ano_same_selecionado = $anos_same[0]->SAME;

        //Busca os previlégios do Usuário Logado
        $previlegio = MethodsGerais::getPrevilegio();

        //Lista os Munícipios
        $municipios = ComparativoMethodsGerais::getMunicipiosComparativo();

        //Lista as Disciplinas
        $disciplinas = MethodsGerais::getDisciplinas();

        //Busca as Sugestões
        $sugestoes = MethodsGerais::getSugestoes();

        //Caso seja Gestor busca as solicitações de seu munícpio
        $solRegistro = MethodsGerais::getSolicitacaoRegistro();
        $solAltCadastral = MethodsGerais::getSolicitacaoAltCadastral();
        $solAddTurma = MethodsGerais::getSolicitacaoTurma();

        //Busca os destaques
        $destaques = MethodsGerais::getDestaques();

        //Busca o munícipio selecionado
        $municipio = $id;

        //Busca as escola ativas do município
        $escolas = MethodsProfMunicipio::getEscolasMunicipio($municipio);

        //Busca as turmas ativas do municípios
        $turmas = MethodsProfMunicipio::getTurmasMunicipio($municipio);

        //Seta os Anos a serem utilizados na listagem
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o primeiro ano da listagem como padrão
        $ano = $ano;

        //Define o município selecionado
        $municipio_selecionado = ComparativoMethodsGerais::getMunicipioSelecionadoComparativo($municipio);

        //Define a disciplina selecionada
        $disciplina_selecionada = MethodsGerais::getDisciplinaSelecionada($id_disciplina);

        //Define a escola selecionada
        $escola_selecionada = ComparativoMethodsGerais::getEscolaSelecionadaComparativo($escolas[0]->id);

        //Reseta o nome da escola selecionada
        $escola_selecionada[0]->nome = null;

        //Busca as Habilidades pela Disciplina e Munícipio
        $habilidades = MethodsProfMunicipio::getHabilidades($disciplina_selecionada, $municipio);

        //Busca dados Sessão de Disciplinas
        $dados_comp_grafico_disciplina = MethodsProfMunicipio::estatisticaDisciplinas($municipio);
        $label_disc = $dados_comp_grafico_disciplina[0];
        $dados_disc = $dados_comp_grafico_disciplina[1];
        $itens_disc = $dados_comp_grafico_disciplina[2];
        $map_itens_disc = $dados_comp_grafico_disciplina[3];

        //Busca dados da Sessão de Temas
        $dados_comp_grafico_tema = MethodsProfMunicipio::estatisticaTemas($municipio, $disciplina_selecionada[0]->id, $ano);
        $label_tema = $dados_comp_grafico_tema[0];
        $dados_tema = $dados_comp_grafico_tema[1];
        $itens_tema = $dados_comp_grafico_tema[2];
        $map_itens_tema = $dados_comp_grafico_tema[3];

        //Busca dados da Sessão de Escolas
        $dados_comp_grafico_escola = MethodsProfMunicipio::estatisticaEscolas($municipio);
        $label_escola = $dados_comp_grafico_escola[0];
        $dados_escola = $dados_comp_grafico_escola[1];
        $itens_escola = $dados_comp_grafico_escola[2];
        $map_itens_escola = $dados_comp_grafico_escola[3];

        //Busca dados da Sessão de Escolas Disciplina
        $dados_comp_grafico_escola_disc = MethodsProfMunicipio::estatisticaEscolasDisciplina($municipio, $disciplina_selecionada[0]->id);
        $label_escola_disc = $dados_comp_grafico_escola_disc[0];
        $dados_escola_disc = $dados_comp_grafico_escola_disc[1];
        $itens_escola_disc = $dados_comp_grafico_escola_disc[2];
        $map_itens_escola_disc = $dados_comp_grafico_escola_disc[3];

        //Busca dados da Sessão de Ano Curricular Disciplina
        $dados_comp_grafico_curricular_disc = MethodsProfMunicipio::estatisticaCurricularDisciplina($municipio, $disciplina_selecionada[0]->id);
        $label_curricular_disc = $dados_comp_grafico_curricular_disc[0];
        $dados_curricular_disc = $dados_comp_grafico_curricular_disc[1];
        $itens_curricular_disc = $dados_comp_grafico_curricular_disc[2];
        $map_itens_curricular_disc = $dados_comp_grafico_curricular_disc[3];

        //Busca dados da Sessão de Habilidade Ano Disciplina
        $dados_comp_grafico_han_ano_disc = MethodsProfMunicipio::estatisticaHabilidadeAnoDisciplina($municipio, $disciplina_selecionada[0]->id, $ano);
        $label_hab_ano_disc = $dados_comp_grafico_han_ano_disc[0];
        $dados_hab_ano_disc = $dados_comp_grafico_han_ano_disc[1];
        $itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[2];
        $map_itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[3];
        $nome_hab = $dados_comp_grafico_han_ano_disc[4];

        $sessao_inicio = "";
        $sessao_inicio = $sessao;
              
        return view('comparativo/secretario/content/secretario', compact(
            'solRegistro','solAltCadastral','solAddTurma','sugestoes','escolas','municipios','destaques','municipio_selecionado','disciplinas','itens_tema','map_itens_tema',
            'disciplina_selecionada','escola_selecionada','anos','ano','habilidades','anos_same','ano_same_selecionado','label_disc','dados_disc','itens_disc','map_itens_disc',
            'label_tema','dados_tema','label_escola','dados_escola','label_escola_disc','dados_escola_disc','sessao_inicio','label_curricular_disc','itens_escola','map_itens_escola',
            'dados_curricular_disc','itens_escola_disc','map_itens_escola_disc','itens_curricular_disc','map_itens_curricular_disc','label_hab_ano_disc','dados_hab_ano_disc',
            'itens_hab_ano_disc','map_itens_hab_ano_disc','nome_hab'
        ));
    }
   
}



