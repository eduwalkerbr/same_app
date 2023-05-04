<?php

namespace App\Http\Controllers\cadastros\manutencao;

use App\Http\Controllers\Controller;
use App\Http\Controllers\staticmethods\comparativo\MethodsGerais as ComparativoMethodsGerais;
use App\Http\Controllers\staticmethods\comparativo\MethodsProfEscola;
use App\Http\Controllers\staticmethods\gerais\MethodsGerais;

class CacheCompEscolaController extends Controller
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
     * Método que carrega os dados da Cache de Escola Disciplina
     */
    public function carregarDisciplinaEscola()
    {

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = ComparativoMethodsGerais::getMunicipiosComparativo();

        //Carrega os dados do Município
        foreach ($municipios as $municipio) {

            $escolas = MethodsProfEscola::getEscolasDiretor($municipio->id);

            foreach($escolas as $escola){

                ComparativoMethodsGerais::getEscolaSelecionadaComparativo($escola->id);
                MethodsProfEscola::estatisticaDisciplinas($escola->id);

            }

        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Escola Disciplina carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio Tema
     */
    public function carregarTemaEscola()
    {

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = ComparativoMethodsGerais::getMunicipiosComparativo();

        //Lista as Disciplinas
        $disciplinas = MethodsGerais::getDisciplinas();

        foreach ($municipios as $municipio) {

            ComparativoMethodsGerais::getMunicipioSelecionadoComparativo($municipio->id);
            $escolas = MethodsProfEscola::getEscolasDiretor($municipio->id);

            foreach($escolas as $escola){
                ComparativoMethodsGerais::getEscolaSelecionadaComparativo($escola->id);
                $turmas = MethodsProfEscola::getTurmasEscola($escola->id);
                $anos = [];
                for ($i = 0; $i < sizeof($turmas); $i++) {
                    if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                        $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
                    }
                }

                foreach ($disciplinas as $disciplina) {
                    MethodsGerais::getDisciplinaSelecionada($disciplina->id);
    
                    foreach ($anos as $ano) {
    
                        $ano = intval($ano);
    
                        MethodsProfEscola::estatisticaTemas($escola->id, $disciplina->id, $ano);
                    }
                }

            }

        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Escola Tema carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Escola Ano Curricular Disciplina
     */
    public function carregarAnoCurricularDisciplinaEscola()
    {

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = ComparativoMethodsGerais::getMunicipiosComparativo();
        $disciplinas = MethodsGerais::getDisciplinas();

        //Carrega os dados do Município
        foreach ($municipios as $municipio) {

            $escolas = MethodsProfEscola::getEscolasDiretor($municipio->id);

            foreach($escolas as $escola){

                ComparativoMethodsGerais::getEscolaSelecionadaComparativo($escola->id);

                foreach($disciplinas as $disciplina){

                    MethodsGerais::getDisciplinaSelecionada($disciplina->id);
                    MethodsProfEscola::estatisticaCurricularDisciplina($escola->id, $disciplina->id);

                }

            }

        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Escola Ano Curricular Disciplina carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Escola Ano Curricular Disciplina
     */
    public function carregarTurmaDisciplinaEscola()
    {

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = ComparativoMethodsGerais::getMunicipiosComparativo();
        $disciplinas = MethodsGerais::getDisciplinas();

        //Carrega os dados do Município
        foreach ($municipios as $municipio) {

            $escolas = MethodsProfEscola::getEscolasDiretor($municipio->id);

            foreach($escolas as $escola){

                ComparativoMethodsGerais::getEscolaSelecionadaComparativo($escola->id);

                foreach($disciplinas as $disciplina){

                    MethodsGerais::getDisciplinaSelecionada($disciplina->id);
                    MethodsProfEscola::estatisticaTurmaDisciplina($escola->id, $disciplina->id);

                }

            }

        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Escola Turma Disciplina carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio
     */
    public function carregarHabAnosDisciplinaEscola()
    {

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = ComparativoMethodsGerais::getMunicipiosComparativo();

        //Lista as Disciplinas
        $disciplinas = MethodsGerais::getDisciplinas();

        foreach ($municipios as $municipio) {

            ComparativoMethodsGerais::getMunicipioSelecionadoComparativo($municipio->id);
            $escolas = MethodsProfEscola::getEscolasDiretor($municipio->id);

            foreach($escolas as $escola){

                ComparativoMethodsGerais::getEscolaSelecionadaComparativo($escola->id);
                $turmas = MethodsProfEscola::getTurmasEscola($escola->id);
                $anos = [];
                for ($i = 0; $i < sizeof($turmas); $i++) {
                    if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                        $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
                    }
                }

                foreach ($disciplinas as $disciplina) {

                    MethodsGerais::getDisciplinaSelecionada($disciplina->id);
    
                    foreach ($anos as $ano) {
    
                        $ano = intval($ano);
    
                        MethodsProfEscola::estatisticaHabilidadeAnoDisciplina($escola->id, $disciplina->id, $ano);
                    }
                }

            }

        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Escola Habilidade por Anos carregada com Sucesso!');
    }
}
