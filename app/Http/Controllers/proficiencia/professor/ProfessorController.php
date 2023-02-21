<?php

namespace App\Http\Controllers\proficiencia\professor;

use App\Models\CriterioQuestao;
use App\Models\DadoUnificado;
use App\Models\DestaqueModel;
use App\Models\DirecaoProfessor;
use App\Models\Disciplina;
use App\Models\Escola;
use App\Models\Habilidade;
use App\Models\Legenda;
use App\Models\Municipio;
use App\Models\Previlegio;
use App\Models\Questao;
use App\Models\Solicitacao;
use App\Models\Sugestao;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

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
        $this->objSolicitacao = new Solicitacao();
        $this->objTurma = new Turma();
        $this->objQuestao = new Questao();
        $this->objSugestao = new Sugestao();
        $this->objDestaque = new DestaqueModel();
        $this->objEscola = new Escola();
        $this->objMunicipio = new Municipio();
        $this->objDisciplina = new Disciplina();
        $this->objLegenda = new Legenda();
        $this->objDirecaoProfessores = new DirecaoProfessor();
        $this->objCriterioQuestao = new CriterioQuestao();
        $this->objHabilidade = new Habilidade();
        $this->objDadoUnificado = new DadoUnificado();
        $this->objPrevilegio = new Previlegio();
        $this->confPresenca = 1;
        $this->horasCache = 4;
    }

    /**
     * Método que busca os previlégios do Usuário Logado usando Cache
     */
    private function getPrevilegio(){

        if(Cache::has('previlegio_usuario'.strval(auth()->user()->id))){
            $previlegio = Cache::get('previlegio_usuario'.strval(auth()->user()->id));
        } else {
            $previlegio = Previlegio::where(['users_id' => auth()->user()->id])->get();
            //Adiciona ao Cache
            Cache::put('previlegio_usuario'.strval(auth()->user()->id),$previlegio, now()->addHours($this->horasCache));    
        }

        return $previlegio;
    }

    /**
     * Método para buscar as disciplinas utilizando Cache
     */
    private function getAnosSAME(){
        if (auth()->user()->perfil == 'Administrador' 
            || (($this->getPrevilegio()[0]->funcaos_id == 13 || $this->getPrevilegio()[0]->funcaos_id == 14) && $this->getPrevilegio()[0]->municipios_id == 5)) {
            if (Cache::has('anos_same')) {
                $anos_same = Cache::get('anos_same');
            } else {
                $anos_same = DB::select('SELECT SAME FROM dado_unificados GROUP BY SAME ORDER BY SAME ASC');  
                    
                //Adiciona ao Cache  
                Cache::forever('anos_same', $anos_same);  
            }        
        } else if($this->getPrevilegio()[0]->funcaos_id == 8) {
            if (Cache::has('anos_same_'.strval(auth()->user()->id))) {
                $anos_same = Cache::get('anos_same'.strval(auth()->user()->id));
            } else {
                $anos_same = DB::select('SELECT pv.SAME FROM previlegios pv 
                                          WHERE pv.users_id = :id_usuario GROUP BY pv.SAME ORDER BY pv.SAME ASC',['id_usuario' => auth()->user()->id]);
                
                //Adiciona ao Cache
                Cache::put('anos_same'.strval(auth()->user()->id),$anos_same, now()->addHours($this->horasCache));     
            }
        } else {
            if (Cache::has('anos_same_'.strval(auth()->user()->id))) {
                $anos_same = Cache::get('anos_same'.strval(auth()->user()->id));
            } else {
                $anos_same = DB::select('SELECT dp.SAME FROM direcao_professors dp INNER JOIN previlegios pr ON pr.id = dp.id_previlegio 
                                          WHERE pr.users_id = :id_usuario GROUP BY dp.SAME ORDER BY dp.SAME ASC',['id_usuario' => auth()->user()->id]);
                
                //Adiciona ao Cache
                Cache::put('anos_same'.strval(auth()->user()->id),$anos_same, now()->addHours($this->horasCache));     
            }
        }

        return $anos_same;
    }

    /**
     * Método para buscar as disciplinas utilizando Cache
     */
    private function getDisciplinas(){

        if ($this->getPrevilegio()[0]->funcaos_id == 13) {
            $disciplinasListadas = Cache::remember('disc_prev_'.strval($this->getPrevilegio()[0]->funcaos_id), ($this->horasCache*3600), function () {
                return $this->objDisciplina->where(['id' => 1])->get();
            });
        } else if ($this->getPrevilegio()[0]->funcaos_id == 14) {
            $disciplinasListadas = Cache::remember('disc_prev_'.strval($this->getPrevilegio()[0]->funcaos_id), ($this->horasCache*3600), function () {
                return $this->objDisciplina->where(['id' => 2])->get();
            });
        } else {
            $disciplinasListadas = Cache::rememberForever('total_disciplinas', function () {
                return $this->objDisciplina->all();
            });
        }

        return $disciplinasListadas;

    }

    /**
     * Método para buscar os munícipios utilizando Cache
     */
    private function getMunicipios($ano_same){


        if (auth()->user()->perfil == 'Administrador' 
            || (($this->getPrevilegio()[0]->funcaos_id == 13 || $this->getPrevilegio()[0]->funcaos_id == 14) && $this->getPrevilegio()[0]->municipios_id == 5)) {
                if (Cache::has('total_municipios'.strval($ano_same))) {
                    $municipiosListados = Cache::get('total_municipios'.strval($ano_same));
                } else {
                    $municipiosListados = $this->objMunicipio->where(['status' => 'Ativo','SAME' => $ano_same])->get(); 
                    
                    //Adiciona ao Cache  
                    Cache::forever('total_municipios'.strval($ano_same), $municipiosListados);    
                }
        } else {
            if (Cache::has('mun_list_'.strval(auth()->user()->id))) {
                $municipiosListados = Cache::get('mun_list_'.strval(auth()->user()->id));
            } else {
                $municipiosListados = $this->objMunicipio->where(['id' => $this->getPrevilegio()[0]->municipios_id, 'SAME' => $ano_same])->get();
                
                //Adiciona ao Cache
                Cache::put('mun_list_'.strval(auth()->user()->id),$municipiosListados, now()->addHours($this->horasCache));     
            }
        }
        return $municipiosListados;

    }

    /**
     * Método para buscar as disciplinas utilizando Cache
     */
    private function getLegendas(){

        $legendasListadas = Cache::remember('legendas', ($this->horasCache*3600), function () {
            return $this->objLegenda->all();
        });

        return $legendasListadas;
    }

    /**
     * Método para buscar as disciplinas utilizando Cache
     */
    private function getQuestoes(){

        $questoesListadas = Cache::rememberForever('questoes_all', function () {
            return $this->objQuestao->all();
        });

        return $questoesListadas;
    }

    /**
     * Método que óbtem os dados do Municipio Selecionado utilizando Cache
     */
    private function getMunicipioSelecionado($id, $ano_same){
        if(Cache::has('mun_'.strval($id).strval($ano_same))){
            $municipio_selecionado = Cache::get('mun_'.strval($id).strval($ano_same));
        } else {
            $municipio_selecionado = $this->objMunicipio->where(['id' => $id])->get();

            //Adiciona ao Cache
            Cache::forever('mun_'.strval($id).strval($ano_same), $municipio_selecionado);    
        }
        
        return $municipio_selecionado;
    }

    /**
     * Método que óbtem os dados da Disciplina Selecionada utilizando Cache
     */
    private function getEscolaSelecionada($id, $ano_same){
        if(Cache::has('esc_'.strval($id).strval($ano_same))){
            $escola_selecionada = Cache::get('esc_'.strval($id).strval($ano_same));
        } else {
            $escola_selecionada = $this->objEscola->where(['id' => $id])->get();

            //Adiciona ao Cache
            Cache::forever('esc_'.strval($id).strval($ano_same), $escola_selecionada);    
        }
        
        return $escola_selecionada;
    }

    /**
     * Método que óbtem os dados da Disciplina Selecionada utilizando Cache
     */
    private function getDisciplinaSelecionada($id){
        if(Cache::has('disc_'.strval($id))){
            $disciplina_selecionada = Cache::get('disc_'.strval($id));
        } else {
            $disciplina_selecionada = $this->objDisciplina->where(['id' => $id])->get();

            //Adiciona ao Cache
            Cache::forever('disc_'.strval($id), $disciplina_selecionada);    
        }
        
        return $disciplina_selecionada;
    }

    /**
     * Método que óbtem os dados da Disciplina Selecionada utilizando Cache
     */
    private function getTurmaSelecionada($id, $ano_same){
        if(Cache::has('turma_sel_'.strval($id).strval($ano_same))){
            $turma_selecionada = Cache::get('turma_sel_'.strval($id).strval($ano_same));
        } else {
            $turma_selecionada = $this->objTurma->where(['id' => $id])->get();

            //Adiciona ao Cache
            Cache::forever('turma_sel_'.strval($id).strval($ano_same), $turma_selecionada);    
        }
        
        return $turma_selecionada;
    }

    /**
     * Método que lista os Critérios das Questões utilizando Cache
     */
    private function getCriteriosQuestao($ano, $disciplina_selecionada){

        $ano = intval($ano);

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        if ($disciplina_selecionada == 2) {
            if(Cache::has('criterio_ano'.strval($ano))){
                $criterios_questao = Cache::get('criterio_ano'.strval($ano));
            } else {
                $criterios_questao = $this->objCriterioQuestao->where(['ano' => $ano])->get();
                Cache::put('criterio_ano'.strval($ano),$criterios_questao, now()->addHours($this->horasCache));
            }  
        } else {
           //Nos demais não existe esse filtro adicional
            if(Cache::has('criterio_total')){
                $criterios_questao = Cache::get('criterio_total');  
            } else {
                $criterios_questao = $this->objCriterioQuestao->all();
                Cache::forever('criterio_total', $criterios_questao);  
            }
           
       }

        return $criterios_questao;
    }

    public function getEscolasProfessor($id_municipio, $id_escola, $ano_same){
        if ((auth()->user()->perfil == 'Administrador') || (($this->getPrevilegio()[0]->funcaos_id == 13 || $this->getPrevilegio()[0]->funcaos_id == 14) && $this->getPrevilegio()[0]->municipios_id == 5)
        || $this->getPrevilegio()[0]->funcaos_id == 8) {
            if(Cache::has('escolas_'.strval($id_municipio).strval($ano_same))){
                $escolasListadas = Cache::get('escolas_'.strval($id_municipio).strval($ano_same));
            } else {
                $escolasListadas = $this->objEscola->where(['status' => 'Ativo', 'municipios_id' => $id_municipio, 'SAME' => $ano_same])->get();
                //Adiciona ao Cache
                Cache::put('escolas_'.strval($id_municipio).strval($ano_same), $escolasListadas, now()->addHours($this->horasCache));
            }
        } else {
            if(isset($id_escola)){
                if(Cache::has('prof_escolas_func'.strval($id_escola))){
                    $escolasListadas = Cache::get('prof_escolas_func'.strval($id_escola));
                } else {
                    $escolasListadas = $this->objEscola->where(['status' => 'Ativo', 'id' => $id_escola, 'SAME' => $ano_same])->get();
                    Cache::put('prof_escolas_func'.strval($id_escola), $escolasListadas, now()->addHours($this->horasCache));
                }
            } else {
                //Restante vê apenas município do previlégio
                if(Cache::has('escolas_prev'.strval($this->getPrevilegio()[0]->id).strval($ano_same))){
                    $escolasListadas = Cache::get('escolas_prev'.strval($this->getPrevilegio()[0]->id).strval($ano_same));
                } else {
                    $direcaoProfessores = $this->objDirecaoProfessores->where(['id_previlegio' => $this->getPrevilegio()[0]->id])->get();
                    $id_escolas = [];
                    for ($e = 0; $e < sizeof($direcaoProfessores); $e++) {
                        $id_escolas[$e] = $direcaoProfessores[$e]->id_escola;
                    }
                    $escolasListadas = $this->objEscola->whereIn('id', $id_escolas)->where('SAME',$ano_same)->get();
                    //Adiciona ao Cache
                    Cache::put('escolas_prev'.strval($this->getPrevilegio()[0]->id).strval($ano_same), $escolasListadas, now()->addHours($this->horasCache));
                }
            }
            
        }

        return $escolasListadas;
    }

    public function getTurmasProfessor($id_escola, $ano_same){
        if ((auth()->user()->perfil == 'Administrador') || (($this->getPrevilegio()[0]->funcaos_id == 13 || $this->getPrevilegio()[0]->funcaos_id == 14) && $this->getPrevilegio()[0]->municipios_id == 5)
        || $this->getPrevilegio()[0]->funcaos_id == 5 || $this->getPrevilegio()[0]->funcaos_id == 8) {
            if(Cache::has('turmas_prof'.strval($id_escola).strval($ano_same))){
                $turmas = Cache::get('turmas_prof'.strval($id_escola).strval($ano_same));
            } else {
                $turmas = $turmas = $this->objTurma->where(['status' => 'Ativo', 'escolas_id' => $id_escola, 'SAME' => $ano_same])->orderBy('TURMA','asc')->get();
                //Adiciona ao Cache
                Cache::put('turmas_prof'.strval($id_escola).strval($ano_same), $turmas, now()->addHours($this->horasCache));
            }
        } else {
            if(Cache::has('turmas_prof_prev'.strval($this->getPrevilegio()[0]->id).strval($ano_same))){
                $turmas = Cache::get('turmas_prof_prev'.strval($this->getPrevilegio()[0]->id).strval($ano_same));
            } else {
                $direcaoProfessores = $this->objDirecaoProfessores->where(['id_previlegio' => $this->getPrevilegio()[0]->id])->get();
                $id_turmas = [];
                for ($r = 0; $r < sizeof($direcaoProfessores); $r++) {
                    $id_turmas[$r] = $direcaoProfessores[$r]->id_turma;
                }
                $turmas = $this->objTurma->whereIn('id', $id_turmas)->where('SAME', $ano_same)->orderBy('TURMA','asc')->get();
                Cache::put('turmas_prov_prev'.strval($this->getPrevilegio()[0]->id).strval($ano_same), $turmas, now()->addHours($this->horasCache));
            }
        }
        
        return $turmas;
    }

    public function estatisticaBaseTurma($confPresenca, $turma, $ano, $ano_same){ 
        //Busca dados Média Turma

        $ano = intval($ano);

        if(Cache::has('prof_dados_base'.strval($turma).strval($ano).strval($ano_same))){
            $dados_base_turma = Cache::get('prof_dados_base'.strval($turma).strval($ano).strval($ano_same));
        } else {
            $dados_base_turma = DB::select(
                'SELECT (ac.acertos*100)/(qtd_questao.num) AS num_alunos, \'Proficiência Média\' AS descricao FROM dado_unificados du 
                    LEFT JOIN ( SELECT count(id) AS num 
                                FROM dado_unificados 
                                WHERE presenca > :presenca1 AND SAME = :same AND id_escola = (SELECT id_escola FROM turmas WHERE id = :id_turma1 AND SAME = :same1) AND ano = :ano1) AS qtd_questao ON TRUE 
                    LEFT JOIN ( SELECT SUM(acerto) AS acertos 
                                FROM dado_unificados 
                                WHERE presenca > :presenca2 AND SAME = :same2 AND id_escola = (SELECT id_escola FROM turmas WHERE id = :id_turma2 AND SAME = :same3) AND ano = :ano2) AS ac ON TRUE 
                UNION
                    SELECT (ac.acertos*100)/(qtd_questao.num) AS num_alunos,\'Proficiência Turma\' AS descricao  
                        FROM dado_unificados du 
                    LEFT JOIN (SELECT count(id) AS num FROM dado_unificados 
                                WHERE id_turma = :id_turma3 AND SAME = :same4 
                                        AND presenca > :presenca3
                                ) AS qtd_questao ON TRUE                               
                    LEFT JOIN (SELECT SUM(acerto) AS acertos FROM dado_unificados WHERE id_turma = :id_turma4 AND presenca > :presenca4 AND SAME = :same5) AS ac ON TRUE',
                ['presenca1' => $confPresenca, 'presenca2' => $confPresenca,'presenca3' => $confPresenca, 'presenca4' => $confPresenca, 'id_turma1' => $turma, 'ano1' => $ano, 
                'id_turma2' => $turma, 'ano2' => $ano, 'id_turma3' => $turma, 'id_turma4' => $turma, 'same' => $ano_same, 'same1' => $ano_same, 'same2' => $ano_same, 'same3' => $ano_same, 
                'same4' => $ano_same, 'same5' => $ano_same]
            );

            Cache::forever('prof_dados_base'.strval($turma).strval($ano).strval($ano_same), $dados_base_turma);
        }
        
        return  $dados_base_turma;
    }

    private function estatisticaComparacaoTurma($confPresenca, $turma, $ano, $ano_same){ 

        $ano = intval($ano);

        if(Cache::has('prof_dad_comp'.strval($turma).strval($ano).strval($ano_same))){
            $dados_comparacao_turma = Cache::get('prof_dad_comp'.strval($turma).strval($ano).strval($ano_same));
        } else {
            $dados_comparacao_turma = DB::select(
                'SELECT (ac.acertos*100)/(qtd_questao.num) AS percentual, \'Proficiência Turma\' AS descricao 
                    FROM dado_unificados du 
                    LEFT JOIN ( SELECT count(id) AS num FROM dado_unificados WHERE presenca > :presenca1 AND SAME = :same AND id_turma = :id_turma1       ) AS qtd_questao ON TRUE 
                    LEFT JOIN ( SELECT SUM(acerto) AS acertos FROM dado_unificados WHERE presenca > :presenca2 AND SAME = :same2 AND id_turma = :id_turma2 ) AS ac ON TRUE 
                    UNION 
                    SELECT (ac.acertos*100)/(qtd_questao.num) AS percentual, \'Proficência Média\' AS descricao FROM dado_unificados du 
                    LEFT JOIN ( SELECT count(id) AS num FROM dado_unificados 
                            WHERE 
                                id_escola = (SELECT id_escola FROM turmas WHERE presenca > :presenca3 AND SAME = :same3 AND id = :id_turma3) AND ano = :ano1) AS qtd_questao ON TRUE 
                    LEFT JOIN ( SELECT SUM(acerto) AS acertos FROM dado_unificados WHERE presenca > :presenca4 AND SAME = :same4 AND id_escola = (SELECT id_escola FROM turmas WHERE id = :id_turma4 AND SAME = :same5) AND ano = :ano2) AS ac ON TRUE ',
                ['presenca1' => $confPresenca,'presenca2' => $confPresenca,'presenca3' => $confPresenca,'presenca4' => $confPresenca,
                 'id_turma1' => $turma, 'ano1' => $ano, 'id_turma2' => $turma, 'ano2' => $ano, 'id_turma3' => $turma, 'id_turma4' => $turma, 'same' => $ano_same, 'same2' => $ano_same, 'same3' => $ano_same, 
                 'same4' => $ano_same, 'same5' => $ano_same]
            );
            Cache::forever('prof_dad_comp'.strval($turma).strval($ano).strval($ano_same), $dados_comparacao_turma);
        }
        
        return  $dados_comparacao_turma;
    }

    /**
     * Método Sessão Tema
     */
    private function estatisticaBaseGrafico($confPresenca, $turma, $disciplina, $ano_same){   
        //Busca dados Base turma gráfico tema
        if(Cache::has('prof_tema_disc'.strval($turma).strval($disciplina).strval($ano_same))){
            $dados_base_grafico = Cache::get('prof_tema_disc'.strval($turma).strval($disciplina).strval($ano_same));
        } else {
            $dados_base_grafico = DB::select(
                'SELECT CONCAT(\'T\',(@contador := @contador + 1)) AS sigla_tema, nome_tema,(SUM(acerto)*100)/(count(id)) AS percentual_tema 
                 FROM (SELECT @contador := 0) AS nada, dado_unificados 
                 WHERE presenca > :presenca AND  id_turma = :id_turma AND id_disciplina = :id_disciplina AND SAME = :same AND id_tema NOT IN (26,28)
                 GROUP BY nome_tema 
                 ORDER BY nome_tema', 
                 ['presenca' => $confPresenca, 'id_turma' => $turma, 'id_disciplina' => $disciplina, 'same' => $ano_same]);
            Cache::forever('prof_tema_disc'.strval($turma).strval($disciplina).strval($ano_same), $dados_base_grafico);     
        }
        
        return  $dados_base_grafico;
    }
   
    /**
     * Método Sessão Habilidade Disciplina
     */
    private function estatisticaHabilidadeDisciplinaGrafico($confPresenca, $turma, $disciplina, $ano_same){   
        //Busca dados Disciplina Gráfico
        if(Cache::has('prof_hab_disc'.strval($turma).strval($disciplina).strval($ano_same))){
            $dados_base_habilidade_disciplina_grafico = Cache::get('prof_hab_disc'.strval($turma).strval($disciplina).strval($ano_same));
        } else {
            $dados_base_habilidade_disciplina_grafico = DB::select(
                'SELECT sigla_habilidade,
                        (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, 
                        id_habilidade, tipo_questao, \'white\' AS cor, nome_habilidade, nome_disciplina 
                FROM dado_unificados 
                WHERE presenca > :presenca AND  id_turma = :id_turma AND id_disciplina = :id_disciplina AND SAME = :same 
                GROUP BY id_habilidade, nome_habilidade, sigla_habilidade, nome_disciplina, tipo_questao 
                ORDER BY sigla_habilidade ASC ', 
                ['presenca' => $confPresenca, 'id_turma' => $turma, 'id_disciplina' => $disciplina, 'same' => $ano_same]
            );

            $dados_ajuste_percentual_base = $this->estatisticaAjustePercentualBase($confPresenca, $turma, $disciplina, $ano_same);

            $legendas = $this->getLegendas();

            //Ajusta os percentuais das questões não objetivas
            for ($j = 0; $j < sizeof($dados_base_habilidade_disciplina_grafico); $j++) {
                $total_questoes = 0;
                $valor_ajuste = 0;
                for ($p = 0; $p < sizeof($dados_ajuste_percentual_base); $p++) {
                        if (
                            $dados_base_habilidade_disciplina_grafico[$j]->id_habilidade == $dados_ajuste_percentual_base[$p]->id_habilidade
                            && $dados_base_habilidade_disciplina_grafico[$j]->tipo_questao != 'Objetivas'
                        ) {
                            $total_questoes += $dados_ajuste_percentual_base[$p]->qtd;
                            if ($dados_ajuste_percentual_base[$p]->qtd > $valor_ajuste) {
                                $valor_ajuste = $dados_ajuste_percentual_base[$p]->qtd;
                                if ($dados_ajuste_percentual_base[$p]->resposta == 'A') {
                                    $dados_base_habilidade_disciplina_grafico[$j]->cor = $legendas[0]->cor_fundo;
                                }
                                if ($dados_ajuste_percentual_base[$p]->resposta == 'B') {
                                    $dados_base_habilidade_disciplina_grafico[$j]->cor = $legendas[1]->cor_fundo;
                                }
                                if ($dados_ajuste_percentual_base[$p]->resposta == 'C') {
                                    $dados_base_habilidade_disciplina_grafico[$j]->cor = $legendas[2]->cor_fundo;
                                }
                                if ($dados_ajuste_percentual_base[$p]->resposta == 'D') {
                                    $dados_base_habilidade_disciplina_grafico[$j]->cor = $legendas[3]->cor_fundo;
                                }
                            }
                        }
                    }
                if ($total_questoes != 0 && $valor_ajuste != 0) {
                    $dados_base_habilidade_disciplina_grafico[$j]->percentual_habilidade = (($valor_ajuste * 100) / ($total_questoes));
                }
            }
        
            //Ajusta Nomenclatura
            for ($t = 0; $t < sizeof($dados_base_habilidade_disciplina_grafico); $t++) {
                if ($t < 9) {
                    $dados_base_habilidade_disciplina_grafico[$t]->sigla_habilidade = 'H0' . ($t + 1);
                } else {
                    $dados_base_habilidade_disciplina_grafico[$t]->sigla_habilidade = 'H' . ($t + 1);
                }
            }

            Cache::forever('prof_hab_disc'.strval($turma).strval($disciplina).strval($ano_same), $dados_base_habilidade_disciplina_grafico);
        }
        
        return  $dados_base_habilidade_disciplina_grafico;
    }

    /**
     * Método Sessão Habilidade Disciplina
     */
    private function estatisticaAjustePercentualBase($confPresenca, $turma, $disciplina, $ano_same){   
        if(Cache::has('prof_aj_perc'.strval($turma).strval($disciplina).strval($ano_same))){
            $dados_ajuste_percentual_base = Cache::get('prof_aj_perc'.strval($turma).strval($disciplina).strval($ano_same));
        } else {
            $dados_ajuste_percentual_base = DB::select(
                'SELECT sigla_habilidade, COUNT(id) AS qtd, resposta, id_habilidade, id_questao 
                FROM dado_unificados 
                WHERE presenca > :presenca AND  id_turma = :id_turma AND id_disciplina = :id_disciplina AND SAME = :same 
                    AND tipo_questao <> \'Objetivas\' AND resposta IS NOT NULL AND resposta <> \'\' GROUP BY sigla_habilidade, resposta, id_habilidade, id_questao',
                ['presenca' => $confPresenca, 'id_turma' => $turma, 'id_disciplina' => $disciplina, 'same' => $ano_same]
            );
            Cache::forever('prof_aj_perc'.strval($turma).strval($disciplina).strval($ano_same), $dados_ajuste_percentual_base);
        }
        
        return  $dados_ajuste_percentual_base;
    }

    /**
     * Método Sessão Habilidade Disciplina
     */
    private function estatisticaDisciplinaQuestao($confPresenca, $turma, $disciplina, $ano_same){   
        //Busca dados das questões habilidade
        if(Cache::has('prof_hab_quest'.strval($turma).strval($disciplina).strval($ano_same))){
            $dados_base_habilidade_questao = Cache::get('prof_hab_quest'.strval($turma).strval($disciplina).strval($ano_same));
        } else {
            $dados_base_habilidade_questao = DB::select(
                'SELECT id_habilidade, (SUM(acerto)*100)/(count(id)) AS percentual_habilidade,
                        id_disciplina, desc_questao, id_questao, nome_tema, id_tipo_questao, tipo_questao, correta,  imagem_questao, ano,
                        \'Nome CRITÉRIO A\' AS nome_A, \'TESTE CRITÉRIO A\' AS Obs_A, 
                        \'Nome CRITÉRIO B\' AS nome_B, \'TESTE CRITÉRIO B\' AS Obs_B, 
                        \'Nome CRITÉRIO C\' AS nome_C, \'TESTE CRITÉRIO C\' AS Obs_C, 
                        \'Nome CRITÉRIO D\' AS nome_D, \'TESTE CRITÉRIO D\' AS Obs_D
                FROM dado_unificados 
                WHERE presenca > :presenca AND  id_turma = :id_turma AND id_disciplina = :id_disciplina AND SAME = :same
                GROUP BY id_habilidade, desc_questao,   nome_disciplina, id_questao, nome_tema, tipo_questao, correta, imagem_questao , id_disciplina
                ORDER BY id_habilidade ASC ',
                ['presenca' => $confPresenca, 'id_turma' => $turma, 'id_disciplina' => $disciplina, 'same' => $ano_same]
            );

            $dados_ajuste_percentual_base = $this-> estatisticaAjustePercentualBase($this->confPresenca, $turma, $disciplina, $ano_same);

            for ($j = 0; $j < sizeof($dados_base_habilidade_questao); $j++) {
                $total_questoes = 0;
                $valor_ajuste = 0;
                for ($p = 0; $p < sizeof($dados_ajuste_percentual_base); $p++) {
                    if (
                        $dados_base_habilidade_questao[$j]->tipo_questao != 'Objetivas'
                        && $dados_base_habilidade_questao[$j]->id_habilidade == $dados_ajuste_percentual_base[$p]->id_habilidade
                        && $dados_base_habilidade_questao[$j]->id_questao == $dados_ajuste_percentual_base[$p]->id_questao
                    ) {
                        $total_questoes += $dados_ajuste_percentual_base[$p]->qtd;
                        if ($dados_ajuste_percentual_base[$p]->qtd > $valor_ajuste) {
                            $valor_ajuste = $dados_ajuste_percentual_base[$p]->qtd;
                        }
                    }
                }
                if ($total_questoes != 0 && $valor_ajuste != 0) {
                    $dados_base_habilidade_questao[$j]->percentual_habilidade = (($valor_ajuste * 100) / ($total_questoes));
                }
            }

            Cache::forever('prof_hab_quest'.strval($turma).strval($disciplina).strval($ano_same), $dados_base_habilidade_questao);
        }

        return $dados_base_habilidade_questao ;
    }

    public function getHabilidadesProfessor($disciplina, $turma, $ano_same){
        if(Cache::has('prof_habs'.strval($disciplina).strval($turma).strval($ano_same))){
            $habilidades = Cache::get('prof_habs'.strval($disciplina).strval($turma).strval($ano_same));
        } else {
            $habilidades = $this->objDadoUnificado->select('id_habilidade', 'nome_habilidade', 'sigla_habilidade')
            ->where(['id_disciplina' => $disciplina, 'id_turma' => $turma, 'SAME' => $ano_same])
            ->groupBy('id_habilidade', 'nome_habilidade', 'sigla_habilidade')->orderBy('nome_habilidade', 'asc')->get();
            Cache::forever('prof_habs'.strval($disciplina).strval($turma).strval($ano_same), $habilidades);
        }
        return $habilidades;
    }

    /**
     * Método Sessão Ano Habilidades
     */
    private function estatisticaHabilidadeDisciplinaAnoGrafico($confPresenca, $id_escola, $disciplina, $ano, $ano_same){  

        $ano = intval($ano);

        //Busca dados habilidade disciplina ano Gráfico 
        if(Cache::has('prof_ano_habs'.strval($id_escola).strval($disciplina).strval($ano).strval($ano_same))){
            $dados_base_habilidade_disciplina_ano_grafico = Cache::get('prof_ano_habs'.strval($id_escola).strval($disciplina).strval($ano).strval($ano_same));
        } else {
            $dados_base_habilidade_disciplina_ano_grafico = DB::select(
                'SELECT sigla_habilidade, (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, tipo_questao, \'white\' AS cor,id_habilidade, nome_habilidade, nome_disciplina 
                FROM dado_unificados 
                WHERE presenca > :presenca AND id_escola = :id_escola AND id_disciplina = :id_disciplina AND ano = :ano AND SAME = :same 
                GROUP BY id_habilidade, nome_habilidade, sigla_habilidade, nome_disciplina, id_escola, tipo_questao 
                ORDER BY nome_habilidade, sigla_habilidade ASC ',
                ['presenca' => $confPresenca, 'id_escola' => $id_escola, 'id_disciplina' => $disciplina, 'ano' => $ano, 'same' => $ano_same]
            );

            $dados_ajuste_percentual = $this->estatisticaAjustePercentual($confPresenca, $id_escola, $disciplina, $ano, $ano_same);
            $legendas = $this->getLegendas();

            //Ajusta os percentuais das questões não objetivas
            for ($j = 0; $j < sizeof($dados_base_habilidade_disciplina_ano_grafico); $j++) {
                $total_questoes = 0;
                $valor_ajuste = 0;
                for ($p = 0; $p < sizeof($dados_ajuste_percentual); $p++) {
                    if (
                        $dados_base_habilidade_disciplina_ano_grafico[$j]->sigla_habilidade == $dados_ajuste_percentual[$p]->sigla_habilidade
                        && $dados_base_habilidade_disciplina_ano_grafico[$j]->tipo_questao != 'Objetivas'
                    ) {
                        $total_questoes += $dados_ajuste_percentual[$p]->qtd;
                        if ($dados_ajuste_percentual[$p]->qtd > $valor_ajuste) {
                            $valor_ajuste = $dados_ajuste_percentual[$p]->qtd;
                            if ($dados_ajuste_percentual[$p]->resposta == 'A') {
                                $dados_base_habilidade_disciplina_ano_grafico[$j]->cor = $legendas[0]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual[$p]->resposta == 'B') {
                                $dados_base_habilidade_disciplina_ano_grafico[$j]->cor = $legendas[1]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual[$p]->resposta == 'C') {
                                $dados_base_habilidade_disciplina_ano_grafico[$j]->cor = $legendas[2]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual[$p]->resposta == 'D') {
                                $dados_base_habilidade_disciplina_ano_grafico[$j]->cor = $legendas[3]->cor_fundo;
                            }
                        }
                    }
                }
                if ($total_questoes != 0 && $valor_ajuste != 0) {
                    $dados_base_habilidade_disciplina_ano_grafico[$j]->percentual_habilidade = (($valor_ajuste * 100) / ($total_questoes));
                }
            }
            Cache::forever('prof_ano_habs'.strval($id_escola).strval($disciplina).strval($ano).strval($ano_same), $dados_base_habilidade_disciplina_ano_grafico);
        }
        
        return $dados_base_habilidade_disciplina_ano_grafico;
    }

    /**
     * Método Sessão Ano Habilidades
     */
    private function estatisticaAjustePercentual($confPresenca, $id_escola, $disciplina, $ano, $ano_same){  
        
        $ano = intval($ano);

        if(Cache::has('prof_quest_aj_perc'.strval($id_escola).strval($disciplina).strval($ano).strval($ano_same))){
            $dados_ajuste_percentual = Cache::get('prof_quest_aj_perc'.strval($id_escola).strval($disciplina).strval($ano).strval($ano_same));
        } else {
            $dados_ajuste_percentual = DB::select(
                'SELECT sigla_habilidade, COUNT(id) AS qtd, resposta, id_habilidade, id_questao FROM dado_unificados  
                WHERE presenca > :presenca AND  id_escola = :id_escola AND id_disciplina = :id_disciplina AND SAME = :same 
                AND ano = :ano AND tipo_questao <> \'Objetivas\' AND resposta IS NOT NULL AND resposta <> \'\' GROUP BY sigla_habilidade, resposta, id_habilidade, id_questao',
                ['presenca' => $confPresenca, 'id_escola' => $id_escola, 'id_disciplina' => $disciplina, 'ano' => $ano, 'same' => $ano_same]
            );
            Cache::forever('prof_quest_aj_perc'.strval($id_escola).strval($disciplina).strval($ano).strval($ano_same), $dados_ajuste_percentual);
        }
        
        return $dados_ajuste_percentual;
    }

    /**
     * Método Sessão Ano Habilidades
     */
    private function estatisticaHabilidadeAnoQuestao($confPresenca, $id_escola, $disciplina, $ano, $ano_same){  

        $ano = intval($ano);
        if(Cache::has('prof_ano_habs_quest'.strval($id_escola).strval($disciplina).strval($ano).strval($ano_same))){
            $dados_base_habilidade_ano_questao = Cache::get('prof_ano_habs_quest'.strval($id_escola).strval($disciplina).strval($ano).strval($ano_same));
        } else {
            $dados_base_habilidade_ano_questao =
            DB::select(
                'SELECT id_habilidade, (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, desc_questao, id_questao, nome_tema, tipo_questao, correta, imagem_questao, ano
                FROM dado_unificados 
                    WHERE presenca > :presenca AND  id_escola = :id_escola AND id_disciplina = :id_disciplina AND ano = :ano AND SAME = :same 
                GROUP BY id_habilidade, desc_questao, nome_disciplina, id_questao, nome_tema, tipo_questao, correta, imagem_questao, id_escola, ano ORDER BY id_habilidade ASC ',
                ['presenca' => $confPresenca, 'id_escola' => $id_escola, 'id_disciplina' => $disciplina, 'ano' => $ano, 'same' => $ano_same]
            );

            $dados_ajuste_percentual = $this->estatisticaAjustePercentual($confPresenca, $id_escola, $disciplina, $ano, $ano_same);

            for ($j = 0; $j < sizeof($dados_base_habilidade_ano_questao); $j++) {
                $total_questoes = 0;
                $valor_ajuste = 0;
                for ($p = 0; $p < sizeof($dados_ajuste_percentual); $p++) {
                    if (
                        $dados_base_habilidade_ano_questao[$j]->tipo_questao != 'Objetivas'
                        && $dados_base_habilidade_ano_questao[$j]->id_habilidade == $dados_ajuste_percentual[$p]->id_habilidade
                        && $dados_base_habilidade_ano_questao[$j]->id_questao == $dados_ajuste_percentual[$p]->id_questao
                    ) {
                        $total_questoes += $dados_ajuste_percentual[$p]->qtd;
                        if ($dados_ajuste_percentual[$p]->qtd > $valor_ajuste) {
                            $valor_ajuste = $dados_ajuste_percentual[$p]->qtd;
                        }
                    }
                }
                if ($total_questoes != 0 && $valor_ajuste != 0) {
                    $dados_base_habilidade_ano_questao[$j]->percentual_habilidade = (($valor_ajuste * 100) / ($total_questoes));
                }
            }

            Cache::forever('prof_ano_habs_quest'.strval($id_escola).strval($disciplina).strval($ano).strval($ano_same), $dados_base_habilidade_ano_questao);
        }
        
        return $dados_base_habilidade_ano_questao;
    }

    /**
     * Método que óbtem os dados da Habilidade Selecionada utilizando Cache
     */
    private function getHabilidadeSelecionada($id){
        if(Cache::has('habilidade_'.strval($id))){
            $habilidade_selecionada = Cache::get('habilidade_'.strval($id));
        } else {
            $habilidade_selecionada = $this->objDadoUnificado->select('id_habilidade', 'nome_habilidade', 'sigla_habilidade')
                ->where(['id_habilidade' => $id])
                ->groupBy('id_habilidade', 'nome_habilidade', 'sigla_habilidade')->get();

            //Adiciona ao Cache
            Cache::forever('habilidade_'.strval($id), $habilidade_selecionada);    
        }
        
        return $habilidade_selecionada;
    }

    /**
     * Método que obtém dados da Sessão Habilidade Selecionada
     */
    private function estatisticaHabilidadeDisciplinaHabilidade($confPresenca, $id_escola, $disciplina, $habilidade, $ano_same){   
        //Busca dados gráfico habilidade individual     
        if(Cache::has('prof_hab_sel'.strval($id_escola).strval($disciplina).strval($habilidade).strval($ano_same))){
            $dados_base_habilidade_disciplina_grafico_habilidade = Cache::get('prof_hab_sel'.strval($id_escola).strval($disciplina).strval($habilidade).strval($ano_same));
        } else {
            $dados_base_habilidade_disciplina_grafico_habilidade = DB::select(
                'SELECT sigla_habilidade, 
                    (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, ano, CONCAT(ano,\'º Ano\') AS sigla_ano, id_habilidade, nome_habilidade, nome_disciplina, tipo_questao, \'white\' AS cor 
                    FROM dado_unificados 
                    WHERE presenca > :presenca AND  id_escola = :id_escola AND id_disciplina = :id_disciplina AND id_habilidade = :id_habilidade AND SAME = :same
                    GROUP BY id_habilidade, nome_habilidade, sigla_habilidade, nome_disciplina, id_escola, ano, tipo_questao 
                    ORDER BY sigla_habilidade, ano ASC ',
                ['presenca' => $confPresenca, 'id_escola' => $id_escola, 'id_disciplina' => $disciplina, 'id_habilidade' => $habilidade, 'same' => $ano_same]
            );

            $dados_ajuste_percentual_ano = $this->estatisticaAjustePercentualAno($confPresenca, $id_escola, $disciplina, $habilidade, $ano_same);
            $legendas = $this->getLegendas();

            //Ajusta os percentuais das questões não objetivas
            for ($j = 0; $j < sizeof($dados_base_habilidade_disciplina_grafico_habilidade); $j++) {
                $total_questoes = 0;
                $valor_ajuste = 0;
                for ($p = 0; $p < sizeof($dados_ajuste_percentual_ano); $p++) {
                    if (
                        $dados_base_habilidade_disciplina_grafico_habilidade[$j]->sigla_habilidade == $dados_ajuste_percentual_ano[$p]->sigla_habilidade
                        && $dados_base_habilidade_disciplina_grafico_habilidade[$j]->ano == $dados_ajuste_percentual_ano[$p]->ano
                        && $dados_base_habilidade_disciplina_grafico_habilidade[$j]->tipo_questao != 'Objetivas'
                    ) {
                        $total_questoes += $dados_ajuste_percentual_ano[$p]->qtd;
                        if ($dados_ajuste_percentual_ano[$p]->qtd > $valor_ajuste) {
                            $valor_ajuste = $dados_ajuste_percentual_ano[$p]->qtd;
                            if ($dados_ajuste_percentual_ano[$p]->resposta == 'A') {
                                $dados_base_habilidade_disciplina_grafico_habilidade[$j]->cor = $legendas[0]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual_ano[$p]->resposta == 'B') {
                                $dados_base_habilidade_disciplina_grafico_habilidade[$j]->cor = $legendas[1]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual_ano[$p]->resposta == 'C') {
                                $dados_base_habilidade_disciplina_grafico_habilidade[$j]->cor = $legendas[2]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual_ano[$p]->resposta == 'D') {
                                $dados_base_habilidade_disciplina_grafico_habilidade[$j]->cor = $legendas[3]->cor_fundo;
                            }
                        }
                    }
                }
                if ($total_questoes != 0 && $valor_ajuste != 0) {
                    $dados_base_habilidade_disciplina_grafico_habilidade[$j]->percentual_habilidade = (($valor_ajuste * 100) / ($total_questoes));
                }
            }

            Cache::forever('prof_hab_sel'.strval($id_escola).strval($disciplina).strval($habilidade).strval($ano_same), $dados_base_habilidade_disciplina_grafico_habilidade);
        }
        
        return $dados_base_habilidade_disciplina_grafico_habilidade;
    }

    /**
     * Método que obtém dados da Sessão Habilidade Selecionada
     */
    private function estatisticaAjustePercentualAno($confPresenca, $id_escola, $disciplina, $habilidade, $ano_same){   
        if(Cache::has('prof_hab_sel_aj'.strval($id_escola).strval($disciplina).strval($habilidade).strval($ano_same))){
            $dados_ajuste_percentual_ano = Cache::get('prof_hab_sel_aj'.strval($id_escola).strval($disciplina).strval($habilidade).strval($ano_same));
        } else {
            $dados_ajuste_percentual_ano = DB::select(
                'SELECT sigla_habilidade, COUNT(id) AS qtd, resposta, id_habilidade, ano, id_questao 
                FROM dado_unificados WHERE presenca > :presenca AND  id_escola = :id_escola AND id_disciplina = :id_disciplina AND SAME = :same 
                    AND id_habilidade = :id_habilidade AND tipo_questao <> \'Objetivas\' AND resposta IS NOT NULL AND resposta <> \'\' 
                GROUP BY sigla_habilidade, resposta, id_habilidade, ano, id_questao ',
                ['presenca' => $confPresenca, 'id_escola' => $id_escola, 'id_disciplina' => $disciplina, 'id_habilidade' => $habilidade, 'same' =>$ano_same]
            );  
            Cache::forever('prof_hab_sel_aj'.strval($id_escola).strval($disciplina).strval($habilidade).strval($ano_same), $dados_ajuste_percentual_ano);
        }
        
        return  $dados_ajuste_percentual_ano;
    }

    /**
     * Método que obtém dados da Sessão Habilidade Selecionada
     */
    private function estatisticaBaseHabilidadeQuestaoHabilidade($confPresenca, $id_escola, $disciplina, $habilidade, $ano_same){   
        //Busca dados questão habilidade individual
        if(Cache::has('prof_hab_sel_quest'.strval($id_escola).strval($disciplina).strval($habilidade).strval($ano_same))){
            $dados_base_habilidade_questao_habilidade = Cache::get('prof_hab_sel_quest'.strval($id_escola).strval($disciplina).strval($habilidade).strval($ano_same));
        } else {
            $dados_base_habilidade_questao_habilidade = DB::select(
                'SELECT id_habilidade, (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, desc_questao, id_questao, nome_tema, tipo_questao, correta, imagem_questao, ano,
                    \'Nome CRITÉRIO A\' AS nome_A, \'TESTE CRITÉRIO A\' AS Obs_A, 
                    \'Nome CRITÉRIO B\' AS nome_B, \'TESTE CRITÉRIO B\' AS Obs_B, 
                    \'Nome CRITÉRIO C\' AS nome_C, \'TESTE CRITÉRIO C\' AS Obs_C, 
                    \'Nome CRITÉRIO D\' AS nome_D, \'TESTE CRITÉRIO D\' AS Obs_D
                    FROM dado_unificados 
                    WHERE presenca > :presenca AND id_escola = :id_escola AND id_disciplina = :id_disciplina AND id_habilidade = :id_habilidade AND SAME = :same               
                    GROUP BY id_habilidade, desc_questao, nome_disciplina, id_questao, nome_tema, tipo_questao, correta, imagem_questao, id_escola, ano ORDER BY id_habilidade ASC ',
                    ['presenca' => $confPresenca ,'id_escola' => $id_escola, 'id_disciplina' => $disciplina, 'id_habilidade' => $habilidade, 'same' => $ano_same]
            );

            $dados_ajuste_percentual_ano = $this->estatisticaAjustePercentualAno($confPresenca, $id_escola, $disciplina, $habilidade, $ano_same);

            for ($j = 0; $j < sizeof($dados_base_habilidade_questao_habilidade); $j++) {
                $total_questoes = 0;
                $valor_ajuste = 0;
                for ($p = 0; $p < sizeof($dados_ajuste_percentual_ano); $p++) {
                    if (
                        $dados_base_habilidade_questao_habilidade[$j]->tipo_questao != 'Objetivas'
                        && $dados_base_habilidade_questao_habilidade[$j]->id_habilidade == $dados_ajuste_percentual_ano[$p]->id_habilidade
                        && $dados_base_habilidade_questao_habilidade[$j]->id_questao == $dados_ajuste_percentual_ano[$p]->id_questao
                        && $dados_base_habilidade_questao_habilidade[$j]->ano == $dados_ajuste_percentual_ano[$p]->ano
                    ) {
                        $total_questoes += $dados_ajuste_percentual_ano[$p]->qtd;
                        if ($dados_ajuste_percentual_ano[$p]->qtd > $valor_ajuste) {
                            $valor_ajuste = $dados_ajuste_percentual_ano[$p]->qtd;
                        }
                    }
                }
                if ($total_questoes != 0 && $valor_ajuste != 0) {
                    $dados_base_habilidade_questao_habilidade[$j]->percentual_habilidade = (($valor_ajuste * 100) / ($total_questoes));
                }
            }

            Cache::forever('prof_hab_sel_quest'.strval($id_escola).strval($disciplina).strval($habilidade).strval($ano_same), $dados_base_habilidade_questao_habilidade);
        }

       return  $dados_base_habilidade_questao_habilidade;
    }

        /**
     * Método Sessão Questões Disciplina
     */
    private function estatisticaQuestaoGraficoDisciplina($confPresenca, $turma, $disciplina, $ano_same){   
        //Busca dados do gráfico de questão disciplina
        if(Cache::has('prof_hab_disc_ques'.strval($turma).strval($disciplina).strval($ano_same))){
            $dados_base_questao_grafico_disciplina = Cache::get('prof_hab_disc_ques'.strval($turma).strval($disciplina).strval($ano_same));
        } else {
            $dados_base_questao_grafico_disciplina = DB::select(
                'SELECT CONCAT(\'Q\',numero_questao) 
                AS sigla_questao, (SUM(acerto)*100)/(count(id)) 
                AS percentual_questao, correta, imagem_questao, nome_tema, nome_habilidade, tipo_questao, id_questao, UPPER(desc_questao) 
                AS nome_questao, nome_disciplina,  \'white\' 
                AS cor 
                FROM dado_unificados 
                WHERE presenca > :presenca AND  id_turma = :id_turma AND id_disciplina = :id_disciplina AND SAME = :same 
                GROUP BY desc_questao, numero_questao, nome_disciplina, correta, imagem_questao, nome_tema, nome_habilidade, tipo_questao,  id_questao 
                ORDER BY numero_questao ASC ',
                ['presenca' => $confPresenca ,'id_turma' => $turma, 'id_disciplina' => $disciplina, 'same' => $ano_same]
            );

            $dados_ajuste_percentual_questao = $this->estatisticaAjustePercentualQuestao($confPresenca, $turma, $disciplina, $ano_same);
            $legendas = $this->getLegendas();

            for ($j = 0; $j < sizeof($dados_base_questao_grafico_disciplina); $j++) {
                $total_questoes = 0;
                $valor_ajuste = 0;
                for ($p = 0; $p < sizeof($dados_ajuste_percentual_questao); $p++) {
                    if (
                        $dados_base_questao_grafico_disciplina[$j]->id_questao == $dados_ajuste_percentual_questao[$p]->id_questao
                        && $dados_base_questao_grafico_disciplina[$j]->tipo_questao != 'Objetivas'
                    ) {
                        $total_questoes += $dados_ajuste_percentual_questao[$p]->qtd;
                        if ($dados_ajuste_percentual_questao[$p]->qtd > $valor_ajuste) {
                            $valor_ajuste = $dados_ajuste_percentual_questao[$p]->qtd;
                            if ($dados_ajuste_percentual_questao[$p]->resposta == 'A') {
                                $dados_base_questao_grafico_disciplina[$j]->cor = $legendas[0]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual_questao[$p]->resposta == 'B') {
                                $dados_base_questao_grafico_disciplina[$j]->cor = $legendas[1]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual_questao[$p]->resposta == 'C') {
                                $dados_base_questao_grafico_disciplina[$j]->cor = $legendas[2]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual_questao[$p]->resposta == 'D') {
                                $dados_base_questao_grafico_disciplina[$j]->cor = $legendas[3]->cor_fundo;
                            }
                        }
                    }
                }
                if ($total_questoes != 0 && $valor_ajuste != 0) {
    
                    $dados_base_questao_grafico_disciplina[$j]->percentual_questao = (($valor_ajuste * 100) / ($total_questoes));
                }
            }

            Cache::forever('prof_hab_disc_ques'.strval($turma).strval($disciplina).strval($ano_same), $dados_base_questao_grafico_disciplina);
        }
        
        return  $dados_base_questao_grafico_disciplina;
    }

    /**
     * Método Sessão Questão Disciplina
     */
    private function estatisticaAjustePercentualQuestao($confPresenca, $turma, $disciplina, $ano_same){   
        if(Cache::has('prof_aj_perc_quest'.strval($turma).strval($disciplina).strval($ano_same))){
            $dados_ajuste_percentual_questao = Cache::get('prof_aj_perc_quest'.strval($turma).strval($disciplina).strval($ano_same));
        } else {
            $dados_ajuste_percentual_questao = DB::select(
                'SELECT COUNT(id) AS qtd, resposta, id_questao 
                FROM dado_unificados 
                WHERE presenca > :presenca AND  id_turma = :id_turma AND id_disciplina = :id_disciplina AND SAME = :same 
                AND tipo_questao <> \'Objetivas\' AND resposta IS NOT NULL AND resposta <> \'\' GROUP BY resposta, id_questao ',
                ['presenca' => $confPresenca, 'id_turma' => $turma, 'id_disciplina' => $disciplina, 'same' => $ano_same]
            );
            Cache::forever('prof_aj_perc_quest'.strval($turma).strval($disciplina).strval($ano_same), $dados_ajuste_percentual_questao);
        }
        
        return $dados_ajuste_percentual_questao;
    }

    /**
     * Método Sessão Alunos
     */
    private function estatisticaBaseAlunoGraficoDisciplina($confPresenca, $turma, $disciplina, $ano_same){ 
        //Busca dados Gráfico aluno disciplina
        if(Cache::has('prof_aluno'.strval($turma).strval($disciplina).strval($ano_same))){
            $dados_base_aluno_grafico_disciplina = Cache::get('prof_aluno'.strval($turma).strval($disciplina).strval($ano_same));
        } else {
            $dados_base_aluno_grafico_disciplina = DB::select(
                'SELECT CONCAT(\'A\',(@contador := @contador +1)) AS sigla_aluno, nome_aluno_abreviado, percentual_aluno, nome_aluno, nome_disciplina,presenca,pontuacao,respostaDoAluno,gabarito_prova
                FROM (SELECT UPPER(CONCAT(SUBSTRING_INDEX(nome_aluno, \' \', 1),\' \', SUBSTRING_INDEX(nome_aluno, \' \', -1))) AS nome_aluno_abreviado, (SUM(acerto)*100)/(count(id)) AS percentual_aluno,nome_aluno,
                        nome_disciplina,presenca,pontuacao,respostaDoAluno,gabarito_prova 
                FROM dado_unificados 
                WHERE id_turma = :id_turma AND id_disciplina = :id_disciplina AND SAME = :same
                GROUP BY nome_aluno_abreviado, nome_aluno, nome_disciplina,presenca,pontuacao,respostaDoAluno,gabarito_prova) dados,(SELECT @contador := 0) AS nada
                ORDER BY nome_aluno ASC',
                ['id_turma' => $turma, 'id_disciplina' => $disciplina, 'same' => $ano_same]
            );
            Cache::forever('prof_aluno'.strval($turma).strval($disciplina).strval($ano_same), $dados_base_aluno_grafico_disciplina);
        }
        
        return $dados_base_aluno_grafico_disciplina;
    }

    private function getHabilidadesCriterios($confPresenc, $turma, $id_disciplina, $ano_same_selecionado){

        $dados_base_habilidade_questao = $this-> estatisticaDisciplinaQuestao($confPresenc, $turma, $id_disciplina, $ano_same_selecionado);

        // Nova definição das Habilidades com os Critérios
        for ($j = 0; $j < sizeof($dados_base_habilidade_questao); $j++) {
            if ($dados_base_habilidade_questao[$j]->tipo_questao != 'Objetivas'){
                //Nos demais não existe esse filtro adicional
                $criterios_questaoAll = $this->objCriterioQuestao->all();
                for ($p = 0; $p < sizeof($criterios_questaoAll); $p++) {
                    if ($dados_base_habilidade_questao[$j]->id_disciplina       == $criterios_questaoAll[$p]->id_disciplina
                        && $dados_base_habilidade_questao[$j]->id_tipo_questao  == $criterios_questaoAll[$p]->id_tipo_questao
                        && $dados_base_habilidade_questao[$j]->ano              == $criterios_questaoAll[$p]->ano
                    ) {
                        if ($criterios_questaoAll[$p]->nome[0] == 'A') {
                            $dados_base_habilidade_questao[$j]->nome_A = $criterios_questaoAll[$p]->nome;
                            $dados_base_habilidade_questao[$j]->Obs_A  = $criterios_questaoAll[$p]->obs;
                        } else 
                        if ($criterios_questaoAll[$p]->nome[0] == 'B') {
                            $dados_base_habilidade_questao[$j]->nome_B = $criterios_questaoAll[$p]->nome;
                            $dados_base_habilidade_questao[$j]->Obs_B = $criterios_questaoAll[$p]->obs;
                        } else 
                        if ($criterios_questaoAll[$p]->nome[0] == 'C') {
                            $dados_base_habilidade_questao[$j]->nome_C = $criterios_questaoAll[$p]->nome;
                            $dados_base_habilidade_questao[$j]->Obs_C = $criterios_questaoAll[$p]->obs;
                        } else 
                        if ($criterios_questaoAll[$p]->nome[0] == 'D') {
                            $dados_base_habilidade_questao[$j]->nome_D = $criterios_questaoAll[$p]->nome;
                            $dados_base_habilidade_questao[$j]->Obs_D = $criterios_questaoAll[$p]->obs;
                        }
                    }
                }
            }
        }

        return $dados_base_habilidade_questao;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();
        $ano_same_selecionado = $anos_same[0]->SAME;

        //Busca os previlégios do Usuário Logado
        $previlegio = $this->getPrevilegio();

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        $disciplinas = $this->getDisciplinas();

        //------------------------------------------ Solicitações -----------------------------------------------------------------
        if ($previlegio[0]->funcaos_id == 6) {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
        } else {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
        }
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------- Municípios -----------------------------------------------------------------
        $municipios = $this->getMunicipios($ano_same_selecionado);

        //------------------------------------------- Escolas -----------------------------------------------------------------
        $escolas = $this->getEscolasProfessor($municipios[0]->id, null, $ano_same_selecionado);

        //------------------------------------------- Turmas -------------------------------------------------------------------
        $turmas = $this->getTurmasProfessor($escolas[0]->id, $ano_same_selecionado);

        //Realiza a busca dos Destaques
        $destaques = $this->objDestaque->orderBy('updated_at', 'desc')->get();

        //Busca as Questões
        $questoes = $this->getQuestoes();

        //Busca as Legendas
        $legendas = $this->getLegendas();

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
        $turma_selecionada = $this->getTurmaSelecionada($turma, $ano_same_selecionado);
        //Carrega dados da Escola Selecionda
        $escola_selecionada = $this->getEscolaSelecionada($turma_selecionada[0]->escolas_id, $ano_same_selecionado);

        //Carrega os dados do Município Selecionda
        $municipio_selecionado = $this->getMunicipioSelecionado($turma_selecionada[0]->escolas_municipios_id, $ano_same_selecionado);

        //Carrega os dados da Disciplina Selecionada
        $disciplina_selecionada = $this->getDisciplinaSelecionada($disciplinas[0]->id);

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = $this->getCriteriosQuestao($ano,$disciplina_selecionada[0]->id);

        //Busca dados Média Turma ---------------------------------------------------------------------------------------------------------------
        $dados_base_turma = $this-> estatisticaBaseTurma($this->confPresenca,$turma,$ano,$ano_same_selecionado);
        $dados_comparacao_turma = $this->estatisticaComparacaoTurma($this->confPresenca,$turma,$ano,$ano_same_selecionado);
        //Divide em 2 por linha para gerar os cards
        $dados_base_turma = array_chunk($dados_base_turma, 2);
        //---------------------------------------------------------------------------------------------------------------------------------------



        //Busca dados Sessão Tema na Disciplina -------------------------------------------------------------------------------------------------
        $dados_base_grafico = $this-> estatisticaBaseGrafico($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 4 por Linha para gerar os Cards
        $dados_base_tema = array_chunk($dados_base_grafico, 4);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Habilidade Disciplina ----------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_grafico = $this-> estatisticaHabilidadeDisciplinaGrafico($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_base = $this-> estatisticaAjustePercentualBase($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca as habilidades
        $habilidades = $this->getHabilidadesProfessor($disciplina_selecionada[0]->id, $turma, $ano_same_selecionado);
        //Busca dados das Questões da Sessão Habilidade Disciplina
        $dados_base_habilidade_questao = $this-> estatisticaDisciplinaQuestao($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $habilidades[0]->id_habilidade);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Ano Habilidades ----------------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico  = $this-> estatisticaHabilidadeDisciplinaAnoGrafico($this->confPresenca, $escola_selecionada[0]->id, $disciplina_selecionada[0]->id,$ano[0],$ano_same_selecionado);      
        $dados_ajuste_percentual= $this-> estatisticaAjustePercentual($this->confPresenca, $escola_selecionada[0]->id, $disciplina_selecionada[0]->id,$anos[0],$ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        //Busca dados Questões Habilidade
        $dados_base_habilidade_ano_questao= $this-> estatisticaHabilidadeAnoQuestao($this->confPresenca,$escola_selecionada[0]->id, $disciplina_selecionada[0]->id,$anos[0],$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados da Habilidade Selecionada  
        $habilidade_selecionada = $this->getHabilidadeSelecionada($habilidades[0]->id_habilidade);  

        //Busca dados Sessão Habilidade Selecionda ----------------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico_habilidade = $this->  estatisticaHabilidadeDisciplinaHabilidade($this->confPresenca, $escola_selecionada[0]->id, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = $this->estatisticaAjustePercentualAno($this->confPresenca, $escola_selecionada[0]->id, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide em 6 por linha para gerar Cards
        $dados_base_habilidades_disciplina_habilidade = array_chunk($dados_base_habilidade_disciplina_grafico_habilidade, 6);
        //Busca dados questão habilidade individual
        $dados_base_habilidade_questao_habilidade = $this->estatisticaBaseHabilidadeQuestaoHabilidade($this->confPresenca, $escola_selecionada[0]->id, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade,$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------
        
        //Busca dados Sessão Questões Disciplinas -----------------------------------------------------------------------------------------------
        $dados_base_questao_grafico_disciplina = $this->estatisticaQuestaoGraficoDisciplina($this->confPresenca,$turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_questao = $this->estatisticaAjustePercentualQuestao($this->confPresenca,$turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
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
        $dados_base_aluno_grafico_disciplina = $this->estatisticaBaseAlunoGraficoDisciplina($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_aluno_disciplina = array_chunk($dados_base_aluno_grafico_disciplina, 6);
        //---------------------------------------------------------------------------------------------------------------------------------------------------------------

        $dados_base_habilidade_questao = $this->getHabilidadesCriterios($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);

        $sessao_inicio = "turma";

        return view('professor/professor', compact(

            'solRegistro','solAltCadastral','solAddTurma','turmas','municipios','questoes','dados_base_turma','turma_selecionada','destaques',
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
        $previlegio = $this->getPrevilegio();

        $ano_same_selecionado = $ano_same;

        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        $disciplinas = $this->getDisciplinas();
        //----------------------------------------------------------------------------------------------------------------------


        //------------------------------------------ Solicitações -----------------------------------------------------------------
        if ($previlegio[0]->funcaos_id == 6) {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
        } else {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
        }
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------- Municípios -----------------------------------------------------------------
        $municipios = $this->getMunicipios($ano_same_selecionado);
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------- Turmas -------------------------------------------------------------------
        $turmas = $this->getTurmasProfessor($id_escola, $ano_same_selecionado);
        if(!isset($turmas) || sizeof($turmas) == 0){
            $escolas = $this->getEscolasProfessor($municipios[0]->id, null, $ano_same_selecionado);
            $turmas = $this->getTurmasProfessor($escolas[0]->id, $ano_same_selecionado);
        }
        //----------------------------------------------------------------------------------------------------------------------

        $turma = $turmas[0]->id;
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if($turmas[$i]->id == $id){
                $turma = $id;   
            }    
        }
       
        $turma_selecionada = $this->getTurmaSelecionada($turma, $ano_same_selecionado);

        //------------------------------------------- Escolas -----------------------------------------------------------------
        $escolas = $this->getEscolasProfessor($turma_selecionada[0]->escolas_municipios_id, $id_escola, $ano_same_selecionado);

        //----------------------------------------------------------------------------------------------------------------------

        //Busca as Questões
        $questoes = $this->getQuestoes();

        //Busca os Destaques
        $destaques = $this->objDestaque->orderBy('updated_at', 'desc')->get();

        //Busca as Legendas
        $legendas = $this->getLegendas();

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
        $disciplina_selecionada = $this->getDisciplinaSelecionada($id_disciplina);

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = $this->getCriteriosQuestao($ano,$disciplina_selecionada[0]->id);

        //Carrega dados da Escola Selecionda
        $escola_selecionada = $this->getEscolaSelecionada($turma_selecionada[0]->escolas_id, $ano_same_selecionado);

        //Carrega os dados do Município Selecionda
        $municipio_selecionado = $this->getMunicipioSelecionado($turma_selecionada[0]->escolas_municipios_id, $ano_same_selecionado);

        //Busca dados Média Turma ---------------------------------------------------------------------------------------------------------------
        $dados_base_turma = $this-> estatisticaBaseTurma($this->confPresenca,$turma,$ano,$ano_same_selecionado);
        $dados_comparacao_turma = $this->estatisticaComparacaoTurma($this->confPresenca,$turma,$ano,$ano_same_selecionado);
        //Divide em 2 por linha para gerar os cards
        $dados_base_turma = array_chunk($dados_base_turma, 2);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Tema na Disciplina -------------------------------------------------------------------------------------------------
        $dados_base_grafico = $this-> estatisticaBaseGrafico($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 4 por Linha para gerar os Cards
        $dados_base_tema = array_chunk($dados_base_grafico, 4);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca as habilidades
        $habilidades = $this->getHabilidadesProfessor($disciplina_selecionada[0]->id, $turma, $ano_same_selecionado);

        //Busca dados Sessão Habilidade Disciplina ----------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_grafico = $this-> estatisticaHabilidadeDisciplinaGrafico($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_base = $this-> estatisticaAjustePercentualBase($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca dados das Questões da Sessão Habilidade Disciplina
        $dados_base_habilidade_questao = $this-> estatisticaDisciplinaQuestao($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Ano Habilidades ----------------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico  = $this-> estatisticaHabilidadeDisciplinaAnoGrafico($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);      
        $dados_ajuste_percentual= $this-> estatisticaAjustePercentual($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        //Busca dados Questões Habilidade
        $dados_base_habilidade_ano_questao= $this-> estatisticaHabilidadeAnoQuestao($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados da Habilidade Selecionada  
        $habilidade_selecionada = $this->getHabilidadeSelecionada($habilidades[0]->id_habilidade);  

        //Busca dados Sessão Habilidade Selecionda ----------------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico_habilidade = $this->  estatisticaHabilidadeDisciplinaHabilidade($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = $this->estatisticaAjustePercentualAno($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide em 6 por linha para gerar Cards
        $dados_base_habilidades_disciplina_habilidade = array_chunk($dados_base_habilidade_disciplina_grafico_habilidade, 6);
        //Busca dados questão habilidade individual
        $dados_base_habilidade_questao_habilidade = $this->estatisticaBaseHabilidadeQuestaoHabilidade($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade,$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------       

        //Busca dados Sessão Questões Disciplinas -----------------------------------------------------------------------------------------------
        $dados_base_questao_grafico_disciplina = $this->estatisticaQuestaoGraficoDisciplina($this->confPresenca,$turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_questao = $this->estatisticaAjustePercentualQuestao($this->confPresenca,$turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
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
        $dados_base_aluno_grafico_disciplina = $this->estatisticaBaseAlunoGraficoDisciplina($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_aluno_disciplina = array_chunk($dados_base_aluno_grafico_disciplina, 6);
        //---------------------------------------------------------------------------------------------------------------------------------------------------------------

        $dados_base_habilidade_questao = $this->getHabilidadesCriterios($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);

        $sessao_inicio = "turma";

        return view('professor/professor', compact(
            'solRegistro','solAltCadastral','solAddTurma','turmas','municipios','questoes','destaques','dados_base_turma','turma_selecionada','dados_base_tema','dados_base_grafico',
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
        $previlegio = $this->getPrevilegio();

        $ano_same_selecionado = $ano_same;

        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        $disciplinas = $this->getDisciplinas();
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------ Solicitações -----------------------------------------------------------------
        if ($previlegio[0]->funcaos_id == 6) {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
        } else {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
        }
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------- Municípios -----------------------------------------------------------------
        $municipios = $this->getMunicipios($ano_same_selecionado);
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------- Turmas -------------------------------------------------------------------
        $turmas = $this->getTurmasProfessor($id_escola, $ano_same_selecionado);
        if(!isset($turmas) || sizeof($turmas) == 0){
            $escolas = $this->getEscolasProfessor($municipios[0]->id, null, $ano_same_selecionado);
            $turmas = $this->getTurmasProfessor($escolas[0]->id, $ano_same_selecionado);
        }
        //----------------------------------------------------------------------------------------------------------------------

        $turma = $turmas[0]->id;
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if($turmas[$i]->id == $id){
                $turma = $id;   
            }    
        }
        $turma_selecionada = $this->getTurmaSelecionada($turma, $ano_same_selecionado);

        //------------------------------------------- Escolas -----------------------------------------------------------------
        $escolas = $this->getEscolasProfessor($turma_selecionada[0]->escolas_municipios_id, $id_escola, $ano_same_selecionado);
        //----------------------------------------------------------------------------------------------------------------------

        //Busca as Questões
        $questoes = $this->getQuestoes();

        //Busca os Destaques
        $destaques = $this->objDestaque->orderBy('updated_at', 'desc')->get();

        //Busca as Legendas
        $legendas = $this->getLegendas();

        //Seta os Anos a serem utilizados no seletor
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o Ano seleciondao
        $disciplina_selecionada = $this->getDisciplinaSelecionada($id_disciplina);

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = $this->getCriteriosQuestao($ano,$disciplina_selecionada[0]->id);

        //Carrega dados da Escola Selecionda
        $escola_selecionada = $this->getEscolaSelecionada($turma_selecionada[0]->escolas_id, $ano_same_selecionado);

        //Carrega os dados do Município Selecionda
        $municipio_selecionado = $this->getMunicipioSelecionado($turma_selecionada[0]->escolas_municipios_id, $ano_same_selecionado);

        //Busca dados Média Turma ---------------------------------------------------------------------------------------------------------------
        $dados_base_turma = $this-> estatisticaBaseTurma($this->confPresenca,$turma,substr(trim($turma_selecionada[0]->DESCR_TURMA), 0, 2),$ano_same_selecionado);
        $dados_comparacao_turma = $this->estatisticaComparacaoTurma($this->confPresenca,$turma,substr(trim($turma_selecionada[0]->DESCR_TURMA), 0, 2),$ano_same_selecionado);
        //Divide em 2 por linha para gerar os cards
        $dados_base_turma = array_chunk($dados_base_turma, 2);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Tema na Disciplina -------------------------------------------------------------------------------------------------
        $dados_base_grafico = $this-> estatisticaBaseGrafico($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 4 por Linha para gerar os Cards
        $dados_base_tema = array_chunk($dados_base_grafico, 4);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca as habilidades
        $habilidades = $this->getHabilidadesProfessor($disciplina_selecionada[0]->id, $turma, $ano_same_selecionado);
            
        //Busca dados Sessão Habilidade Disciplina ----------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_grafico = $this-> estatisticaHabilidadeDisciplinaGrafico($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_base = $this-> estatisticaAjustePercentualBase($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca dados das Questões da Sessão Habilidade Disciplina
        $dados_base_habilidade_questao = $this-> estatisticaDisciplinaQuestao($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Ano Habilidades ----------------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico  = $this-> estatisticaHabilidadeDisciplinaAnoGrafico($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);      
        $dados_ajuste_percentual= $this-> estatisticaAjustePercentual($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        //Busca dados Questões Habilidade
        $dados_base_habilidade_ano_questao= $this-> estatisticaHabilidadeAnoQuestao($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------
        
        $ano_selecionado = $ano;

        //Busca dados da Habilidade Selecionada     
        $habilidade_selecionada = $this->getHabilidadeSelecionada($habilidades[0]->id_habilidade); 

        //Busca dados Sessão Habilidade Selecionda ----------------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico_habilidade = $this->  estatisticaHabilidadeDisciplinaHabilidade($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = $this->estatisticaAjustePercentualAno($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide em 6 por linha para gerar Cards
        $dados_base_habilidades_disciplina_habilidade = array_chunk($dados_base_habilidade_disciplina_grafico_habilidade, 6);
        //Busca dados questão habilidade individual
        $dados_base_habilidade_questao_habilidade = $this->estatisticaBaseHabilidadeQuestaoHabilidade($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade,$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Questões Disciplinas -----------------------------------------------------------------------------------------------
        $dados_base_questao_grafico_disciplina = $this->estatisticaQuestaoGraficoDisciplina($this->confPresenca,$turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_questao = $this->estatisticaAjustePercentualQuestao($this->confPresenca,$turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
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
        $dados_base_aluno_grafico_disciplina = $this->estatisticaBaseAlunoGraficoDisciplina($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_aluno_disciplina = array_chunk($dados_base_aluno_grafico_disciplina, 6);
        //---------------------------------------------------------------------------------------------------------------------------------------------------------------

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        if ($disciplina_selecionada[0]->id == 2) {
            $testeProfessor=31;
            $criterios_questaoAno = $this->objCriterioQuestao->where(['ano' => $ano_selecionado[0]])->get();
        } else {
            //Nos demais não existe esse filtro adicional
            $testeProfessor=32;
            $criterios_questaoAno = $this->objCriterioQuestao->all();
        }

        $dados_base_habilidade_questao = $this->getHabilidadesCriterios($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);

        $sessao_inicio = "habilidadeanodisciplina";

        return view('professor/professor', compact(
            'criterios_questaoAno','solRegistro','solAltCadastral','solAddTurma','turmas','municipios','questoes','destaques','dados_base_turma','turma_selecionada',
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
         $previlegio = $this->getPrevilegio();

         $ano_same_selecionado = $ano_same;
 
         //Listagem de Anos do SAME
         $anos_same = $this->getAnosSAME();
 
         //----------------------------------------- Disciplinas ----------------------------------------------------------------
         $disciplinas = $this->getDisciplinas();
         //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------ Solicitações -----------------------------------------------------------------
        if ($previlegio[0]->funcaos_id == 6) {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
        } else {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
        }
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------- Municípios -----------------------------------------------------------------
        $municipios = $this->getMunicipios($ano_same_selecionado);
        //----------------------------------------------------------------------------------------------------------------------

        //------------------------------------------- Turmas -------------------------------------------------------------------
        $turmas = $this->getTurmasProfessor($id_escola, $ano_same_selecionado);
        if(!isset($turmas) || sizeof($turmas) == 0){
            $escolas = $this->getEscolasProfessor($municipios[0]->id, null, $ano_same_selecionado);
            $turmas = $this->getTurmasProfessor($escolas[0]->id, $ano_same_selecionado);
         }
        //----------------------------------------------------------------------------------------------------------------------

        $turma = $turmas[0]->id;
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if($turmas[$i]->id == $id){
                $turma = $id;   
            }    
        }
        $turma_selecionada = $this->getTurmaSelecionada($turma, $ano_same_selecionado);

        //------------------------------------------- Escolas -----------------------------------------------------------------
        $escolas = $this->getEscolasProfessor($turma_selecionada[0]->escolas_municipios_id, $id_escola, $ano_same_selecionado);
        //----------------------------------------------------------------------------------------------------------------------

        //Busca as Questões
        $questoes = $this->getQuestoes();

        //Busca os Destaques
        $destaques = $this->objDestaque->orderBy('updated_at', 'desc')->get();

        //Busca as Legendas
        $legendas = $this->getLegendas();

        //Seta os Anos a serem utilizados no seletor
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o Ano seleciondao
        $disciplina_selecionada = $this->getDisciplinaSelecionada($id_disciplina);

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = $this->getCriteriosQuestao($ano,$disciplina_selecionada[0]->id);

        //Carrega dados da Escola Selecionda
        $escola_selecionada = $this->getEscolaSelecionada($turma_selecionada[0]->escolas_id, $ano_same_selecionado);

        //Carrega os dados do Município Selecionda
        $municipio_selecionado = $this->getMunicipioSelecionado($turma_selecionada[0]->escolas_municipios_id, $ano_same_selecionado);

        //Busca dados Média Turma ---------------------------------------------------------------------------------------------------------------
        $dados_base_turma = $this-> estatisticaBaseTurma($this->confPresenca,$turma,substr(trim($turma_selecionada[0]->DESCR_TURMA), 0, 2),$ano_same_selecionado);
        $dados_comparacao_turma = $this->estatisticaComparacaoTurma($this->confPresenca,$turma,substr(trim($turma_selecionada[0]->DESCR_TURMA), 0, 2),$ano_same_selecionado);
        //Divide em 2 por linha para gerar os cards
        $dados_base_turma = array_chunk($dados_base_turma, 2);
        //---------------------------------------------------------------------------------------------------------------------------------------
   
        //Busca dados Sessão Tema na Disciplina -------------------------------------------------------------------------------------------------
        $dados_base_grafico = $this-> estatisticaBaseGrafico($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 4 por Linha para gerar os Cards
        $dados_base_tema = array_chunk($dados_base_grafico, 4);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca as habilidades
        $habilidades = $this->getHabilidadesProfessor($disciplina_selecionada[0]->id, $turma, $ano_same_selecionado);

        //Busca dados da Habilidade Selecionada     
        $habilidade_selecionada = $this->getHabilidadeSelecionada($id_habilidade); 

        //Busca dados Sessão Habilidade Disciplina ----------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_grafico = $this-> estatisticaHabilidadeDisciplinaGrafico($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_base = $this-> estatisticaAjustePercentualBase($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os Cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca dados das Questões da Sessão Habilidade Disciplina
        $dados_base_habilidade_questao = $this-> estatisticaDisciplinaQuestao($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Ano Habilidades ----------------------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico  = $this-> estatisticaHabilidadeDisciplinaAnoGrafico($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);      
        $dados_ajuste_percentual= $this-> estatisticaAjustePercentual($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        //Busca dados Questões Habilidade
        $dados_base_habilidade_ano_questao= $this-> estatisticaHabilidadeAnoQuestao($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        $ano_selecionado = $ano;

        //Busca dados Sessão Habilidade Selecionda ----------------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico_habilidade = $this->  estatisticaHabilidadeDisciplinaHabilidade($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = $this->estatisticaAjustePercentualAno($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide em 6 por linha para gerar Cards
        $dados_base_habilidades_disciplina_habilidade = array_chunk($dados_base_habilidade_disciplina_grafico_habilidade, 6);
        //Busca dados questão habilidade individual
        $dados_base_habilidade_questao_habilidade = $this->estatisticaBaseHabilidadeQuestaoHabilidade($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade,$ano_same_selecionado);
        //---------------------------------------------------------------------------------------------------------------------------------------

        //Busca dados Sessão Questões Disciplinas -----------------------------------------------------------------------------------------------
        $dados_base_questao_grafico_disciplina = $this->estatisticaQuestaoGraficoDisciplina($this->confPresenca,$turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
        $dados_ajuste_percentual_questao = $this->estatisticaAjustePercentualQuestao($this->confPresenca,$turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
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
        $dados_base_aluno_grafico_disciplina = $this->estatisticaBaseAlunoGraficoDisciplina($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide em 6 por linha para gerar os cards
        $dados_base_aluno_disciplina = array_chunk($dados_base_aluno_grafico_disciplina, 6);
        //---------------------------------------------------------------------------------------------------------------------------------------------------------------
        
        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        if ($disciplina_selecionada[0]->id == 2) {
            $testeProfessor=41;
            $criterios_questaoAno = $this->objCriterioQuestao->where(['ano' => $ano_selecionado[0]])->get();
        } else {
             //Nos demais não existe esse filtro adicional
            $testeProfessor=42;
            $criterios_questaoAno = $this->objCriterioQuestao->all();
        }

        $dados_base_habilidade_questao = $this->getHabilidadesCriterios($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);

        $sessao_inicio = "habilidadeselecionadadisciplina";

        return view('professor/professor', compact(
            'criterios_questaoAno','solRegistro','solAltCadastral','solAddTurma','turmas','municipios','questoes','destaques','dados_base_turma','dados_comparacao_turma','ano',
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
         $previlegio = $this->getPrevilegio();
 
         $ano_same_selecionado = $ano_same;
 
         //Listagem de Anos do SAME
         $anos_same = $this->getAnosSAME();
 
         //----------------------------------------- Disciplinas ----------------------------------------------------------------
         $disciplinas = $this->getDisciplinas();
         //----------------------------------------------------------------------------------------------------------------------
 
 
         //------------------------------------------ Solicitações -----------------------------------------------------------------
         if ($previlegio[0]->funcaos_id == 6) {
             $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
             $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
             $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
         } else {
             $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
             $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
             $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
         }
         //----------------------------------------------------------------------------------------------------------------------
 
         //------------------------------------------- Municípios -----------------------------------------------------------------
         $municipios = $this->getMunicipios($ano_same_selecionado);
         //----------------------------------------------------------------------------------------------------------------------
 
         //------------------------------------------- Turmas -------------------------------------------------------------------
         $turmas = $this->getTurmasProfessor($id_escola, $ano_same_selecionado);
         if(!isset($turmas) || sizeof($turmas) == 0){
            $escolas = $this->getEscolasProfessor($municipios[0]->id, null, $ano_same_selecionado);
            $turmas = $this->getTurmasProfessor($escolas[0]->id, $ano_same_selecionado);
         }
         //----------------------------------------------------------------------------------------------------------------------
 
         $turma = $turmas[0]->id;
         $turma_selecionada = $this->getTurmaSelecionada($turma, $ano_same_selecionado);
 
         //------------------------------------------- Escolas -----------------------------------------------------------------
         $escolas = $this->getEscolasProfessor($turma_selecionada[0]->escolas_municipios_id, $id_escola, $ano_same_selecionado);
 
         //----------------------------------------------------------------------------------------------------------------------
 
         //Busca as Questões
         $questoes = $this->getQuestoes();
 
         //Busca os Destaques
         $destaques = $this->objDestaque->orderBy('updated_at', 'desc')->get();
 
         //Busca as Legendas
         $legendas = $this->getLegendas();
 
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
         $disciplina_selecionada = $this->getDisciplinaSelecionada($id_disciplina);
 
         //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
         $criterios_questao = $this->getCriteriosQuestao($ano,$disciplina_selecionada[0]->id);
 
         //Carrega dados da Escola Selecionda
         $escola_selecionada = $this->getEscolaSelecionada($turma_selecionada[0]->escolas_id, $ano_same_selecionado);
 
         //Carrega os dados do Município Selecionda
         $municipio_selecionado = $this->getMunicipioSelecionado($turma_selecionada[0]->escolas_municipios_id, $ano_same_selecionado);
 
         //Busca dados Média Turma ---------------------------------------------------------------------------------------------------------------
         $dados_base_turma = $this-> estatisticaBaseTurma($this->confPresenca,$turma,$ano,$ano_same_selecionado);
         $dados_comparacao_turma = $this->estatisticaComparacaoTurma($this->confPresenca,$turma,$ano,$ano_same_selecionado);
         //Divide em 2 por linha para gerar os cards
         $dados_base_turma = array_chunk($dados_base_turma, 2);
         //---------------------------------------------------------------------------------------------------------------------------------------
 
         //Busca dados Sessão Tema na Disciplina -------------------------------------------------------------------------------------------------
         $dados_base_grafico = $this-> estatisticaBaseGrafico($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
         //Divide em 4 por Linha para gerar os Cards
         $dados_base_tema = array_chunk($dados_base_grafico, 4);
         //---------------------------------------------------------------------------------------------------------------------------------------
 
         //Busca as habilidades
         $habilidades = $this->getHabilidadesProfessor($disciplina_selecionada[0]->id, $turma, $ano_same_selecionado);
 
         //Busca dados Sessão Habilidade Disciplina ----------------------------------------------------------------------------------------------
         $dados_base_habilidade_disciplina_grafico = $this-> estatisticaHabilidadeDisciplinaGrafico($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
         $dados_ajuste_percentual_base = $this-> estatisticaAjustePercentualBase($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
         //Divide em 6 por linha para gerar os Cards
         $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
         //Busca dados das Questões da Sessão Habilidade Disciplina
         $dados_base_habilidade_questao = $this-> estatisticaDisciplinaQuestao($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
         //---------------------------------------------------------------------------------------------------------------------------------------
 
         //Busca dados Sessão Ano Habilidades ----------------------------------------------------------------------------------------------------
         $dados_base_habilidade_disciplina_ano_grafico  = $this-> estatisticaHabilidadeDisciplinaAnoGrafico($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);      
         $dados_ajuste_percentual= $this-> estatisticaAjustePercentual($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
         //Divide em 6 por linha para gerar os cards
         $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
         //Busca dados Questões Habilidade
         $dados_base_habilidade_ano_questao= $this-> estatisticaHabilidadeAnoQuestao($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id,$ano,$ano_same_selecionado);
         //---------------------------------------------------------------------------------------------------------------------------------------
 
         //Busca dados da Habilidade Selecionada  
         $habilidade_selecionada = $this->getHabilidadeSelecionada($habilidades[0]->id_habilidade);  
 
         //Busca dados Sessão Habilidade Selecionda ----------------------------------------------------------------------------------------------    
         $dados_base_habilidade_disciplina_grafico_habilidade = $this->  estatisticaHabilidadeDisciplinaHabilidade($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
         $dados_ajuste_percentual_ano = $this->estatisticaAjustePercentualAno($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
         //Divide em 6 por linha para gerar Cards
         $dados_base_habilidades_disciplina_habilidade = array_chunk($dados_base_habilidade_disciplina_grafico_habilidade, 6);
         //Busca dados questão habilidade individual
         $dados_base_habilidade_questao_habilidade = $this->estatisticaBaseHabilidadeQuestaoHabilidade($this->confPresenca, $id_escola, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade,$ano_same_selecionado);
         //---------------------------------------------------------------------------------------------------------------------------------------       
 
         //Busca dados Sessão Questões Disciplinas -----------------------------------------------------------------------------------------------
         $dados_base_questao_grafico_disciplina = $this->estatisticaQuestaoGraficoDisciplina($this->confPresenca,$turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
         $dados_ajuste_percentual_questao = $this->estatisticaAjustePercentualQuestao($this->confPresenca,$turma,$disciplina_selecionada[0]->id, $ano_same_selecionado);
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
         $dados_base_aluno_grafico_disciplina = $this->estatisticaBaseAlunoGraficoDisciplina($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);
         //Divide em 6 por linha para gerar os cards
         $dados_base_aluno_disciplina = array_chunk($dados_base_aluno_grafico_disciplina, 6);
         //---------------------------------------------------------------------------------------------------------------------------------------------------------------
 
         $dados_base_habilidade_questao = $this->getHabilidadesCriterios($this->confPresenca, $turma, $disciplina_selecionada[0]->id, $ano_same_selecionado);

         $sessao_inicio = "turma";
 
         return view('professor/professor', compact(
             'solRegistro','solAltCadastral','solAddTurma','turmas','municipios','questoes','destaques','dados_base_turma','turma_selecionada','dados_base_tema','dados_base_grafico',
             'dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_questao_grafico_disciplina','dados_base_questao_disciplina','escolas','disciplinas',
             'disciplina_selecionada','escola_selecionada','municipio_selecionado','legendas','dados_base_aluno_grafico_disciplina','dados_base_aluno_disciplina','anos',
             'dados_base_habilidade_questao','ano','dados_base_habilidade_disciplina_ano_grafico','dados_base_habilidades_ano_disciplina','dados_base_habilidade_ano_questao',
             'tipos_questoes','criterios_questao','habilidades','habilidade_selecionada','dados_base_habilidade_disciplina_grafico_habilidade','dados_base_habilidades_disciplina_habilidade',
             'dados_base_habilidade_questao_habilidade','dados_ajuste_percentual','dados_ajuste_percentual_ano','dados_ajuste_percentual_base','dados_ajuste_percentual_questao','anos_same',
             'ano_same_selecionado','dados_comparacao_turma','sessao_inicio'));
     }

}
