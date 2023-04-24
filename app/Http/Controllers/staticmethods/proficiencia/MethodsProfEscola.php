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

class MethodsProfEscola extends Controller
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
     * Método que busca as informações de Direção Professor do Usuário Logado usando Cache
     */
    public static function getDirecaoProfessor($ano_same){

        if(Cache::has('direc_profes_'.strval(MethodsGerais::getPrevilegio()[0]->id).strval($ano_same))){
            $direcaoProfessor = Cache::get('direc_profes_'.strval(MethodsGerais::getPrevilegio()[0]->id).strval($ano_same));
        } else {
            $direcaoProfessor = DirecaoProfessor::where(['id_previlegio' => MethodsGerais::getPrevilegio()[0]->id],['SAME' => $ano_same])->get();
            //Adiciona ao Cache
            Cache::put('direc_profes_'.strval(MethodsGerais::getPrevilegio()[0]->id).strval($ano_same),$direcaoProfessor, now()->addHours(config('constants.options.horas_cache')));     
        }

        return $direcaoProfessor;
    }

    /**
     * Busca a listagem das Escolas pelo Munícipio e Ano SAME
     */
    public static function getEscolasDiretor($id_municipio, $ano_same){
        
        //Administrador ou Pesquisador Unijuí lista todas Escolas
        if (auth()->user()->perfil == 'Administrador' 
            || ((MethodsGerais::getPrevilegio()[0]->funcaos_id == 13 || MethodsGerais::getPrevilegio()[0]->funcaos_id == 14) 
            && MethodsGerais::getPrevilegio()[0]->municipios_id == 5)) {
           if(Cache::has('esc_dir_total'.strval($id_municipio).strval($ano_same))){
               $escolas = Cache::get('esc_dir_total'.strval($id_municipio).strval($ano_same));
           } else {
               $escolas = Escola::where(['status' => 'Ativo','municipios_id' => $id_municipio, 'SAME' =>$ano_same])->get();
               //Adiciona Cache
               Cache::forever('esc_dir_total'.strval($id_municipio).strval($ano_same), $escolas);  
           }

       } else if (isset(MethodsGerais::getPrevilegio()[0]) && MethodsGerais::getPrevilegio()[0]->funcaos_id == 8) {
           if(Cache::has('esc_dir_total'.strval($id_municipio).strval($ano_same))){
               $escolas = Cache::get('esc_dir_total'.strval($id_municipio).strval($ano_same));
           } else {
               $escolas = Escola::where(['status' => 'Ativo', 'municipios_id' => $id_municipio, 'SAME' => $ano_same])->get();
               //Adiciona ao Cache
               Cache::forever('esc_dir_total'.strval($id_municipio).strval($ano_same), $escolas);
           }
       } else {
           //Os demais pega apenas a escola para o qual foi designado seus previlégios
           if(Cache::has('esc_'.strval(MethodsProfEscola::getDirecaoProfessor($ano_same)[0]->id_escola))){
               $escolas = Cache::get('esc_'.strval(MethodsProfEscola::getDirecaoProfessor($ano_same)[0]->id_escola));
           } else {
               $escolas = Escola::where(['id' => MethodsProfEscola::getDirecaoProfessor($ano_same)[0]->id_escola])->get();

               //Adiciona Cache
               Cache::put('esc_'.strval(MethodsProfEscola::getDirecaoProfessor($ano_same)[0]->id_escola),$escolas, now()->addHours(config('constants.options.horas_cache')));
           }
           
       }

       return $escolas;
   }

   /**
     * Método para buscar as turmas da Escola utilizando Cache
     */
    public static function getTurmasEscola($id_escola, $ano_same){

        if(Cache::has('turmas_esc'.strval($id_escola).strval($ano_same))){
            $turmas = Cache::get('turmas_esc'.strval($id_escola).strval($ano_same));
        } else {
            $turmas = Turma::where(['status' => 'Ativo', 'escolas_id' => $id_escola, 'SAME' => $ano_same])->orderBy('TURMA','asc')->get();
            //Adiciona ao Cache
            Cache::put('turmas_esc'.strval($id_escola).strval($ano_same), $turmas, now()->addHours(config('constants.options.horas_cache')));
        }
        
        return $turmas;
    }

    /**
     * Método que busca os dados base de Escola utilizando Cache
     */
    public static function estatisticaEscola($escola, $ano_same){   
        //Busca dados Gráfico Média Escola
        if(Cache::has('dir_est_esc_'.strval($escola).strval($ano_same))){
            $dados_base_escola = Cache::get('dir_est_esc_'.strval($escola).strval($ano_same));
        } else {
            $dados_base_escola = DB::select(
                'SELECT (ac.acertos*100)/(qtd_questao.num) AS percentual_escola, \'Proficiência Média\' AS descricao FROM dado_unificados du 
                    LEFT JOIN ( SELECT count(id) AS num 
                                FROM dado_unificados 
                                WHERE presenca > :presenca1 AND SAME = :SAME AND 
                                      id_municipio = (SELECT municipios_id FROM escolas WHERE id = :id_escola1 AND SAME = :SAME1)) AS qtd_questao ON TRUE 
                    LEFT JOIN ( SELECT SUM(acerto) AS acertos 
                                FROM dado_unificados 
                                WHERE presenca > :presenca2 AND SAME = :SAME2 AND 
                                      id_municipio = (SELECT municipios_id FROM escolas WHERE id = :id_escola2 AND SAME = :SAME3)) AS ac ON TRUE 
                    UNION
                    SELECT (ac.acertos*100)/(qtd_questao.num) AS percentual_escola,\'Proficiência Escola\' AS descricao FROM dado_unificados du 
                    LEFT JOIN (SELECT count(id) AS num 
                               FROM dado_unificados 
                               WHERE id_escola = :id_escola3
                                    AND presenca > :presenca3 AND SAME = :SAME4) AS qtd_questao ON TRUE      
                    LEFT JOIN (SELECT SUM(acerto) AS acertos 
                               FROM dado_unificados 
                               WHERE id_escola = :id_escola4 
                                    AND presenca > :presenca4 AND SAME = :SAME5) AS ac ON TRUE',
                ['presenca1' => config('constants.options.confPresenca'), 'presenca2' => config('constants.options.confPresenca'),
                'presenca3' => config('constants.options.confPresenca'),'presenca4' => config('constants.options.confPresenca'),
                 'id_escola1' => $escola, 'id_escola2' => $escola, 'id_escola3' => $escola, 'id_escola4' => $escola, 'SAME' => $ano_same,
                 'SAME1' => $ano_same, 'SAME2' => $ano_same, 'SAME3' => $ano_same, 'SAME4' => $ano_same, 'SAME5' => $ano_same]);
            
            //Adiciona ao Cache
            Cache::forever('dir_est_esc_'.strval($escola).strval($ano_same), $dados_base_escola);        
        }
        
        return  $dados_base_escola;
    }

    /**
     * Método que obtém os dados Comparativos entre Escolas utilizando Cache
     */
    public static function estatisticaComparacaoEscola($escola, $ano_same){   
        //Busca dados para geração do Gráfico da Escola
        if(Cache::has('dir_esc_comp_'.strval($escola).strval($ano_same))){
            $dados_comparacao_escola = Cache::get('dir_esc_comp_'.strval($escola).strval($ano_same));        
        } else {
            $dados_comparacao_escola = DB::select(
                'SELECT (ac.acertos*100)/(qtd_questao.num) AS percentual, \'Proficiência Escola\' AS descricao 
                    FROM dado_unificados du 
                    LEFT JOIN ( SELECT count(id) AS num FROM dado_unificados 
                                WHERE presenca > :presenca1 AND id_escola = :id_escola1 AND SAME = :SAME ) AS qtd_questao ON TRUE 
                    LEFT JOIN ( SELECT SUM(acerto) AS acertos FROM dado_unificados 
                                WHERE presenca > :presenca2 AND id_escola = :id_escola2 AND SAME = :SAME2) AS ac ON TRUE 
                    UNION 
                    SELECT (ac.acertos*100)/(qtd_questao.num) AS percentual, \'Proficência Média\' AS descricao FROM dado_unificados du 
                    LEFT JOIN ( SELECT count(id) AS num FROM dado_unificados 
                                WHERE presenca > :presenca3 AND SAME = :SAME3 AND id_municipio = (SELECT municipios_id FROM escolas WHERE id = :id_escola3 AND SAME = :SAME4)) AS qtd_questao ON TRUE 
                    LEFT JOIN ( SELECT SUM(acerto) AS acertos FROM dado_unificados 
                                WHERE presenca > :presenca4 AND SAME = :SAME5 AND id_municipio = (SELECT municipios_id FROM escolas WHERE id = :id_escola4 AND SAME = :SAME6)) AS ac ON TRUE ',
                ['presenca1' => config('constants.options.confPresenca'), 'presenca2' => config('constants.options.confPresenca'),
                 'presenca3' => config('constants.options.confPresenca'),'presenca4' => config('constants.options.confPresenca'),
                 'id_escola1' => $escola, 'id_escola2' => $escola, 'id_escola3' => $escola, 'id_escola4' => $escola, 'SAME' => $ano_same, 'SAME2' => $ano_same,
                 'SAME3' => $ano_same, 'SAME4' => $ano_same, 'SAME5' => $ano_same, 'SAME6' => $ano_same]);

            Cache::forever('dir_esc_comp_'.strval($escola).strval($ano_same), $dados_comparacao_escola);     
        }
        
        return  $dados_comparacao_escola;
    }

    /**
     * Método que obtém os Dados de Percentual da Disciplina na Escola
     */
    public static function estatisticaGraficoDisciplina($escola, $ano_same){
        //Busca Dados para Gráfico de Disciplina da Escola
        if(Cache::has('dir_esc_disc_'.strval($escola).strval($ano_same))){
            $dados_base_grafico_disciplina = Cache::get('dir_esc_disc_'.strval($escola).strval($ano_same));
        } else {
            $dados_base_grafico_disciplina = DB::select(
                'SELECT nome_disciplina AS descricao,(SUM(acerto)*100)/(count(id)) AS percentual 
                 FROM dado_unificados WHERE id_escola = :id_escola AND presenca > :presenca AND SAME = :SAME
                 GROUP BY nome_disciplina', 
                 ['presenca' => config('constants.options.confPresenca'), 'id_escola' => $escola, 'SAME' => $ano_same]);

            Cache::forever('dir_esc_disc_'.strval($escola).strval($ano_same), $dados_base_grafico_disciplina);        
        }   

        return  $dados_base_grafico_disciplina;
    }
  
    /**
     * Método que óbtem os dados de Escola na Disciplina por Ano Curricular
     */
    public static function estatisticaDisciplinaGrafico ($escola, $disciplina, $ano_same){   
        //Busca Dados para Disciplina  Grafico
        if(Cache::has('dir_disc_ano_'.strval($escola).strval($disciplina).strval($ano_same))){
            $dados_base_anos_disciplina_grafico = Cache::get('dir_disc_ano_'.strval($escola).strval($disciplina).strval($ano_same));
        } else {
            $dados_base_anos_disciplina_grafico = DB::select(
                'SELECT CONCAT(\'Ano \',ano) AS descricao, (SUM(acerto)*100)/(count(id)) AS percentual, ano, nome_disciplina 
                FROM dado_unificados WHERE id_escola = :id_escola AND id_disciplina = :id_disciplina
                      AND presenca > :presenca AND SAME = :SAME GROUP BY ano, nome_disciplina ORDER BY ano ASC ', 
                ['presenca' => config('constants.options.confPresenca'),'id_escola' => $escola, 'id_disciplina' => $disciplina, 'SAME' => $ano_same]);
            
            Cache::forever('dir_disc_ano_'.strval($escola).strval($disciplina).strval($ano_same), $dados_base_anos_disciplina_grafico);    
        }
        
        return  $dados_base_anos_disciplina_grafico;
    }

    /**
     * Método que óbtem os dados de Turmas da Escola pela Disciplina
     */
    public static function estatisticaTurmaDisciplinaGrafico ($escola, $disciplina, $ano_same){         
        //Busca os dados para Gráfico de Turmas Disciplina
        if(Cache::has('dir_tur_disc_'.strval($escola).strval($disciplina).strval($ano_same))){
            $dados_base_turmas_disciplina_grafico = Cache::get('dir_tur_disc_'.strval($escola).strval($disciplina).strval($ano_same));
        } else {
            $dados_base_turmas_disciplina_grafico = DB::select(
                'SELECT nome_turma AS descricao, turma_resumo AS sigla, (SUM(acerto)*100)/(count(id)) AS percentual, 
                nome_turma, nome_disciplina FROM dado_unificados 
                WHERE id_escola = :id_escola AND id_disciplina = :id_disciplina 
                      AND presenca > :presenca AND SAME = :SAME
                GROUP BY nome_turma, nome_disciplina, turma_resumo 
                ORDER BY nome_turma ASC ', 
                ['presenca' => config('constants.options.confPresenca'),'id_escola' => $escola, 'id_disciplina' => $disciplina, 'SAME' => $ano_same]);

            Cache::forever('dir_tur_disc_'.strval($escola).strval($disciplina).strval($ano_same), $dados_base_turmas_disciplina_grafico); 
        }
        
        return  $dados_base_turmas_disciplina_grafico;
    }

    /**
     * Método que óbtem os dados de Habilidade por Ano na Disciplina
     */
    public static function estatisticaHabilidadeDisciplinaGrafico($escola, $disciplina, $ano, $ano_same){  
        //Busca Dados para Gerar Gráfico de Habilidade por Ano
        $ano = intval($ano);
        if(Cache::has('dir_hab_ano_disc'.strval($escola).strval($disciplina).strval($ano).strval($ano_same))){
            $dados_base_habilidade_ano_disciplina_grafico = Cache::get('dir_hab_ano_disc'.strval($escola).strval($disciplina).strval($ano).strval($ano_same));
        } else {
            $dados_base_habilidade_ano_disciplina_grafico = DB::select(
                'SELECT sigla_habilidade, (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, id_habilidade, tipo_questao, \'white\' AS cor,
                 nome_habilidade, nome_disciplina 
                 FROM dado_unificados 
                 WHERE id_escola = :id_escola AND id_disciplina = :id_disciplina AND ano = :ano 
                       AND presenca > :presenca AND SAME = :SAME
                 GROUP BY nome_habilidade, sigla_habilidade, nome_disciplina, id_habilidade, tipo_questao 
                 ORDER BY sigla_habilidade, nome_disciplina ASC ',
                ['presenca' => config('constants.options.confPresenca'),'id_escola' => $escola, 'id_disciplina' => $disciplina, 'ano' => $ano, 'SAME' => $ano_same]
            );   
            
            $dados_ajuste_percentual = MethodsProfEscola::estatisticaAjustePercentual($escola, $disciplina, $ano, $ano_same);

            $legendas = MethodsGerais::getLegendas();

            for ($j = 0; $j < sizeof($dados_base_habilidade_ano_disciplina_grafico); $j++) {
                $total_questoes = 0;
                $valor_ajuste = 0;
                for ($p = 0; $p < sizeof($dados_ajuste_percentual); $p++) {
                    if (
                        $dados_base_habilidade_ano_disciplina_grafico[$j]->sigla_habilidade == $dados_ajuste_percentual[$p]->sigla_habilidade
                        && $dados_base_habilidade_ano_disciplina_grafico[$j]->tipo_questao != 'Objetivas'
                    ) {
                        $total_questoes += $dados_ajuste_percentual[$p]->qtd;
                        if ($dados_ajuste_percentual[$p]->qtd > $valor_ajuste) {
                            $valor_ajuste = $dados_ajuste_percentual[$p]->qtd;
                            if ($dados_ajuste_percentual[$p]->resposta == 'A') {
                                $dados_base_habilidade_ano_disciplina_grafico[$j]->cor = $legendas[0]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual[$p]->resposta == 'B') {
                                $dados_base_habilidade_ano_disciplina_grafico[$j]->cor = $legendas[1]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual[$p]->resposta == 'C') {
                                $dados_base_habilidade_ano_disciplina_grafico[$j]->cor = $legendas[2]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual[$p]->resposta == 'D') {
                                $dados_base_habilidade_ano_disciplina_grafico[$j]->cor = $legendas[3]->cor_fundo;
                            }
                        }
                    }
                }
                if ($total_questoes != 0 && $valor_ajuste != 0) {
                    $dados_base_habilidade_ano_disciplina_grafico[$j]->percentual_habilidade = (($valor_ajuste * 100) / ($total_questoes));
                }
            }

            Cache::forever('dir_hab_ano_disc'.strval($escola).strval($disciplina).strval($ano).strval($ano_same), $dados_base_habilidade_ano_disciplina_grafico);
            
        }
        
        return  $dados_base_habilidade_ano_disciplina_grafico;
    }

    /**
     * Método de busca de dados para Ajuste de Percentual da Sessão habilidades por ano na Disciplina
     */
    public static function estatisticaAjustePercentual($escola, $disciplina, $ano, $ano_same){ 
        $ano = intval($ano);
        if(Cache::has('dir_ajuste_perc_'.strval($escola).strval($disciplina).strval($ano).strval($ano_same))){
            $dados_ajuste_percentual = Cache::get('dir_ajuste_perc_'.strval($escola).strval($disciplina).strval($ano).strval($ano_same));
        } else {
            $dados_ajuste_percentual = DB::select(
                'SELECT sigla_habilidade, COUNT(id) AS qtd, resposta, id_habilidade, id_questao 
                FROM dado_unificados 
                WHERE presenca > :presenca AND SAME = :SAME AND id_escola = :id_escola AND id_disciplina = :id_disciplina 
                      AND ano = :ano AND tipo_questao <> \'Objetivas\' AND resposta IS NOT NULL AND resposta <> \'\' 
                GROUP BY sigla_habilidade, resposta, id_habilidade, id_questao',
                ['presenca' => config('constants.options.confPresenca'),'id_escola' => $escola, 'id_disciplina' => $disciplina, 'ano' => $ano, 'SAME' => $ano_same]
            );

            Cache::forever('dir_ajuste_perc_'.strval($escola).strval($disciplina).strval($ano).strval($ano_same), $dados_ajuste_percentual); 
        }
        
        return  $dados_ajuste_percentual;
    }

    /**
     * Método que óbtem os dados para a formação dos Modais da Sessão de Habilidades por Ano na Disciplina
     */
    public static function estatisticaHabilidadeAnoQuestao($escola, $disciplina, $ano, $ano_same){   
        $ano = intval($ano);
        if(Cache::has('dir_questao_hab_ano_'.strval($escola).strval($disciplina).strval($ano).strval($ano_same))){
            $dados_base_habilidade_ano_questao = Cache::get('dir_questao_hab_ano_'.strval($escola).strval($disciplina).strval($ano).strval($ano_same));
        } else {
            $dados_base_habilidade_ano_questao = DB::select(
                'SELECT id_habilidade, (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, desc_questao, id_questao, nome_tema, tipo_questao, correta, imagem_questao, ano, id_habilidade
                FROM dado_unificados 
                WHERE id_escola = :id_escola AND id_disciplina = :id_disciplina AND ano = :ano AND presenca > :presenca AND SAME = :SAME 
                GROUP BY id_habilidade, desc_questao, nome_disciplina, id_questao, nome_tema, tipo_questao, correta, imagem_questao, ano ORDER BY id_habilidade ASC ',
                ['presenca' => config('constants.options.confPresenca'),'id_escola' => $escola, 'id_disciplina' => $disciplina, 'ano' => $ano, 'SAME' => $ano_same]
            );
    
            $dados_ajuste_percentual = MethodsProfEscola::estatisticaAjustePercentual($escola, $disciplina, $ano, $ano_same);
    
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

            Cache::forever('dir_questao_hab_ano_'.strval($escola).strval($disciplina).strval($ano).strval($ano_same), $dados_base_habilidade_ano_questao);
        }
        
        return  $dados_base_habilidade_ano_questao;
    }

    /**
     * Método que lista as habilidades pelo Munícipio e Disciplina utilizando Cache
     */
    public static function getHabilidadesEscola($disciplina_selecionada, $escola_selecionada){

        if (Cache::has('dir_hab_disc_esc_'.strval($disciplina_selecionada).'_'.strval($escola_selecionada))) {
            $habilidades = Cache::get('dir_hab_disc_esc_'.strval($disciplina_selecionada).'_'.strval($escola_selecionada));
        } else {
            $habilidades = DadoUnificado::select('id_habilidade', 'nome_habilidade', 'sigla_habilidade')
            ->where(['id_disciplina' => $disciplina_selecionada, 'id_escola' => $escola_selecionada])
            ->groupBy('id_habilidade', 'nome_habilidade', 'sigla_habilidade')->orderBy('nome_habilidade', 'asc')->get();
            
            //Adiciona ao Cache   
            Cache::forever('dir_hab_disc_esc_'.strval($disciplina_selecionada).'_'.strval($escola_selecionada), $habilidades);    
        }

        return $habilidades;
    }

    /**
     * Método que ótem dados da Habilidade indivual pelos Anos
     */
    public static function estatisticaEscolaDisciplinaHabilidade($escola, $disciplina,$habilidade, $ano_same){   
        //Busca Dados do Gráfico Habilidade Individual   
        if(Cache::has('dir_hab_ind_disc_'.strval($escola).strval($disciplina).strval($habilidade).strval($ano_same))){
            $dados_base_habilidade_disciplina_grafico = Cache::get('dir_hab_ind_disc_'.strval($escola).strval($disciplina).strval($habilidade).strval($ano_same));
        } else {
            $dados_base_habilidade_disciplina_grafico = DB::select(
                'SELECT sigla_habilidade, 
                    (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, ano, CONCAT(ano,\'º Ano\') AS sigla_ano, id_habilidade, nome_habilidade, nome_disciplina, tipo_questao, \'white\' AS cor
                    FROM dado_unificados 
                    WHERE id_escola = :id_escola AND id_disciplina = :id_disciplina AND id_habilidade = :id_habilidade 
                          AND presenca > :presenca AND SAME = :SAME
                    GROUP BY id_habilidade, nome_habilidade, sigla_habilidade, nome_disciplina, id_escola, ano, tipo_questao
                    ORDER BY sigla_habilidade, ano ASC ',
                ['presenca' => config('constants.options.confPresenca'),'id_escola' => $escola,'id_disciplina' => $disciplina,'id_habilidade' => $habilidade, 'SAME' => $ano_same]
            );      
    
            $dados_ajuste_percentual_ano = MethodsProfEscola::estatisticaPercentualAno($escola, $disciplina,$habilidade, $ano_same);
            
            $legendas = MethodsGerais::getLegendas();
    
            //Ajusta os percentuais das questões não objetivas
            for ($j = 0; $j < sizeof($dados_base_habilidade_disciplina_grafico); $j++) {
                $total_questoes = 0;
                $valor_ajuste = 0;
                for ($p = 0; $p < sizeof($dados_ajuste_percentual_ano); $p++) {
                    if (
                        $dados_base_habilidade_disciplina_grafico[$j]->sigla_habilidade == $dados_ajuste_percentual_ano[$p]->sigla_habilidade
                        && $dados_base_habilidade_disciplina_grafico[$j]->ano == $dados_ajuste_percentual_ano[$p]->ano
                        && $dados_base_habilidade_disciplina_grafico[$j]->tipo_questao != 'Objetivas'
                    ) {
                        $total_questoes += $dados_ajuste_percentual_ano[$p]->qtd;
                        if ($dados_ajuste_percentual_ano[$p]->qtd > $valor_ajuste) {
                            $valor_ajuste = $dados_ajuste_percentual_ano[$p]->qtd;
                            if ($dados_ajuste_percentual_ano[$p]->resposta == 'A') {
                                $dados_base_habilidade_disciplina_grafico[$j]->cor = $legendas[0]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual_ano[$p]->resposta == 'B') {
                                $dados_base_habilidade_disciplina_grafico[$j]->cor = $legendas[1]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual_ano[$p]->resposta == 'C') {
                                $dados_base_habilidade_disciplina_grafico[$j]->cor = $legendas[2]->cor_fundo;
                            }
                            if ($dados_ajuste_percentual_ano[$p]->resposta == 'D') {
                                $dados_base_habilidade_disciplina_grafico[$j]->cor = $legendas[3]->cor_fundo;
                            }
                        }
                    }
                }
                if ($total_questoes != 0 && $valor_ajuste != 0) {
                    $dados_base_habilidade_disciplina_grafico[$j]->percentual_habilidade = (($valor_ajuste * 100) / ($total_questoes));
                }
            }

            //Adiciona ao Cache
            Cache::forever('dir_hab_ind_disc_'.strval($escola).strval($disciplina).strval($habilidade).strval($ano_same), $dados_base_habilidade_disciplina_grafico); 
        }
         
        return  $dados_base_habilidade_disciplina_grafico;
    }

    /**
     * Método que óbtem os Percentuais de Ajuste da Sessão Habilidade Individual pelos Anos
     */
    public static function estatisticaPercentualAno($escola, $disciplina, $habilidade, $ano_same){  
        if(Cache::has('dir_per_hab_ano_'.strval($escola).strval($disciplina).strval($habilidade).strval($ano_same))){
            $dados_ajuste_percentual_ano = Cache::get('dir_per_hab_ano_'.strval($escola).strval($disciplina).strval($habilidade).strval($ano_same));
        } else {
            $dados_ajuste_percentual_ano = DB::select(
                'SELECT sigla_habilidade, COUNT(id) AS qtd, resposta, id_habilidade, ano, id_questao 
                FROM dado_unificados 
                WHERE id_escola = :id_escola AND id_disciplina = :id_disciplina 
                      AND presenca > :presenca AND SAME = :SAME 
                      AND id_habilidade = :id_habilidade AND tipo_questao <> \'Objetivas\' AND resposta IS NOT NULL AND resposta <> \'\' 
                GROUP BY sigla_habilidade, resposta, id_habilidade, ano, id_questao ',
                ['presenca' => config('constants.options.confPresenca'),'id_escola' => $escola, 'id_disciplina' => $disciplina, 'id_habilidade' => $habilidade, 'SAME' => $ano_same]
            );

            //Adiciona ao Cache
            Cache::forever('dir_per_hab_ano_'.strval($escola).strval($disciplina).strval($habilidade).strval($ano_same), $dados_ajuste_percentual_ano);    
        }
        
        return  $dados_ajuste_percentual_ano;
    }

    /**
     * Método para obtenção dos utilizados nos Modais da Sessão de Habilidade Individual pelos Anos
     */
    public static function estatisticaHabilidadeQuestao($escola, $disciplina, $habilidade, $ano_same){   
        //Busca os dados das Questões para Gerar os Modais de Habilidade Individual
        if(Cache::has('dir_questao_hab_ind_'.strval($escola).strval($disciplina).strval($habilidade).strval($ano_same))){
            $dados_base_habilidade_questao = Cache::get('dir_questao_hab_ind_'.strval($escola).strval($disciplina).strval($habilidade).strval($ano_same));
        } else {
            $dados_base_habilidade_questao =
            DB::select(
                'SELECT id_habilidade, (SUM(acerto)*100)/(count(id)) AS percentual_habilidade,
                        id_disciplina, desc_questao, id_questao, nome_tema, id_tipo_questao, tipo_questao, correta,  imagem_questao, ano,
                        \'Nome CRITÉRIO A\' AS nome_A, \'TESTE CRITÉRIO A\' AS Obs_A, 
                        \'Nome CRITÉRIO B\' AS nome_B, \'TESTE CRITÉRIO B\' AS Obs_B, 
                        \'Nome CRITÉRIO C\' AS nome_C, \'TESTE CRITÉRIO C\' AS Obs_C, 
                        \'Nome CRITÉRIO D\' AS nome_D, \'TESTE CRITÉRIO D\' AS Obs_D
                FROM dado_unificados 
                WHERE id_escola = :id_escola AND id_disciplina = :id_disciplina AND id_habilidade = :id_habilidade
                      AND presenca > :presenca AND SAME = :SAME 
                GROUP BY id_habilidade, id_disciplina, desc_questao, nome_disciplina, id_questao, nome_tema, id_tipo_questao,  tipo_questao, correta,  imagem_questao, id_municipio, ano 
                ORDER BY id_habilidade ASC ',
                ['presenca' => config('constants.options.confPresenca'),'id_escola' => $escola, 'id_disciplina' => $disciplina, 'id_habilidade' => $habilidade, 'SAME' => $ano_same]
            );

            $dados_ajuste_percentual_ano = MethodsProfEscola::estatisticaPercentualAno($escola, $disciplina,$habilidade, $ano_same);

            for ($j = 0; $j < sizeof($dados_base_habilidade_questao); $j++) {
                $total_questoes = 0;
                $valor_ajuste = 0;
                for ($p = 0; $p < sizeof($dados_ajuste_percentual_ano); $p++) {
                    if (
                        $dados_base_habilidade_questao[$j]->tipo_questao != 'Objetivas'
                        && $dados_base_habilidade_questao[$j]->id_habilidade == $dados_ajuste_percentual_ano[$p]->id_habilidade
                        && $dados_base_habilidade_questao[$j]->id_questao == $dados_ajuste_percentual_ano[$p]->id_questao
                        && $dados_base_habilidade_questao[$j]->ano == $dados_ajuste_percentual_ano[$p]->ano
                    ) {
                        $total_questoes += $dados_ajuste_percentual_ano[$p]->qtd;
                        if ($dados_ajuste_percentual_ano[$p]->qtd > $valor_ajuste) {
                            $valor_ajuste = $dados_ajuste_percentual_ano[$p]->qtd;
                        }
                    }
                }
                if ($total_questoes != 0 && $valor_ajuste != 0) {
                    $dados_base_habilidade_questao[$j]->percentual_habilidade = (($valor_ajuste * 100) / ($total_questoes));
                }
            }

            //Adiciona ao Cache
            Cache::forever('dir_questao_hab_ind_'.strval($escola).strval($disciplina).strval($habilidade).strval($ano_same), $dados_base_habilidade_questao); 
        }

        return  $dados_base_habilidade_questao;
    }

    public static function getHabilidadeQuestaoCriterio($dados_base_habilidade_questao){
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



