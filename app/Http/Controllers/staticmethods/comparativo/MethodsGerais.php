<?php

namespace App\Http\Controllers\staticmethods\comparativo;

use App\Models\Escola;
use App\Models\Municipio;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Http\Controllers\staticmethods\gerais\MethodsGerais as GeraisMethodsGerais;
use App\Models\DirecaoProfessor;

class MethodsGerais extends Controller
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
     * Método para buscar os munícipios do Comparativo utilizando Cache
     */
    public static function getMunicipiosComparativo()
    {
        //Caso for Administrador ou Pesquisador da Unijuí, busca todos os Munícipios
        if (
            auth()->user()->perfil == 'Administrador'
            || ((GeraisMethodsGerais::getPrevilegio()[0]->funcaos_id == 13 || GeraisMethodsGerais::getPrevilegio()[0]->funcaos_id == 14)
                && GeraisMethodsGerais::getPrevilegio()[0]->municipios_id == 5)
        ) {
            //Se existe Cache, busca o valor dela
            if (Cache::has('total_municipios_comparativo')) {
                $municipiosListados = Cache::get('total_municipios_comparativo');
            } else {
                //Busca todos os Múnicipios Ativos, agrupando por Nome
                $municipiosListados = Municipio::where(['status' => 'Ativo'])->groupBy('nome')->get();

                //Adiciona ao Cache continuamente
                Cache::forever('total_municipios_comparativo', $municipiosListados);
            }
        } else {
            //Se existe Cache, busca o valor dela
            if (Cache::has('mun_list_comparativo' . strval(auth()->user()->id))) {
                $municipiosListados = Cache::get('mun_list_comparativo' . strval(auth()->user()->id));
            } else {
                //Busca os munícipios de acordo com os previlégios dos Usuários
                $municipiosListados = Municipio::where(['id' => GeraisMethodsGerais::getPrevilegio()[0]->municipios_id])->get();

                //Adiciona ao Cache utilizando a constante de Horas da Cache
                Cache::put(
                    'mun_list_comparativo' . strval(auth()->user()->id),
                    $municipiosListados,
                    now()->addHours(config('constants.options.horas_cache'))
                );
            }
        }
        return $municipiosListados;
    }

    /**
     * Método que óbtem os dados do Municipio Selecionado utilizando Cache
     */
    public static function getMunicipioSelecionadoComparativo($id){
        if(Cache::has('mun_comp'.strval($id))){
            $municipio_selecionado = Cache::get('mun_comp'.strval($id));
        } else {
            $municipio_selecionado = Municipio::where(['id' => $id])->groupBy('nome')->get();

            //Adiciona ao Cache
            Cache::forever('mun_comp'.strval($id), $municipio_selecionado);    
        }
        
        return $municipio_selecionado;
    }

    /**
     * Método que óbtem os dados da Disciplina Selecionada utilizando Cache
     */
    public static function getEscolaSelecionadaComparativo($id){
        if(Cache::has('esc_comp'.strval($id))){
            $escola_selecionada = Cache::get('esc_comp'.strval($id));
        } else {
            $escola_selecionada = Escola::where(['id' => $id])->groupBy('nome')->get();

            //Adiciona ao Cache
            Cache::forever('esc_comp'.strval($id), $escola_selecionada);    
        }
        
        return $escola_selecionada;
    }

    /**
     * Método que busca os dados de Direcao Professor do Usuário Logado usando Cache
     */
    public static function getDirecaoProfessor(){

        if(Cache::has('direc_comp_profes_'.strval(GeraisMethodsGerais::getPrevilegio()[0]->id))){
            $direcaoProfessor = Cache::get('direc_comp_profes_'.strval(GeraisMethodsGerais::getPrevilegio()[0]->id));
        } else {
            $direcaoProfessor = DirecaoProfessor::where(['id_previlegio' => GeraisMethodsGerais::getPrevilegio()[0]->id])->groupBy('id_escola')->get();
            //Adiciona ao Cache
            Cache::put('direc_comp_profes_'.strval(GeraisMethodsGerais::getPrevilegio()[0]->id),$direcaoProfessor, now()->addHours(config('constants.options.horas_cache')));     
        }

        return $direcaoProfessor;
    }

    /**
     * Método que realiza montagem de DataSet dos Gráficos Comparativo
     */
    public static function getDataSet($resultSet, $cacheName){

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
                    'backgroundColor' => config('constants.options.graficsBackgroundColors')[$contColors],
                    'borderColor' => config('constants.options.graficsBorderColors')[$contColors],
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
     * Método que realiza montagem de DataSet dos Gráficos Comparativo da Sessão de Habilidade
     */
    public static function getDataSetHabilidade($resultSet, $cacheName){

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
                                    'nome_habilidade'.$resultSet[$k]->item => $resultSet[$k]->nome,);
                                //Combina os Itens para criar o Array completo    
                                $map_itens_label[$labels_hab[$j]] = array_merge($item_array, $novo_item_array);
                            } else {
                                //Caso seja o primeiro item do array
                                $map_itens_label[$resultSet[$k]->label] = array(
                                    'x' => $resultSet[$k]->label,
                                    $resultSet[$k]->item => $resultSet[$k]->percentual,
                                    'nome_habilidade'.$resultSet[$k]->item => $resultSet[$k]->nome
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
                                'nome_habilidade'.$itens_hab[$i] => $nome_hab[$i],);
                            //Combina os Itens para criar o Array completo    
                            $map_itens_label[$labels_hab[$j]] = array_merge($item_array, $novo_item_array);
                        } else {
                            //Caso seja o primeiro item do array
                            $map_itens_label[$labels_hab[$j]] = array(
                                'x' => $labels_hab[$j],
                                $itens_hab[$i] => "Ausente",
                                'nome_habilidade'.$itens_hab[$i] => $nome_hab[$i]
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
                    'backgroundColor' => config('constants.options.graficsBackgroundColors')[$contColors],
                    'borderColor' => config('constants.options.graficsBorderColors')[$contColors],
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
}
