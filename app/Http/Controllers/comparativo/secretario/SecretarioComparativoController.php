<?php

namespace App\Http\Controllers\comparativo\secretario;

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

class SecretarioComparativoController extends Controller
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
        $this->backgroundColors = ['rgba(255, 26, 104, 0.2)','rgba(54, 162, 235, 0.2)','rgba(255, 206, 86, 0.2)',
        'rgba(75, 192, 192, 0.2)','rgba(153, 102, 255, 0.2)','rgba(255, 159, 64, 0.2)','rgba(0, 0, 0, 0.2)'];
        $this->borderColors = ['rgba(255, 26, 104, 1)','rgba(54, 162, 235, 1)','rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)','rgba(153, 102, 255, 1)','rgba(255, 159, 64, 1)','rgba(0, 0, 0, 1)'];
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
        if (Cache::has('compar_disciplina_mun_'.strval($municipio).strval($ano_same))) {
            $dados_base_grafico_disciplina = Cache::get('compar_disciplina_mun_'.strval($municipio).strval($ano_same));
        } else {
            $dados_base_grafico_disciplina  = DB::select('SELECT nome_disciplina as item, CONCAT(\'Ano \',SAME) AS label,(SUM(acerto)*100)/(count(id)) AS percentual
                 FROM dado_unificados WHERE id_municipio = :id_municipio AND presenca > :presenca GROUP BY SAME, nome_disciplina', 
                 ['presenca' => $confPresenca, 'id_municipio' => $municipio]);   
            
            $dados_base_grafico_disciplina = $this->getDataSet($dados_base_grafico_disciplina, 'compar_disciplina_mun_'.strval($municipio).strval($ano_same));     
        }

        return $dados_base_grafico_disciplina;
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
                //Monta o Array de Itens por Label
                if(array_key_exists($resultSet[$i]->label,$map_itens_label)){
                    //Pega Itens Existente no Array
                    $item_array = $map_itens_label[$resultSet[$i]->label];
                    //Cria o Novo Item
                    $novo_item_array = array(
                        'x' => $resultSet[$i]->label,
                        $resultSet[$i]->item => $resultSet[$i]->percentual,);
                    //Combina os Itens para criar o Array completo    
                    $map_itens_label[$resultSet[$i]->label] = array_merge($item_array, $novo_item_array);
                } else {
                    //Caso seja o primeiro item do array
                    $map_itens_label[$resultSet[$i]->label] = array(
                        'x' => $resultSet[$i]->label,
                        $resultSet[$i]->item => $resultSet[$i]->percentual,
                    );
                } 
            }

            //Monta o DataSet do Gráfico
            for ($i = 0; $i < sizeof($itens_disc); $i++) {
                $item_data_set = [
                    'label' => $itens_disc[$i],
                    'data' => array_values($map_itens_label),
                    'backgroundColor' => $this->backgroundColors[$i],
                    'borderColor' => $this->borderColors[$i],
                    'borderWidth' => 1,
                    'hoverBorderWidth' => 2,
                    'hoverBorderColor' => 'green',
                    'parsing' => [
                        'yAxisKey' => $itens_disc[$i]
                    ]
                ];   
    
                $dataSet[$i] = $item_data_set;
            }

            $dados[0] = $labels_disc;
            $dados[1] = $dataSet;

            //Adiciona ao Cache
            Cache::forever($cacheName,$dados);
        }

        return $dados;
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

        //Busca as Habilidades pela Disciplina e Munícipio
        $habilidades = $this->getHabilidades($disciplina_selecionada, $municipio);

        $dados_comp_grafico_disciplina=$this->estatisticaDisciplinas($this->confPresenca, $municipio, $ano_same_selecionado);
        $label_disc = $dados_comp_grafico_disciplina[0];
        $dados_disc = $dados_comp_grafico_disciplina[1];

        $turmas = null;
              
        return view('comparativo/secretario/content/secretario', compact(
            'solRegistro','solAltCadastral','solAddTurma','sugestoes','turmas','escolas','municipios','destaques','municipio_selecionado','disciplinas',
            'disciplina_selecionada','escola_selecionada','anos','ano','habilidades','anos_same','ano_same_selecionado','label_disc','dados_disc'));
    }
   
}



