<?php

namespace App\Http\Controllers\staticmethods\comparativo;

use App\Models\Escola;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Http\Controllers\staticmethods\comparativo\MethodsGerais as ComparativoMethodsGerais;
use App\Http\Controllers\staticmethods\gerais\MethodsGerais;
use App\Models\Turma;
use Illuminate\Support\Facades\DB;

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
     * Busca Escolas pelo Município, agrupando para o Comparativo
     */
    public static function getEscolasDiretor($id_municipio){
        
        //Administrador lista todas Escolas
        if (auth()->user()->perfil == 'Administrador' || ((MethodsGerais::getPrevilegio()[0]->funcaos_id == 13 
        || MethodsGerais::getPrevilegio()[0]->funcaos_id == 14) && MethodsGerais::getPrevilegio()[0]->municipios_id == 5)) {
           if(Cache::has('esc_comp_dir_total'.strval($id_municipio))){
               $escolas = Cache::get('esc_comp_dir_total'.strval($id_municipio));
           } else {
               $escolas = Escola::where(['status' => 'Ativo','municipios_id' => $id_municipio])->groupBy('nome')->get();
               //Adiciona Cache
               Cache::forever('esc_comp_dir_total'.strval($id_municipio), $escolas);  
           }
       } else if (isset(MethodsGerais::getPrevilegio()[0]) && MethodsGerais::getPrevilegio()[0]->funcaos_id == 8) {
           if(Cache::has('escolas_comp'.strval($id_municipio))){
               $escolas = Cache::get('escolas_comp'.strval($id_municipio));
           } else {
               $escolas = Escola::where(['status' => 'Ativo', 'municipios_id' => $id_municipio])->groupBy('nome')->get();
               //Adiciona ao Cache
               Cache::put('escolas_comp'.strval($id_municipio), $escolas, now()->addHours(config('constants.options.horas_cache')));
           }
       } else {
           //Os demais pega apenas a escola para o qual foi designado seus previlégios
           if(Cache::has('esc_comp_dp'.strval(ComparativoMethodsGerais::getDirecaoProfessor()[0]->id_previlegio))){
               $escolas = Cache::get('esc_comp_dp'.strval(ComparativoMethodsGerais::getDirecaoProfessor()[0]->id_escola));
           } else {
               $id_escolas = [];
               for ($i = 0; $i < sizeof(ComparativoMethodsGerais::getDirecaoProfessor()); $i++) {
                   $id_escolas[$i] = ComparativoMethodsGerais::getDirecaoProfessor()[$i]->id_escola;
               }
               $escolas = Escola::whereIn('id', $id_escolas)->groupBy('nome')->get();

               //Adiciona Cache
               Cache::put('esc_comp_dp'.strval(ComparativoMethodsGerais::getDirecaoProfessor()[0]->id_previlegio),$escolas, now()->addHours(config('constants.options.horas_cache')));
           }
           
       }

       return $escolas;
    }

   /**
     * Método para buscar as turmas do Munícipio utilizando Cache
     */
    public static function getTurmasEscola($id_escola){

        if(Cache::has('turmas_esc_comp'.strval($id_escola))){
            $turmas = Cache::get('turmas_esc_comp'.strval($id_escola));
        } else {
            $turmas = $turmas = Turma::where(['status' => 'Ativo', 'escolas_id' => $id_escola])->groupBy('TURMA')->orderBy('TURMA','asc')->get();
            //Adiciona ao Cache
            Cache::put('turmas_esc_comp'.strval($id_escola), $turmas, now()->addHours(config('constants.options.horas_cache')));
        }
        
        return $turmas;
    }

    /**
     * Método que busca os dados para montar a sessão Disciplinas Escola
     */
    public static function estatisticaDisciplinas($escola){
        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_disciplina_esc_'.strval($escola))) {
            $dados_base_grafico_disciplina = Cache::get('compar_disciplina_esc_'.strval($escola));
        } else {
            $dados_base_grafico_disciplina  = DB::select('SELECT nome_disciplina as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_escola = :id_escola AND presenca > :presenca GROUP BY SAME, nome_disciplina', 
                 ['presenca' => config('constants.options.confPresenca'), 'id_escola' => $escola]);   
            
            $dados_base_grafico_disciplina = ComparativoMethodsGerais::getDataSet($dados_base_grafico_disciplina, 'compar_disciplina_esc_'.strval($escola));     
        }

        return $dados_base_grafico_disciplina;
    }

    /**
     * Método que busca os dados para montar a sessão Temas Escola
     */
    public static function estatisticaTemas($escola, $id_disciplina, $ano){

        $ano = intval($ano);
        
        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_tema_esc_'.strval($escola).strval($id_disciplina).strval($ano))) {
            $dados_base_grafico_tema = Cache::get('compar_tema_esc_'.strval($escola).strval($id_disciplina).strval($ano));
        } else {
            $dados_base_grafico_tema = DB::select('SELECT REPLACE(nome_tema,\'.\', \'\') as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_escola = :id_escola AND presenca > :presenca AND id_disciplina = :id_disciplina AND ano = :ano GROUP BY SAME, nome_tema', 
                 ['presenca' => config('constants.options.confPresenca'), 'id_escola' => $escola, 'id_disciplina' => $id_disciplina, 'ano' => $ano]);   
            
            $dados_base_grafico_tema = ComparativoMethodsGerais::getDataSet($dados_base_grafico_tema, 'compar_tema_esc_'.strval($escola).strval($id_disciplina).strval($ano));     
        }

        return $dados_base_grafico_tema;
    }

    /**
     * Método que busca os dados para montar a sessão Ano Curricular Escola
     */
    public static function estatisticaCurricularDisciplina($escola, $id_disciplina){
        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_curricular_esc_'.strval($escola).strval($id_disciplina))) {
            $dados_base_grafico_curricular_disc = Cache::get('compar_curricular_esc_'.strval($escola).strval($id_disciplina));
        } else {
            $dados_base_grafico_curricular_disc = DB::select('SELECT CONCAT(\'Ano \',ano) as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_escola = :id_escola AND presenca > :presenca AND id_disciplina = :id_disciplina GROUP BY SAME, ano', 
                 ['presenca' => config('constants.options.confPresenca'), 'id_escola' => $escola, 'id_disciplina' => $id_disciplina]);   
            
            $dados_base_grafico_curricular_disc = ComparativoMethodsGerais::getDataSet($dados_base_grafico_curricular_disc, 'compar_curricular_esc_'.strval($escola).strval($id_disciplina));     
        }

        return $dados_base_grafico_curricular_disc;
    }

    /**
     * Método que busca os dados para montar a sessão Turma Escola
     */
    public static function estatisticaTurmaDisciplina($escola, $id_disciplina){
        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_turma_esc_'.strval($escola).strval($id_disciplina))) {
            $dados_base_grafico_turma_disc = Cache::get('compar_turma_esc_'.strval($escola).strval($id_disciplina));
        } else {
            $dados_base_grafico_turma_disc = DB::select('SELECT REPLACE(nome_turma,\'\t\',\'\') as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_escola = :id_escola AND presenca > :presenca AND id_disciplina = :id_disciplina GROUP BY SAME, nome_turma', 
                 ['presenca' => config('constants.options.confPresenca'), 'id_escola' => $escola, 'id_disciplina' => $id_disciplina]);   
            
            $dados_base_grafico_turma_disc = ComparativoMethodsGerais::getDataSet($dados_base_grafico_turma_disc, 'compar_turma_esc_'.strval($escola).strval($id_disciplina));     
        }

        return $dados_base_grafico_turma_disc;
    }

    /**
     * Método que busca os dados para montar a sessão Habilidade Ano Curricular Escola
     */
    public static function estatisticaHabilidadeAnoDisciplina($escola, $id_disciplina, $ano){

        $ano = intval($ano);

        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_hab_ano_esc_'.strval($escola).strval($id_disciplina).strval($ano))) {
            $dados_base_grafico_hab_ano_disc = Cache::get('compar_hab_ano_esc_'.strval($escola).strval($id_disciplina).strval($ano));
        } else {
            $dados_base_grafico_hab_ano_disc = DB::select('SELECT sigla_habilidade as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual, nome_habilidade AS nome
                 FROM dado_unificados WHERE id_escola = :id_escola AND presenca > :presenca AND id_disciplina = :id_disciplina AND ano = :ano GROUP BY SAME, sigla_habilidade, nome_habilidade', 
                 ['presenca' => config('constants.options.confPresenca'), 'id_escola' => $escola, 'id_disciplina' => $id_disciplina, 'ano' => $ano]);   
            
            $dados_base_grafico_hab_ano_disc = ComparativoMethodsGerais::getDataSetHabilidade($dados_base_grafico_hab_ano_disc, 'compar_hab_ano_esc_'.strval($escola).strval($id_disciplina).strval($ano));     
        }

        return $dados_base_grafico_hab_ano_disc;
    }

}



