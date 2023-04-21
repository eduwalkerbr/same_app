<?php

namespace App\Http\Controllers\cadastros\manutencao;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\staticmethods\gerais\MethodsGerais;
use App\Http\Controllers\staticmethods\proficiencia\MethodsProfMunicipio;

class CacheMunicipioController extends Controller
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
     * Método que carrega os dados da Cache de Munícipio
     */
    public function carregarCacheMunDadosBase(){

        set_time_limit(0);

        MethodsGerais::getPrevilegio();

        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();

        //Lista os Munícipios por Ano SAME
        foreach($anos_same as $ano_same){
            $municipios = MethodsGerais::getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){
                MethodsGerais::getMunicipioSelecionado($municipio->id, $ano_same->SAME);
                $escolas = MethodsProfMunicipio::getEscolasMunicipio($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){
                    MethodsGerais::getEscolaSelecionada($escola->id, $ano_same->SAME);  
                }

                //Busca e carregar as Turmas Ativas do Município
                MethodsProfMunicipio::getTurmasMunicipio($municipio->id, $ano_same->SAME);
            }
        }
        
        //Lista as Disciplinas em Geral
        $disciplinas = MethodsGerais::getDisciplinas();
        foreach($disciplinas as $disciplina){
            //Carrega os dados das Disciplinas
            MethodsGerais::getDisciplinaSelecionada($disciplina->id);
        }

        //Busca as Legendas em Geral
        MethodsGerais::getLegendas();

        //---------------- Dados para a Sessão Proficiência Disciplina -----------------------------------------------------------------------------------------
        foreach($anos_same as $ano_same){
            $municipiosListados = MethodsGerais::getMunicipios($ano_same->SAME);    
            foreach($municipiosListados as $municipio){
                MethodsProfMunicipio::estatisticaDisciplinas($municipio->id, $ano_same->SAME);
            }
        }
        //---------------- Dados para a Sessão Proficiência Disciplina ----------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------
        foreach($anos_same as $ano_same){
            $municipiosListados = MethodsGerais::getMunicipios($ano_same->SAME);   
            foreach($municipiosListados as $municipio){
                MethodsProfMunicipio::estatisticaEscola($municipio->id, $ano_same->SAME);
            }
        }
        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------
        foreach($anos_same as $ano_same){
            $municipiosListados = MethodsGerais::getMunicipios($ano_same->SAME);    
            foreach($municipiosListados as $municipio){
                foreach($disciplinas as $disciplina){
                    MethodsProfMunicipio::estatisticaEscolaDisciplina($municipio->id, $disciplina->id, $ano_same->SAME);
                }
            }
        }
        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------
        foreach($anos_same as $ano_same){
            $municipiosListados = MethodsGerais::getMunicipios($ano_same->SAME);   
            foreach($municipiosListados as $municipio){
                foreach($disciplinas as $disciplina){
                    MethodsProfMunicipio::estatisticaAnoDisciplinas($municipio->id, $disciplina->id, $ano_same->SAME);
                }
            }
        }
        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------

        return redirect()->route('lista_manutencao')->with('status', 'Cache Município Dados Base carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio
     */
    public function carregarCacheMunHabAnoMat(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = MethodsGerais::getDisciplinas();

        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------
        foreach($anos_same as $ano_same){
            $municipiosListados = MethodsGerais::getMunicipios($ano_same->SAME);   
            foreach($municipiosListados as $municipio){
                $turmasListadas = Cache::get('turmas_'.strval($municipio->id).strval($ano_same->SAME));
                $anos = [];
                for ($i = 0; $i < sizeof($turmasListadas); $i++) {
                    if (!in_array(substr(trim($turmasListadas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                        $anos[$i] = substr(trim($turmasListadas[$i]->DESCR_TURMA), 0, 2);
                    }
                }
                foreach($anos as $ano){
                    $ano = intval($ano);
                    MethodsProfMunicipio::estatisticaHabilidadeDisciplinaAno($municipio->id, $disciplinas[0]->id, $ano, $ano_same->SAME);
                    MethodsProfMunicipio::estatisticaAjustePercentual($municipio->id, $disciplinas[0]->id, $ano, $ano_same->SAME);
                    // Dados das questões das Habilidades Selecionadas por Ano
                    MethodsProfMunicipio::estatisticaHabilidadeAnoQuestao($municipio->id, $disciplinas[0]->id, $ano, $ano_same->SAME);
                }
            }
        }
        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------

        return redirect()->route('lista_manutencao')->with('status', 'Cache Município Habilidade por Anos Matemática carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio
     */
    public function carregarCacheMunHabAnoPort(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = MethodsGerais::getDisciplinas();

        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------
        foreach($anos_same as $ano_same){
            $municipiosListados = MethodsGerais::getMunicipios($ano_same->SAME);
            foreach($municipiosListados as $municipio){
                $turmasListadas = Cache::get('turmas_'.strval($municipio->id).strval($ano_same->SAME));
                $anos = [];
                for ($i = 0; $i < sizeof($turmasListadas); $i++) {
                    if (!in_array(substr(trim($turmasListadas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                        $anos[$i] = substr(trim($turmasListadas[$i]->DESCR_TURMA), 0, 2);
                    }
                }
                foreach($anos as $ano){
                    $ano = intval($ano);
                    MethodsProfMunicipio::estatisticaHabilidadeDisciplinaAno($municipio->id, $disciplinas[1]->id, $ano, $ano_same->SAME);
                    MethodsProfMunicipio::estatisticaAjustePercentual($municipio->id, $disciplinas[1]->id, $ano, $ano_same->SAME);
                    // Dados das questões das Habilidades Selecionadas por Ano
                    MethodsProfMunicipio::estatisticaHabilidadeAnoQuestao($municipio->id, $disciplinas[1]->id, $ano, $ano_same->SAME);
                }
            }
        }
        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------

        return redirect()->route('lista_manutencao')->with('status', 'Cache Município Habilidade por Anos Português carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio
     */
    public function carregarCacheMunAnoHab(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = MethodsGerais::getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = MethodsGerais::getDisciplinas();

        //Busca as Habilidades pela Disciplina e Munícipio
        foreach($anos_same as $ano_same){
            $municipiosListados = MethodsGerais::getMunicipios($ano_same->SAME);  
            foreach($municipiosListados as $municipio){
                foreach($disciplinas as $disciplina){
                    $habilidades = MethodsProfMunicipio::getHabilidades($disciplina->id, $municipio->id);
                    foreach($habilidades as $habilidade){
                        MethodsGerais::getHabilidadeSelecionada($habilidade->id_habilidade);
                    }
                }
            }
        }

        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------    
        foreach($anos_same as $ano_same){
            $municipiosListados = MethodsGerais::getMunicipios($ano_same->SAME);
            foreach($municipiosListados as $municipio){
                foreach($disciplinas as $disciplina){
                    $habilidades = Cache::get('hab_disc_mun_'.strval($disciplina->id).'_'.strval($municipio->id));
                    foreach($habilidades as $habilidade){
                        MethodsProfMunicipio::estatisticaHabilidadeSelecionadaDisciplina($municipio->id, $disciplina->id, $habilidade->id_habilidade, $ano_same->SAME);
                        MethodsProfMunicipio::estatisticaAjustePercentualAno($municipio->id, $disciplina->id, $habilidade->id_habilidade, $ano_same->SAME);
                        //Busca dados das Questões das Habilidades
                        MethodsProfMunicipio::estatisticaHabilidadeQuestao($municipio->id, $disciplina->id, $habilidade->id_habilidade, $ano_same->SAME);
                    }
                }
            }
        }
        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------  

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        foreach($anos_same as $ano_same){
            $municipiosListados = MethodsGerais::getMunicipios($ano_same->SAME); 
            foreach($municipiosListados as $municipio){
                $turmasListadas = Cache::get('turmas_'.strval($municipio->id).strval($ano_same->SAME));
                foreach($turmasListadas as $turma){
                    $ano = substr(trim($turma->DESCR_TURMA), 0, 2);
                    foreach($disciplinas as $disciplina){
                        MethodsGerais::getCriteriosQuestao($ano, $disciplina->id);
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Município Habilidade Selecionada carregada com Sucesso!');
    }
}
