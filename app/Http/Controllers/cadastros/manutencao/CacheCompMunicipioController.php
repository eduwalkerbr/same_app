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

class CacheCompMunicipioController extends Controller
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
        $this->backgroundColors = [
            'rgba(139,0,0, 0.2)','rgba(54, 162, 235, 0.2)','rgba(255, 206, 86, 0.2)','rgba(75, 192, 192, 0.2)','rgba(153, 102, 255, 0.2)',
            'rgba(255, 159, 64, 0.2)','rgba(0, 0, 0, 0.2)','rgba(220,220,220,0.3)','rgba(0,0,139,0.2)','rgba(160,82,45,0.2)',
            'rgba(255,0,255,0.2)','rgba(0,128,0,0.2)','rgba(255,255,0,0.2)','rgba(0,0,255,0.2)','rgba(0,255,0,0.2)','rgba(255,255,255,0.2)',
            'rgba(255,0,0,0.2)','rgba(255,140,0,0.3)','rgba(128,128,0,0.3)','rgba(255,20,147,0.3)','rgba(250,128,114,0.3)','rgba(0,255,0,0.3)',
            'rgba(255,215,0,0.4)'];
        $this->borderColors = [
            'rgba(128,0,0, 1)','rgba(54, 162, 235, 1)','rgba(255, 206, 86, 1)','rgba(75, 192, 192, 1)','rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)','rgba(0, 0, 0, 1)','rgba(54,54,54,1)','rgba(25,25,112,1)','rgba(139,69,19,1)',
            'rgba(139,0,139,1)','rgba(0,100,0,1)','rgba(255,215,0,1)','rgba(0,0,139,1)','rgba(0,100,0,1)','rgba(0,0,0,1)',
            'rgba(128,0,0,1)','rgba(255,69,0,1)','rgba(107,142,35,1)','rgba(199,21,133,1)','rgba(165,42,42,1)','rgba(0,100,0,1)',
            'rgba(184,134,11,1)'];
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
     * Método para buscar os munícipios utilizando Cache
     */
    private function getMunicipios(){

        if (auth()->user()->perfil == 'Administrador' 
            || (($this->getPrevilegio()[0]->funcaos_id == 13 || $this->getPrevilegio()[0]->funcaos_id == 14) && $this->getPrevilegio()[0]->municipios_id == 5)) {
                if (Cache::has('total_municipios_comparativo')) {
                    $municipiosListados = Cache::get('total_municipios_comparativo');
                } else {
                    $municipiosListados = $this->objMunicipio->where(['status' => 'Ativo'])->groupBy('nome')->get(); 
                    
                    //Adiciona ao Cache
                    Cache::forever('total_municipios_comparativo', $municipiosListados);      
                }
        } else {
            if (Cache::has('mun_list_comparativo'.strval(auth()->user()->id))) {
                $municipiosListados = Cache::get('mun_list_comparativo'.strval(auth()->user()->id));
            } else {
                $municipiosListados = $this->objMunicipio->where(['id' => $this->getPrevilegio()[0]->municipios_id])->get();
                
                //Adiciona ao Cache
                Cache::put('mun_list_comparativo'.strval(auth()->user()->id),$municipiosListados, now()->addHours($this->horasCache));     
            }
        }
        return $municipiosListados;

    }

    /**
     * Método que óbtem os dados do Municipio Selecionado utilizando Cache
     */
    private function getMunicipioSelecionado($id){
        if(Cache::has('mun_comp'.strval($id))){
            $municipio_selecionado = Cache::get('mun_comp'.strval($id));
        } else {
            $municipio_selecionado = $this->objMunicipio->where(['id' => $id])->groupBy('nome')->get();

            //Adiciona ao Cache
            Cache::forever('mun_comp'.strval($id), $municipio_selecionado);    
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

    private function getDataSetHabilidade($resultSet, $cacheName){

        $dados = [];

        //Verifica se já tem o valor em Cache
        if (Cache::has($cacheName)) {
            $dados = Cache::get($cacheName);
        } else {
            //Inicializa Labels do Gráfico
            $labels_hab = [];
            $itens_hab = [];
            $nome_hab = [];
            $map_itens_label = [];
            $cont_label = 0;
            $cont_item = 0;

            //Executa para listar os Labels e Itens Diferentes
            for ($i = 0; $i < sizeof($resultSet); $i++) {
                //Monta a Lista de Labels
                if (!in_array(trim($resultSet[$i]->label), $labels_hab)) {
                    $labels_hab[$cont_label] = $resultSet[$i]->label;
                    $cont_label++;
                }
                //Monta a Lista de Itens
                if (!in_array(trim($resultSet[$i]->item), $itens_hab)) {
                    $itens_hab[$cont_item] = $resultSet[$i]->item;
                    $nome_hab[$cont_item] = $resultSet[$i]->nome;
                    $cont_item++;
                }
            }
            for ($i = 0; $i < sizeof($itens_hab); $i++) {
                for ($j = 0; $j < sizeof($labels_hab); $j++) {
                    $cont_item = 0;
                    for ($k = 0; $k < sizeof($resultSet); $k++) {
                        if($resultSet[$k]->item == $itens_hab[$i] && $resultSet[$k]->label == $labels_hab[$j]){
                            $cont_item = 1;
                            //Caso Exista o Mapeamento para o Ano
                            if(array_key_exists($labels_hab[$j],$map_itens_label)){
                                //Pega Itens Existente no Array
                                $item_array = $map_itens_label[$labels_hab[$j]];
                                //Cria o Novo Item
                                $novo_item_array = array(
                                    'x' => $resultSet[$k]->label,
                                    $resultSet[$k]->item => $resultSet[$k]->percentual,
                                    'nome_habilidade' => $resultSet[$k]->nome,);
                                //Combina os Itens para criar o Array completo    
                                $map_itens_label[$labels_hab[$j]] = array_merge($item_array, $novo_item_array);
                            } else {
                                //Caso seja o primeiro item do array
                                $map_itens_label[$resultSet[$k]->label] = array(
                                    'x' => $resultSet[$k]->label,
                                    $resultSet[$k]->item => $resultSet[$k]->percentual,
                                    'nome_habilidade' => $resultSet[$k]->nome
                                );   
                            }    
                        }
                    }
                    if($cont_item == 0){
                        if(array_key_exists($labels_hab[$j],$map_itens_label)){
                            //Pega Itens Existente no Array
                            $item_array = $map_itens_label[$labels_hab[$j]];
                            //Cria o Novo Item
                            $novo_item_array = array(
                                'x' => $labels_hab[$j],
                                $itens_hab[$i] => "Ausente",
                                'nome_habilidade' => $nome_hab[$i],);
                            //Combina os Itens para criar o Array completo    
                            $map_itens_label[$labels_hab[$j]] = array_merge($item_array, $novo_item_array);
                        } else {
                            //Caso seja o primeiro item do array
                            $map_itens_label[$labels_hab[$j]] = array(
                                'x' => $labels_hab[$j],
                                $itens_hab[$i] => "Ausente",
                                'nome_habilidade' => $nome_hab[$i]
                            );     
                        }
                    }
                }
            }
            
            //Monta o DataSet do Gráfico
            $contColors = 0;
            for ($i = 0; $i < sizeof($itens_hab); $i++) {
                $item_data_set = [
                    'label' => $itens_hab[$i],
                    'data' => array_values($map_itens_label),
                    'backgroundColor' => $this->backgroundColors[$contColors],
                    'borderColor' => $this->borderColors[$contColors],
                    'borderWidth' => 1,
                    'hoverBorderWidth' => 2,
                    'hoverBorderColor' => 'green',
                    'parsing' => [
                        'yAxisKey' => $itens_hab[$i]
                    ]
                ];   
    
                $dataSet[$i] = $item_data_set;
                if($contColors == 22){   
                    $contColors = 0;
                } else {
                    $contColors++;
                }
                
            }

            $dados[0] = $labels_hab;
            $dados[1] = $dataSet;
            $dados[2] = $itens_hab;
            $dados[3] = $map_itens_label;
            $dados[4] = $nome_hab;

            //Adiciona ao Cache
            Cache::forever($cacheName,$dados);
        }

        return $dados;
    }

    private function getDataSet($resultSet, $cacheName){

        $dados = [];

        //Verifica se já tem o valor em Cache
        if (Cache::has($cacheName)) {
            $dados = Cache::get($cacheName);
        } else {
            //Inicializa Labels do Gráfico
            $labels_disc = [];
            $itens_disc = [];
            $map_itens_label = [];
            $cont_label = 0;
            $cont_item = 0;

            //Executa para listar os Labels e Itens Diferentes
            for ($i = 0; $i < sizeof($resultSet); $i++) {
                //Monta a Lista de Labels
                if (!in_array(trim($resultSet[$i]->label), $labels_disc)) {
                    $labels_disc[$cont_label] = $resultSet[$i]->label;
                    $cont_label++;
                }
                //Monta a Lista de Itens
                if (!in_array(trim($resultSet[$i]->item), $itens_disc)) {
                    $itens_disc[$cont_item] = $resultSet[$i]->item;
                    $cont_item++;
                }
            }
            for ($i = 0; $i < sizeof($itens_disc); $i++) {
                for ($j = 0; $j < sizeof($labels_disc); $j++) {
                    $cont_item = 0;
                    for ($k = 0; $k < sizeof($resultSet); $k++) {
                        if($resultSet[$k]->item == $itens_disc[$i] && $resultSet[$k]->label == $labels_disc[$j]){
                            $cont_item = 1;
                            //Caso Exista o Mapeamento para o Ano
                            if(array_key_exists($labels_disc[$j],$map_itens_label)){
                                //Pega Itens Existente no Array
                                $item_array = $map_itens_label[$labels_disc[$j]];
                                //Cria o Novo Item
                                $novo_item_array = array(
                                    'x' => $resultSet[$k]->label,
                                    $resultSet[$k]->item => $resultSet[$k]->percentual,);
                                //Combina os Itens para criar o Array completo    
                                $map_itens_label[$labels_disc[$j]] = array_merge($item_array, $novo_item_array);
                            } else {
                                //Caso seja o primeiro item do array
                                $map_itens_label[$resultSet[$k]->label] = array(
                                    'x' => $resultSet[$k]->label,
                                    $resultSet[$k]->item => $resultSet[$k]->percentual,
                                );   
                            }    
                        }
                    }
                    if($cont_item == 0){
                        if(array_key_exists($labels_disc[$j],$map_itens_label)){
                            //Pega Itens Existente no Array
                            $item_array = $map_itens_label[$labels_disc[$j]];
                            //Cria o Novo Item
                            $novo_item_array = array(
                                'x' => $labels_disc[$j],
                                $itens_disc[$i] => "Ausente",);
                            //Combina os Itens para criar o Array completo    
                            $map_itens_label[$labels_disc[$j]] = array_merge($item_array, $novo_item_array);
                        } else {
                            //Caso seja o primeiro item do array
                            $map_itens_label[$labels_disc[$j]] = array(
                                'x' => $labels_disc[$j],
                                $itens_disc[$i] => "Ausente",
                            );     
                        }
                    }
                }
            }
            
            //Monta o DataSet do Gráfico
            $contColors = 0;
            for ($i = 0; $i < sizeof($itens_disc); $i++) {
                $item_data_set = [
                    'label' => $itens_disc[$i],
                    'data' => array_values($map_itens_label),
                    'backgroundColor' => $this->backgroundColors[$contColors],
                    'borderColor' => $this->borderColors[$contColors],
                    'borderWidth' => 1,
                    'hoverBorderWidth' => 2,
                    'hoverBorderColor' => 'green',
                    'parsing' => [
                        'yAxisKey' => $itens_disc[$i]
                    ]
                ];   
    
                $dataSet[$i] = $item_data_set;
                if($contColors == 22){   
                    $contColors = 0;
                } else {
                    $contColors++;
                }
                
            }

            $dados[0] = $labels_disc;
            $dados[1] = $dataSet;
            $dados[2] = $itens_disc;
            $dados[3] = $map_itens_label;

            //Adiciona ao Cache
            Cache::forever($cacheName,$dados);
        }

        return $dados;
    }

    /**
     * Método que busca os dados para montar a sessão Disciplinas Munícipio
     */
    private function estatisticaDisciplinas($confPresenca, $municipio){
        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_disciplina_mun_'.strval($municipio))) {
            $dados_base_grafico_disciplina = Cache::get('compar_disciplina_mun_'.strval($municipio));
        } else {
            $dados_base_grafico_disciplina  = DB::select('SELECT nome_disciplina as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca GROUP BY SAME, nome_disciplina', 
                 ['presenca' => $confPresenca, 'id_municipio' => $municipio]);   
            
            $dados_base_grafico_disciplina = $this->getDataSet($dados_base_grafico_disciplina, 'compar_disciplina_mun_'.strval($municipio));     
        }

        return $dados_base_grafico_disciplina;
    }

    /**
     * Método que busca os dados para montar a sessão Temas Munícipio
     */
    private function estatisticaTemas($confPresenca, $municipio){
        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_tema_mun_'.strval($municipio))) {
            $dados_base_grafico_tema = Cache::get('compar_tema_mun_'.strval($municipio));
        } else {
            $dados_base_grafico_tema = DB::select('SELECT REPLACE(nome_tema,\'.\', \'\') as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca GROUP BY SAME, nome_tema, id_tema ORDER BY SAME, nome_tema', 
                 ['presenca' => $confPresenca, 'id_municipio' => $municipio]);   
            
            $dados_base_grafico_tema = $this->getDataSet($dados_base_grafico_tema, 'compar_tema_mun_'.strval($municipio));     
        }

        return $dados_base_grafico_tema;
    }

    /**
     * Método que busca os dados para montar a sessão Temas Munícipio
     */
    private function estatisticaEscolas($confPresenca, $municipio){
        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_escola_mun_'.strval($municipio))) {
            $dados_base_grafico_escola = Cache::get('compar_escola_mun_'.strval($municipio));
        } else {
            $dados_base_grafico_escola = DB::select('SELECT REPLACE(nome_escola, \'.\', \'\') as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca GROUP BY SAME, nome_escola', 
                 ['presenca' => $confPresenca, 'id_municipio' => $municipio]);   
            
            $dados_base_grafico_escola = $this->getDataSet($dados_base_grafico_escola, 'compar_escola_mun_'.strval($municipio));     
        }

        return $dados_base_grafico_escola;
    }

    /**
     * Método que busca os dados para montar a sessão Temas Munícipio
     */
    private function estatisticaEscolasDisciplina($confPresenca, $municipio, $id_disciplina){
        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_escola_mun_'.strval($municipio).strval($id_disciplina))) {
            $dados_base_grafico_escola_disc = Cache::get('compar_escola_mun_'.strval($municipio).strval($id_disciplina));
        } else {
            $dados_base_grafico_escola_disc = DB::select('SELECT REPLACE(nome_escola, \'.\', \'\') as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca AND id_disciplina = :id_disciplina GROUP BY SAME, nome_escola', 
                 ['presenca' => $confPresenca, 'id_municipio' => $municipio, 'id_disciplina' => $id_disciplina]);   
            
            $dados_base_grafico_escola_disc = $this->getDataSet($dados_base_grafico_escola_disc, 'compar_escola_mun_'.strval($municipio).strval($id_disciplina));     
        }

        return $dados_base_grafico_escola_disc;
    }

    /**
     * Método que busca os dados para montar a sessão Temas Munícipio
     */
    private function estatisticaCurricularDisciplina($confPresenca, $municipio, $id_disciplina){
        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_curricular_mun_'.strval($municipio).strval($id_disciplina))) {
            $dados_base_grafico_curricular_disc = Cache::get('compar_curricular_mun_'.strval($municipio).strval($id_disciplina));
        } else {
            $dados_base_grafico_curricular_disc = DB::select('SELECT CONCAT(\'Ano \',ano) as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca AND id_disciplina = :id_disciplina GROUP BY SAME, ano', 
                 ['presenca' => $confPresenca, 'id_municipio' => $municipio, 'id_disciplina' => $id_disciplina]);   
            
            $dados_base_grafico_curricular_disc = $this->getDataSet($dados_base_grafico_curricular_disc, 'compar_curricular_mun_'.strval($municipio).strval($id_disciplina));     
        }

        return $dados_base_grafico_curricular_disc;
    }

    /**
     * Método que busca os dados para montar a sessão Temas Munícipio
     */
    private function estatisticaHabilidadeAnoDisciplina($confPresenca, $municipio, $id_disciplina, $ano){
        //Busca os dados do gráfico de disciplina
        if (Cache::has('compar_hab_ano_mun_'.strval($municipio).strval($id_disciplina).strval($ano))) {
            $dados_base_grafico_hab_ano_disc = Cache::get('compar_hab_ano_mun_'.strval($municipio).strval($id_disciplina).strval($ano));
        } else {
            $dados_base_grafico_hab_ano_disc = DB::select('SELECT sigla_habilidade as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual, nome_habilidade AS nome
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca AND id_disciplina = :id_disciplina AND ano = :ano GROUP BY SAME, sigla_habilidade, nome_habilidade', 
                 ['presenca' => $confPresenca, 'id_municipio' => $municipio, 'id_disciplina' => $id_disciplina, 'ano' => $ano]);   
            
            $dados_base_grafico_hab_ano_disc = $this->getDataSetHabilidade($dados_base_grafico_hab_ano_disc, 'compar_hab_ano_mun_'.strval($municipio).strval($id_disciplina).strval($ano));     
        }

        return $dados_base_grafico_hab_ano_disc;
    }

    /**
     * Método para buscar as turmas do Munícipio utilizando Cache
     */
    private function getTurmasMunicipio($id_municipio){

        if(Cache::has('turmas_comp'.strval($id_municipio))){
            $turmasListadas = Cache::get('turmas_comp'.strval($id_municipio));
        } else {
            $turmasListadas = $this->objTurma->where(['status' => 'Ativo', 'escolas_municipios_id' => $id_municipio])->groupBy('TURMA')->orderBy('TURMA','asc')->get();
            //Adiciona ao Cache
            Cache::put('turmas_comp'.strval($id_municipio), $turmasListadas, now()->addHours($this->horasCache));
        }
        
        return $turmasListadas;
    }

    /**
     * Método que carrega os dados da Cache de Munícipio Disciplina
     */
    public function carregarDisciplinaMunicipio(){

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = $this->getMunicipios();

        //Carrega os dados do Município
        foreach($municipios as $municipio){

            $this->getMunicipioSelecionado($municipio->id);

            $this->estatisticaDisciplinas($this->confPresenca, $municipio->id);

        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Município Disciplina carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio Tema
     */
    public function carregarTemaMunicipio(){

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = $this->getMunicipios();

        //Lista as Disciplinas
        $disciplinas = $this->getDisciplinas();

        foreach($municipios as $municipio){

            $this->getMunicipioSelecionado($municipio->id);

            $turmas = $this->getTurmasMunicipio($municipio);
            $anos = [];
            for ($i = 0; $i < sizeof($turmas); $i++) {
                if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                    $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
                }
            }

            foreach($disciplinas as $disciplina){

                $this->getDisciplinaSelecionada($disciplina->id);

                foreach($anos as $ano){

                    $ano = intval($ano);

                    $this->estatisticaHabilidadeAnoDisciplina($this->confPresenca, $municipio, $disciplina->id, $ano);

                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Município Tema carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio Escola
     */
    public function carregarEscolaMunicipio(){

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = $this->getMunicipios();

        //Carrega os dados do Município
        foreach($municipios as $municipio){

            $this->getMunicipioSelecionado($municipio->id);

            $this->estatisticaEscolas($this->confPresenca, $municipio->id);

        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Município Escola carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio Escola Disciplina
     */
    public function carregarEscolaDisciplinaMunicipio(){

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = $this->getMunicipios();

        //Lista as Disciplinas
        $disciplinas = $this->getDisciplinas();

        //Carrega os dados do Município
        foreach($municipios as $municipio){
            
            $this->getMunicipioSelecionado($municipio->id);

            foreach($disciplinas as $disciplina){

                $this->getDisciplinaSelecionada($disciplina->id);

                $this->estatisticaEscolasDisciplina($this->confPresenca, $municipio->id, $disciplina->id);

            }

        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Município Escola Disciplina carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio Ano Curricular Disciplina
     */
    public function carregarAnoCurricularDisciplinaMunicipio(){

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = $this->getMunicipios();

        //Lista as Disciplinas
        $disciplinas = $this->getDisciplinas();

        //Carrega os dados do Município
        foreach($municipios as $municipio){
            
            $this->getMunicipioSelecionado($municipio->id);

            foreach($disciplinas as $disciplina){

                $this->getDisciplinaSelecionada($disciplina->id);

                $this->estatisticaCurricularDisciplina($this->confPresenca, $municipio->id, $disciplina->id);

            }

        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Município Ano Curricular Disciplina carregada com Sucesso!');
    }

    /**
     * Método que carrega os dados da Cache de Munícipio
     */
    public function carregarHabAnosDisciplinaMunicipio(){

        set_time_limit(0);

        //Lista os Munícipios
        $municipios = $this->getMunicipios();

        //Lista as Disciplinas
        $disciplinas = $this->getDisciplinas();

        foreach($municipios as $municipio){

            $this->getMunicipioSelecionado($municipio->id);

            $turmas = $this->getTurmasMunicipio($municipio);
            $anos = [];
            for ($i = 0; $i < sizeof($turmas); $i++) {
                if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                    $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
                }
            }

            foreach($disciplinas as $disciplina){

                $this->getDisciplinaSelecionada($disciplina->id);

                foreach($anos as $ano){

                    $ano = intval($ano);

                    $this->estatisticaHabilidadeAnoDisciplina($this->confPresenca, $municipio->id, $disciplina->id, $ano);

                }
            }
        }

        return redirect()->route('lista_manutencao')->with('status', 'Cache Comparativo Município Habilidade por Anos carregada com Sucesso!');
    }

}
