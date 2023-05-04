<?php

namespace App\Http\Controllers\staticmethods\comparativo;

use App\Models\DadoUnificado;
use App\Models\Escola;
use App\Models\Turma;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

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
     * Método para buscar as escolas do Munícipio utilizando Cache
     */
    public static function getEscolasMunicipio($id_municipio){

        if(Cache::has('escolas_comp'.strval($id_municipio))){
            $escolasListadas = Cache::get('escolas_comp'.strval($id_municipio));
        } else {
            $escolasListadas = Escola::where(['status' => 'Ativo', 'municipios_id' => $id_municipio])->groupBy('nome')->get();
            //Adiciona ao Cache
            Cache::put('escolas_comp'.strval($id_municipio), $escolasListadas, now()->addHours(config('constants.options.horas_cache')));
        }
        
        return $escolasListadas;
    }

    /**
     * Método para buscar as turmas do Munícipio utilizando Cache
     */
    public static function getTurmasMunicipio($id_municipio){

        if(Cache::has('turmas_comp'.strval($id_municipio))){
            $turmasListadas = Cache::get('turmas_comp'.strval($id_municipio));
        } else {
            $turmasListadas = Turma::where(['status' => 'Ativo', 'escolas_municipios_id' => $id_municipio])->groupBy('TURMA')->orderBy('TURMA','asc')->get();
            //Adiciona ao Cache
            Cache::put('turmas_comp'.strval($id_municipio), $turmasListadas, now()->addHours(config('constants.options.horas_cache')));
        }
        
        return $turmasListadas;
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
     * Método que busca os dados para montar a sessão Disciplinas Munícipio
     */
    public static function estatisticaDisciplinas($municipio){
        //Busca os dados do gráfico de disciplina da Cache
        if (Cache::has('compar_disciplina_mun_'.strval($municipio))) {
            $dados_base_grafico_disciplina = Cache::get('compar_disciplina_mun_'.strval($municipio));
        } else {
            //Busca os dados do BD
            $dados_base_grafico_disciplina = DB::select('SELECT nome_disciplina as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca GROUP BY SAME, nome_disciplina', 
                 ['presenca' => config('constants.options.confPresenca'), 'id_municipio' => $municipio]);   
            
            //Ajusta os dados do DataSet e adiciona a Cache     
            $dados_base_grafico_disciplina = MethodsGerais::getDataSet($dados_base_grafico_disciplina, 'compar_disciplina_mun_'.strval($municipio));     
        }

        return $dados_base_grafico_disciplina;
    }

    /**
     * Método que busca os dados para montar a sessão Temas Munícipio
     */
    public static function estatisticaTemas($municipio, $id_disciplina, $ano){

        $ano = intval($ano);

        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_tema_mun_'.strval($municipio).strval($id_disciplina).strval($ano))) {
            $dados_base_grafico_tema = Cache::get('compar_tema_mun_'.strval($municipio).strval($id_disciplina).strval($ano));
        } else {
            //Busca os dados do BD
            $dados_base_grafico_tema = DB::select('SELECT REPLACE(nome_tema,\'.\', \'\') as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca AND id_disciplina = :id_disciplina AND ano = :ano 
                 GROUP BY SAME, nome_tema, id_tema ORDER BY SAME, nome_tema', 
                 ['presenca' => config('constants.options.confPresenca'), 'id_municipio' => $municipio, 'id_disciplina' => $id_disciplina, 'ano' => $ano]);   
            
            //Ajusta os dados do DataSet e adiciona a Cache     
            $dados_base_grafico_tema = MethodsGerais::getDataSet($dados_base_grafico_tema, 'compar_tema_mun_'.strval($municipio).strval($id_disciplina).strval($ano));     
        }

        return $dados_base_grafico_tema;
    }

    /**
     * Método que busca os dados para montar a sessão Temas Munícipio
     */
    public static function estatisticaEscolas($municipio){
        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_escola_mun_'.strval($municipio))) {
            $dados_base_grafico_escola = Cache::get('compar_escola_mun_'.strval($municipio));
        } else {
            //Busca os dados do BD
            $dados_base_grafico_escola = DB::select('SELECT REPLACE(nome_escola, \'.\', \'\') as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca GROUP BY SAME, nome_escola', 
                 ['presenca' => config('constants.options.confPresenca'), 'id_municipio' => $municipio]);   
            
            //Ajusta os dados do DataSet e adiciona a Cache     
            $dados_base_grafico_escola = MethodsGerais::getDataSet($dados_base_grafico_escola, 'compar_escola_mun_'.strval($municipio));     
        }

        return $dados_base_grafico_escola;
    }

    /**
     * Método que busca os dados para montar a sessão Escolas Disciplina Munícipio
     */
    public static function estatisticaEscolasDisciplina($municipio, $id_disciplina){
        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_escola_mun_'.strval($municipio).strval($id_disciplina))) {
            $dados_base_grafico_escola_disc = Cache::get('compar_escola_mun_'.strval($municipio).strval($id_disciplina));
        } else {
            //Busca os dados do BD
            $dados_base_grafico_escola_disc = DB::select('SELECT REPLACE(nome_escola, \'.\', \'\') as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca AND id_disciplina = :id_disciplina GROUP BY SAME, nome_escola', 
                 ['presenca' => config('constants.options.confPresenca'), 'id_municipio' => $municipio, 'id_disciplina' => $id_disciplina]);   
            
            //Ajusta os dados do DataSet e adiciona a Cache     
            $dados_base_grafico_escola_disc = MethodsGerais::getDataSet($dados_base_grafico_escola_disc, 'compar_escola_mun_'.strval($municipio).strval($id_disciplina));     
        }

        return $dados_base_grafico_escola_disc;
    }

    /**
     * Método que busca os dados para montar a sessão Ano Curricular Disciplina Munícipio
     */
    public static function estatisticaCurricularDisciplina($municipio, $id_disciplina){
        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_curricular_mun_'.strval($municipio).strval($id_disciplina))) {
            $dados_base_grafico_curricular_disc = Cache::get('compar_curricular_mun_'.strval($municipio).strval($id_disciplina));
        } else {
            //Busca os dados do BD
            $dados_base_grafico_curricular_disc = DB::select('SELECT CONCAT(\'Ano \',ano) as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca AND id_disciplina = :id_disciplina GROUP BY SAME, ano', 
                 ['presenca' => config('constants.options.confPresenca'), 'id_municipio' => $municipio, 'id_disciplina' => $id_disciplina]);   
            
            //Ajusta os dados do DataSet e adiciona a Cache     
            $dados_base_grafico_curricular_disc = MethodsGerais::getDataSet($dados_base_grafico_curricular_disc, 'compar_curricular_mun_'.strval($municipio).strval($id_disciplina));     
        }

        return $dados_base_grafico_curricular_disc;
    }

    /**
     * Método que busca os dados para montar a sessão Habilidade Ano Disciplina Munícipio
     */
    public static function estatisticaHabilidadeAnoDisciplina($municipio, $id_disciplina, $ano){

        $ano = intval($ano);
        
        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_hab_ano_mun_'.strval($municipio).strval($id_disciplina).strval($ano))) {
            $dados_base_grafico_hab_ano_disc = Cache::get('compar_hab_ano_mun_'.strval($municipio).strval($id_disciplina).strval($ano));
        } else {
            $dados_base_grafico_hab_ano_disc = DB::select('SELECT sigla_habilidade as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual, nome_habilidade AS nome
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca AND id_disciplina = :id_disciplina AND ano = :ano GROUP BY SAME, sigla_habilidade, nome_habilidade', 
                 ['presenca' => config('constants.options.confPresenca'), 'id_municipio' => $municipio, 'id_disciplina' => $id_disciplina, 'ano' => $ano]);   
            
            $dados_base_grafico_hab_ano_disc = MethodsGerais::getDataSetHabilidade($dados_base_grafico_hab_ano_disc, 'compar_hab_ano_mun_'.strval($municipio).strval($id_disciplina).strval($ano));     
        }

        return $dados_base_grafico_hab_ano_disc;
    }

}



