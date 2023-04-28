<?php

namespace App\Http\Controllers\cadastros\manutencao;

use App\Http\Controllers\Controller;
use App\Http\Controllers\staticmethods\gerais\MethodsGerais;
use App\Http\Controllers\staticmethods\proficiencia\MethodsProfTurma;

class CacheTurmaController extends Controller
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
     * Carrega Cache Dados Base Turma
     */
    public function cacheDadosBase(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();

        //Busca os previlégios do Usuário Logado
        MethodsGerais::getPrevilegio();

        //Listagem de Disciplinas
        $disciplinas = MethodsGerais::getDisciplinas();
        foreach($disciplinas as $disciplina){
            MethodsGerais::getDisciplinaSelecionada($disciplina->id);
        }

        //Lista os Munícipios por Ano SAME
        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){
                MethodsGerais::getMunicipioSelecionado($municipio->id, $ano_same->SAME);
                $escolas = MethodsProfTurma::getEscolasProfessor($municipio->id,null,$ano_same->SAME);
                foreach($escolas as $escola){
                    MethodsGerais::getEscolaSelecionada($escola->id, $ano_same->SAME);

                    //Busca e carregar as Turmas Ativas do Município
                    $turmas = MethodsProfTurma::getTurmasProfessor($escola->id, $ano_same->SAME);
                    foreach($turmas as $turma){
                        $turma_selecionada = MethodsGerais::getTurmaSelecionada($turma->id, $ano_same->SAME);
                        
                        foreach($disciplinas as $disciplina){
                            MethodsGerais::getCriteriosQuestao(substr($turma_selecionada[0]->DESCR_TURMA, 1, 1),$disciplina->id);
                        }
                    }
                }
            }
        }

        //Busca as Questões
        MethodsGerais::getQuestoes();

        //Busca as Legendas
        MethodsGerais::getLegendas();

        return redirect()->route('lista_manutencao')->with('status', 'Cache Turma Sessão Dados Base carregada com Sucesso!'); 
    }

    /**
     * Carrega Cache Dados Média Turma
     */
    public function cacheMediaTurma(){

        set_time_limit(0);
        $anos_same = MethodsGerais::getAnosSAME();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);

            foreach($municipios as $municipio){
                $escolas = MethodsProfTurma::getEscolasProfessor($municipio->id,null,$ano_same->SAME);
                foreach($escolas as $escola){ 
                    $turmas = MethodsProfTurma::getTurmasProfessor($escola->id, $ano_same->SAME);
                    foreach($turmas as $turma){
                        $ano = substr(trim($turma->DESCR_TURMA), 0, 2);

                        MethodsProfTurma::estatisticaBaseTurma($turma->id,$ano,$ano_same->SAME);

                        MethodsProfTurma::estatisticaComparacaoTurma($turma->id,$ano,$ano_same->SAME);
                        
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Turma Sessão Média carregada com Sucesso!'); 
    }

    /**
     * Carrega Cache Dados Tema Turma
     */
    public function cacheTemaTurma(){

        set_time_limit(0);
        $anos_same = MethodsGerais::getAnosSAME();

        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);

            foreach($municipios as $municipio){
                $escolas = MethodsProfTurma::getEscolasProfessor($municipio->id,null,$ano_same->SAME);
                foreach($escolas as $escola){ 
                    $turmas = MethodsProfTurma::getTurmasProfessor($escola->id, $ano_same->SAME);
                    foreach($turmas as $turma){
                        foreach($disciplinas as $disciplina){
                            MethodsProfTurma::estatisticaBaseGrafico($turma->id, $disciplina->id, $ano_same->SAME);
                            $habilidades = MethodsProfTurma::getHabilidadesProfessor($disciplina->id, $turma->id, $ano_same->SAME);
                            foreach($habilidades as $habilidade){
                                MethodsGerais::getHabilidadeSelecionada($habilidade->id_habilidade);
                            }
                        }
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Turma Sessão Tema carregada com Sucesso!'); 
    }

    /**
     * Carrega Cache Dados Habilidade Matemática Turma
     */
    public function cacheHabilidadeMatTurma(){

        set_time_limit(0);
        $anos_same = MethodsGerais::getAnosSAME();

        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);

            foreach($municipios as $municipio){
                $escolas = MethodsProfTurma::getEscolasProfessor($municipio->id,null,$ano_same->SAME);
                foreach($escolas as $escola){ 
                    $turmas = MethodsProfTurma::getTurmasProfessor($escola->id, $ano_same->SAME);
                    foreach($turmas as $turma){
                        MethodsProfTurma::estatisticaHabilidadeDisciplinaGrafico($turma->id, $disciplinas[0]->id, $ano_same->SAME);
                        MethodsProfTurma::estatisticaAjustePercentualBase($turma->id, $disciplinas[0]->id, $ano_same->SAME);
                        //Busca dados das Questões da Sessão Habilidade Disciplina
                        MethodsProfTurma::estatisticaDisciplinaQuestao($turma->id, $disciplinas[0]->id, $ano_same->SAME);
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Turma Sessão Habilidades Matemática carregada com Sucesso!'); 
    }

    /**
     * Carrega Cache Dados Habilidade Portugûes Turma
     */
    public function cacheHabilidadePortTurma(){

        set_time_limit(0);
        $anos_same = MethodsGerais::getAnosSAME();

        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);

            foreach($municipios as $municipio){
                $escolas = MethodsProfTurma::getEscolasProfessor($municipio->id,null,$ano_same->SAME);
                foreach($escolas as $escola){ 
                    $turmas = MethodsProfTurma::getTurmasProfessor($escola->id, $ano_same->SAME);
                    foreach($turmas as $turma){
                        MethodsProfTurma::estatisticaHabilidadeDisciplinaGrafico($turma->id, $disciplinas[1]->id, $ano_same->SAME);
                        MethodsProfTurma::estatisticaAjustePercentualBase($turma->id, $disciplinas[1]->id, $ano_same->SAME);
                        //Busca dados das Questões da Sessão Habilidade Disciplina
                        MethodsProfTurma::estatisticaDisciplinaQuestao($turma->id, $disciplinas[1]->id, $ano_same->SAME);
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Turma Sessão Habilidades Português carregada com Sucesso!'); 
    }

    /**
     * Carrega Cache Dados Habilidade Ano Matemática Turma
     */
    public function cacheHabilidadeAnoMatTurma(){

        set_time_limit(0);
        $anos_same = MethodsGerais::getAnosSAME();

        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);

            foreach($municipios as $municipio){
                $escolas = MethodsProfTurma::getEscolasProfessor($municipio->id,null,$ano_same->SAME);
                foreach($escolas as $escola){ 
                    $turmas = MethodsProfTurma::getTurmasProfessor($escola->id, $ano_same->SAME);
                    $anos = [];
                    for ($i = 0; $i < sizeof($turmas); $i++) {
                        if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                            $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
                        }
                    }
                    foreach($anos as $ano){
                        $ano = intval($ano);

                        MethodsProfTurma::estatisticaHabilidadeDisciplinaAnoGrafico($escola->id, $disciplinas[0]->id,$ano,$ano_same->SAME);

                        MethodsProfTurma::estatisticaAjustePercentual($escola->id, $disciplinas[0]->id,$ano,$ano_same->SAME);

                        MethodsProfTurma::estatisticaHabilidadeAnoQuestao($escola->id, $disciplinas[0]->id,$ano,$ano_same->SAME);
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Turma Sessão Habilidades por Ano na Matemática carregada com Sucesso!'); 
    }

    /**
     * Carrega Cache Dados Habilidade Ano Matemática Turma
     */
    public function cacheHabilidadeAnoPortTurma(){

        set_time_limit(0);
        $anos_same = MethodsGerais::getAnosSAME();

        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            foreach($municipios as $municipio){
                $escolas = MethodsProfTurma::getEscolasProfessor($municipio->id,null,$ano_same->SAME);
                foreach($escolas as $escola){ 
                    $turmas = MethodsProfTurma::getTurmasProfessor($escola->id, $ano_same->SAME);
                    $anos = [];
                    for ($i = 0; $i < sizeof($turmas); $i++) {
                        if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                            $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
                        }
                    }
                    
                    foreach($anos as $ano){
                        
                        $ano = intval($ano);

                        MethodsProfTurma::estatisticaHabilidadeDisciplinaAnoGrafico($escola->id, $disciplinas[1]->id,$ano,$ano_same->SAME);

                        MethodsProfTurma::estatisticaAjustePercentual($escola->id, $disciplinas[1]->id,$ano,$ano_same->SAME);

                        MethodsProfTurma::estatisticaHabilidadeAnoQuestao($escola->id, $disciplinas[1]->id,$ano,$ano_same->SAME);
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Turma Sessão Habilidades por Ano no Português carregada com Sucesso!'); 
    }

    /**
     * Carrega Cache Dados Habilidade Ano Matemática Turma
     */
    public function cacheHabilidadeSelMatTurma(){

        set_time_limit(0);
        $anos_same = MethodsGerais::getAnosSAME();

        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            foreach($municipios as $municipio){
                $escolas = MethodsProfTurma::getEscolasProfessor($municipio->id,null,$ano_same->SAME);
                foreach($escolas as $escola){ 
                    $turmas = MethodsProfTurma::getTurmasProfessor($escola->id, $ano_same->SAME);
                    foreach($turmas as $turma){
                        $habilidades = MethodsProfTurma::getHabilidadesProfessor($disciplinas[0]->id, $turma->id, $ano_same->SAME);
                        foreach($habilidades as $habilidade){
                            MethodsProfTurma::estatisticaHabilidadeDisciplinaHabilidade($escola->id, $disciplinas[0]->id, $habilidade->id_habilidade, $ano_same->SAME);
                            MethodsProfTurma::estatisticaAjustePercentualAno($escola->id, $disciplinas[0]->id, $habilidade->id_habilidade, $ano_same->SAME);
                            MethodsProfTurma::estatisticaBaseHabilidadeQuestaoHabilidade($escola->id, $disciplinas[0]->id, $habilidade->id_habilidade,$ano_same->SAME);
                        }
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Turma Sessão Habilidade Selecionada na Matemática carregada com Sucesso!'); 
    }

    /**
     * Carrega Cache Dados Habilidade Ano Português Turma
     */
    public function cacheHabilidadeSelPortTurma(){

        set_time_limit(0);
        $anos_same = MethodsGerais::getAnosSAME();

        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            foreach($municipios as $municipio){
                $escolas = MethodsProfTurma::getEscolasProfessor($municipio->id,null,$ano_same->SAME);
                foreach($escolas as $escola){ 
                    $turmas = MethodsProfTurma::getTurmasProfessor($escola->id, $ano_same->SAME);
                    foreach($turmas as $turma){
                        $habilidades = MethodsProfTurma::getHabilidadesProfessor($disciplinas[1]->id, $turma->id, $ano_same->SAME);
                        foreach($habilidades as $habilidade){
                            MethodsProfTurma::estatisticaHabilidadeDisciplinaHabilidade($escola->id, $disciplinas[1]->id, $habilidade->id_habilidade, $ano_same->SAME);
                            MethodsProfTurma::estatisticaAjustePercentualAno($escola->id, $disciplinas[1]->id, $habilidade->id_habilidade, $ano_same->SAME);
                            MethodsProfTurma::estatisticaBaseHabilidadeQuestaoHabilidade($escola->id, $disciplinas[1]->id, $habilidade->id_habilidade,$ano_same->SAME);
                        }
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Turma Sessão Habilidade Selecionada no Português carregada com Sucesso!'); 
    }

    /**
     * Carrega Cache Dados Questões Disciplina Matemática Turma
     */
    public function cacheQuestaoMatTurma(){

        set_time_limit(0);
        $anos_same = MethodsGerais::getAnosSAME();

        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            foreach($municipios as $municipio){
                $escolas = MethodsProfTurma::getEscolasProfessor($municipio->id,null,$ano_same->SAME);
                foreach($escolas as $escola){ 
                    $turmas = MethodsProfTurma::getTurmasProfessor($escola->id, $ano_same->SAME);
                    foreach($turmas as $turma){
                        MethodsProfTurma::estatisticaQuestaoGraficoDisciplina($turma->id,$disciplinas[0]->id, $ano_same->SAME);
                        MethodsProfTurma::estatisticaAjustePercentualQuestao($turma->id,$disciplinas[0]->id, $ano_same->SAME);
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Turma Sessão Questões na Matemática carregada com Sucesso!'); 
    }

    /**
     * Carrega Cache Dados Questões Disciplina Português Turma
     */
    public function cacheQuestaoPortTurma(){

        set_time_limit(0);
        $anos_same = MethodsGerais::getAnosSAME();

        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            foreach($municipios as $municipio){
                $escolas = MethodsProfTurma::getEscolasProfessor($municipio->id,null,$ano_same->SAME);
                foreach($escolas as $escola){ 
                    $turmas = MethodsProfTurma::getTurmasProfessor($escola->id, $ano_same->SAME);
                    foreach($turmas as $turma){
                        MethodsProfTurma::estatisticaQuestaoGraficoDisciplina($turma->id,$disciplinas[1]->id, $ano_same->SAME);
                        MethodsProfTurma::estatisticaAjustePercentualQuestao($turma->id,$disciplinas[1]->id, $ano_same->SAME);
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Turma Sessão Questões no Português carregada com Sucesso!'); 
    }

    /**
     * Carrega Cache Dados Alunos Turma
     */
    public function cacheAlunosTurma(){

        set_time_limit(0);
        $anos_same = MethodsGerais::getAnosSAME();

        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            foreach($municipios as $municipio){
                $escolas = MethodsProfTurma::getEscolasProfessor($municipio->id,null,$ano_same->SAME);
                foreach($escolas as $escola){ 
                    $turmas = MethodsProfTurma::getTurmasProfessor($escola->id, $ano_same->SAME);
                    foreach($turmas as $turma){
                        foreach($disciplinas as $disciplina){
                            MethodsProfTurma::estatisticaBaseAlunoGraficoDisciplina($turma->id, $disciplina->id, $ano_same->SAME);
                        }
                    }
                }
            }
        }
        return redirect()->route('lista_manutencao')->with('status', 'Cache Turma Sessão Alunos carregada com Sucesso!'); 
    }
}
