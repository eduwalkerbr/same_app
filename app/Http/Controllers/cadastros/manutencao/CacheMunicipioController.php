<?php

namespace App\Http\Controllers\cadastros\manutencao;

use Illuminate\Http\Request;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\proficiencia\secretario\SecretarioController;

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
     * Método que lista as habilidades pelo Munícipio e Disciplina utilizando Cache
     */
    private function getHabilidades($disciplina_selecionada, $municipio_selecionado){

        if (Cache::has('hab_disc_mun_'.strval($disciplina_selecionada).'_'.strval($municipio_selecionado))) {
            $habilidades = Cache::get('hab_disc_mun_'.strval($disciplina_selecionada).'_'.strval($municipio_selecionado));
        } else {
            $habilidades = $this->objDadoUnificado->select('id_habilidade', 'nome_habilidade', 'sigla_habilidade')
            ->where(['id_disciplina' => $disciplina_selecionada, 'id_municipio' => $municipio_selecionado])
            ->groupBy('id_habilidade', 'nome_habilidade', 'sigla_habilidade')->orderBy('nome_habilidade', 'asc')->get();
            
            //Adiciona ao Cache
            Cache::put('hab_disc_mun_'.strval($disciplina_selecionada).'_'.strval($municipio_selecionado),$habilidades, now()->addHours($this->horasCache));     
        }

        return $habilidades;
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
     * Método para buscar as escolas do Munícipio utilizando Cache
     */
    private function getEscolasMunicipio($id_municipio, $ano_same){

        if(Cache::has('escolas_'.strval($id_municipio).strval($ano_same))){
            $escolasListadas = Cache::get('escolas_'.strval($id_municipio).strval($ano_same));
        } else {
            $escolasListadas = $this->objEscola->where(['status' => 'Ativo', 'municipios_id' => $id_municipio, 'SAME' => $ano_same])->get();
            //Adiciona ao Cache
            Cache::put('escolas_'.strval($id_municipio).strval($ano_same), $escolasListadas, now()->addHours($this->horasCache));
        }
        
        return $escolasListadas;
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
     * Método para buscar as turmas do Munícipio utilizando Cache
     */
    private function getTurmasMunicipio($id_municipio, $ano_same){

        if(Cache::has('turmas_'.strval($id_municipio).strval($ano_same))){
            $turmasListadas = Cache::get('turmas_'.strval($id_municipio).strval($ano_same));
        } else {
            $turmasListadas = $this->objTurma->where(['status' => 'Ativo', 'escolas_municipios_id' => $id_municipio, 'SAME' => $ano_same])->orderBy('TURMA','asc')->get();
            //Adiciona ao Cache
            Cache::put('turmas_'.strval($id_municipio).strval($ano_same), $turmasListadas, now()->addHours($this->horasCache));
        }
        
        return $turmasListadas;
    }

    /**
     * Método que busca os dados para montar a sessão Disciplinas Munícipio
     */
    private function estatisticaDisciplinas($confPresenca, $municipio, $ano_same){
        //Busca os dados do gráfico de disciplina
        if (Cache::has('disciplina_mun_'.strval($municipio).strval($ano_same))) {
            $dados_base_grafico_disciplina = Cache::get('disciplina_mun_'.strval($municipio).strval($ano_same));
        } else {
            $dados_base_grafico_disciplina  = DB::select('SELECT nome_disciplina AS descricao,(SUM(acerto)*100)/(count(id)) AS percentual 
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca AND SAME = :SAME GROUP BY nome_disciplina', 
                 ['presenca' => $confPresenca, 'id_municipio' => $municipio, 'SAME' => $ano_same]);   
            
            //Adiciona ao Cache
            Cache::forever('disciplina_mun_'.strval($municipio).strval($ano_same),$dados_base_grafico_disciplina);     
        }

        return $dados_base_grafico_disciplina;
    }

    /**
     * Método que busca os dados para montar a sessão Escolas Munícipio
     */
    private function estatisticaEscola($confPresenca, $municipio, $ano_same){
        //Busca os dados do gráfico de disciplina
        if (Cache::has('est_esc_mun_'.strval($municipio).strval($ano_same))) {
            $dados_base_grafico_escola = Cache::get('est_esc_mun_'.strval($municipio).strval($ano_same));
        } else {
            $dados_base_grafico_escola = DB::select('SELECT CONCAT(\'E\',(@contador := @contador + 1)) AS sigla, UPPER(nome_escola) AS descricao,(SUM(acerto)*100)/(count(id)) AS percentual 
                FROM (SELECT @contador := 0) AS nada,dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca AND SAME = :SAME
                GROUP BY nome_escola, id_escola ORDER BY nome_escola, sigla', 
                ['presenca' => $confPresenca,'id_municipio' => $municipio, 'SAME' => $ano_same]);  

            //Ajusta as siglas para manter a ordenação
            for ($i = 0; $i < sizeof($dados_base_grafico_escola); $i++) {
                if ($i < 9) {
                    $dados_base_grafico_escola[$i]->sigla = 'E0' . ($i + 1);
                } else {
                    $dados_base_grafico_escola[$i]->sigla = 'E' . ($i + 1);
                }
            }    
            
            //Adiciona ao Cache
            Cache::forever('est_esc_mun_'.strval($municipio).strval($ano_same),$dados_base_grafico_escola);     
        }

        return $dados_base_grafico_escola ;
    }

    /**
     * Método que busca os dados para montar a sessão Escolas Disciplina Munícipio
     */
    private function estatisticaEscolaDisciplina($confPresenca, $municipio, $id, $ano_same){
         //Busca os dados de gráfico de escola por disciplina
         if (Cache::has('est_esc_disc_mun_'.strval($municipio).strval($id).strval($ano_same))) {
            $dados_base_grafico_escola_disciplina = Cache::get('est_esc_disc_mun_'.strval($municipio).strval($id).strval($ano_same));
        } else {
            $dados_base_grafico_escola_disciplina  = DB::select('SELECT CONCAT(\'E\',(@contador := @contador + 1)) AS sigla, 
                UPPER(nome_escola) AS descricao,(SUM(acerto)*100)/(count(id)) AS percentual FROM (SELECT @contador := 0) AS nada,
                dado_unificados WHERE id_municipio = :id_municipio AND id_disciplina = :id_disciplina AND presenca > :presenca AND SAME = :SAME
                GROUP BY nome_escola, id_escola ORDER BY nome_escola, sigla',
                ['presenca' => $confPresenca,'id_municipio' => $municipio, 'id_disciplina' => $id, 'SAME' => $ano_same]);
            
            //Ajusta as siglas para manter a ordenação
            for ($i = 0; $i < sizeof($dados_base_grafico_escola_disciplina); $i++) {
                if ($i < 9) {
                    $dados_base_grafico_escola_disciplina[$i]->sigla = 'E0' . ($i + 1);
                } else {
                    $dados_base_grafico_escola_disciplina[$i]->sigla = 'E' . ($i + 1);
                }
            }    
            
            //Adiciona ao Cache
            Cache::forever('est_esc_disc_mun_'.strval($municipio).strval($id).strval($ano_same),$dados_base_grafico_escola_disciplina);     
        }

        return $dados_base_grafico_escola_disciplina;
    }

    /**
     * Método que obtem os Dados de Discipliba por Ano Curricular utilizando Cache
     */
    private function estatisticaAnoDisciplinas($confPresenca, $municipio, $id, $ano_same){
        //Busca dados de anos na disciplina para montagem do gráfico
        if (Cache::has('est_ano_disc_mun_'.strval($municipio).strval($id).strval($ano_same))) {
            $dados_base_anos_disciplina_grafico = Cache::get('est_ano_disc_mun_'.strval($municipio).strval($id).strval($ano_same));
        } else {
            $dados_base_anos_disciplina_grafico  = DB::select('SELECT CONCAT(\'Ano \',ano) AS descricao, (SUM(acerto)*100)/(count(id)) AS percentual,ano, nome_disciplina 
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND id_disciplina = :id_disciplina AND presenca > :presenca AND SAME = :SAME
                 GROUP BY ano, nome_disciplina ORDER BY ano ASC ', 
                 ['presenca' => $confPresenca,'id_municipio' => $municipio, 'id_disciplina' => $id, 'SAME' => $ano_same]);
            
            //Adiciona ao Cache
            Cache::forever('est_ano_disc_mun_'.strval($municipio).strval($id).strval($ano_same),$dados_base_anos_disciplina_grafico);     
        }

        return $dados_base_anos_disciplina_grafico;
    }

    /**
     * Método que obtem os Dados de Habilidades na Disciplina por Ano utilizando Cache
     */
    private function estatisticaHabilidadeDisciplinaAno($confPresenca, $municipio, $disciplina, $ano, $ano_same){

        //Busca dados de gráfico 
        if (Cache::has('hab_disc_ano_mun_'.strval($municipio).strval($disciplina).strval($ano).strval($ano_same))) {
            $dados_base_habilidade_disciplina_ano_grafico = Cache::get('hab_disc_ano_mun_'.strval($municipio).strval($disciplina).strval($ano).strval($ano_same));
        } else {
            $dados_base_habilidade_disciplina_ano_grafico  = DB::select('SELECT sigla_habilidade, (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, tipo_questao, \'white\' AS cor, 
                id_habilidade, nome_habilidade, nome_disciplina FROM dado_unificados WHERE id_municipio = :id_municipio AND id_disciplina = :id_disciplina AND ano = :ano  AND presenca > :presenca AND SAME = :SAME
                GROUP BY id_habilidade, nome_habilidade, sigla_habilidade, nome_disciplina, id_municipio, tipo_questao ORDER BY sigla_habilidade, nome_disciplina ASC ',
                ['presenca' => $confPresenca,'id_municipio' => $municipio, 'id_disciplina' => $disciplina, 'ano' => $ano, 'SAME' => $ano_same]);

            $dados_ajuste_percentual = $this->estatisticaAjustePercentual($confPresenca, $municipio, $disciplina, $ano, $ano_same);
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
            
            //Adiciona ao Cache
            Cache::forever('hab_disc_ano_mun_'.strval($municipio).strval($disciplina).strval($ano).strval($ano_same),$dados_base_habilidade_disciplina_ano_grafico);     
        }

        return  $dados_base_habilidade_disciplina_ano_grafico ;
    }

    /**
     * Método que obtem os valores para Ajuste de Percentual utilizando Cache
     */
    private function estatisticaAjustePercentual($confPresenca, $municipio, $disciplina, $anos, $ano_same){
        if (Cache::has('ajuste_perc_mun_'.strval($municipio).strval($disciplina).strval($anos).strval($ano_same))) {
            $dados_ajuste_percentual = Cache::get('ajuste_perc_mun_'.strval($municipio).strval($disciplina).strval($anos).strval($ano_same));
        } else {
            $dados_ajuste_percentual = DB::select('SELECT sigla_habilidade, COUNT(id) AS qtd, resposta, id_habilidade, id_questao 
                FROM dado_unificados WHERE id_municipio = :id_municipio AND id_disciplina = :id_disciplina AND ano = :ano AND tipo_questao <> \'Objetivas\' AND resposta IS NOT NULL AND resposta <> \'\' 
                AND presenca > :presenca AND SAME = :SAME GROUP BY sigla_habilidade, resposta, id_habilidade, id_questao',
                ['presenca' => $confPresenca,'id_municipio' => $municipio, 'id_disciplina' => $disciplina, 'ano' => $anos, 'SAME' => $ano_same]);
            
            //Adiciona ao Cache
            Cache::forever('ajuste_perc_mun_'.strval($municipio).strval($disciplina).strval($anos).strval($ano_same),$dados_ajuste_percentual);     
        }

        return  $dados_ajuste_percentual;
    }

    /**
     * Método que óbtem os dados de Questões da Habilidade por Ano Disciplinas
     */
    private function estatisticaHabilidadeAnoQuestao($confPresenca, $municipio, $disciplina, $ano, $ano_same){   

        //Busca os dados das questões das habilidades por ano
        if (Cache::has('questao_ano_disc_mun'.strval($ano).strval($municipio).strval($disciplina).strval($ano_same))) {
                $dados_base_habilidade_ano_questao = Cache::get('questao_ano_disc_mun'.strval($ano).strval($municipio).strval($disciplina).strval($ano_same));
        } else {
            $dados_base_habilidade_ano_questao = DB::select('SELECT id_habilidade, (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, correta, desc_questao, id_questao, nome_tema, tipo_questao, 
                imagem_questao, ano FROM dado_unificados WHERE id_municipio = :id_municipio AND id_disciplina = :id_disciplina AND ano = :ano
                AND presenca > :presenca AND SAME = :SAME GROUP BY id_habilidade, correta, desc_questao, nome_disciplina, id_questao, nome_tema, tipo_questao, imagem_questao, id_municipio, ano 
                ORDER BY id_habilidade ASC ', ['presenca' => $confPresenca,'id_municipio' => $municipio, 'id_disciplina' => $disciplina,  'ano' => $ano, 'SAME' => $ano_same]);

            $dados_ajuste_percentual = $this->estatisticaAjustePercentual($confPresenca, $municipio, $disciplina, $ano, $ano_same);    

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
                
            //Adiciona ao Cache
            Cache::forever('questao_ano_disc_mun'.strval($ano).strval($municipio).strval($disciplina).strval($ano_same),$dados_base_habilidade_ano_questao);     
        }

        return  $dados_base_habilidade_ano_questao;
    }

    /**
     * Método que busca os dados da Sessão Habilidade Selecionada Disciplina utilizando Cache
     */
    private function estatisticaHabilidadeSelecionadaDisciplina($confPresenca, $municipio, $disciplina, $habilidade, $ano_same){
        //Busca dados para o gráfico de anos da habilidade    
        if (Cache::has('est_hab_disc_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same))) {
            $dados_base_habilidade_disciplina_grafico = Cache::get('est_hab_disc_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same));
        } else {
            $dados_base_habilidade_disciplina_grafico = DB::select('SELECT sigla_habilidade, tipo_questao,(SUM(acerto)*100)/(count(id)) AS percentual_habilidade, ano, CONCAT(ano,\'º Ano\') AS sigla_ano, 
                id_habilidade, nome_habilidade, nome_disciplina, \'white\' AS cor FROM dado_unificados WHERE id_municipio = :id_municipio AND id_disciplina = :id_disciplina AND id_habilidade = :id_habilidade 
                AND presenca > :presenca AND SAME = :SAME GROUP BY id_habilidade, nome_habilidade, sigla_habilidade, nome_disciplina, id_municipio, ano, tipo_questao 
                ORDER BY id_habilidade, ano ASC ', ['presenca' => $confPresenca,'id_municipio' => $municipio, 'id_disciplina' => $disciplina,  'id_habilidade' => $habilidade, 'SAME' => $ano_same]);
            
            $dados_ajuste_percentual_ano = $this->estatisticaAjustePercentuaAno($confPresenca, $municipio, $disciplina, $habilidade, $ano_same);

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
            Cache::forever('est_hab_disc_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same),$dados_base_habilidade_disciplina_grafico);     
        }
        
        return  $dados_base_habilidade_disciplina_grafico;
    }

    /**
     * Método que busca Dados para Ajuste de Percentual Sessão Habilidade Selecionada utilizando Cache
     */
    private function estatisticaAjustePercentuaAno($confPresenca, $municipio, $disciplina, $habilidade, $ano_same){   
        if (Cache::has('ajuste_ano_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same))) {
            $dados_ajuste_percentual_ano = Cache::get('ajuste_ano_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same));
        } else {
            $dados_ajuste_percentual_ano = DB::select('SELECT sigla_habilidade, COUNT(id) AS qtd, resposta, id_habilidade, ano, id_questao FROM dado_unificados WHERE id_municipio = :id_municipio 
                AND id_disciplina = :id_disciplina AND id_habilidade = :id_habilidade AND tipo_questao <> \'Objetivas\' AND resposta IS NOT NULL AND resposta <> \'\'
                AND presenca > :presenca AND SAME = :SAME GROUP BY sigla_habilidade, resposta, id_habilidade, ano, id_questao ',
                ['presenca' => $confPresenca,'id_municipio' => $municipio, 'id_disciplina' => $disciplina,  'id_habilidade' => $habilidade, 'SAME' => $ano_same]);
            
            //Adiciona ao Cache
            Cache::forever('ajuste_ano_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same),$dados_ajuste_percentual_ano);     
        }

        return  $dados_ajuste_percentual_ano ;
    }

    /**
     * Método que óbtem os dados de Questão das Habilidades Selecionada utilizando Cache
     */
    private function estatisticaHabilidadeQuestao($confPresenca, $municipio, $disciplina, $habilidade, $ano_same){   
        //Busca dados das Questões das Habilidades
        if(Cache::has('questao_hab_disc_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same))){
            $dados_base_habilidade_questao = Cache::get('questao_hab_disc_mun_'.strval($habilidade).strval($disciplina).strval($municipio).strval($ano_same));
        } else {
            $dados_base_habilidade_questao = DB::select('SELECT id_habilidade, (SUM(acerto)*100)/(count(id)) AS percentual_habilidade, 
                id_disciplina, desc_questao, id_questao, nome_tema, id_tipo_questao, tipo_questao, correta,  imagem_questao, ano,
                \'Nome CRITÉRIO A\' AS nome_A, \'TESTE CRITÉRIO A\' AS Obs_A,\'Nome CRITÉRIO B\' AS nome_B, \'TESTE CRITÉRIO B\' AS Obs_B, 
                \'Nome CRITÉRIO C\' AS nome_C, \'TESTE CRITÉRIO C\' AS Obs_C,\'Nome CRITÉRIO D\' AS nome_D, \'TESTE CRITÉRIO D\' AS Obs_D
                FROM dado_unificados WHERE id_municipio = :id_municipio AND id_disciplina = :id_disciplina AND id_habilidade = :id_habilidade
                AND presenca > :presenca AND SAME = :SAME GROUP BY id_habilidade, id_disciplina, desc_questao, nome_disciplina, id_questao, nome_tema, id_tipo_questao,  tipo_questao, correta,  imagem_questao, id_municipio, ano 
                ORDER BY id_habilidade ASC ', ['presenca' => $confPresenca,'id_municipio' => $municipio, 'id_disciplina' => $disciplina,  'id_habilidade' => $habilidade, 'SAME' => $ano_same]);   

            $dados_ajuste_percentual_ano = $this->estatisticaAjustePercentuaAno($confPresenca, $municipio, $disciplina, $habilidade, $ano_same);

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
    
    /**
     * Método que carrega os dados da Cache de Munícipio
     */
    public function carregarCacheMunDadosBase(){

        set_time_limit(0);
        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();
        //Busca os previlégios do Usuário Logado
        $this->getPrevilegio();

        //Lista os Munícipios por Ano SAME
        foreach($anos_same as $ano_same){
            $municipios = $this->getMunicipios($ano_same->SAME);
            //Carrega os dados do Município
            foreach($municipios as $municipio){
                $this->getMunicipioSelecionado($municipio->id, $ano_same->SAME);

                //Busca e carrega as Escolas Ativas do Munícipio
                //$escolas = SecretarioController::getEscolasMunicipio($municipio->id, $ano_same->SAME);
                //dump($escolas);

                $escolas = $this->getEscolasMunicipio($municipio->id, $ano_same->SAME);
                foreach($escolas as $escola){
                    $this->getEscolaSelecionada($escola->id, $ano_same->SAME);    
                }

                //Busca e carregar as Turmas Ativas do Município
                $this->getTurmasMunicipio($municipio->id, $ano_same->SAME);
            }
        }
        
        //Lista as Disciplinas em Geral
        $disciplinas = $this->getDisciplinas();
        foreach($disciplinas as $disciplina){
            //Carrega os dados das Disciplinas
            $this->getDisciplinaSelecionada($disciplina->id);
        }

        //Busca as Legendas em Geral
        $this->getLegendas();

        //---------------- Dados para a Sessão Proficiência Disciplina -----------------------------------------------------------------------------------------
        foreach($anos_same as $ano_same){
            $municipiosListados = Cache::get('total_municipios_'.strval($ano_same->SAME));    
            foreach($municipiosListados as $municipio){
                $this->estatisticaDisciplinas($this->confPresenca, $municipio->id, $ano_same->SAME);
            }
        }
        //---------------- Dados para a Sessão Proficiência Disciplina ----------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------
        foreach($anos_same as $ano_same){
            $municipiosListados = Cache::get('total_municipios_'.strval($ano_same->SAME));    
            foreach($municipiosListados as $municipio){
                $this->estatisticaEscola($this->confPresenca, $municipio->id, $ano_same->SAME);
            }
        }
        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------
        foreach($anos_same as $ano_same){
            $municipiosListados = Cache::get('total_municipios_'.strval($ano_same->SAME));    
            foreach($municipiosListados as $municipio){
                foreach($disciplinas as $disciplina){
                    $this->estatisticaEscolaDisciplina($this->confPresenca, $municipio->id, $disciplina->id, $ano_same->SAME);
                }
            }
        }
        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------
        foreach($anos_same as $ano_same){
            $municipiosListados = Cache::get('total_municipios_'.strval($ano_same->SAME));    
            foreach($municipiosListados as $municipio){
                foreach($disciplinas as $disciplina){
                    $this->estatisticaAnoDisciplinas($this->confPresenca, $municipio->id, $disciplina->id, $ano_same->SAME);
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
        $anos_same = $this->getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = $this->getDisciplinas();

        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------
        foreach($anos_same as $ano_same){
            $municipiosListados = Cache::get('total_municipios_'.strval($ano_same->SAME));   
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
                    $this->estatisticaHabilidadeDisciplinaAno($this->confPresenca,$municipio->id, $disciplinas[0]->id, $ano, $ano_same->SAME);
                    $this->estatisticaAjustePercentual($this->confPresenca,$municipio->id, $disciplinas[0]->id, $ano, $ano_same->SAME);
                    // Dados das questões das Habilidades Selecionadas por Ano
                    $this->estatisticaHabilidadeAnoQuestao($this->confPresenca, $municipio->id, $disciplinas[0]->id, $ano, $ano_same->SAME);
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
        $anos_same = $this->getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = $this->getDisciplinas();

        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------
        foreach($anos_same as $ano_same){
            $municipiosListados = Cache::get('total_municipios_'.strval($ano_same->SAME));   
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
                    $this->estatisticaHabilidadeDisciplinaAno($this->confPresenca,$municipio->id, $disciplinas[1]->id, $ano, $ano_same->SAME);
                    $this->estatisticaAjustePercentual($this->confPresenca,$municipio->id, $disciplinas[1]->id, $ano, $ano_same->SAME);
                    // Dados das questões das Habilidades Selecionadas por Ano
                    $this->estatisticaHabilidadeAnoQuestao($this->confPresenca, $municipio->id, $disciplinas[1]->id, $ano, $ano_same->SAME);
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
        $anos_same = $this->getAnosSAME();
                
        //Lista as Disciplinas em Geral
        $disciplinas = $this->getDisciplinas();

        //Busca as Habilidades pela Disciplina e Munícipio
        foreach($anos_same as $ano_same){
            $municipiosListados = Cache::get('total_municipios_'.strval($ano_same->SAME));   
            foreach($municipiosListados as $municipio){
                foreach($disciplinas as $disciplina){
                    $habilidades = $this->getHabilidades($disciplina->id, $municipio->id);
                    foreach($habilidades as $habilidade){
                        $this->getHabilidadeSelecionada($habilidade->id_habilidade);
                    }
                }
            }
        }

        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------    
        foreach($anos_same as $ano_same){
            $municipiosListados = Cache::get('total_municipios_'.strval($ano_same->SAME));   
            foreach($municipiosListados as $municipio){
                foreach($disciplinas as $disciplina){
                    $habilidades = Cache::get('hab_disc_mun_'.strval($disciplina->id).'_'.strval($municipio->id));
                    foreach($habilidades as $habilidade){
                        $this->estatisticaHabilidadeSelecionadaDisciplina($this->confPresenca,$municipio->id, $disciplina->id, $habilidade->id_habilidade, $ano_same->SAME);
                        $this->estatisticaAjustePercentuaAno($this->confPresenca,$municipio->id, $disciplina->id, $habilidade->id_habilidade, $ano_same->SAME);
                        //Busca dados das Questões das Habilidades
                        $this->estatisticaHabilidadeQuestao($this->confPresenca,$municipio->id, $disciplina->id, $habilidade->id_habilidade, $ano_same->SAME);
                    }
                }
            }
        }
        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------  

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        foreach($anos_same as $ano_same){
            $municipiosListados = Cache::get('total_municipios_'.strval($ano_same->SAME));   
            foreach($municipiosListados as $municipio){
                $turmasListadas = Cache::get('turmas_'.strval($municipio->id).strval($ano_same->SAME));
                foreach($turmasListadas as $turma){
                    //$ano = substr($turma->DESCR_TURMA, 1, 2);
                    $ano = substr(trim($turma->DESCR_TURMA), 0, 2);
                    foreach($disciplinas as $disciplina){
                        $this->getCriteriosQuestao($ano, $disciplina->id);
                    }
                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Município Habilidade Selecionada carregada com Sucesso!');
    }
}
