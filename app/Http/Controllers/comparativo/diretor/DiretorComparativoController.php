<?php
namespace App\Http\Controllers\comparativo\diretor;
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
use phpDocumentor\Reflection\DocBlock\Tags\Var_;

class DiretorComparativoController extends Controller
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
        $this->objEscola = new Escola();
        $this->objQuestao = new Questao();
        $this->objSugestao = new Sugestao();
        $this->objDestaque = new DestaqueModel();
        $this->objMunicipio = new Municipio();
        $this->objDisciplina = new Disciplina();
        $this->objLegenda = new Legenda();
        $this->objPrevilegio = new Previlegio();
        $this->objDirecaoProfessores = new DirecaoProfessor();
        $this->objHabilidade = new Habilidade();
        $this->objDadoUnificado = new DadoUnificado();
        $this->objCriterioQuestao = new CriterioQuestao();
        $this->confPresenca = 1;
        $this->horasCache = 4;
        $this->previlegio = [];
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
     * M??todo que busca os previl??gios do Usu??rio Logado usando Cache
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
     * M??todo que busca os previl??gios do Usu??rio Logado usando Cache
     */
    private function getDirecaoProfessor(){

        if(Cache::has('direc_comp_profes_'.strval($this->getPrevilegio()[0]->id))){
            $direcaoProfessor = Cache::get('direc_comp_profes_'.strval($this->getPrevilegio()[0]->id));
        } else {
            $direcaoProfessor = $this->objDirecaoProfessores->where(['id_previlegio' => $this->getPrevilegio()[0]->id])->groupBy('id_escola')->get();
            //Adiciona ao Cache
            Cache::put('direc_comp_profes_'.strval($this->getPrevilegio()[0]->id),$direcaoProfessor, now()->addHours($this->horasCache));     
        }

        return $direcaoProfessor;
    }

    /**
     * M??todo para buscar as disciplinas utilizando Cache
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

    private function getEscolasDiretor($id_municipio){
        
         //Administrador lista todas Escolas
         if (auth()->user()->perfil == 'Administrador' || (($this->getPrevilegio()[0]->funcaos_id == 13 || $this->getPrevilegio()[0]->funcaos_id == 14) && $this->getPrevilegio()[0]->municipios_id == 5)) {
            if(Cache::has('esc_comp_dir_total'.strval($id_municipio))){
                $escolas = Cache::get('esc_comp_dir_total'.strval($id_municipio));
            } else {
                $escolas = $this->objEscola->where(['status' => 'Ativo','municipios_id' => $id_municipio])->groupBy('nome')->get();
                //Adiciona Cache
                Cache::forever('esc_comp_dir_total'.strval($id_municipio), $escolas);  
            }
        } else if (isset($this->getPrevilegio()[0]) && $this->getPrevilegio()[0]->funcaos_id == 8) {
            if(Cache::has('escolas_comp'.strval($id_municipio))){
                $escolas = Cache::get('escolas_comp'.strval($id_municipio));
            } else {
                $escolas = $this->objEscola->where(['status' => 'Ativo', 'municipios_id' => $id_municipio])->groupBy('nome')->get();
                //Adiciona ao Cache
                Cache::put('escolas_comp'.strval($id_municipio), $escolas, now()->addHours($this->horasCache));
            }
        } else {
            //Os demais pega apenas a escola para o qual foi designado seus previl??gios
            if(Cache::has('esc_comp_dp'.strval($this->getDirecaoProfessor()[0]->id_previlegio))){
                $escolas = Cache::get('esc_comp_dp'.strval($this->getDirecaoProfessor()[0]->id_escola));
            } else {
                $id_escolas = [];
                for ($i = 0; $i < sizeof($this->getDirecaoProfessor()); $i++) {
                    $id_escolas[$i] = $this->getDirecaoProfessor()[$i]->id_escola;
                }
                $escolas = $this->objEscola->whereIn('id', $id_escolas)->groupBy('nome')->get();

                //Adiciona Cache
                Cache::put('esc_comp_dp'.strval($this->getDirecaoProfessor()[0]->id_previlegio),$escolas, now()->addHours($this->horasCache));
            }
            
        }

        return $escolas;
    }

    /**
     * M??todo para buscar as disciplinas utilizando Cache
     */
    private function getLegendas(){

        $legendasListadas = Cache::remember('legendas', ($this->horasCache*3600), function () {
            return $this->objLegenda->all();
        });

        return $legendasListadas;
    }

    /**
     * M??todo para buscar as disciplinas utilizando Cache
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
     * M??todo para buscar os mun??cipios utilizando Cache
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
     * M??todo para buscar as turmas do Mun??cipio utilizando Cache
     */
    private function getTurmasEscola($id_escola){

        if(Cache::has('turmas_esc_comp'.strval($id_escola))){
            $turmas = Cache::get('turmas_esc_comp'.strval($id_escola));
        } else {
            $turmas = $turmas = $this->objTurma->where(['status' => 'Ativo', 'escolas_id' => $id_escola])->groupBy('TURMA')->orderBy('TURMA','asc')->get();
            //Adiciona ao Cache
            Cache::put('turmas_esc_comp'.strval($id_escola), $turmas, now()->addHours($this->horasCache));
        }
        
        return $turmas;
    }

    /**
     * M??todo que ??btem os dados da Disciplina Selecionada utilizando Cache
     */
    private function getEscolaSelecionada($id){
        if(Cache::has('esc_comp'.strval($id))){
            $escola_selecionada = Cache::get('esc_comp'.strval($id));
        } else {
            $escola_selecionada = $this->objEscola->where(['id' => $id])->groupBy('nome')->get();

            //Adiciona ao Cache
            Cache::forever('esc_comp'.strval($id), $escola_selecionada);    
        }
        
        return $escola_selecionada;
    }

    /**
     * M??todo que ??btem os dados da Disciplina Selecionada utilizando Cache
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
     * M??todo que ??btem os dados do Municipio Selecionado utilizando Cache
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
     * M??todo que lista as habilidades pelo Mun??cipio e Disciplina utilizando Cache
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
     * M??todo que ??btem os dados da Habilidade Selecionada utilizando Cache
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
     * M??todo para buscar os crit??rios utilizando Cache
     */
    private function getCriterios(){

        $criterios = Cache::rememberForever('criterio_total', function () {
            return $this->objCriterioQuestao->all();
        });

        return $criterios;
    }

    /**
     * M??todo que busca os dados para montar a sess??o Disciplinas Escola
     */
    private function estatisticaDisciplinas($confPresenca, $escola){
        //Busca os dados do gr??fico de disciplina
        if (Cache::has('compar_disciplina_esc_'.strval($escola))) {
            $dados_base_grafico_disciplina = Cache::get('compar_disciplina_esc_'.strval($escola));
        } else {
            $dados_base_grafico_disciplina  = DB::select('SELECT nome_disciplina as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_escola = :id_escola AND presenca > :presenca GROUP BY SAME, nome_disciplina', 
                 ['presenca' => $confPresenca, 'id_escola' => $escola]);   
            
            $dados_base_grafico_disciplina = $this->getDataSet($dados_base_grafico_disciplina, 'compar_disciplina_esc_'.strval($escola));     
        }

        return $dados_base_grafico_disciplina;
    }

    /**
     * M??todo que busca os dados para montar a sess??o Temas Escola
     */
    private function estatisticaTemas($confPresenca, $escola, $id_disciplina, $ano){

        $ano = intval($ano);
        
        //Busca os dados do gr??fico de disciplina
        if (Cache::has('compar_tema_esc_'.strval($escola).strval($id_disciplina).strval($ano))) {
            $dados_base_grafico_tema = Cache::get('compar_tema_esc_'.strval($escola).strval($id_disciplina).strval($ano));
        } else {
            $dados_base_grafico_tema = DB::select('SELECT REPLACE(nome_tema,\'.\', \'\') as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_escola = :id_escola AND presenca > :presenca AND id_disciplina = :id_disciplina AND ano = :ano GROUP BY SAME, nome_tema', 
                 ['presenca' => $confPresenca, 'id_escola' => $escola, 'id_disciplina' => $id_disciplina, 'ano' => $ano]);   
            
            $dados_base_grafico_tema = $this->getDataSet($dados_base_grafico_tema, 'compar_tema_esc_'.strval($escola).strval($id_disciplina).strval($ano));     
        }

        return $dados_base_grafico_tema;
    }

    /**
     * M??todo que busca os dados para montar a sess??o Ano Curricular Escola
     */
    private function estatisticaCurricularDisciplina($confPresenca, $escola, $id_disciplina){
        //Busca os dados do gr??fico de disciplina
        if (Cache::has('compar_curricular_esc_'.strval($escola).strval($id_disciplina))) {
            $dados_base_grafico_curricular_disc = Cache::get('compar_curricular_esc_'.strval($escola).strval($id_disciplina));
        } else {
            $dados_base_grafico_curricular_disc = DB::select('SELECT CONCAT(\'Ano \',ano) as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_escola = :id_escola AND presenca > :presenca AND id_disciplina = :id_disciplina GROUP BY SAME, ano', 
                 ['presenca' => $confPresenca, 'id_escola' => $escola, 'id_disciplina' => $id_disciplina]);   
            
            $dados_base_grafico_curricular_disc = $this->getDataSet($dados_base_grafico_curricular_disc, 'compar_curricular_esc_'.strval($escola).strval($id_disciplina));     
        }

        return $dados_base_grafico_curricular_disc;
    }

    /**
     * M??todo que busca os dados para montar a sess??o Turma Escola
     */
    private function estatisticaTurmaDisciplina($confPresenca, $escola, $id_disciplina){
        //Busca os dados do gr??fico de disciplina
        if (Cache::has('compar_turma_esc_'.strval($escola).strval($id_disciplina))) {
            $dados_base_grafico_turma_disc = Cache::get('compar_turma_esc_'.strval($escola).strval($id_disciplina));
        } else {
            $dados_base_grafico_turma_disc = DB::select('SELECT REPLACE(nome_turma,\'\t\',\'\') as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_escola = :id_escola AND presenca > :presenca AND id_disciplina = :id_disciplina GROUP BY SAME, nome_turma', 
                 ['presenca' => $confPresenca, 'id_escola' => $escola, 'id_disciplina' => $id_disciplina]);   
            
            $dados_base_grafico_turma_disc = $this->getDataSet($dados_base_grafico_turma_disc, 'compar_turma_esc_'.strval($escola).strval($id_disciplina));     
        }

        return $dados_base_grafico_turma_disc;
    }

    /**
     * M??todo que busca os dados para montar a sess??o Temas Mun??cipio
     */
    private function estatisticaHabilidadeAnoDisciplina($confPresenca, $escola, $id_disciplina, $ano){

        $ano = intval($ano);

        //Busca os dados do gr??fico de disciplina
        if (Cache::has('compar_hab_ano_esc_'.strval($escola).strval($id_disciplina).strval($ano))) {
            $dados_base_grafico_hab_ano_disc = Cache::get('compar_hab_ano_esc_'.strval($escola).strval($id_disciplina).strval($ano));
        } else {
            $dados_base_grafico_hab_ano_disc = DB::select('SELECT sigla_habilidade as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual, nome_habilidade AS nome
                 FROM dado_unificados WHERE id_escola = :id_escola AND presenca > :presenca AND id_disciplina = :id_disciplina AND ano = :ano GROUP BY SAME, sigla_habilidade, nome_habilidade', 
                 ['presenca' => $confPresenca, 'id_escola' => $escola, 'id_disciplina' => $id_disciplina, 'ano' => $ano]);   
            
            $dados_base_grafico_hab_ano_disc = $this->getDataSetHabilidade($dados_base_grafico_hab_ano_disc, 'compar_hab_ano_esc_'.strval($escola).strval($id_disciplina).strval($ano));     
        }

        return $dados_base_grafico_hab_ano_disc;
    }

    private function getDataSetHabilidade($resultSet, $cacheName){

        $dados = [];

        //Verifica se j?? tem o valor em Cache
        if (Cache::has($cacheName)) {
            $dados = Cache::get($cacheName);
        } else {
            //Inicializa Labels do Gr??fico
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
            
            //Monta o DataSet do Gr??fico
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

        //Verifica se j?? tem o valor em Cache
        if (Cache::has($cacheName)) {
            $dados = Cache::get($cacheName);
        } else {
            //Inicializa Labels do Gr??fico
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
            
            //Monta o DataSet do Gr??fico
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        //Busca os previl??gios do Usu??rio Logado
        $previlegio = $this->getPrevilegio();

        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();
        $ano_same_selecionado = $anos_same[0]->SAME;

        //Listage, de Dire????o Professor utilizando Cache
        $direcaoProfessor = $this->getDirecaoProfessor();

        //----------------------------------------- Escolas -------------------------------------------------------------------
        $escolas = $this->getEscolasDiretor($previlegio[0]->municipios_id);

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        $disciplinas = $this->getDisciplinas();

        //------------------------------------------ Solicita????es -----------------------------------------------------------------
        //Caso seja Gestor busca todas solicita????es em aberto do mun??cipio
        if ($previlegio[0]->funcaos_id == 6) {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
        } else {
            //Nos demais casos busca todas as solicita????es independente do munic??pio (demais valida????es para quem exibir est??o na blade)
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
        }

        //Busca as Legendas
        $legendas = $this->getLegendas();

        //Realiza a busca dos Destaques
        $destaques = $this->objDestaque->orderBy('updated_at', 'desc')->get();

        //------------------------------------------- Munic??pios -----------------------------------------------------------------
        $municipios = $this->getMunicipios();

        //Identifica a escola selecionada
        $escola = $escolas[0]->id;

        //Busca as turmas da escola selecionda
        $turmas = $this->getTurmasEscola($escola);

        //Seta os Anos a serem utilizados no Select
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }

        //Define o ano padr??o do Select
        $ano = substr(trim($turmas[0]->DESCR_TURMA), 0, 2);

        //Busca os dados da Escola Selecionda
        $escola_selecionada = $this->getEscolaSelecionada($escola);

        //Busca os dados do Munic??pio selecionado partindo da escola
        $municipio_selecionado = $this->getMunicipioSelecionado($escola_selecionada[0]->municipios_id);

        //Busca os dados da Disciplina Selecionada
        $disciplina_selecionada = $this->getDisciplinaSelecionada($disciplinas[0]->id);

        //Buscas as Habilidades
        $habilidades = $this->getHabilidadesEscola($disciplina_selecionada[0]->id, $escola_selecionada[0]->id);    

        //Busca os dados da Habilidade Selecionda    
        $habilidade_selecionada = $this->getHabilidadeSelecionada($habilidades[0]->id_habilidade);

        //Busca todos os Crit??rios
        $criterios_questaoAll = $this->getCriterios();

        //Busca dados Sess??o de Disciplinas
        $dados_comp_grafico_disciplina=$this->estatisticaDisciplinas($this->confPresenca, $escola);
        $label_disc = $dados_comp_grafico_disciplina[0];
        $dados_disc = $dados_comp_grafico_disciplina[1];
        $itens_disc = $dados_comp_grafico_disciplina[2];
        $map_itens_disc = $dados_comp_grafico_disciplina[3];

        //Busca dados da Sess??o de Temas
        $dados_comp_grafico_tema=$this->estatisticaTemas($this->confPresenca, $escola, $disciplina_selecionada[0]->id, $ano);
        $label_tema = $dados_comp_grafico_tema[0];
        $dados_tema = $dados_comp_grafico_tema[1];
        $itens_tema = $dados_comp_grafico_tema[2];
        $map_itens_tema = $dados_comp_grafico_tema[3];

        //Busca dados da Sess??o de Ano Curricular Disciplina
        $dados_comp_grafico_curricular_disc=$this->estatisticaCurricularDisciplina($this->confPresenca, $escola, $disciplina_selecionada[0]->id);
        $label_curricular_disc = $dados_comp_grafico_curricular_disc[0];
        $dados_curricular_disc = $dados_comp_grafico_curricular_disc[1];
        $itens_curricular_disc = $dados_comp_grafico_curricular_disc[2];
        $map_itens_curricular_disc = $dados_comp_grafico_curricular_disc[3];

        //Busca dados da Sess??o de Turma Disciplina
        $dados_comp_grafico_turma_disc=$this->estatisticaTurmaDisciplina($this->confPresenca, $escola, $disciplina_selecionada[0]->id);
        $label_turma_disc = $dados_comp_grafico_turma_disc[0];
        $dados_turma_disc = $dados_comp_grafico_turma_disc[1];
        $itens_turma_disc = $dados_comp_grafico_turma_disc[2];
        $map_itens_turma_disc = $dados_comp_grafico_turma_disc[3];

        //Busca dados da Sess??o de Habilidade Ano Disciplina
        $dados_comp_grafico_han_ano_disc=$this->estatisticaHabilidadeAnoDisciplina($this->confPresenca, $escola, $disciplina_selecionada[0]->id, $ano);
        $label_hab_ano_disc = $dados_comp_grafico_han_ano_disc[0];
        $dados_hab_ano_disc = $dados_comp_grafico_han_ano_disc[1];
        $itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[2];
        $map_itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[3];
        $nome_hab = $dados_comp_grafico_han_ano_disc[4];

        $sessao_inicio = "municipio_comparativo";
  
        return view('comparativo/diretor/content/diretor', compact(
            'criterios_questaoAll','solRegistro','solAltCadastral','solAddTurma','turmas','escolas','municipios','destaques','escola_selecionada','sessao_inicio',
            'disciplinas','disciplina_selecionada','municipio_selecionado','legendas','anos','ano','habilidades','habilidade_selecionada','anos_same',
            'ano_same_selecionado','label_disc','dados_disc','label_tema','dados_tema','label_curricular_disc','dados_curricular_disc','label_turma_disc','dados_turma_disc',
            'itens_disc','map_itens_disc','itens_tema','map_itens_tema','itens_curricular_disc','map_itens_curricular_disc','itens_turma_disc','map_itens_turma_disc',
            'label_hab_ano_disc','dados_hab_ano_disc','itens_hab_ano_disc','map_itens_hab_ano_disc','nome_hab'    
        ));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirEscolaComparativo($id, $id_municipio, $id_disciplina, $sessao)
    {
        //Busca os previl??gios do Usu??rio Logado
        $previlegio = $this->getPrevilegio();

        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();
        $ano_same_selecionado = $anos_same[0]->SAME;

        //Listage, de Dire????o Professor utilizando Cache
        $direcaoProfessor = $this->getDirecaoProfessor();

        //------------------------------------------- Munic??pios -----------------------------------------------------------------
        $municipios = $this->getMunicipios();

        //----------------------------------------- Escolas -------------------------------------------------------------------
        $escolas = $this->getEscolasDiretor($id_municipio);
        if(!isset($escolas) || sizeof($escolas) == 0){
            $escolas = $this->getEscolasDiretor($municipios[0]->id);
        }

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        $disciplinas = $this->getDisciplinas();

        //------------------------------------------ Solicita????es -----------------------------------------------------------------
        //Caso seja Gestor busca todas solicita????es em aberto do mun??cipio
        if ($previlegio[0]->funcaos_id == 6) {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
        } else {
            //Nos demais casos busca todas as solicita????es independente do munic??pio (demais valida????es para quem exibir est??o na blade)
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
        }
        //----------------------------------------------------------------------------------------------------------------------

        //Busca as Legendas
        $legendas = $this->getLegendas();

        //Realiza a busca dos Destaques
        $destaques = $this->objDestaque->orderBy('updated_at', 'desc')->get();

        //Identifica a escola selecionada
        $escola = $escolas[0]->id;
        for ($i = 0; $i < sizeof($escolas); $i++) {
            if($escolas[$i]->id == $id){
                $escola = $id;    
            }    
        }

        //Busca as turmas da escola selecionda
        $turmas = $this->getTurmasEscola($escola);

        //Seta os Anos a serem utilizados no Select
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }
        //Define o ano padr??o do Select
        $ano = substr(trim($turmas[0]->DESCR_TURMA), 0, 2);

        //Busca os dados da Escola Selecionda
        $escola_selecionada = $this->getEscolaSelecionada($escola);

        //Busca os dados do Munic??pio selecionado partindo da escola
        $municipio_selecionado = $this->getMunicipioSelecionado($escola_selecionada[0]->municipios_id);

        //Busca os dados da Disciplina Selecionada
        $disciplina_selecionada = $this->getDisciplinaSelecionada($id_disciplina);

        //Buscas as Habilidades
        $habilidades = $this->getHabilidadesEscola($disciplina_selecionada[0]->id, $escola_selecionada[0]->id);    

        //Busca os dados da Habilidade Selecionda    
        $habilidade_selecionada = $this->getHabilidadeSelecionada($habilidades[0]->id_habilidade);  
        
        //Busca dados Sess??o de Disciplinas
        $dados_comp_grafico_disciplina=$this->estatisticaDisciplinas($this->confPresenca, $escola);
        $label_disc = $dados_comp_grafico_disciplina[0];
        $dados_disc = $dados_comp_grafico_disciplina[1];
        $itens_disc = $dados_comp_grafico_disciplina[2];
        $map_itens_disc = $dados_comp_grafico_disciplina[3];

        //Busca dados da Sess??o de Temas
        $dados_comp_grafico_tema=$this->estatisticaTemas($this->confPresenca, $escola, $disciplina_selecionada[0]->id, $ano);
        $label_tema = $dados_comp_grafico_tema[0];
        $dados_tema = $dados_comp_grafico_tema[1];
        $itens_tema = $dados_comp_grafico_tema[2];
        $map_itens_tema = $dados_comp_grafico_tema[3];

        //Busca dados da Sess??o de Ano Curricular Disciplina
        $dados_comp_grafico_curricular_disc=$this->estatisticaCurricularDisciplina($this->confPresenca, $escola, $disciplina_selecionada[0]->id);
        $label_curricular_disc = $dados_comp_grafico_curricular_disc[0];
        $dados_curricular_disc = $dados_comp_grafico_curricular_disc[1];
        $itens_curricular_disc = $dados_comp_grafico_curricular_disc[2];
        $map_itens_curricular_disc = $dados_comp_grafico_curricular_disc[3];

        //Busca dados da Sess??o de Turma Disciplina
        $dados_comp_grafico_turma_disc=$this->estatisticaTurmaDisciplina($this->confPresenca, $escola, $disciplina_selecionada[0]->id);
        $label_turma_disc = $dados_comp_grafico_turma_disc[0];
        $dados_turma_disc = $dados_comp_grafico_turma_disc[1];
        $itens_turma_disc = $dados_comp_grafico_turma_disc[2];
        $map_itens_turma_disc = $dados_comp_grafico_turma_disc[3];

        //Busca dados da Sess??o de Habilidade Ano Disciplina
        $dados_comp_grafico_han_ano_disc=$this->estatisticaHabilidadeAnoDisciplina($this->confPresenca, $escola, $disciplina_selecionada[0]->id, $ano);
        $label_hab_ano_disc = $dados_comp_grafico_han_ano_disc[0];
        $dados_hab_ano_disc = $dados_comp_grafico_han_ano_disc[1];
        $itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[2];
        $map_itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[3];
        $nome_hab = $dados_comp_grafico_han_ano_disc[4];

        //Busca todos os Crit??rios
        $criterios_questaoAll = $this->getCriterios();

        $sessao_inicio = "";
        $sessao_inicio = $sessao;

        return view('comparativo/diretor/content/diretor', compact(
            'criterios_questaoAll','solRegistro','solAltCadastral','solAddTurma','turmas','escolas','municipios','destaques','escola_selecionada','disciplinas','sessao_inicio',
            'disciplina_selecionada','municipio_selecionado','legendas','anos','ano','habilidades','habilidade_selecionada','anos_same','ano_same_selecionado','label_disc',
            'dados_disc','label_tema','dados_tema','label_curricular_disc','dados_curricular_disc','label_turma_disc','dados_turma_disc','itens_disc','map_itens_disc',
            'itens_tema','map_itens_tema','itens_curricular_disc','map_itens_curricular_disc','itens_turma_disc','map_itens_turma_disc','label_hab_ano_disc','dados_hab_ano_disc',
            'itens_hab_ano_disc','map_itens_hab_ano_disc','nome_hab'
        ));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirEscolaComparativoAno($id, $id_municipio, $id_disciplina, $ano, $sessao)
    {
        //Busca os previl??gios do Usu??rio Logado
        $previlegio = $this->getPrevilegio();

        //Listagem de Anos do SAME
        $anos_same = $this->getAnosSAME();
        $ano_same_selecionado = $anos_same[0]->SAME;

        //Listage, de Dire????o Professor utilizando Cache
        $direcaoProfessor = $this->getDirecaoProfessor();

        //------------------------------------------- Munic??pios -----------------------------------------------------------------
        $municipios = $this->getMunicipios();

        //----------------------------------------- Escolas -------------------------------------------------------------------
        $escolas = $this->getEscolasDiretor($id_municipio);
        if(!isset($escolas) || sizeof($escolas) == 0){
            $escolas = $this->getEscolasDiretor($municipios[0]->id);
        }

        //----------------------------------------- Disciplinas ----------------------------------------------------------------
        $disciplinas = $this->getDisciplinas();

        //------------------------------------------ Solicita????es -----------------------------------------------------------------
        //Caso seja Gestor busca todas solicita????es em aberto do mun??cipio
        if ($previlegio[0]->funcaos_id == 6) {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
        } else {
            //Nos demais casos busca todas as solicita????es independente do munic??pio (demais valida????es para quem exibir est??o na blade)
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
        }
        //----------------------------------------------------------------------------------------------------------------------

        //Busca as Legendas
        $legendas = $this->getLegendas();

        //Realiza a busca dos Destaques
        $destaques = $this->objDestaque->orderBy('updated_at', 'desc')->get();

        //Identifica a escola selecionada
        $escola = $escolas[0]->id;
        for ($i = 0; $i < sizeof($escolas); $i++) {
            if($escolas[$i]->id == $id){
                $escola = $id;    
            }    
        }

        //Busca as turmas da escola selecionda
        $turmas = $this->getTurmasEscola($escola);

        //Seta os Anos a serem utilizados no Select
        $anos = [];
        for ($i = 0; $i < sizeof($turmas); $i++) {
            if (!in_array(substr(trim($turmas[$i]->DESCR_TURMA), 0, 2), $anos)) {
                $anos[$i] = substr(trim($turmas[$i]->DESCR_TURMA), 0, 2);
            }
        }
        //Define o ano padr??o do Select
        $ano = $ano;

        //Busca os dados da Escola Selecionda
        $escola_selecionada = $this->getEscolaSelecionada($escola);

        //Busca os dados do Munic??pio selecionado partindo da escola
        $municipio_selecionado = $this->getMunicipioSelecionado($escola_selecionada[0]->municipios_id);

        //Busca os dados da Disciplina Selecionada
        $disciplina_selecionada = $this->getDisciplinaSelecionada($id_disciplina);

        //Buscas as Habilidades
        $habilidades = $this->getHabilidadesEscola($disciplina_selecionada[0]->id, $escola_selecionada[0]->id);    

        //Busca os dados da Habilidade Selecionda    
        $habilidade_selecionada = $this->getHabilidadeSelecionada($habilidades[0]->id_habilidade);  
        
        //Busca dados Sess??o de Disciplinas
        $dados_comp_grafico_disciplina=$this->estatisticaDisciplinas($this->confPresenca, $escola);
        $label_disc = $dados_comp_grafico_disciplina[0];
        $dados_disc = $dados_comp_grafico_disciplina[1];
        $itens_disc = $dados_comp_grafico_disciplina[2];
        $map_itens_disc = $dados_comp_grafico_disciplina[3];

        //Busca dados da Sess??o de Temas
        $dados_comp_grafico_tema=$this->estatisticaTemas($this->confPresenca, $escola, $disciplina_selecionada[0]->id, $ano);
        $label_tema = $dados_comp_grafico_tema[0];
        $dados_tema = $dados_comp_grafico_tema[1];
        $itens_tema = $dados_comp_grafico_tema[2];
        $map_itens_tema = $dados_comp_grafico_tema[3];

        //Busca dados da Sess??o de Ano Curricular Disciplina
        $dados_comp_grafico_curricular_disc=$this->estatisticaCurricularDisciplina($this->confPresenca, $escola, $disciplina_selecionada[0]->id);
        $label_curricular_disc = $dados_comp_grafico_curricular_disc[0];
        $dados_curricular_disc = $dados_comp_grafico_curricular_disc[1];
        $itens_curricular_disc = $dados_comp_grafico_curricular_disc[2];
        $map_itens_curricular_disc = $dados_comp_grafico_curricular_disc[3];

        //Busca dados da Sess??o de Turma Disciplina
        $dados_comp_grafico_turma_disc=$this->estatisticaTurmaDisciplina($this->confPresenca, $escola, $disciplina_selecionada[0]->id);
        $label_turma_disc = $dados_comp_grafico_turma_disc[0];
        $dados_turma_disc = $dados_comp_grafico_turma_disc[1];
        $itens_turma_disc = $dados_comp_grafico_turma_disc[2];
        $map_itens_turma_disc = $dados_comp_grafico_turma_disc[3];

        //Busca dados da Sess??o de Habilidade Ano Disciplina
        $dados_comp_grafico_han_ano_disc=$this->estatisticaHabilidadeAnoDisciplina($this->confPresenca, $escola, $disciplina_selecionada[0]->id, $ano);
        $label_hab_ano_disc = $dados_comp_grafico_han_ano_disc[0];
        $dados_hab_ano_disc = $dados_comp_grafico_han_ano_disc[1];
        $itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[2];
        $map_itens_hab_ano_disc = $dados_comp_grafico_han_ano_disc[3];
        $nome_hab = $dados_comp_grafico_han_ano_disc[4];

        //Busca todos os Crit??rios
        $criterios_questaoAll = $this->getCriterios();

        $sessao_inicio = "";
        $sessao_inicio = $sessao;

        return view('comparativo/diretor/content/diretor', compact(
            'criterios_questaoAll','solRegistro','solAltCadastral','solAddTurma','turmas','escolas','municipios','destaques','escola_selecionada','disciplinas','sessao_inicio',
            'disciplina_selecionada','municipio_selecionado','legendas','anos','ano','habilidades','habilidade_selecionada','anos_same','ano_same_selecionado','label_disc',
            'dados_disc','label_tema','dados_tema','label_curricular_disc','dados_curricular_disc','label_turma_disc','dados_turma_disc','itens_disc','map_itens_disc',
            'itens_tema','map_itens_tema','itens_curricular_disc','map_itens_curricular_disc','itens_turma_disc','map_itens_turma_disc','label_hab_ano_disc','dados_hab_ano_disc',
            'itens_hab_ano_disc','map_itens_hab_ano_disc','nome_hab'
        ));
    }
}
