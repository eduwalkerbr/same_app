<?php

namespace App\Http\Controllers\staticmethods\proficiencia;

use App\Models\DadoUnificado;
use App\Models\Escola;
use App\Models\Turma;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Http\Controllers\staticmethods\gerais\MethodsGerais;
use App\Models\DirecaoProfessor;

class MethodsProfTurma extends Controller
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
     * Método que obtém listagem de escolas do Professor, pelo município, escola e Ano SAME
     */
    public static function getEscolasProfessor($id_municipio, $id_escola, $ano_same){
        if ((auth()->user()->perfil == 'Administrador') || ((MethodsGerais::getPrevilegio()[0]->funcaos_id == 13 
        || MethodsGerais::getPrevilegio()[0]->funcaos_id == 14) && MethodsGerais::getPrevilegio()[0]->municipios_id == 5)
        || MethodsGerais::getPrevilegio()[0]->funcaos_id == 8) {
            if(Cache::has('escolas_'.strval($id_municipio).strval($ano_same))){
                $escolasListadas = Cache::get('escolas_'.strval($id_municipio).strval($ano_same));
            } else {
                $escolasListadas = Escola::where(['status' => 'Ativo', 'municipios_id' => $id_municipio, 'SAME' => $ano_same])->get();
                //Adiciona ao Cache
                Cache::put('escolas_'.strval($id_municipio).strval($ano_same), $escolasListadas, now()->addHours(config('constants.options.horas_cache')));
            }
        } else {
            if(isset($id_escola)){
                if(Cache::has('prof_escolas_func'.strval($id_escola))){
                    $escolasListadas = Cache::get('prof_escolas_func'.strval($id_escola));
                } else {
                    $escolasListadas = Escola::where(['status' => 'Ativo', 'id' => $id_escola, 'SAME' => $ano_same])->get();
                    Cache::put('prof_escolas_func'.strval($id_escola), $escolasListadas, now()->addHours(config('constants.options.horas_cache')));
                }
            } else {
                //Restante vê apenas município do previlégio
                if(Cache::has('escolas_prev'.strval(MethodsGerais::getPrevilegio()[0]->id).strval($ano_same))){
                    $escolasListadas = Cache::get('escolas_prev'.strval(MethodsGerais::getPrevilegio()[0]->id).strval($ano_same));
                } else {
                    $direcaoProfessores = DirecaoProfessor::where(['id_previlegio' => MethodsGerais::getPrevilegio()[0]->id])->get();
                    $id_escolas = [];
                    for ($e = 0; $e < sizeof($direcaoProfessores); $e++) {
                        $id_escolas[$e] = $direcaoProfessores[$e]->id_escola;
                    }
                    $escolasListadas = Escola::whereIn('id', $id_escolas)->where('SAME',$ano_same)->get();
                    //Adiciona ao Cache
                    Cache::put('escolas_prev'.strval(MethodsGerais::getPrevilegio()[0]->id).strval($ano_same), $escolasListadas, now()->addHours(config('constants.options.horas_cache')));
                }
            }
            
        }

        return $escolasListadas;
    }

    /**
     * Método que obtém listagem de turmas pela Escola, Ano SAME e Previlégio dos Usuários
     */
    public static function getTurmasProfessor($id_escola, $ano_same){
        if ((auth()->user()->perfil == 'Administrador') || ((MethodsGerais::getPrevilegio()[0]->funcaos_id == 13 
        || MethodsGerais::getPrevilegio()[0]->funcaos_id == 14) && MethodsGerais::getPrevilegio()[0]->municipios_id == 5)
        || MethodsGerais::getPrevilegio()[0]->funcaos_id == 5 || MethodsGerais::getPrevilegio()[0]->funcaos_id == 8) {
            if(Cache::has('turmas_prof'.strval($id_escola).strval($ano_same))){
                $turmas = Cache::get('turmas_prof'.strval($id_escola).strval($ano_same));
            } else {
                $turmas = Turma::where(['status' => 'Ativo', 'escolas_id' => $id_escola, 'SAME' => $ano_same])->orderBy('TURMA','asc')->get();
                //Adiciona ao Cache
                Cache::put('turmas_prof'.strval($id_escola).strval($ano_same), $turmas, now()->addHours(config('constants.options.horas_cache')));
            }
        } else {
            if(Cache::has('turmas_prof_prev'.strval(MethodsGerais::getPrevilegio()[0]->id).strval($ano_same))){
                $turmas = Cache::get('turmas_prof_prev'.strval(MethodsGerais::getPrevilegio()[0]->id).strval($ano_same));
            } else {
                $direcaoProfessores = DirecaoProfessor::where(['id_previlegio' => MethodsGerais::getPrevilegio()[0]->id])->get();
                $id_turmas = [];
                for ($r = 0; $r < sizeof($direcaoProfessores); $r++) {
                    $id_turmas[$r] = $direcaoProfessores[$r]->id_turma;
                }
                $turmas = Turma::whereIn('id', $id_turmas)->where('SAME', $ano_same)->orderBy('TURMA','asc')->get();
                Cache::put('turmas_prov_prev'.strval(MethodsGerais::getPrevilegio()[0]->id).strval($ano_same), $turmas, now()->addHours(config('constants.options.horas_cache')));
            }
        }
        
        return $turmas;
    }

    /**
     * Método que obtém os dados de Comparação entre Proficiência Média e da
     * Turma, pelo Ano e Ano SAME
     */
    public static function estatisticaBaseTurma($turma, $ano, $ano_same){ 
        //Busca dados Média Turma

        $ano = intval($ano);

        if(Cache::has('prof_dados_base'.strval($turma).strval($ano).strval($ano_same))){
            $dados_base_turma = Cache::get('prof_dados_base'.strval($turma).strval($ano).strval($ano_same));
        } else {
            $dados_base_turma = DB::select(
                'SELECT (ac.acertos*100)/(qtd_questao.num) AS percentual, \'Proficiência Média\' AS descricao FROM dado_unificados du 
                    LEFT JOIN ( SELECT count(id) AS num 
                                FROM dado_unificados 
                                WHERE presenca > :presenca1 AND SAME = :same AND id_escola = (SELECT id_escola FROM turmas WHERE id = :id_turma1 AND SAME = :same1) AND ano = :ano1) AS qtd_questao ON TRUE 
                    LEFT JOIN ( SELECT SUM(acerto) AS acertos 
                                FROM dado_unificados 
                                WHERE presenca > :presenca2 AND SAME = :same2 AND id_escola = (SELECT id_escola FROM turmas WHERE id = :id_turma2 AND SAME = :same3) AND ano = :ano2) AS ac ON TRUE 
                UNION
                    SELECT (ac.acertos*100)/(qtd_questao.num) AS percentual,\'Proficiência Turma\' AS descricao  
                        FROM dado_unificados du 
                    LEFT JOIN (SELECT count(id) AS num FROM dado_unificados 
                                WHERE id_turma = :id_turma3 AND SAME = :same4 
                                        AND presenca > :presenca3
                                ) AS qtd_questao ON TRUE                               
                    LEFT JOIN (SELECT SUM(acerto) AS acertos FROM dado_unificados WHERE id_turma = :id_turma4 AND presenca > :presenca4 AND SAME = :same5) AS ac ON TRUE',
                ['presenca1' => config('constants.options.confPresenca'), 'presenca2' => config('constants.options.confPresenca'),
                 'presenca3' => config('constants.options.confPresenca'), 'presenca4' => config('constants.options.confPresenca'), 'id_turma1' => $turma, 'ano1' => $ano, 
                'id_turma2' => $turma, 'ano2' => $ano, 'id_turma3' => $turma, 'id_turma4' => $turma, 'same' => $ano_same, 'same1' => $ano_same, 'same2' => $ano_same, 'same3' => $ano_same, 
                'same4' => $ano_same, 'same5' => $ano_same]
            );

            Cache::forever('prof_dados_base'.strval($turma).strval($ano).strval($ano_same), $dados_base_turma);
        }
        
        return  $dados_base_turma;
    }

    /**
     * Método como obtém as dados Comparativos entre Proficiência da Turma e a Média 
     * das Turmas do Presente Ano e Ano SAME
     */
    public static function estatisticaComparacaoTurma($turma, $ano, $ano_same){ 

        $ano = intval($ano);

        if(Cache::has('prof_dad_comp'.strval($turma).strval($ano).strval($ano_same))){
            $dados_comparacao_turma = Cache::get('prof_dad_comp'.strval($turma).strval($ano).strval($ano_same));
        } else {
            $dados_comparacao_turma = DB::select(
                'SELECT (ac.acertos*100)/(qtd_questao.num) AS percentual, \'Proficiência Turma\' AS descricao 
                    FROM dado_unificados du 
                    LEFT JOIN ( SELECT count(id) AS num FROM dado_unificados WHERE presenca > :presenca1 AND SAME = :same AND id_turma = :id_turma1) AS qtd_questao ON TRUE 
                    LEFT JOIN ( SELECT SUM(acerto) AS acertos FROM dado_unificados WHERE presenca > :presenca2 AND SAME = :same2 AND id_turma = :id_turma2) AS ac ON TRUE 
                    UNION 
                    SELECT (ac.acertos*100)/(qtd_questao.num) AS percentual, \'Proficência Média\' AS descricao FROM dado_unificados du 
                    LEFT JOIN ( SELECT count(id) AS num FROM dado_unificados 
                            WHERE 
                                id_escola = (SELECT id_escola FROM turmas WHERE presenca > :presenca3 AND SAME = :same3 AND id = :id_turma3) AND ano = :ano1) AS qtd_questao ON TRUE 
                    LEFT JOIN ( SELECT SUM(acerto) AS acertos FROM dado_unificados WHERE presenca > :presenca4 AND SAME = :same4 
                    AND id_escola = (SELECT id_escola FROM turmas WHERE id = :id_turma4 AND SAME = :same5) AND ano = :ano2) AS ac ON TRUE ',
                ['presenca1' => config('constants.options.confPresenca'),'presenca2' => config('constants.options.confPresenca'),
                 'presenca3' => config('constants.options.confPresenca'),'presenca4' => config('constants.options.confPresenca'),
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
    public static function estatisticaBaseGrafico($turma, $disciplina, $ano_same){   
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
                 ['presenca' => config('constants.options.confPresenca'), 'id_turma' => $turma, 'id_disciplina' => $disciplina, 'same' => $ano_same]);
            Cache::forever('prof_tema_disc'.strval($turma).strval($disciplina).strval($ano_same), $dados_base_grafico);     
        }
        
        return  $dados_base_grafico;
    }

    /**
     * Método Sessão Habilidade Disciplina
     */
    public static function estatisticaHabilidadeDisciplinaGrafico($turma, $disciplina, $ano_same){   
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
                ['presenca' => config('constants.options.confPresenca'), 'id_turma' => $turma, 'id_disciplina' => $disciplina, 'same' => $ano_same]
            );

            $dados_ajuste_percentual_base = MethodsProfTurma::estatisticaAjustePercentualBase($turma, $disciplina, $ano_same);

            $legendas = MethodsGerais::getLegendas();

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
    public static function estatisticaAjustePercentualBase($turma, $disciplina, $ano_same){   
        if(Cache::has('prof_aj_perc'.strval($turma).strval($disciplina).strval($ano_same))){
            $dados_ajuste_percentual_base = Cache::get('prof_aj_perc'.strval($turma).strval($disciplina).strval($ano_same));
        } else {
            $dados_ajuste_percentual_base = DB::select(
                'SELECT sigla_habilidade, COUNT(id) AS qtd, resposta, id_habilidade, id_questao 
                FROM dado_unificados 
                WHERE presenca > :presenca AND  id_turma = :id_turma AND id_disciplina = :id_disciplina AND SAME = :same 
                    AND tipo_questao <> \'Objetivas\' AND resposta IS NOT NULL AND resposta <> \'\' GROUP BY sigla_habilidade, resposta, id_habilidade, id_questao',
                ['presenca' => config('constants.options.confPresenca'), 'id_turma' => $turma, 'id_disciplina' => $disciplina, 'same' => $ano_same]
            );
            Cache::forever('prof_aj_perc'.strval($turma).strval($disciplina).strval($ano_same), $dados_ajuste_percentual_base);
        }
        
        return  $dados_ajuste_percentual_base;
    }

    /**
     * Método que busca Habilidades pela Turma, Disciplina e Ano SAME
     */
    public static function getHabilidadesProfessor($disciplina, $turma, $ano_same){
        if(Cache::has('prof_habs'.strval($disciplina).strval($turma).strval($ano_same))){
            $habilidades = Cache::get('prof_habs'.strval($disciplina).strval($turma).strval($ano_same));
        } else {
            $habilidades = DadoUnificado::select('id_habilidade', 'nome_habilidade', 'sigla_habilidade')
            ->where(['id_disciplina' => $disciplina, 'id_turma' => $turma, 'SAME' => $ano_same])
            ->groupBy('id_habilidade', 'nome_habilidade', 'sigla_habilidade')->orderBy('nome_habilidade', 'asc')->get();
            Cache::forever('prof_habs'.strval($disciplina).strval($turma).strval($ano_same), $habilidades);
        }
        return $habilidades;
    }

    /**
     * Método Sessão Habilidade Disciplina
     */
    public static function estatisticaDisciplinaQuestao($turma, $disciplina, $ano_same){   
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
                ['presenca' => config('constants.options.confPresenca'), 'id_turma' => $turma, 'id_disciplina' => $disciplina, 'same' => $ano_same]
            );

            $dados_ajuste_percentual_base = MethodsProfTurma::estatisticaAjustePercentualBase($turma, $disciplina, $ano_same);

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

    /**
     * Método Sessão Ano Habilidades
     */
    public static function estatisticaHabilidadeDisciplinaAnoGrafico($id_escola, $disciplina, $ano, $ano_same){  

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
                ['presenca' => config('constants.options.confPresenca'), 'id_escola' => $id_escola, 'id_disciplina' => $disciplina, 'ano' => $ano, 'same' => $ano_same]
            );

            $dados_ajuste_percentual = MethodsProfTurma::estatisticaAjustePercentual($id_escola, $disciplina, $ano, $ano_same);
            $legendas = MethodsGerais::getLegendas();

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
    public static function estatisticaAjustePercentual($id_escola, $disciplina, $ano, $ano_same){  
        
        $ano = intval($ano);

        if(Cache::has('prof_quest_aj_perc'.strval($id_escola).strval($disciplina).strval($ano).strval($ano_same))){
            $dados_ajuste_percentual = Cache::get('prof_quest_aj_perc'.strval($id_escola).strval($disciplina).strval($ano).strval($ano_same));
        } else {
            $dados_ajuste_percentual = DB::select(
                'SELECT sigla_habilidade, COUNT(id) AS qtd, resposta, id_habilidade, id_questao FROM dado_unificados  
                WHERE presenca > :presenca AND  id_escola = :id_escola AND id_disciplina = :id_disciplina AND SAME = :same 
                AND ano = :ano AND tipo_questao <> \'Objetivas\' AND resposta IS NOT NULL AND resposta <> \'\' GROUP BY sigla_habilidade, resposta, id_habilidade, id_questao',
                ['presenca' => config('constants.options.confPresenca'), 'id_escola' => $id_escola, 'id_disciplina' => $disciplina, 'ano' => $ano, 'same' => $ano_same]
            );
            Cache::forever('prof_quest_aj_perc'.strval($id_escola).strval($disciplina).strval($ano).strval($ano_same), $dados_ajuste_percentual);
        }
        
        return $dados_ajuste_percentual;
    }

    /**
     * Método Sessão Ano Habilidades
     */
    public static function estatisticaHabilidadeAnoQuestao($id_escola, $disciplina, $ano, $ano_same){  

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
                ['presenca' => config('constants.options.confPresenca'), 'id_escola' => $id_escola, 'id_disciplina' => $disciplina, 'ano' => $ano, 'same' => $ano_same]
            );

            $dados_ajuste_percentual = MethodsProfTurma::estatisticaAjustePercentual($id_escola, $disciplina, $ano, $ano_same);

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
     * Método que obtém dados da Sessão Habilidade Selecionada
     */
    public static function estatisticaHabilidadeDisciplinaHabilidade($id_escola, $disciplina, $habilidade, $ano_same){   
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
                ['presenca' => config('constants.options.confPresenca'), 'id_escola' => $id_escola, 'id_disciplina' => $disciplina, 'id_habilidade' => $habilidade, 'same' => $ano_same]
            );

            $dados_ajuste_percentual_ano = MethodsProfTurma::estatisticaAjustePercentualAno($id_escola, $disciplina, $habilidade, $ano_same);
            $legendas = MethodsGerais::getLegendas();

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
    public static function estatisticaAjustePercentualAno($id_escola, $disciplina, $habilidade, $ano_same){   
        if(Cache::has('prof_hab_sel_aj'.strval($id_escola).strval($disciplina).strval($habilidade).strval($ano_same))){
            $dados_ajuste_percentual_ano = Cache::get('prof_hab_sel_aj'.strval($id_escola).strval($disciplina).strval($habilidade).strval($ano_same));
        } else {
            $dados_ajuste_percentual_ano = DB::select(
                'SELECT sigla_habilidade, COUNT(id) AS qtd, resposta, id_habilidade, ano, id_questao 
                FROM dado_unificados WHERE presenca > :presenca AND  id_escola = :id_escola AND id_disciplina = :id_disciplina AND SAME = :same 
                    AND id_habilidade = :id_habilidade AND tipo_questao <> \'Objetivas\' AND resposta IS NOT NULL AND resposta <> \'\' 
                GROUP BY sigla_habilidade, resposta, id_habilidade, ano, id_questao ',
                ['presenca' => config('constants.options.confPresenca'), 'id_escola' => $id_escola, 'id_disciplina' => $disciplina, 'id_habilidade' => $habilidade, 'same' =>$ano_same]
            );  
            Cache::forever('prof_hab_sel_aj'.strval($id_escola).strval($disciplina).strval($habilidade).strval($ano_same), $dados_ajuste_percentual_ano);
        }
        
        return  $dados_ajuste_percentual_ano;
    }

    /**
     * Método que obtém dados da Sessão Habilidade Selecionada
     */
    public static function estatisticaBaseHabilidadeQuestaoHabilidade($id_escola, $disciplina, $habilidade, $ano_same){   
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
                    ['presenca' => config('constants.options.confPresenca'),'id_escola' => $id_escola, 'id_disciplina' => $disciplina, 'id_habilidade' => $habilidade, 'same' => $ano_same]
            );

            $dados_ajuste_percentual_ano = MethodsProfTurma::estatisticaAjustePercentualAno($id_escola, $disciplina, $habilidade, $ano_same);

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
    public static function estatisticaQuestaoGraficoDisciplina($turma, $disciplina, $ano_same){   
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
                ['presenca' => config('constants.options.confPresenca'),'id_turma' => $turma, 'id_disciplina' => $disciplina, 'same' => $ano_same]
            );

            $dados_ajuste_percentual_questao = MethodsProfTurma::estatisticaAjustePercentualQuestao($turma, $disciplina, $ano_same);
            $legendas = MethodsGerais::getLegendas();

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
    public static function estatisticaAjustePercentualQuestao($turma, $disciplina, $ano_same){   
        if(Cache::has('prof_aj_perc_quest'.strval($turma).strval($disciplina).strval($ano_same))){
            $dados_ajuste_percentual_questao = Cache::get('prof_aj_perc_quest'.strval($turma).strval($disciplina).strval($ano_same));
        } else {
            $dados_ajuste_percentual_questao = DB::select(
                'SELECT COUNT(id) AS qtd, resposta, id_questao 
                FROM dado_unificados 
                WHERE presenca > :presenca AND  id_turma = :id_turma AND id_disciplina = :id_disciplina AND SAME = :same 
                AND tipo_questao <> \'Objetivas\' AND resposta IS NOT NULL AND resposta <> \'\' GROUP BY resposta, id_questao ',
                ['presenca' => config('constants.options.confPresenca'), 'id_turma' => $turma, 'id_disciplina' => $disciplina, 'same' => $ano_same]
            );
            Cache::forever('prof_aj_perc_quest'.strval($turma).strval($disciplina).strval($ano_same), $dados_ajuste_percentual_questao);
        }
        
        return $dados_ajuste_percentual_questao;
    }

    /**
     * Método Sessão Alunos
     */
    public static function estatisticaBaseAlunoGraficoDisciplina($turma, $disciplina, $ano_same){ 
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

    /**
     * Método que busca Critérios de Habilidade pela Turma, Disciplina e Ano SAME
     */
    public static function getHabilidadesCriterios($turma, $id_disciplina, $ano_same_selecionado){

        $dados_base_habilidade_questao = MethodsProfTurma::estatisticaDisciplinaQuestao($turma, $id_disciplina, $ano_same_selecionado);

        // Nova definição das Habilidades com os Critérios
        for ($j = 0; $j < sizeof($dados_base_habilidade_questao); $j++) {
            if ($dados_base_habilidade_questao[$j]->tipo_questao != 'Objetivas'){
                //Nos demais não existe esse filtro adicional
                $criterios_questaoAll = MethodsGerais::getCriterios();
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

}



