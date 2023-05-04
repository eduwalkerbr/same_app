<?php

namespace App\Http\Controllers\cadastros\manutencao;

use App\Http\Controllers\Controller;
use App\Http\Controllers\staticmethods\comparativo\MethodsGerais;
use App\Http\Controllers\staticmethods\comparativo\MethodsProfMunicipio;
use App\Http\Controllers\staticmethods\gerais\MethodsGerais as GeraisMethodsGerais;

class CacheCompMunicipioController extends Controller
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
     * Método que carrega os dados da Cache de Munícipio Disciplina
     */
    public function carregarDisciplinaMunicipio(){

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = MethodsGerais::getMunicipiosComparativo();

        //Carrega os dados do Município
        foreach($municipios as $municipio){

            MethodsGerais::getMunicipioSelecionadoComparativo($municipio->id);
            MethodsProfMunicipio::estatisticaDisciplinas($municipio->id);

        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Município Disciplina carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio Tema
     */
    public function carregarTemaMunicipio(){

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = MethodsGerais::getMunicipiosComparativo();

        //Lista as Disciplinas
        $disciplinas = GeraisMethodsGerais::getDisciplinas();

        foreach($municipios as $municipio){
            MethodsGerais::getMunicipioSelecionadoComparativo($municipio->id);

            $turmas = MethodsProfMunicipio::getTurmasMunicipio($municipio);
            $anos = [];
            for ($i = 0; $i < sizeof($turmas); $i++) {
                if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                    $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
                }
            }

            foreach($disciplinas as $disciplina){

                GeraisMethodsGerais::getDisciplinaSelecionada($disciplina->id);

                foreach($anos as $ano){

                    $ano = intval($ano);
                    MethodsProfMunicipio::estatisticaTemas($municipio, $disciplina->id, $ano);

                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Município Tema carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio Escola
     */
    public function carregarEscolaMunicipio(){

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = MethodsGerais::getMunicipiosComparativo();

        //Carrega os dados do Município
        foreach($municipios as $municipio){

            MethodsGerais::getMunicipioSelecionadoComparativo($municipio->id);
            MethodsProfMunicipio::estatisticaEscolas($municipio->id);

        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Município Escola carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio Escola Disciplina
     */
    public function carregarEscolaDisciplinaMunicipio(){

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = MethodsGerais::getMunicipiosComparativo();

        //Lista as Disciplinas
        $disciplinas = GeraisMethodsGerais::getDisciplinas();

        //Carrega os dados do Município
        foreach($municipios as $municipio){
            MethodsGerais::getMunicipioSelecionadoComparativo($municipio->id);

            foreach($disciplinas as $disciplina){

                GeraisMethodsGerais::getDisciplinaSelecionada($disciplina->id);
                MethodsProfMunicipio::estatisticaEscolasDisciplina($municipio->id, $disciplina->id);

            }

        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Município Escola Disciplina carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio Ano Curricular Disciplina
     */
    public function carregarAnoCurricularDisciplinaMunicipio(){

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = MethodsGerais::getMunicipiosComparativo();

        //Lista as Disciplinas
        $disciplinas = GeraisMethodsGerais::getDisciplinas();

        //Carrega os dados do Município
        foreach($municipios as $municipio){
            MethodsGerais::getMunicipioSelecionadoComparativo($municipio->id);

            foreach($disciplinas as $disciplina){

                GeraisMethodsGerais::getDisciplinaSelecionada($disciplina->id);
                MethodsProfMunicipio::estatisticaCurricularDisciplina($municipio->id, $disciplina->id);

            }

        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Município Ano Curricular Disciplina carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio
     */
    public function carregarHabAnosDisciplinaMunicipio(){

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = MethodsGerais::getMunicipiosComparativo();

        //Lista as Disciplinas
        $disciplinas = GeraisMethodsGerais::getDisciplinas();

        foreach($municipios as $municipio){
            MethodsGerais::getMunicipioSelecionadoComparativo($municipio->id);
            $turmas = MethodsProfMunicipio::getTurmasMunicipio($municipio);
            $anos = [];
            for ($i = 0; $i < sizeof($turmas); $i++) {
                if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                    $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
                }
            }

            foreach($disciplinas as $disciplina){
                GeraisMethodsGerais::getDisciplinaSelecionada($disciplina->id);

                foreach($anos as $ano){

                    $ano = intval($ano);

                    MethodsProfMunicipio::estatisticaHabilidadeAnoDisciplina($municipio->id, $disciplina->id, $ano);

                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Município Habilidade por Anos carregada com Sucesso!');
    }

}
