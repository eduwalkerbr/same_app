<?php

namespace App\Http\Controllers\cadastros\manutencao;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
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

        //Inicializa os objetos que serão utilizados na Página
        $this->objSolicitacao = new Solicitacao();
        $this->objTurma = new Turma();
        $this->objEscola = new Escola();
        $this->objQuestao = new Questao();
        $this->objSugestao = new Sugestao();
        $this->objDestaque = new DestaqueModel();
        $this->objMunicipio = new Municipio();
        $this->objDisciplina = new Disciplina();
        $this->objLegenda = new Legenda();
        $this->objPrevilegio = new Previlegio();
        $this->objHabilidade = new Habilidade();
        $this->objDadoUnificado = new DadoUnificado();
        $this->objCriterioQuestao = new CriterioQuestao();
        $this->objDirecaoProfessores = new DirecaoProfessor();
        $this->confPresenca = 1;
        $this->previlegio = [];
        $this->horasCache = 4;
    }

        /**
     * Método que busca os previlégios do Usuário Logado usando Cache
     */
    private function getPrevilegio(){

        if(Cache::has('previlegio_usuario'.strval(auth()->user()->id))){
            $previlegio = Cache::get('previlegio_usuario'.strval(auth()->user()->id));
        } else {
            $previlegio = $this->objPrevilegio->where(['users_id' => auth()->user()->id])->get();
            //Adiciona ao Cache
            Cache::put('previlegio_usuario'.strval(auth()->user()->id),$previlegio, now()->addHours($this->horasCache));    
        }

        return $previlegio;
    }

    /**
     * Método para buscar os munícipios utilizando Cache
     */
    private function getMunicipios($ano_same){

        if (auth()->user()->perfil == 'Administrador' 
            || (($this->getPrevilegio()[0]->funcaos_id == 13 || $this->getPrevilegio()[0]->funcaos_id == 14) && $this->getPrevilegio()[0]->municipios_id == 5)) {
                if (Cache::has('total_municipios_'.strval($ano_same))) {
                    $municipiosListados = Cache::get('total_municipios_'.strval($ano_same));
                } else {
                    $municipiosListados = $this->objMunicipio->where(['status' => 'Ativo','SAME' => $ano_same])->get(); 
                    
                    //Adiciona ao Cache
                    Cache::forever('total_municipios_'.strval($ano_same), $municipiosListados);      
                }
        } else {
            if (Cache::has('mun_list_'.strval(auth()->user()->id).strval($ano_same))) {
                $municipiosListados = Cache::get('mun_list_'.strval(auth()->user()->id).strval($ano_same));
            } else {
                $municipiosListados = $this->objMunicipio->where(['id' => $this->getPrevilegio()[0]->municipios_id, 'SAME' => $ano_same])->get();
                
                //Adiciona ao Cache
                Cache::put('mun_list_'.strval(auth()->user()->id).strval($ano_same),$municipiosListados, now()->addHours($this->horasCache));     
            }
        }
        return $municipiosListados;

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
     * Método para buscar as disciplinas utilizando Cache
     */
    private function getLegendas(){

        $legendasListadas = Cache::remember('legendas', ($this->horasCache*3600), function () {
            return $this->objLegenda->all();
        });

        return $legendasListadas;
    }


    /**
     * Método que lista os Critérios das Questões utilizando Cache
     */
    private function getCriteriosQuestao($ano, $disciplina_selecionada){

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
     * Método que busca os previlégios do Usuário Logado usando Cache
     */
    private function getDirecaoProfessor($ano_same){

        if(Cache::has('direc_profes_'.strval($this->getPrevilegio()[0]->id).strval($ano_same))){
            $direcaoProfessor = Cache::get('direc_profes_'.strval($this->getPrevilegio()[0]->id).strval($ano_same));
        } else {
            $direcaoProfessor = $this->objDirecaoProfessores->where(['id_previlegio' => $this->getPrevilegio()[0]->id],['SAME' => $ano_same])->get();
            //Adiciona ao Cache
            Cache::put('direc_profes_'.strval($this->getPrevilegio()[0]->id).strval($ano_same),$direcaoProfessor, now()->addHours($this->horasCache));     
        }

        return $direcaoProfessor;
    }

    private function getEscolasDiretor($id_municipio, $ano_same){
        
        //Administrador lista todas Escolas
        if (auth()->user()->perfil == 'Administrador' || (($this->getPrevilegio()[0]->funcaos_id == 13 || $this->getPrevilegio()[0]->funcaos_id == 14) && $this->getPrevilegio()[0]->municipios_id == 5)) {
           if(Cache::has('esc_dir_total'.strval($id_municipio).strval($ano_same))){
               $escolas = Cache::get('esc_dir_total'.strval($id_municipio).strval($ano_same));
           } else {
               $escolas = $this->objEscola->where(['status' => 'Ativo','municipios_id' => $id_municipio, 'SAME' =>$ano_same])->get();
               //Adiciona Cache
               Cache::forever('esc_dir_total'.strval($id_municipio).strval($ano_same), $escolas);  
           }
       } else if (isset($this->getPrevilegio()[0]) && $this->getPrevilegio()[0]->funcaos_id == 8) {
           if(Cache::has('escolas_'.strval($id_municipio).strval($ano_same))){
               $escolas = Cache::get('escolas_'.strval($id_municipio).strval($ano_same));
           } else {
               $escolas = $this->objEscola->where(['status' => 'Ativo', 'municipios_id' => $id_municipio, 'SAME' => $ano_same])->get();
               //Adiciona ao Cache
               Cache::put('escolas_'.strval($id_municipio).strval($ano_same), $escolas, now()->addHours($this->horasCache));
           }
       } else {
           //Os demais pega apenas a escola para o qual foi designado seus previlégios
           if(Cache::has('esc_'.strval($this->getDirecaoProfessor($ano_same)[0]->id_escola))){
               $escolas = Cache::get('esc_'.strval($this->getDirecaoProfessor($ano_same)[0]->id_escola));
           } else {
               $escolas = $this->objEscola->where(['id' => $this->getDirecaoProfessor($ano_same)[0]->id_escola])->get();

               //Adiciona Cache
               Cache::put('esc_'.strval($this->getDirecaoProfessor($ano_same)[0]->id_escola),$escolas, now()->addHours($this->horasCache));
           }
           
       }

       return $escolas;
   }

    /**
     * Método para buscar as turmas do Munícipio utilizando Cache
     */
    private function getTurmasEscola($id_escola, $ano_same){

        if(Cache::has('turmas_esc'.strval($id_escola).strval($ano_same))){
            $turmas = Cache::get('turmas_esc'.strval($id_escola).strval($ano_same));
        } else {
            $turmas = $turmas = $this->objTurma->where(['status' => 'Ativo', 'escolas_id' => $id_escola, 'SAME' => $ano_same])->orderBy('TURMA','asc')->get();
            //Adiciona ao Cache
            Cache::put('turmas_esc'.strval($id_escola).strval($ano_same), $turmas, now()->addHours($this->horasCache));
        }
        
        return $turmas;
    }

    /**
     * Método que busca os dados base de Escola utilizando Cache
     */
    private function estatisticaEscolaDiretor($confPresenca, $escola, $ano_same){   
        //Busca dados Gráfico Médica Escola
        if(Cache::has('dir_est_esc_'.strval($escola).strval($ano_same))){
            $dados_base_escola = Cache::get('dir_est_esc_'.strval($escola).strval($ano_same));
        } else {
            $dados_base_escola = DB::select(
                'SELECT (ac.acertos*100)/(qtd_questao.num) AS percentual_escola, \'Proficência Média\' AS descricao FROM dado_unificados du 
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
                ['presenca1' => $confPresenca, 'presenca2' => $confPresenca,'presenca3' => $confPresenca,'presenca4' => $confPresenca,
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
    private function estatisticaComparacaoEscola($confPresenca, $escola, $ano_same){   
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
                ['presenca1' => $confPresenca, 'presenca2' => $confPresenca,'presenca3' => $confPresenca,'presenca4' => $confPresenca,
                 'id_escola1' => $escola, 'id_escola2' => $escola, 'id_escola3' => $escola, 'id_escola4' => $escola, 'SAME' => $ano_same, 'SAME2' => $ano_same,
                 'SAME3' => $ano_same, 'SAME4' => $ano_same, 'SAME5' => $ano_same, 'SAME6' => $ano_same]);

            Cache::forever('dir_esc_comp_'.strval($escola).strval($ano_same), $dados_comparacao_escola);     
        }
        
        return  $dados_comparacao_escola;
    }

    /**
     * Método que obtém os Dados de Percentual da Disciplina na Escola
     */
    private function estatisticaGraficoDisciplina($confPresenca, $escola, $ano_same){
        //Busca Dados para Gráfico de Disciplina da Escola
        if(Cache::has('dir_esc_disc_'.strval($escola).strval($ano_same))){
            $dados_base_grafico_disciplina = Cache::get('dir_esc_disc_'.strval($escola).strval($ano_same));
        } else {
            $dados_base_grafico_disciplina = DB::select(
                'SELECT nome_disciplina AS descricao,(SUM(acerto)*100)/(count(id)) AS percentual 
                 FROM dado_unificados WHERE id_escola = :id_escola AND presenca > :presenca AND SAME = :SAME
                 GROUP BY nome_disciplina', 
                 ['presenca' => $confPresenca, 'id_escola' => $escola, 'SAME' => $ano_same]);

            Cache::forever('dir_esc_disc_'.strval($escola).strval($ano_same), $dados_base_grafico_disciplina);        
        }   

        return  $dados_base_grafico_disciplina;
    }

    /**
     * Método que óbtem os dados de Escola na Disciplina por Ano Curricular
     */
    private function estatisticaDisciplinaGrafico ($confPresenca, $escola, $disciplina, $ano_same){   
        //Busca Dados para Disciplina  Grafico
        if(Cache::has('dir_disc_ano_'.strval($escola).strval($disciplina).strval($ano_same))){
            $dados_base_anos_disciplina_grafico = Cache::get('dir_disc_ano_'.strval($escola).strval($disciplina).strval($ano_same));
        } else {
            $dados_base_anos_disciplina_grafico = DB::select(
                'SELECT CONCAT(\'Ano \',ano) AS descricao, (SUM(acerto)*100)/(count(id)) AS percentual, ano, nome_disciplina 
                FROM dado_unificados WHERE id_escola = :id_escola AND id_disciplina = :id_disciplina
                      AND presenca > :presenca AND SAME = :SAME GROUP BY ano, nome_disciplina ORDER BY ano ASC ', 
                ['presenca' => $confPresenca,'id_escola' => $escola, 'id_disciplina' => $disciplina, 'SAME' => $ano_same]);
            
            Cache::forever('dir_disc_ano_'.strval($escola).strval($disciplina).strval($ano_same), $dados_base_anos_disciplina_grafico);    
        }
        
        return  $dados_base_anos_disciplina_grafico;
    }
  
    /**
     * Método que óbtem os dados de Turmas da Escola pela Disciplina
     */
    private function estatisticaTurmaDisciplinaGrafico ($confPresenca, $escola, $disciplina, $ano_same){         
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
                ['presenca' => $confPresenca,'id_escola' => $escola, 'id_disciplina' => $disciplina, 'SAME' => $ano_same]);

            Cache::forever('dir_tur_disc_'.strval($escola).strval($disciplina).strval($ano_same), $dados_base_turmas_disciplina_grafico); 
        }
        
        return  $dados_base_turmas_disciplina_grafico;
    }

    /**
     * Método que lista as habilidades pelo Munícipio e Disciplina utilizando Cache
     */
    private function getHabilidadesEscola($disciplina_selecionada, $escola_selecionada){

        if (Cache::has('dir_hab_disc_esc_'.strval($disciplina_selecionada).'_'.strval($escola_selecionada))) {
            $habilidades = Cache::get('dir_hab_disc_esc_'.strval($disciplina_selecionada).'_'.strval($escola_selecionada));
        } else {
            $habilidades = $this->objDadoUnificado->select('id_habilidade', 'nome_habilidade', 'sigla_habilidade')
            ->where(['id_disciplina' => $disciplina_selecionada, 'id_escola' => $escola_selecionada])
            ->groupBy('id_habilidade', 'nome_habilidade', 'sigla_habilidade')->orderBy('nome_habilidade', 'asc')->get();
            
            //Adiciona ao Cache   
            Cache::forever('dir_hab_disc_esc_'.strval($disciplina_selecionada).'_'.strval($escola_selecionada), $habilidades);    
        }

        return $habilidades;
    }

    /**
     * Método para buscar os critérios utilizando Cache
     */
    private function getCriterios(){

        $criterios = Cache::rememberForever('criterio_total', function () {
            return $this->objCriterioQuestao->all();
        });

        return $criterios;
    }

        /**
     * Método que óbtem os dados de Habilidade por Ano na Disciplina
     */
    private function estatisticaHabilidadeDiscipliaGrafico($confPresenca, $escola, $disciplina, $ano, $ano_same){  
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
                ['presenca' => $confPresenca,'id_escola' => $escola, 'id_disciplina' => $disciplina, 'ano' => $ano, 'SAME' => $ano_same]
            );   
            
            $dados_ajuste_percentual = $this->estatisticaAjustePercentualDiretor($confPresenca, $escola, $disciplina, $ano, $ano_same);
            $legendas = $this->getLegendas();

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
    private function estatisticaAjustePercentualDiretor($confPresenca, $escola, $disciplina, $ano, $ano_same){ 
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
                ['presenca' => $confPresenca,'id_escola' => $escola, 'id_disciplina' => $disciplina, 'ano' => $ano, 'SAME' => $ano_same]
            );

            Cache::forever('dir_ajuste_perc_'.strval($escola).strval($disciplina).strval($ano).strval($ano_same), $dados_ajuste_percentual); 
        }
        
        return  $dados_ajuste_percentual;
    }
 
    /**
     * Método que óbtem os dados para a formação dos Modais da Sessão de Habilidades por Ano na Disciplina
     */
    private function estatisticaHabilidadeAnoQuestaoDiretor($confPresenca, $escola, $disciplina, $ano, $ano_same){   
        $ano = intval($ano);
        if(Cache::has('dir_questoa_hab_ano_'.strval($escola).strval($disciplina).strval($ano).strval($ano_same))){
            $dados_base_habilidade_ano_questao = Cache::get('dir_questoa_hab_ano_'.strval($escola).strval($disciplina).strval($ano).strval($ano_same));
        } else {
            $dados_base_habilidade_ano_questao = DB::select(
                'SELECT id_habilidade, (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, desc_questao, id_questao, nome_tema, tipo_questao, correta, imagem_questao, ano, id_habilidade
                FROM dado_unificados 
                WHERE id_escola = :id_escola AND id_disciplina = :id_disciplina AND ano = :ano AND presenca > :presenca AND SAME = :SAME 
                GROUP BY id_habilidade, desc_questao, nome_disciplina, id_questao, nome_tema, tipo_questao, correta, imagem_questao, ano ORDER BY id_habilidade ASC ',
                ['presenca' => $confPresenca,'id_escola' => $escola, 'id_disciplina' => $disciplina, 'ano' => $ano, 'SAME' => $ano_same]
            );
    
            $dados_ajuste_percentual = $this->estatisticaAjustePercentualDiretor($confPresenca, $escola, $disciplina, $ano, $ano_same);
    
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

            Cache::forever('dir_questoa_hab_ano_'.strval($escola).strval($disciplina).strval($ano).strval($ano_same), $dados_base_habilidade_ano_questao);
        }
        
        return  $dados_base_habilidade_ano_questao;
    }

    /**
     * Método que óbtem os Percentuais de Ajuste da Sessão Habilidade Individual pelos Anos
     */
    private function estatisticaPercentualAno($confPresenca, $escola, $disciplina, $habilidade, $ano_same){  
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
                ['presenca' => $confPresenca,'id_escola' => $escola, 'id_disciplina' => $disciplina, 'id_habilidade' => $habilidade, 'SAME' => $ano_same]
            );

            //Adiciona ao Cache
            Cache::forever('dir_per_hab_ano_'.strval($escola).strval($disciplina).strval($habilidade).strval($ano_same), $dados_ajuste_percentual_ano);    
        }
        
        return  $dados_ajuste_percentual_ano;
    }

    /**
     * Método que ótem dados da Habilidade indivual pelos Anos
     */
    private function estatisticaEscolaDiscipliaHabilidade($confPresenca, $escola, $disciplina,$habilidade, $ano_same){   
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
                ['presenca' => $confPresenca,'id_escola' => $escola,'id_disciplina' => $disciplina,'id_habilidade' => $habilidade, 'SAME' => $ano_same]
            );      
    
            $dados_ajuste_percentual_ano = $this->estatisticaPercentualAno($confPresenca, $escola, $disciplina,$habilidade, $ano_same);
            $legendas = $this->getLegendas();
    
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
     * Método para obtenção dos utilizados nos Modais da Sessão de Habilidade Individual pelos Anos
     */
    private function estatisticaHabilidadeQuestaoDiretor($confPresenca, $escola, $disciplina, $habilidade, $ano_same){   
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
                ['presenca' => $confPresenca,'id_escola' => $escola, 'id_disciplina' => $disciplina, 'id_habilidade' => $habilidade, 'SAME' => $ano_same]
            );

            $dados_ajuste_percentual_ano = $this->estatisticaPercentualAno($confPresenca, $escola, $disciplina,$habilidade, $ano_same);

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

    /**
     * Método que carrega em Cache Dados de Escola
     */
    public Function carregarCacheEscDadosBase(){

        set_time_limit(0);
        //Busca os previlégios do Usuário Logado
        $this->getPrevilegio();

        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();

        //Busca as Legendas
        $this->getLegendas();

        //Listage, de Direção Professor utilizando Cache
        foreach($anos_same as $ano_same){
            $this->getDirecaoProfessor($ano_same->SAME);
        }

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        //Lista as Disciplinas em Geral
        $disciplinas = $this->getDisciplinas();
        foreach($disciplinas as $disciplina){
            //Carrega os dados das Disciplinas
            $this->getDisciplinaSelecionada($disciplina->id);
        }

        //------------------------------------------- Municípios -----------------------------------------------------------------
        //Lista os Munícipios por Ano SAME
        foreach($anos_same as $ano_same){
            $municipios = $this->getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){
                $this->getMunicipioSelecionado($municipio->id, $ano_same->SAME);
                //Busca e carrega as Escolas Ativas do Munícipio
                $escolas = $this->getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){
                    $this->getEscolaSelecionada($escola->id, $ano_same->SAME);   
                    
                    //Busca e carregar as Turmas Ativas do Município
                    $this->getTurmasEscola($escola->id, $ano_same->SAME);

                    foreach($disciplinas as $disciplina){
                        //Buscas as Habilidades
                        $habilidades = $this->getHabilidadesEscola($disciplina->id, $escola->id);   
                        foreach($habilidades as $habilidade){
                            //Busca os dados da Habilidade Selecionda    
                            $this->getHabilidadeSelecionada($habilidade->id_habilidade);    
                        } 
                    }
                }
            }
        }

        //Busca todos os Critérios
        $this->getCriterios();

        return redirect()->route('lista_manutencao')->with('status', 'Cache Escola Dados Básicos carregados com Sucesso!');
    }

    /**
     * Método que carrega em Cache Dados de Escola
     */
    public Function carregarCacheEscSesMediaEscola(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();

        foreach($anos_same as $ano_same){
            $municipios = $this->getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){    
                $escolas = $this->getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){    
                    //Busca dados Sessão Base de Escola --------------------------------------------------------------
                    $this->estatisticaEscolaDiretor($this->confPresenca, $escola->id, $ano_same->SAME);
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Escola Sessão Média Escolas carregada com Sucesso!');
        
    }

    public Function carregarCacheEscSesCompDisc(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();

        foreach($anos_same as $ano_same){
            $municipios = $this->getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){    
                $escolas = $this->getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){    

                    //Busca dados da Sessão de Comparativo entre a Escola Selecionado e as Demais --------------------
                    $this->estatisticaComparacaoEscola($this->confPresenca, $escola->id, $ano_same->SAME);
                    
                    //Busca Dados para Sessão de Proficiência da Escola pela Disciplina ------------------------------
                    $this->estatisticaGraficoDisciplina($this->confPresenca,  $escola->id, $ano_same->SAME);
                }
                
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Escola Sessões Comparação e Disciplina carregadas com Sucesso!');
    }

    public Function carregarCacheEscAnoCurTurmas(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = $this->getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = $this->getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){    
                $escolas = $this->getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){    
                    foreach($disciplinas as $disciplina){
                        //Busca Dados para Sessão de Ano Curricular Disciplina -------------------------------------------
                        $this->estatisticaDisciplinaGrafico($this->confPresenca,$escola->id,$disciplina->id, $ano_same->SAME);

                        //Busca os dados para Sessão de Turmas da Escola na Disciplina -----------------------------------
                        $this->estatisticaTurmaDisciplinaGrafico($this->confPresenca,$escola->id,$disciplina->id, $ano_same->SAME);
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Escola Sessões Anos Curriculares e Turmas carregada com Sucesso!');
    }

    public Function carregarCacheEscSesAnoHabMat(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = $this->getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = $this->getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){    
                $escolas = $this->getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){
                    $turmasListadas = $this->getTurmasEscola($escola->id, $ano_same->SAME);
                    $anos = [];
                    for ($i = 0; $i < sizeof($turmasListadas); $i++) {
                        if (!in_array(substr(trim($turmasListadas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                            $anos[$i] = substr(trim($turmasListadas[$i]->DESCR_TURMA), 0, 2);
                        }
                    }
                    foreach($anos as $ano){
                        $ano = intval($ano);
                        //Busca Dados da Sessão de Habilidades por Ano da Escola na Disciplina ---------------------------
                        $this->estatisticaHabilidadeDiscipliaGrafico($this->confPresenca, $escola->id, $disciplinas[0]->id, $ano, $ano_same->SAME);
                        $this->estatisticaAjustePercentualDiretor($this->confPresenca, $escola->id, $disciplinas[0]->id, $ano, $ano_same->SAME);
                        //Busca dados das Questões para gerar os Modais de Habilidade por Ano
                        $this->estatisticaHabilidadeAnoQuestaoDiretor($this->confPresenca, $escola->id, $disciplinas[0]->id, $ano, $ano_same->SAME);

                        //Busca os Critérios de Acordo com Ano e Disciplina
                        $this->getCriteriosQuestao($ano, $disciplinas[0]->id);
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Escola Sessão Habilidades por Ano em Matemática carregada com Sucesso!'); 
        
    }

    public Function carregarCacheEscSesAnoHabPort(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = $this->getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = $this->getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){    
                $escolas = $this->getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){
                    $turmasListadas = $this->getTurmasEscola($escola->id, $ano_same->SAME);
                    $anos = [];
                    for ($i = 0; $i < sizeof($turmasListadas); $i++) {
                        if (!in_array(substr(trim($turmasListadas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                            $anos[$i] = substr(trim($turmasListadas[$i]->DESCR_TURMA), 0, 2);
                        }
                    }
                    foreach($anos as $ano){
                        $ano = intval($ano);
                        //Busca Dados da Sessão de Habilidades por Ano da Escola na Disciplina ---------------------------
                        $this->estatisticaHabilidadeDiscipliaGrafico($this->confPresenca, $escola->id, $disciplinas[1]->id, $ano, $ano_same->SAME);
                        $this->estatisticaAjustePercentualDiretor($this->confPresenca, $escola->id, $disciplinas[1]->id, $ano, $ano_same->SAME);
                        //Busca dados das Questões para gerar os Modais de Habilidade por Ano
                        $this->estatisticaHabilidadeAnoQuestaoDiretor($this->confPresenca, $escola->id, $disciplinas[1]->id, $ano, $ano_same->SAME);

                        //Busca os Critérios de Acordo com Ano e Disciplina
                        $this->getCriteriosQuestao($ano, $disciplinas[1]->id);
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
        $anos_same = $this->getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = $this->getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = $this->getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){    
                $escolas = $this->getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){
                    //Buscas as Habilidades
                    $habilidades = $this->getHabilidadesEscola($disciplinas[0]->id, $escola->id);  
                    foreach($habilidades as $habilidade){
                        //Busca Dados da Sessão de Habilidade Individual pelos Anos --------------------------------------   
                        $this->estatisticaEscolaDiscipliaHabilidade($this->confPresenca, $escola->id, $disciplinas[0]->id, $habilidade->id_habilidade, $ano_same->SAME);
                        $this->estatisticaPercentualAno($this->confPresenca, $escola->id, $disciplinas[0]->id, $habilidade->id_habilidade, $ano_same->SAME);
                        //Busca os dados das Questões para Gerar os Modais de Habilidade Individual
                        $this->estatisticaHabilidadeQuestaoDiretor($this->confPresenca, $escola->id, $disciplinas[0]->id,$habilidade->id_habilidade, $ano_same->SAME); 
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
        $anos_same = $this->getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = $this->getDisciplinas();

        foreach($anos_same as $ano_same){
            $municipios = $this->getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){    
                $escolas = $this->getEscolasDiretor($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){
                    //Buscas as Habilidades
                    $habilidades = $this->getHabilidadesEscola($disciplinas[1]->id, $escola->id);  
                    foreach($habilidades as $habilidade){
                        //Busca Dados da Sessão de Habilidade Individual pelos Anos --------------------------------------   
                        $this->estatisticaEscolaDiscipliaHabilidade($this->confPresenca, $escola->id, $disciplinas[1]->id, $habilidade->id_habilidade, $ano_same->SAME);
                        $this->estatisticaPercentualAno($this->confPresenca, $escola->id, $disciplinas[1]->id, $habilidade->id_habilidade, $ano_same->SAME);
                        //Busca os dados das Questões para Gerar os Modais de Habilidade Individual
                        $this->estatisticaHabilidadeQuestaoDiretor($this->confPresenca, $escola->id, $disciplinas[1]->id,$habilidade->id_habilidade, $ano_same->SAME); 
                    }
                }
            }
        }  

        return redirect()->route('lista_manutencao')->with('status', 'Cache Escola Sessão Habilidade transcorrer Anos Português carregada com Sucesso!');       
    }
}
