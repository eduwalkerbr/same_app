<?php

namespace App\Http\Controllers\cadastros\manutencao;

use App\Http\Controllers\Controller;
use App\Http\Controllers\staticmethods\gerais\MethodsGerais;
use App\Http\Controllers\staticmethods\proficiencia\MethodsProfEscola;

class CacheEscolaController extends Controller
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
     * Método que carrega em Cache Dados de Escola
     */
    public Function carregarCacheEscDadosBase(){

        set_time_limit(0);
        //Busca os previlégios do Usuário Logado
        MethodsGerais::getPrevilegio();

        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();

        //Busca as Legendas
        MethodsGerais::getLegendas();

        //Listage, de Direção Professor utilizando Cache
        foreach($anos_same as $ano_same){
            MethodsProfEscola::getDirecaoProfessor($ano_same->SAME);
        }

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        //Lista as Disciplinas em Geral
        $disciplinas = MethodsGerais::getDisciplinas();
        foreach($disciplinas as $disciplina){
            //Carrega os dados das Disciplinas
            MethodsGerais::getDisciplinaSelecionada($disciplina->id);
        }

        //------------------------------------------- Municípios -----------------------------------------------------------------
        //Lista os Munícipios por Ano SAME
        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){
                MethodsGerais::getMunicipioSelecionado($municipio->id, $ano_same->SAME);
                //Busca e carrega as Escolas Ativas do Munícipio
                $escolas = MethodsProfEscola::getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){
                    MethodsGerais::getEscolaSelecionada($escola->id, $ano_same->SAME);
                    
                    //Busca e carregar as Turmas Ativas do Município
                    MethodsProfEscola::getTurmasEscola($escola->id, $ano_same->SAME);

                    foreach($disciplinas as $disciplina){
                        //Buscas as Habilidades
                        $habilidades = MethodsProfEscola::getHabilidadesEscola($disciplina->id, $escola->id);
                        foreach($habilidades as $habilidade){
                            //Busca os dados da Habilidade Selecionda    
                            MethodsGerais::getHabilidadeSelecionada($habilidade->id_habilidade);
                        } 
                    }
                }
            }
        }

        //Busca todos os Critérios
        MethodsGerais::getCriterios();

        return redirect()->route('lista_manutencao')->with('status', 'Cache Escola Dados Básicos carregados com Sucesso!');
    }

    /**
     * Método que carrega em Cache Dados de Escola
     */
    public Function carregarCacheEscSesMediaEscola(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){    
                $escolas = MethodsProfEscola::getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){    
                    //Busca dados Sessão Base de Escola --------------------------------------------------------------
                    MethodsProfEscola::estatisticaEscola($escola->id, $ano_same->SAME);
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Escola Sessão Média Escolas carregada com Sucesso!');
        
    }

    /**
     * Método que carrega em Cache Dados de Escola
     */
    public Function carregarCacheEscSesCompDisc(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){    
                $escolas = MethodsProfEscola::getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){    

                    //Busca dados da Sessão de Comparativo entre a Escola Selecionado e as Demais --------------------
                    MethodsProfEscola::estatisticaComparacaoEscola($escola->id, $ano_same->SAME);
                    
                    //Busca Dados para Sessão de Proficiência da Escola pela Disciplina ------------------------------
                    MethodsProfEscola::estatisticaGraficoDisciplina($escola->id, $ano_same->SAME);
                }
                
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Escola Sessões Comparação e Disciplina carregadas com Sucesso!');
    }

    /**
     * Método que carrega em Cache Dados de Escola
     */
    public Function carregarCacheEscAnoCurTurmas(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){    
                $escolas = MethodsProfEscola::getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){    
                    foreach($disciplinas as $disciplina){
                        //Busca Dados para Sessão de Ano Curricular Disciplina -------------------------------------------
                        MethodsProfEscola::estatisticaDisciplinaGrafico($escola->id,$disciplina->id, $ano_same->SAME);

                        //Busca os dados para Sessão de Turmas da Escola na Disciplina -----------------------------------
                        MethodsProfEscola::estatisticaTurmaDisciplinaGrafico($escola->id,$disciplina->id, $ano_same->SAME);
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Escola Sessões Anos Curriculares e Turmas carregada com Sucesso!');
    }

    /**
     * Método que carrega em Cache Dados de Escola
     */
    public Function carregarCacheEscSesAnoHabMat(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){    
                $escolas = MethodsProfEscola::getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){
                    $turmasListadas = MethodsProfEscola::getTurmasEscola($escola->id, $ano_same->SAME);
                    $anos = [];
                    for ($i = 0; $i < sizeof($turmasListadas); $i++) {
                        if (!in_array(substr(trim($turmasListadas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                            $anos[$i] = substr(trim($turmasListadas[$i]->DESCR_TURMA), 0, 2);
                        }
                    }
                    foreach($anos as $ano){
                        $ano = intval($ano);
                        //Busca Dados da Sessão de Habilidades por Ano da Escola na Disciplina ---------------------------
                        MethodsProfEscola::estatisticaHabilidadeDisciplinaGrafico($escola->id, $disciplinas[0]->id, $ano, $ano_same->SAME);
                        MethodsProfEscola::estatisticaAjustePercentual($escola->id, $disciplinas[0]->id, $ano, $ano_same->SAME);
                        //Busca dados das Questões para gerar os Modais de Habilidade por Ano
                        MethodsProfEscola::estatisticaHabilidadeAnoQuestao($escola->id, $disciplinas[0]->id, $ano, $ano_same->SAME);

                        //Busca os Critérios de Acordo com Ano e Disciplina
                        MethodsGerais::getCriteriosQuestao($ano, $disciplinas[0]->id);
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Escola Sessão Habilidades por Ano em Matemática carregada com Sucesso!'); 
        
    }

    /**
     * Método que carrega em Cache Dados de Escola
     */
    public Function carregarCacheEscSesAnoHabPort(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){    
                $escolas = MethodsProfEscola::getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){
                    $turmasListadas = MethodsProfEscola::getTurmasEscola($escola->id, $ano_same->SAME);
                    $anos = [];
                    for ($i = 0; $i < sizeof($turmasListadas); $i++) {
                        if (!in_array(substr(trim($turmasListadas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                            $anos[$i] = substr(trim($turmasListadas[$i]->DESCR_TURMA), 0, 2);
                        }
                    }
                    foreach($anos as $ano){
                        $ano = intval($ano);
                        //Busca Dados da Sessão de Habilidades por Ano da Escola na Disciplina ---------------------------
                        MethodsProfEscola::estatisticaHabilidadeDisciplinaGrafico($escola->id, $disciplinas[1]->id, $ano, $ano_same->SAME);
                        MethodsProfEscola::estatisticaAjustePercentual($escola->id, $disciplinas[1]->id, $ano, $ano_same->SAME);
                        //Busca dados das Questões para gerar os Modais de Habilidade por Ano
                        MethodsProfEscola::estatisticaHabilidadeAnoQuestao($escola->id, $disciplinas[1]->id, $ano, $ano_same->SAME);

                        //Busca os Critérios de Acordo com Ano e Disciplina
                        MethodsGerais::getCriteriosQuestao($ano, $disciplinas[1]->id);
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Escola Sessão Habilidades por Ano em Português carregada com Sucesso!'); 
        
    }

    /**
     * Método que carrega em Cache Dados de Escola
     */
    public Function carregarCacheEscSesHabAnoMat(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){    
                $escolas = MethodsProfEscola::getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){
                    //Buscas as Habilidades
                    $habilidades = MethodsProfEscola::getHabilidadesEscola($disciplinas[0]->id, $escola->id);
                    foreach($habilidades as $habilidade){
                        //Busca Dados da Sessão de Habilidade Individual pelos Anos --------------------------------------
                        MethodsProfEscola::estatisticaEscolaDisciplinaHabilidade($escola->id, $disciplinas[0]->id, $habilidade->id_habilidade, $ano_same->SAME);   
                        MethodsProfEscola::estatisticaPercentualAno($escola->id, $disciplinas[0]->id, $habilidade->id_habilidade, $ano_same->SAME);
                        //Busca os dados das Questões para Gerar os Modais de Habilidade Individual
                        MethodsProfEscola::estatisticaHabilidadeQuestao($escola->id, $disciplinas[0]->id,$habilidade->id_habilidade, $ano_same->SAME); 
                    }
                }
            }
        }  

        return redirect()->route('lista_manutencao')->with('status', 'Cache Escola Sessão Habilidade transcorrer Anos Matemática carregada com Sucesso!');       
    }

    /**
     * Método que carrega em Cache Dados de Escola
     */
    public Function carregarCacheEscSesHabAnoPort(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = MethodsGerais::getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){    
                $escolas = MethodsProfEscola::getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){
                    //Buscas as Habilidades
                    $habilidades = MethodsProfEscola::getHabilidadesEscola($disciplinas[1]->id, $escola->id);
                    foreach($habilidades as $habilidade){
                        //Busca Dados da Sessão de Habilidade Individual pelos Anos --------------------------------------
                        MethodsProfEscola::estatisticaEscolaDisciplinaHabilidade($escola->id, $disciplinas[1]->id, $habilidade->id_habilidade, $ano_same->SAME);   
                        MethodsProfEscola::estatisticaPercentualAno($escola->id, $disciplinas[1]->id, $habilidade->id_habilidade, $ano_same->SAME);
                        //Busca os dados das Questões para Gerar os Modais de Habilidade Individual
                        MethodsProfEscola::estatisticaHabilidadeQuestao($escola->id, $disciplinas[1]->id,$habilidade->id_habilidade, $ano_same->SAME); 
                    }
                }
            }
        }  

        return redirect()->route('lista_manutencao')->with('status', 'Cache Escola Sessão Habilidade transcorrer Anos Português carregada com Sucesso!');       
    }
}
