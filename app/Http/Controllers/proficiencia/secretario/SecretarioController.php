<?php

namespace App\Http\Controllers\proficiencia\secretario;

use App\Models\CriterioQuestao;
use App\Models\DadoUnificado;
use App\Models\DestaqueModel;
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
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

use function Symfony\Component\String\b;

class SecretarioController extends Controller
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
        $this->confPresenca = 1;
        $this->previlegio = [];
        $this->horasCache = 4;
    }

    /**
     * Método que busca os previlégios do Usuário Logado usando Cache
     */
    public function getPrevilegio(){

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
    public function getAnosSAME(){
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
            $municipio_selecionado = $this->objMunicipio->where(['id' => $id])->where(['SAME' => $ano_same])->get();

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

        if (Cache::has('hab_disc_mun_'.strval($disciplina_selecionada[0]->id).'_'.strval($municipio_selecionado))) {
            $habilidades = Cache::get('hab_disc_mun_'.strval($disciplina_selecionada[0]->id).'_'.strval($municipio_selecionado));
        } else {
            $habilidades = $this->objDadoUnificado->select('id_habilidade', 'nome_habilidade', 'sigla_habilidade')
            ->where(['id_disciplina' => $disciplina_selecionada[0]->id, 'id_municipio' => $municipio_selecionado])
            ->groupBy('id_habilidade', 'nome_habilidade', 'sigla_habilidade')->orderBy('nome_habilidade', 'asc')->get();
            
            //Adiciona ao Cache
            Cache::put('hab_disc_mun_'.strval($disciplina_selecionada[0]->id).'_'.strval($municipio_selecionado),$habilidades, now()->addHours($this->horasCache));     
        }

        return $habilidades;
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
            $escola_selecionada = $this->objEscola->where(['id' => $id])->where(['SAME' => $ano_same])->get();

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

        $ano = intval($ano);
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
        $anos = intval($anos);

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
        $ano = intval($ano);
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

    private function getHabilidadesCriterios($dados_base_habilidade_questao){
        // Nova definição das Habilidades com os Critérios
        for ($j = 0; $j < sizeof($dados_base_habilidade_questao); $j++) {
            if ($dados_base_habilidade_questao[$j]->tipo_questao != 'Objetivas'){
                //Nos demais não existe esse filtro adicional
                if(Cache::has('criterio_total')){
                    $criterios_questaoAll = Cache::get('criterio_total');  
                } else {
                    $criterios_questaoAll = $this->objCriterioQuestao->all();
                    Cache::put('criterio_total',$criterios_questaoAll, now()->addHours($this->horasCache));
                }
                //$criterios_questaoAll = $this->objCriterioQuestao->all();
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
     * Método para disponibilização de página Inicial
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();
        $ano_same_selecionado = $anos_same[0]->SAME;

        //Busca os previlégios do Usuário Logado
        $previlegio = $this->getPrevilegio();

        //Lista os Munícipios
        $municipios = $this->getMunicipios($ano_same_selecionado);

        //Lista as Disciplinas
        $disciplinas = $this->getDisciplinas();

        //Busca as Sugestões
        $sugestoes = $this->objSugestao->orderBy('updated_at', 'desc')->paginate(2);

        //Caso seja Gestor busca as solicitações de seu munícpio
        if ($previlegio[0]->funcaos_id == 6) {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
        } else {
            //Caso contrário busca todas as solicições
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
        }

        //Busca as Legendas
        $legendas = $this->getLegendas();

        //Busca os destaques
        $destaques = $this->objDestaque->orderBy('updated_at', 'desc')->get();

        //Busca o munícipio selecionado
        $municipio = $municipios[0]->id;

        //Busca as escola ativas do município
        $escolas = $this->getEscolasMunicipio($municipio, $ano_same_selecionado);

        //Busca as turmas ativas do municípios
        $turmas = $this->getTurmasMunicipio($municipio, $ano_same_selecionado);
        

        //Seta os Anos a serem utilizados na listagem
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o primeiro ano da listagem como padrão
        $ano = substr(trim($turmas[0]->DESCR_TURMA), 0, 2);

        //Define o município selecionado
        $municipio_selecionado = $this->getMunicipioSelecionado($municipio, $ano_same_selecionado);

        //Define a disciplina selecionada
        $disciplina_selecionada = $this->getDisciplinaSelecionada($disciplinas[0]->id);

        //Define a escola selecionada
        $escola_selecionada = $this->getEscolaSelecionada($escolas[0]->id, $ano_same_selecionado);

        //Reseta o nome da escola selecionada
        $escola_selecionada[0]->nome = null;

        //---------------- Dados para a Sessão Proficiência Disciplina -----------------------------------------------------------------------------------------
        $dados_base_grafico_disciplina=$this->estatisticaDisciplinas($this->confPresenca, $municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_disciplina = array_chunk($dados_base_grafico_disciplina, 4);
        //---------------- Dados para a Sessão Proficiência Disciplina ----------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------
        $dados_base_grafico_escola = $this->estatisticaEscola($this->confPresenca, $municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola = array_chunk($dados_base_grafico_escola, 4);
        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------
        $dados_base_grafico_escola_disciplina = $this->estatisticaEscolaDisciplina($this->confPresenca, $municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola_disciplina = array_chunk($dados_base_grafico_escola_disciplina, 4);
        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------
        $dados_base_anos_disciplina_grafico = $this->estatisticaAnoDisciplinas($this->confPresenca, $municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_anos_disciplina = array_chunk($dados_base_anos_disciplina_grafico, 6);
        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico = $this->estatisticaHabilidadeDisciplinaAno($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $anos[0], $ano_same_selecionado);
        $dados_ajuste_percentual = $this->estatisticaAjustePercentual($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $anos[0], $ano_same_selecionado);
        //Divide em 6 registros por linha para gerar os Cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        // Dados das questões das Habilidades Selecionadas por Ano
        $dados_base_habilidade_ano_questao = $this->estatisticaHabilidadeAnoQuestao($this->confPresenca, $municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------

        //Busca as Habilidades pela Disciplina e Munícipio
        $habilidades = $this->getHabilidades($disciplina_selecionada, $municipio);

        //Busca habilidade selecionada
        $habilidade_selecionada = $this->getHabilidadeSelecionada($habilidades[0]->id_habilidade);

        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico = $this->estatisticaHabilidadeSelecionadaDisciplina($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = $this->estatisticaAjustePercentuaAno($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca dados das Questões das Habilidades
        $dados_base_habilidade_questao = $this->estatisticaHabilidadeQuestao($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------  

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = $this->getCriteriosQuestao($ano, $disciplina_selecionada[0]->id);

        $turmas = null;

        $sessao_inicio = "municipio";
              
        return view('secretario/secretario', compact(
            'solRegistro','solAltCadastral','solAddTurma','sugestoes','turmas','escolas','municipios','destaques','municipio_selecionado','dados_base_grafico_disciplina',
            'dados_base_disciplina','dados_base_grafico_escola','dados_base_escola','dados_base_grafico_escola_disciplina','dados_base_escola_disciplina','disciplinas',
            'disciplina_selecionada','legendas','dados_base_anos_disciplina_grafico','dados_base_anos_disciplina','escola_selecionada','anos','ano','dados_base_habilidade_disciplina_ano_grafico',
            'dados_base_habilidades_ano_disciplina','dados_base_habilidade_ano_questao','habilidades','habilidade_selecionada','dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina',
            'dados_base_habilidade_questao','dados_ajuste_percentual','dados_ajuste_percentual_ano','criterios_questao','anos_same','ano_same_selecionado','sessao_inicio'));
    }
    //

    /**
     * Show the application dashboard.
     * Método para disponibilização da página após seleção de um município e ou disciplina diferente
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirMunicipio($id, $id_disciplina, $ano_same)
    {
        $ano_same_selecionado = $ano_same;

        //Busca os previlégios do Usuário Logado
        $previlegio = $this->getPrevilegio();

        
        //Lista os Munícipios
        $municipios = $this->getMunicipios($ano_same_selecionado);

        //Seta o Munícipio Padrão
        $municipio = $municipios[0]->id;

        //Caso esteja na listagem substitui pelo informado
        for ($i = 0; $i < sizeof($municipios); $i++) {
            if($municipios[$i]->id == $id){
                $municipio = $id;    
            }    
        }

        //Lista as Disciplinas
        $disciplinas = $this->getDisciplinas();

        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();

        //Busca as Sugestões
        $sugestoes = $this->objSugestao->orderBy('updated_at', 'desc')->paginate(2);

        //Caso seja Gestor busca as solicitações de seu munícipio
        if ($previlegio[0]->funcaos_id == 6) {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
        } else {
            //Caso seja Gestor busca as solicitações de seu munícipio
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
        }

        //Busca as Legendas
        $legendas = $this->getLegendas();

        //Busca os destaques
        $destaques = $this->objDestaque->orderBy('updated_at', 'desc')->get();

        //Busca os destaques
        //$municipio = $id;

        //Busca as escola ativas do município
        $escolas = $this->getEscolasMunicipio($municipio, $ano_same_selecionado);

        //Busca as turmas ativas do municípios
        $turmas = $this->getTurmasMunicipio($municipio, $ano_same_selecionado);

        //Seta os Anos a serem utilizados na listagem
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o primeiro ano da listagem como padrão
        $ano = substr(trim($turmas[0]->DESCR_TURMA), 0, 2);

        //Define o município selecionado
        $municipio_selecionado = $this->getMunicipioSelecionado($municipio, $ano_same_selecionado);

        //Define a disciplina selecionada
        $disciplina_selecionada = $this->getDisciplinaSelecionada($id_disciplina);

        //Define a escola selecionada
        $escola_selecionada = $this->getEscolaSelecionada($escolas[0]->id, $ano_same_selecionado);

        //Reseta o nome da escola selecionada
        $escola_selecionada[0]->nome = null;
        
        //---------------- Dados para a Sessão Proficiência Disciplina -----------------------------------------------------------------------------------------
        $dados_base_grafico_disciplina=$this->estatisticaDisciplinas($this->confPresenca, $municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_disciplina = array_chunk($dados_base_grafico_disciplina, 4);
        //---------------- Dados para a Sessão Proficiência Disciplina ----------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------
        $dados_base_grafico_escola = $this->estatisticaEscola($this->confPresenca, $municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola = array_chunk($dados_base_grafico_escola, 4);
        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------
        $dados_base_grafico_escola_disciplina = $this->estatisticaEscolaDisciplina($this->confPresenca, $municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola_disciplina = array_chunk($dados_base_grafico_escola_disciplina, 4);
        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------
        $dados_base_anos_disciplina_grafico = $this->estatisticaAnoDisciplinas($this->confPresenca, $municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_anos_disciplina = array_chunk($dados_base_anos_disciplina_grafico, 6);
        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------

       //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico = $this->estatisticaHabilidadeDisciplinaAno($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $anos[0], $ano_same_selecionado);
        $dados_ajuste_percentual = $this->estatisticaAjustePercentual($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $anos[0], $ano_same_selecionado);
        //Divide em 6 registros por linha para gerar os Cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        // Dados das questões das Habilidades Selecionadas por Ano
        $dados_base_habilidade_ano_questao = $this->estatisticaHabilidadeAnoQuestao($this->confPresenca, $municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------

        //Busca as Habilidades pela Disciplina e Munícipio
        $habilidades = $this->getHabilidades($disciplina_selecionada, $municipio);

        //Busca habilidade selecionada
        $habilidade_selecionada = $this->getHabilidadeSelecionada($habilidades[0]->id_habilidade);

        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico = $this->estatisticaHabilidadeSelecionadaDisciplina($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = $this->estatisticaAjustePercentuaAno($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca dados das Questões das Habilidades
        $dados_base_habilidade_questao = $this->estatisticaHabilidadeQuestao($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------  

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = $this->getCriteriosQuestao($ano, $disciplina_selecionada[0]->id);

        $turmas = null;

        $sessao_inicio = "municipio";

        return view('secretario/secretario', compact(

            'solRegistro','solAltCadastral','solAddTurma','sugestoes','turmas','escolas','municipios','destaques','municipio_selecionado','dados_base_grafico_disciplina',
            'dados_base_disciplina','dados_base_grafico_escola','dados_base_escola','dados_base_grafico_escola_disciplina','dados_base_escola_disciplina','disciplinas',
            'disciplina_selecionada','legendas','dados_base_anos_disciplina_grafico','dados_base_anos_disciplina','escola_selecionada',
            'anos','ano','dados_base_habilidade_disciplina_ano_grafico','dados_base_habilidades_ano_disciplina','dados_base_habilidade_ano_questao','habilidades','habilidade_selecionada',
            'dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_habilidade_questao','dados_ajuste_percentual','dados_ajuste_percentual_ano',
            'criterios_questao','anos_same','ano_same_selecionado','sessao_inicio'));
    }

    /**
     * Show the application dashboard.
     * Método para disponibilização da página após a seleção de um ano
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirMunicipioAno($id, $id_disciplina, $ano, $ano_same)
    {
        $ano_same_selecionado = $ano_same;

        //Busca os previlégios do Usuário Logado
        $previlegio = $this->getPrevilegio();

         //Lista os Munícipios
         $municipios = $this->getMunicipios($ano_same_selecionado);

         //Seta o Munícipio Padrão
         $municipio = $municipios[0]->id;
 
         //Caso esteja na listagem substitui pelo informado
         for ($i = 0; $i < sizeof($municipios); $i++) {
             if($municipios[$i]->id == $id){
                 $municipio = $id;    
             }    
         }

        //Lista as Disciplinas
        $disciplinas = $this->getDisciplinas();

        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();

        //Busca as Sugestões
        $sugestoes = $this->objSugestao->orderBy('updated_at', 'desc')->paginate(2);

        //Caso seja Gestor busca as solicitações de seu munícipio
        if ($previlegio[0]->funcaos_id == 6) {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
        } else {
            //Caso seja Gestor busca as solicitações de seu munícipio
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
        }

        //Busca as Legendas
        $legendas = $this->getLegendas();

        //Busca os destaques
        $destaques = $this->objDestaque->orderBy('updated_at', 'desc')->get();

        //Busca os municipio
       // $municipio = $id;

        //Busca as escola ativas do município
        $escolas = $this->getEscolasMunicipio($municipio, $ano_same_selecionado);

        //Busca as turmas ativas do municípios
        $turmas = $this->getTurmasMunicipio($municipio, $ano_same_selecionado);

        //Seta os Anos a serem utilizados na listagem
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o município selecionado
        $municipio_selecionado = $this->getMunicipioSelecionado($municipio, $ano_same_selecionado);

        //Define a disciplina selecionada
        $disciplina_selecionada = $this->getDisciplinaSelecionada($id_disciplina);

        //Define a escola selecionada
        $escola_selecionada = $this->getEscolaSelecionada($escolas[0]->id, $ano_same_selecionado);

        //Reseta o nome da escola selecionada
        $escola_selecionada[0]->nome = null;

        //---------------- Dados para a Sessão Proficiência Disciplina -----------------------------------------------------------------------------------------
        $dados_base_grafico_disciplina=$this->estatisticaDisciplinas($this->confPresenca, $municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_disciplina = array_chunk($dados_base_grafico_disciplina, 4);
        //---------------- Dados para a Sessão Proficiência Disciplina ----------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------
        $dados_base_grafico_escola = $this->estatisticaEscola($this->confPresenca, $municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola = array_chunk($dados_base_grafico_escola, 4);
        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------
        $dados_base_grafico_escola_disciplina = $this->estatisticaEscolaDisciplina($this->confPresenca, $municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola_disciplina = array_chunk($dados_base_grafico_escola_disciplina, 4);
        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------
        $dados_base_anos_disciplina_grafico = $this->estatisticaAnoDisciplinas($this->confPresenca, $municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_anos_disciplina = array_chunk($dados_base_anos_disciplina_grafico, 6);
        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------

        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico = $this->estatisticaHabilidadeDisciplinaAno($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        $dados_ajuste_percentual = $this->estatisticaAjustePercentual($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //Divide em 6 registros por linha para gerar os Cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        // Dados das questões das Habilidades Selecionadas por Ano
        $dados_base_habilidade_ano_questao = $this->estatisticaHabilidadeAnoQuestao($this->confPresenca, $municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------

        //Busca as Habilidades pela Disciplina e Munícipio
        $habilidades = $this->getHabilidades($disciplina_selecionada, $municipio);

        //Busca habilidade selecionada
        $habilidade_selecionada = $this->getHabilidadeSelecionada($habilidades[0]->id_habilidade);

        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico = $this->estatisticaHabilidadeSelecionadaDisciplina($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = $this->estatisticaAjustePercentuaAno($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca dados das Questões das Habilidades
        $dados_base_habilidade_questao = $this->estatisticaHabilidadeQuestao($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------  

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = $this->getCriteriosQuestao($ano, $disciplina_selecionada[0]->id);

        $turmas = null;

        $sessao_inicio = "habilidadeanodisciplina";

        return view('secretario/secretario', compact(
            
            'solRegistro','solAltCadastral','solAddTurma','sugestoes','turmas','escolas','municipios','destaques','municipio_selecionado','dados_base_grafico_disciplina','dados_base_disciplina',
            'dados_base_grafico_escola','dados_base_escola','dados_base_grafico_escola_disciplina','dados_base_escola_disciplina','disciplinas','disciplina_selecionada','legendas',
            'dados_base_anos_disciplina_grafico','dados_base_anos_disciplina','escola_selecionada','anos','ano','dados_base_habilidade_disciplina_ano_grafico','dados_base_habilidades_ano_disciplina',
            'dados_base_habilidade_ano_questao','habilidades','habilidade_selecionada','dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_habilidade_questao',
            'dados_ajuste_percentual','dados_ajuste_percentual_ano','criterios_questao','anos_same','ano_same_selecionado','sessao_inicio'));
    }

    /**
     * Show the application dashboard.
     * Método para disponibilização da página após seleção de uma habilidade
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirMunicipioHabilidade($id, $id_disciplina, $ano, $id_habilidade, $ano_same)
    {
        $ano_same_selecionado = $ano_same;

         //Busca os previlégios do Usuário Logado
        $previlegio = $this->getPrevilegio();

         //Lista os Munícipios
         $municipios = $this->getMunicipios($ano_same_selecionado);

         //Seta o Munícipio Padrão
         $municipio = $municipios[0]->id;
 
         //Caso esteja na listagem substitui pelo informado
         for ($i = 0; $i < sizeof($municipios); $i++) {
             if($municipios[$i]->id == $id){
                 $municipio = $id;    
             }    
         }

        //Lista as Disciplinas
        $disciplinas = $this->getDisciplinas();

        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();

        //Busca as Sugestões
        $sugestoes = $this->objSugestao->orderBy('updated_at', 'desc')->paginate(2);
 
         //Caso seja Gestor busca as solicitações de seu munícipio
         if ($previlegio[0]->funcaos_id == 6) {
             $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
             $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
             $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
         } else {
             //Caso seja Gestor busca as solicitações de seu munícipio
             $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
             $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
             $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
         }
 
         //Busca as Legendas
        $legendas = $this->getLegendas();

        //Busca os destaques
        $destaques = $this->objDestaque->orderBy('updated_at', 'desc')->get();

        //Busca os municipio
        //$municipio = $id;

        //Busca as escola ativas do município
        $escolas = $this->getEscolasMunicipio($municipio, $ano_same_selecionado);

        //Busca as turmas ativas do municípios
        $turmas = $this->getTurmasMunicipio($municipio, $ano_same_selecionado);
 
         //Seta os Anos a serem utilizados na listagem
         $anos = [];
         for ($i = 0; $i < sizeof($turmas); $i++) {
             if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                 $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
             }
         }
 
        //Define o município selecionado
        $municipio_selecionado = $this->getMunicipioSelecionado($municipio, $ano_same_selecionado);

        //Define a disciplina selecionada
        $disciplina_selecionada = $this->getDisciplinaSelecionada($id_disciplina);

        //Define a escola selecionada
        $escola_selecionada = $this->getEscolaSelecionada($escolas[0]->id, $ano_same_selecionado);

        //Reseta o nome da escola selecionada
        $escola_selecionada[0]->nome = null;
 
        //---------------- Dados para a Sessão Proficiência Disciplina -----------------------------------------------------------------------------------------
        $dados_base_grafico_disciplina=$this->estatisticaDisciplinas($this->confPresenca, $municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_disciplina = array_chunk($dados_base_grafico_disciplina, 4);
        //---------------- Dados para a Sessão Proficiência Disciplina ----------------------------------------------------------------------------------------
 
        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------
        $dados_base_grafico_escola = $this->estatisticaEscola($this->confPresenca, $municipio, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola = array_chunk($dados_base_grafico_escola, 4);
        //---------------- Dados para a Sessão Escolas Município ----------------------------------------------------------------------------------------------
 
        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------
        $dados_base_grafico_escola_disciplina = $this->estatisticaEscolaDisciplina($this->confPresenca, $municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 4 por linha para montagem dos cards
        $dados_base_escola_disciplina = array_chunk($dados_base_grafico_escola_disciplina, 4);
        //---------------- Dados para a Sessão Escolas Disciplina Município -----------------------------------------------------------------------------------
 
        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------
        $dados_base_anos_disciplina_grafico = $this->estatisticaAnoDisciplinas($this->confPresenca, $municipio, $disciplina_selecionada[0]->id, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_anos_disciplina = array_chunk($dados_base_anos_disciplina_grafico, 6);
        //---------------- Dados para a Sessão Disciplina por Ano Curricular ----------------------------------------------------------------------------------
 
        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------
        $dados_base_habilidade_disciplina_ano_grafico = $this->estatisticaHabilidadeDisciplinaAno($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        $dados_ajuste_percentual = $this->estatisticaAjustePercentual($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //Divide em 6 registros por linha para gerar os Cards
        $dados_base_habilidades_ano_disciplina = array_chunk($dados_base_habilidade_disciplina_ano_grafico, 6);
        // Dados das questões das Habilidades Selecionadas por Ano
        $dados_base_habilidade_ano_questao = $this->estatisticaHabilidadeAnoQuestao($this->confPresenca, $municipio, $disciplina_selecionada[0]->id, $ano, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Ano Disciplina --------------------------------------------------------------------------------------
 
        //Busca as Habilidades pela Disciplina e Munícipio
        $habilidades = $this->getHabilidades($disciplina_selecionada, $municipio);

        //Busca habilidade selecionada
        $habilidade_selecionada = $this->getHabilidadeSelecionada($id_habilidade);
 
        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------    
        $dados_base_habilidade_disciplina_grafico = $this->estatisticaHabilidadeSelecionadaDisciplina($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        $dados_ajuste_percentual_ano = $this->estatisticaAjustePercentuaAno($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //Divide os dados em 6 por linha para montagem dos cards
        $dados_base_habilidades_disciplina = array_chunk($dados_base_habilidade_disciplina_grafico, 6);
        //Busca dados das Questões das Habilidades
        $dados_base_habilidade_questao = $this->estatisticaHabilidadeQuestao($this->confPresenca,$municipio, $disciplina_selecionada[0]->id, $habilidade_selecionada[0]->id_habilidade, $ano_same_selecionado);
        //---------------- Dados para a Sessão Habilidade Selecionada Anos ------------------------------------------------------------------------------------  

        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        $criterios_questao = $this->getCriteriosQuestao($ano, $disciplina_selecionada[0]->id);

        // Nova definição das Habilidades com os Critérios
        $dados_base_habilidade_questao = $this->getHabilidadesCriterios($dados_base_habilidade_questao);

        $turmas = null;

        $sessao_inicio = "habilidadeselecionadadisciplina";
         
        return view('secretario/secretario', compact(
            'solRegistro','solAltCadastral','solAddTurma','sugestoes','turmas','escolas','municipios','destaques','municipio_selecionado','dados_base_grafico_disciplina',
            'dados_base_disciplina','dados_base_grafico_escola','dados_base_escola','dados_base_grafico_escola_disciplina','dados_base_escola_disciplina','disciplinas',
            'disciplina_selecionada','legendas','dados_base_anos_disciplina_grafico','dados_base_anos_disciplina','escola_selecionada','anos','ano',
            'dados_base_habilidade_disciplina_ano_grafico','dados_base_habilidades_ano_disciplina','dados_base_habilidade_ano_questao','habilidades','habilidade_selecionada',
            'dados_base_habilidade_disciplina_grafico','dados_base_habilidades_disciplina','dados_base_habilidade_questao','dados_ajuste_percentual','dados_ajuste_percentual_ano' 
            ,'criterios_questao','anos_same','ano_same_selecionado','sessao_inicio'));
    }
}



