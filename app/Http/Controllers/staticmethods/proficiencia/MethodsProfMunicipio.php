<?php

namespace App\Http\Controllers\staticmethods\proficiencia;

use App\Models\DadoUnificado;
use App\Models\Escola;
use App\Models\Turma;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Http\Controllers\staticmethods\gerais\MethodsGerais;

class MethodsProfMunicipio extends Controller
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
     * Método que lista as habilidades pelo Munícipio e Disciplina utilizando Cache
     */
    public static function getHabilidades($disciplina_selecionada, $municipio_selecionado){

        //Caso tenham valor em Cache busca pela disciplina e Munícipio
        if (Cache::has('hab_disc_mun_'.strval($disciplina_selecionada).'_'.strval($municipio_selecionado))) {
            $habilidades = Cache::get('hab_disc_mun_'.strval($disciplina_selecionada).'_'.strval($municipio_selecionado));
        } else {
            //Busca do BD pela Disciplina e Município
            $habilidades = DadoUnificado::select('id_habilidade', 'nome_habilidade', 'sigla_habilidade')
            ->where(['id_disciplina' => $disciplina_selecionada, 'id_municipio' => $municipio_selecionado])
            ->groupBy('id_habilidade', 'nome_habilidade', 'sigla_habilidade')->orderBy('nome_habilidade', 'asc')->get();
            
            //Adiciona ao Cache pelo tempo determinado na constante
            Cache::put('hab_disc_mun_'.strval($disciplina_selecionada).'_'.strval($municipio_selecionado),
            $habilidades, now()->addHours(config('constants.options.horas_cache')));     
        }

        return $habilidades;
    }

    /**
     * Método para realizar a busca das Escolas Ativas do Município e Ano SAME informado
     */
    public static function getEscolasMunicipio($id_municipio, $ano_same){

        //Busca as Escolas da Cache pelo identificador do Município e Ano SAME
        if(Cache::has('escolas_'.strval($id_municipio).strval($ano_same))){
            $escolasListadas = Cache::get('escolas_'.strval($id_municipio).strval($ano_same));
        } else {

            //Busca do BD as escolas ativos, do município e Ano SAME informado
            $escolasListadas = Escola::where(['status' => 'Ativo', 'municipios_id' => $id_municipio, 'SAME' => $ano_same])->get();
            
            //Adiciona ao Cache pelo tempo da Constante
            Cache::put('escolas_'.strval($id_municipio).strval($ano_same), $escolasListadas, now()->addHours(config('constants.options.horas_cache')));
        }
        
        return $escolasListadas;
    }

    /**
     * Método para buscar as turmas do Munícipio utilizando Cache
     */
    public static function getTurmasMunicipio($id_municipio, $ano_same){

        //Busca em Cache as Turmas pelo Município e Ano SAME informado
        if(Cache::has('turmas_'.strval($id_municipio).strval($ano_same))){
            $turmasListadas = Cache::get('turmas_'.strval($id_municipio).strval($ano_same));
        } else {

            //Busca do BD as turmas ativas do Município e Ano SAME informado
            $turmasListadas = Turma::where(['status' => 'Ativo', 'escolas_municipios_id' => $id_municipio, 'SAME' => $ano_same])->orderBy('TURMA','asc')->get();
            
            //Adiciona ao Cache pelo tempo determinado na Constante
            Cache::put('turmas_'.strval($id_municipio).strval($ano_same), $turmasListadas, now()->addHours(config('constants.options.horas_cache')));
        }
        
        return $turmasListadas;
    }

    /**
     * Método que busca os dados para montar a sessão Disciplinas Munícipio
     */
    public static function estatisticaDisciplinas($municipio, $ano_same){

        //Busca os dados do gráfico de disciplina na Cache pelo Munícipio e Ano SAME
        if (Cache::has('disciplina_mun_'.strval($municipio).strval($ano_same))) {
            $dados_base_grafico_disciplina = Cache::get('disciplina_mun_'.strval($municipio).strval($ano_same));
        } else {
            //Busca do BD pelo Munícipio e Ano SAME
            $dados_base_grafico_disciplina  = DB::select('SELECT nome_disciplina AS descricao,(SUM(acerto)*100)/(count(id)) AS percentual 
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca AND SAME = :SAME GROUP BY nome_disciplina', 
                 ['presenca' => config('constants.options.confPresenca'), 'id_municipio' => $municipio, 'SAME' => $ano_same]);   
            
            //Adiciona ao Cache em prazo indefinido
            Cache::forever('disciplina_mun_'.strval($municipio).strval($ano_same),$dados_base_grafico_disciplina);     
        }

        return $dados_base_grafico_disciplina;
    }

    /**
     * Método que busca os dados para montar a sessão Escolas Munícipio
     */
    public static function estatisticaEscola($municipio, $ano_same){

        //Busca os dados do gráfico de escolas utilizando Munícipio e Ano SAME
        if (Cache::has('est_esc_mun_'.strval($municipio).strval($ano_same))) {
            $dados_base_grafico_escola = Cache::get('est_esc_mun_'.strval($municipio).strval($ano_same));
        } else {

            //Busca dados do BD utilizando Munícipio e Ano SAME
            $dados_base_grafico_escola = DB::select('SELECT CONCAT(\'E\',(@contador := @contador + 1)) AS sigla, UPPER(nome_escola) AS descricao,(SUM(acerto)*100)/(count(id)) AS percentual 
                FROM (SELECT @contador := 0) AS nada,dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca AND SAME = :SAME
                GROUP BY nome_escola, id_escola ORDER BY nome_escola, sigla', 
                ['presenca' => config('constants.options.confPresenca'),'id_municipio' => $municipio, 'SAME' => $ano_same]);  

            //Ajusta as siglas para manter a ordenação
            for ($i = 0; $i < sizeof($dados_base_grafico_escola); $i++) {
                if ($i < 9) {
                    $dados_base_grafico_escola[$i]->sigla = 'E0' . ($i + 1);
                } else {
                    $dados_base_grafico_escola[$i]->sigla = 'E' . ($i + 1);
                }
            }    
            
            //Adiciona ao Cache por prazo indefinido
            Cache::forever('est_esc_mun_'.strval($municipio).strval($ano_same),$dados_base_grafico_escola);     
        }

        return $dados_base_grafico_escola ;
    }

    /**
     * Método que busca os dados para montar a sessão Escolas Disciplina Munícipio
     */
    public static function estatisticaEscolaDisciplina($municipio, $id, $ano_same){

         //Busca os dados de gráfico de escola por disciplina, utilizando Município, Ano SAME e Disciplina
         if (Cache::has('est_esc_disc_mun_'.strval($municipio).strval($id).strval($ano_same))) {
            $dados_base_grafico_escola_disciplina = Cache::get('est_esc_disc_mun_'.strval($municipio).strval($id).strval($ano_same));
        } else {

            //Busca os dados do BD utilizando o Município, Ano SAME e disciplina informada
            $dados_base_grafico_escola_disciplina  = DB::select('SELECT CONCAT(\'E\',(@contador := @contador + 1)) AS sigla, 
                UPPER(nome_escola) AS descricao,(SUM(acerto)*100)/(count(id)) AS percentual FROM (SELECT @contador := 0) AS nada,
                dado_unificados WHERE id_municipio = :id_municipio AND id_disciplina = :id_disciplina AND presenca > :presenca AND SAME = :SAME
                GROUP BY nome_escola, id_escola ORDER BY nome_escola, sigla',
                ['presenca' => config('constants.options.confPresenca'),'id_municipio' => $municipio, 'id_disciplina' => $id, 'SAME' => $ano_same]);
            
            //Ajusta as siglas para manter a ordenação
            for ($i = 0; $i < sizeof($dados_base_grafico_escola_disciplina); $i++) {
                if ($i < 9) {
                    $dados_base_grafico_escola_disciplina[$i]->sigla = 'E0' . ($i + 1);
                } else {
                    $dados_base_grafico_escola_disciplina[$i]->sigla = 'E' . ($i + 1);
                }
            }    
            
            //Adiciona ao Cache por prazo Indefinido
            Cache::forever('est_esc_disc_mun_'.strval($municipio).strval($id).strval($ano_same),$dados_base_grafico_escola_disciplina);     
        }

        return $dados_base_grafico_escola_disciplina;
    }

    /**
     * Método que obtem os Dados de Discipliba por Ano Curricular utilizando Cache
     */
    public static function estatisticaAnoDisciplinas($municipio, $id, $ano_same){

        //Busca dados de anos na disciplina da Cache para montagem do gráfico, pelo Município, Disciplina e Ano SAME
        if (Cache::has('est_ano_disc_mun_'.strval($municipio).strval($id).strval($ano_same))) {
            $dados_base_anos_disciplina_grafico = Cache::get('est_ano_disc_mun_'.strval($municipio).strval($id).strval($ano_same));
        } else {

            //Busca dados de anos na disciplina do BD para montagem do gráfico, pelo Município, Disciplina e Ano SAME
            $dados_base_anos_disciplina_grafico  = DB::select('SELECT CONCAT(\'Ano \',ano) AS descricao, (SUM(acerto)*100)/(count(id)) AS percentual,ano, nome_disciplina 
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND id_disciplina = :id_disciplina AND presenca > :presenca AND SAME = :SAME
                 GROUP BY ano, nome_disciplina ORDER BY ano ASC ', 
                 ['presenca' => config('constants.options.confPresenca'),'id_municipio' => $municipio, 'id_disciplina' => $id, 'SAME' => $ano_same]);
            
            //Adiciona ao Cache por prazo indeterminado
            Cache::forever('est_ano_disc_mun_'.strval($municipio).strval($id).strval($ano_same),$dados_base_anos_disciplina_grafico);     
        }

        return $dados_base_anos_disciplina_grafico;
    }

    /**
     * Método que obtem os Dados de Habilidades na Disciplina por Ano utilizando Cache
     */
    public static function estatisticaHabilidadeDisciplinaAno($municipio, $disciplina, $ano, $ano_same){

        $ano = intval($ano);

        //Busca dados de gráfico de Habilidades da Cache pelo Município, Disciplina, Ano SAME e Ano Curricular
        if (Cache::has('hab_disc_ano_mun_'.strval($municipio).strval($disciplina).strval($ano).strval($ano_same))) {
            $dados_base_habilidade_disciplina_ano_grafico = Cache::get('hab_disc_ano_mun_'.strval($municipio).strval($disciplina).strval($ano).strval($ano_same));
        } else {
            //Busca dados de gráfico de Habilidades do BD pelo Município, Disciplina, Ano SAME e Ano Curricular
            $dados_base_habilidade_disciplina_ano_grafico  = DB::select('SELECT sigla_habilidade, (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, 
                tipo_questao, \'white\' AS cor, id_habilidade, nome_habilidade, nome_disciplina FROM dado_unificados WHERE id_municipio = :id_municipio 
                AND id_disciplina = :id_disciplina AND ano = :ano  AND presenca > :presenca AND SAME = :SAME
                GROUP BY id_habilidade, nome_habilidade, sigla_habilidade, nome_disciplina, id_municipio, tipo_questao ORDER BY sigla_habilidade, 
                nome_disciplina ASC ', ['presenca' => config('constants.options.confPresenca'),'id_municipio' => $municipio, 'id_disciplina' => $disciplina, 
                'ano' => $ano, 'SAME' => $ano_same]);

            $dados_ajuste_percentual = MethodsProfMunicipio::estatisticaAjustePercentual($municipio, $disciplina, $ano, $ano_same);

            //Busca Legendas de Cache e BD
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
            
            //Adiciona ao Cache por prazo indeterminado
            Cache::forever('hab_disc_ano_mun_'.strval($municipio).strval($disciplina).strval($ano).strval($ano_same),$dados_base_habilidade_disciplina_ano_grafico);     
        }

        return  $dados_base_habilidade_disciplina_ano_grafico ;
    }

    /**
     * Método que obtem os valores para Ajuste de Percentual utilizando Cache
     */
    public static function estatisticaAjustePercentual($municipio, $disciplina, $ano, $ano_same){
        
        $ano = intval($ano);

        //Busca da Cache pelo Município, Disciplina, Ano Curricular e Ano SAME
        if (Cache::has('ajuste_perc_mun_'.strval($municipio).strval($disciplina).strval($ano).strval($ano_same))) {
            $dados_ajuste_percentual = Cache::get('ajuste_perc_mun_'.strval($municipio).strval($disciplina).strval($ano).strval($ano_same));
        } else {
            //Busca do BD pelo Município, Disciplina, Ano Curricular e Ano SAME
            $dados_ajuste_percentual = DB::select('SELECT sigla_habilidade, COUNT(id) AS qtd, resposta, id_habilidade, id_questao 
                FROM dado_unificados WHERE id_municipio = :id_municipio AND id_disciplina = :id_disciplina AND ano = :ano AND tipo_questao <> \'Objetivas\' 
                AND resposta IS NOT NULL AND resposta <> \'\' AND presenca > :presenca AND SAME = :SAME GROUP BY sigla_habilidade, resposta, 
                id_habilidade, id_questao', ['presenca' => config('constants.options.confPresenca'),'id_municipio' => $municipio, 'id_disciplina' => $disciplina, 
                'ano' => $ano, 'SAME' => $ano_same]);
            
            //Adiciona ao Cache por prazo indeterminado
            Cache::forever('ajuste_perc_mun_'.strval($municipio).strval($disciplina).strval($ano).strval($ano_same),$dados_ajuste_percentual);     
        }

        return  $dados_ajuste_percentual;
    }

    /**
     * Método que óbtem os dados de Questões da Habilidade por Ano Disciplinas
     */
    public static function estatisticaHabilidadeAnoQuestao($municipio, $disciplina, $ano, $ano_same){   
        
        $ano = intval($ano);
        
        //Busca os dados das questões das habilidades por ano da Cache, utilizando o Município, Disciplina, Ano Curricular e Ano SAME
        if (Cache::has('questao_ano_disc_mun'.strval($ano).strval($municipio).strval($disciplina).strval($ano_same))) {
                $dados_base_habilidade_ano_questao = Cache::get('questao_ano_disc_mun'.strval($ano).strval($municipio).strval($disciplina).strval($ano_same));
        } else {
            //Busca os dados das questões das habilidades por ano do BD, utilizando o Município, Disciplina, Ano Curricular e Ano SAME
            $dados_base_habilidade_ano_questao = DB::select('SELECT id_habilidade, (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, correta, 
            desc_questao, id_questao, nome_tema, tipo_questao, imagem_questao, ano FROM dado_unificados WHERE id_municipio = :id_municipio 
            AND id_disciplina = :id_disciplina AND ano = :ano AND presenca > :presenca AND SAME = :SAME GROUP BY id_habilidade, correta, desc_questao, 
            nome_disciplina, id_questao, nome_tema, tipo_questao, imagem_questao, id_municipio, ano ORDER BY id_habilidade ASC ', 
            ['presenca' => config('constants.options.confPresenca'),'id_municipio' => $municipio, 'id_disciplina' => $disciplina,  'ano' => $ano, 'SAME' => $ano_same]);

            $dados_ajuste_percentual = MethodsProfMunicipio::estatisticaAjustePercentual($municipio, $disciplina, $ano, $ano_same);    

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
                
            //Adiciona ao Cache por prazo indeterminado
            Cache::forever('questao_ano_disc_mun'.strval($ano).strval($municipio).strval($disciplina).strval($ano_same),$dados_base_habilidade_ano_questao);     
        }

        return  $dados_base_habilidade_ano_questao;
    }

    /**
     * Método que busca os dados da Sessão Habilidade Selecionada Disciplina utilizando Cache
     */
    public static function estatisticaHabilidadeSelecionadaDisciplina($municipio, $disciplina, $habilidade, $ano_same){

        //Busca dados para o gráfico de anos da habilidade em Cache pelo Município, Disciplina, Habilidade e Ano SAME
        if (Cache::has('est_hab_disc_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same))) {
            $dados_base_habilidade_disciplina_grafico = Cache::get('est_hab_disc_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same));
        } else {
            //Busca os dados de BD por Município, Disciplina, Habilidade e Ano SAME
            $dados_base_habilidade_disciplina_grafico = DB::select('SELECT sigla_habilidade, tipo_questao,(SUM(acerto)*100)/(count(id)) AS percentual_habilidade, 
            ano, CONCAT(ano,\'º Ano\') AS sigla_ano, id_habilidade, nome_habilidade, nome_disciplina, \'white\' AS cor FROM dado_unificados 
            WHERE id_municipio = :id_municipio AND id_disciplina = :id_disciplina AND id_habilidade = :id_habilidade AND presenca > :presenca AND SAME = :SAME 
            GROUP BY id_habilidade, nome_habilidade, sigla_habilidade, nome_disciplina, id_municipio, ano, tipo_questao 
            ORDER BY id_habilidade, ano ASC ', ['presenca' => config('constants.options.confPresenca'),'id_municipio' => $municipio, 'id_disciplina' => $disciplina,
            'id_habilidade' => $habilidade, 'SAME' => $ano_same]);
            
            //Busca os dados de Ajuste Percentual por Ano
            $dados_ajuste_percentual_ano = MethodsProfMunicipio::estatisticaAjustePercentualAno($municipio, $disciplina, $habilidade, $ano_same);

            //Busca a listagem geral de Legendas
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
            //Adiciona ao Cache indefinidamente
            Cache::forever('est_hab_disc_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same),$dados_base_habilidade_disciplina_grafico);     
        }
        
        return  $dados_base_habilidade_disciplina_grafico;
    }

    /**
     * Método que busca Dados para Ajuste de Percentual Sessão Habilidade Selecionada utilizando Cache
     */
    public static function estatisticaAjustePercentualAno($municipio, $disciplina, $habilidade, $ano_same){   
        //Busca os Dados da Cache por Habilidade, Disciplina, Município e Ano SAME
        if (Cache::has('ajuste_ano_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same))) {
            $dados_ajuste_percentual_ano = Cache::get('ajuste_ano_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same));
        } else {
            //Busca os dados de BD por Habilidade, Disciplina, Município e Ano SAME
            $dados_ajuste_percentual_ano = DB::select('SELECT sigla_habilidade, COUNT(id) AS qtd, resposta, id_habilidade, ano, id_questao 
            FROM dado_unificados WHERE id_municipio = :id_municipio AND id_disciplina = :id_disciplina AND id_habilidade = :id_habilidade 
            AND tipo_questao <> \'Objetivas\' AND resposta IS NOT NULL AND resposta <> \'\' AND presenca > :presenca AND SAME = :SAME 
            GROUP BY sigla_habilidade, resposta, id_habilidade, ano, id_questao ', ['presenca' => config('constants.options.confPresenca'),
            'id_municipio' => $municipio, 'id_disciplina' => $disciplina,  'id_habilidade' => $habilidade, 'SAME' => $ano_same]);
            
            //Adiciona ao Cache indefinidamente
            Cache::forever('ajuste_ano_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same),$dados_ajuste_percentual_ano);     
        }

        return  $dados_ajuste_percentual_ano ;
    }

    /**
     * Método que óbtem os dados de Questão das Habilidades Selecionada utilizando Cache
     */
    public static function estatisticaHabilidadeQuestao($municipio, $disciplina, $habilidade, $ano_same){   
        //Busca dados das Questões das Habilidades pelo Município, Disciplina, Habilidade e Ano SAME
        if(Cache::has('questao_hab_disc_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same))){
            $dados_base_habilidade_questao = Cache::get('questao_hab_disc_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same));
        } else {
            //Busca dados de BD pelo Município, Disciplina, Habilidade e Ano SAME
            $dados_base_habilidade_questao = DB::select('SELECT id_habilidade, (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, 
                id_disciplina, desc_questao, id_questao, nome_tema, id_tipo_questao, tipo_questao, correta,  imagem_questao, ano,
                \'Nome CRITÉRIO A\' AS nome_A, \'TESTE CRITÉRIO A\' AS Obs_A,\'Nome CRITÉRIO B\' AS nome_B, \'TESTE CRITÉRIO B\' AS Obs_B, 
                \'Nome CRITÉRIO C\' AS nome_C, \'TESTE CRITÉRIO C\' AS Obs_C,\'Nome CRITÉRIO D\' AS nome_D, \'TESTE CRITÉRIO D\' AS Obs_D
                FROM dado_unificados WHERE id_municipio = :id_municipio AND id_disciplina = :id_disciplina AND id_habilidade = :id_habilidade
                AND presenca > :presenca AND SAME = :SAME GROUP BY id_habilidade, id_disciplina, desc_questao, nome_disciplina, id_questao, nome_tema, 
                id_tipo_questao,  tipo_questao, correta,  imagem_questao, id_municipio, ano ORDER BY id_habilidade ASC ', 
                ['presenca' => config('constants.options.confPresenca'),'id_municipio' => $municipio, 'id_disciplina' => $disciplina, 
                'id_habilidade' => $habilidade, 'SAME' => $ano_same]);   

            //Busca Dados de Ajuste Percentual Ano
            $dados_ajuste_percentual_ano = MethodsProfMunicipio::estatisticaAjustePercentualAno($municipio, $disciplina, $habilidade, $ano_same);

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
            Cache::forever('questao_hab_disc_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same),$dados_base_habilidade_questao);                     
           }
           
        return  $dados_base_habilidade_questao  ;
    }

}



