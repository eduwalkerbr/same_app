<?php

namespace App\Http\Controllers\staticmethods\gerais;

use App\Models\CriterioQuestao;
use App\Models\DadoUnificado;
use App\Models\Disciplina;
use App\Models\Escola;
use App\Models\Legenda;
use App\Models\Municipio;
use App\Models\Previlegio;
use App\Models\Questao;
use App\Models\Turma;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use App\Models\DestaqueModel;
use App\Models\Solicitacao;
use App\Models\Sugestao;

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
     * Método que busca os previlégios do Usuário Logado usando Cache
     */
    public static function getPrevilegio(){
        //Caso houver previlégio em Cache busca da mesma
        if(Cache::has('previlegio_usuario'.strval(auth()->user()->id))){

            $previlegio = Cache::get('previlegio_usuario'.strval(auth()->user()->id));
        } else {
            //Caso não existir busca previlégio pelo id do usuário
            $previlegio = Previlegio::where(['users_id' => auth()->user()->id])->get();
            
            //Adiciona ao Cache utilizando a constante de Horas da Cache
            
            Cache::put('previlegio_usuario'.strval(auth()->user()->id),$previlegio, 
            now()->addHours(config('constants.options.horas_cache')));    
        }

        return $previlegio;
    }

    /**
     * Método para buscar os Anos SAME utilizando Cache
     */
    public static function getAnosSAME(){
        //Se o Usuário for Administrador, ou Pesquisadores da Unijuí
        if (auth()->user()->perfil == 'Administrador' 
            || ((MethodsGerais::getPrevilegio()[0]->funcaos_id == 13 || MethodsGerais::getPrevilegio()[0]->funcaos_id == 14) 
            && MethodsGerais::getPrevilegio()[0]->municipios_id == 5)) {
            //Se existe Cache, busca o valor dela
            if (Cache::has('anos_same')) {
                $anos_same = Cache::get('anos_same');
            } else {
                //Busca todos os Anos disponíveis dos dados Unificados
                $anos_same = DB::select('SELECT SAME FROM dado_unificados GROUP BY SAME ORDER BY SAME ASC');  
                    
                //Adiciona ao Cache  
                Cache::forever('anos_same', $anos_same);  
            }        
            //Caso função seja Secretaria
        } else if(MethodsGerais::getPrevilegio()[0]->funcaos_id == 8) {
            //Se existe Cache, busca o valor dela
            if (Cache::has('anos_same_'.strval(auth()->user()->id))) {
                $anos_same = Cache::get('anos_same'.strval(auth()->user()->id));
            } else {
                //Caso contrário busca o previlégio pelo identificador do Usuário
                $anos_same = DB::select('SELECT pv.SAME FROM previlegios pv 
                             WHERE pv.users_id = :id_usuario GROUP BY pv.SAME ORDER BY pv.SAME ASC',['id_usuario' => auth()->user()->id]);
                
                //Adiciona ao Cache utilizando a constante de Horas da Cache
                Cache::put('anos_same'.strval(auth()->user()->id),$anos_same, 
                now()->addHours(config('constants.options.horas_cache')));     
            }
        } else {
            //Se existe Cache, busca o valor dela
            if (Cache::has('anos_same_'.strval(auth()->user()->id))) {
                $anos_same = Cache::get('anos_same'.strval(auth()->user()->id));
            } else {
                //Busca os Anos Same da tabela direção professor
                $anos_same = DB::select('SELECT dp.SAME FROM direcao_professors dp INNER JOIN previlegios pr ON pr.id = dp.id_previlegio 
                                 WHERE pr.users_id = :id_usuario GROUP BY dp.SAME ORDER BY dp.SAME ASC',['id_usuario' => auth()->user()->id]);
                
                //Adiciona ao Cache utilizando a constante de Horas da Cache
                Cache::put('anos_same'.strval(auth()->user()->id),$anos_same, 
                now()->addHours(config('constants.options.horas_cache')));     
            }
        }

        return $anos_same;
    }

    /**
     * Método para buscar os munícipios do Comparativo utilizando Cache
     */
    public static function getMunicipiosComparativo(){
        //Caso for Administrador ou Pesquisador da Unijuí, busca todos os Munícipios
        if (auth()->user()->perfil == 'Administrador' 
            || ((MethodsGerais::getPrevilegio()[0]->funcaos_id == 13 || MethodsGerais::getPrevilegio()[0]->funcaos_id == 14) 
            && MethodsGerais::getPrevilegio()[0]->municipios_id == 5)) {
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
            if (Cache::has('mun_list_comparativo'.strval(auth()->user()->id))) {
                $municipiosListados = Cache::get('mun_list_comparativo'.strval(auth()->user()->id));
            } else {
                //Busca os munícipios de acordo com os previlégios dos Usuários
                $municipiosListados = Municipio::where(['id' => MethodsGerais::getPrevilegio()[0]->municipios_id])->get();
                
                //Adiciona ao Cache utilizando a constante de Horas da Cache
                Cache::put('mun_list_comparativo'.strval(auth()->user()->id),$municipiosListados, 
                now()->addHours(config('constants.options.horas_cache')));     
            }
        }
        return $municipiosListados;

    }

    /**
     * Método para buscar os munícipios utilizando Cache
     */
    public static function getMunicipios($ano_same){
        //Caso for Administrador ou Pesquisador da Unijuí, busca todos os Munícipios
        if (auth()->user()->perfil == 'Administrador' 
            || ((MethodsGerais::getPrevilegio()[0]->funcaos_id == 13 || MethodsGerais::getPrevilegio()[0]->funcaos_id == 14) 
            && MethodsGerais::getPrevilegio()[0]->municipios_id == 5)) {
                
                //Se existe Cache, busca o valor dela
                if (Cache::has('total_municipios'.strval($ano_same))) {
                    $municipiosListados = Cache::get('total_municipios'.strval($ano_same));
                } else {
                    //Busca os Munícipios Ativos pelo Ano SAME
                    $municipiosListados = Municipio::where(['status' => 'Ativo','SAME' => $ano_same])->get(); 
                    
                    //Adiciona ao Cache continuamente
                    Cache::forever('total_municipios'.strval($ano_same), $municipiosListados);      
                }
        } else {
            //Se existe Cache, busca o valor dela
            if (Cache::has('mun_list_'.strval(auth()->user()->id))) {
                $municipiosListados = Cache::get('mun_list_'.strval(auth()->user()->id));
            } else {
                //Busca os Munícipios pelo previlégio e Ano SAME
                $municipiosListados = Municipio::where(['id' => MethodsGerais::getPrevilegio()[0]->municipios_id, 'SAME' => $ano_same])->get();
                
                //Adiciona ao Cache utilizando a constante de Horas da Cache
                Cache::put('mun_list_'.strval(auth()->user()->id),$municipiosListados, 
                now()->addHours(config('constants.options.horas_cache')));     
            }
        }
        return $municipiosListados;

    }

    /**
     * Método que óbtem os dados do Municipio Selecionado utilizando Cache
     */
    public static function getMunicipioSelecionado($id, $ano_same){
        //Se existe Cache, busca o valor dela
        if(Cache::has('mun_'.strval($id).strval($ano_same))){
            $municipio_selecionado = Cache::get('mun_'.strval($id).strval($ano_same));
        } else {
            //Busca os dados do Munícipio pelo identificador e Ano SAME
            $municipio_selecionado = Municipio::where(['id' => $id])->where(['SAME' => $ano_same])->get();

            //Adiciona ao Cache continuamente
            Cache::forever('mun_'.strval($id).strval($ano_same), $municipio_selecionado);    
        }
        
        return $municipio_selecionado;
    }

    /**
     * Método para buscar as disciplinas utilizando Cache
     */
    public static function getDisciplinas(){
        //Caso seja Pesquisador Matemática
        if (MethodsGerais::getPrevilegio()[0]->funcaos_id == 13) {
            //Busca os dados em Cache, e caso não haja busca do BD e seta em Cache
            $disciplinasListadas = Cache::remember('disc_prev_'.strval(MethodsGerais::getPrevilegio()[0]->funcaos_id), 
            (config('constants.options.options.horas_cache')*3600), function () {
                return Disciplina::where(['id' => 1])->get();
            });
        //Caso seja Pesquisador Português    
        } else if (MethodsGerais::getPrevilegio()[0]->funcaos_id == 14) {
            //Busca os dados em Cache, e caso não haja busca do BD e seta em Cache
            $disciplinasListadas = Cache::remember('disc_prev_'.strval(MethodsGerais::getPrevilegio()[0]->funcaos_id), 
            (config('constants.options.horas_cache')*3600), function () {
                return Disciplina::where(['id' => 2])->get();
            });
        } else {
            //Nos demais Perfis, lista todas as disciplinas, utilizando Cache continuamente
            $disciplinasListadas = Cache::rememberForever('total_disciplinas', function () {
                return Disciplina::all();
            });
        }

        return $disciplinasListadas;

    }

    /**
     * Método que óbtem os dados da Disciplina Selecionada utilizando Cache
     */
    public static function getDisciplinaSelecionada($id){
        //Se houver dados em Cache busca da mesma
        if(Cache::has('disc_'.strval($id))){
            $disciplina_selecionada = Cache::get('disc_'.strval($id));
        } else {
            //Busca os dados da Disciplina do BD
            $disciplina_selecionada = Disciplina::where(['id' => $id])->get();

            //Adiciona ao Cache continuamente
            Cache::forever('disc_'.strval($id), $disciplina_selecionada);    
        }
        
        return $disciplina_selecionada;
    }

    /**
     * Método para buscar as disciplinas utilizando Cache
     */
    public static function getLegendas(){
        //Busca em Cache as legendas em geral, e caso não exista, busca do BD e adiciona a Cache pelo número de horas da constante
        $legendasListadas = Cache::remember('legendas', (config('constants.options.horas_cache')*3600), function () {
            return Legenda::all();
        });

        return $legendasListadas;
    }

    /**
     * Método que lista os Critérios das Questões utilizando Cache
     */
    public static function getCriteriosQuestao($ano, $disciplina_selecionada){
        $ano = intval($ano);
        //Critérios das Disciplinas de Português utilizam critérios diferentes por ano
        if ($disciplina_selecionada == 2) {
            //Busca em Cache pelo Ano informado
            if(Cache::has('criterio_ano'.strval($ano))){
                $criterios_questao = Cache::get('criterio_ano'.strval($ano));
            } else {
                //Busca do BD, pelo Ano informado
                $criterios_questao = CriterioQuestao::where(['ano' => $ano])->get();
                //Adiciona em Cache utilizando a constante de Horas Cache
                Cache::put('criterio_ano'.strval($ano),$criterios_questao, 
                now()->addHours(config('constants.options.horas_cache')));
            }  
        } else {
           //Nos demais não existe esse filtro adicional, buscando todos os critérios cadastrados
            if(Cache::has('criterio_total')){
                $criterios_questao = Cache::get('criterio_total');  
            } else {
                //Busca todos os critérios do BD
                $criterios_questao = CriterioQuestao::all();

                //Adiciona em Cache continuamente
                Cache::forever('criterio_total', $criterios_questao);  
            }
           
       }

        return $criterios_questao;
    }

    /**
     * Método que óbtem os dados da Habilidade Selecionada utilizando Cache
     */
    public static function getHabilidadeSelecionada($id){
        //Busca em Cache caso existe pelo identificador da Habilidade
        if(Cache::has('habilidade_'.strval($id))){
            $habilidade_selecionada = Cache::get('habilidade_'.strval($id));
        } else {
            //Busca do BD pelo identificador da habilidade
            $habilidade_selecionada = DadoUnificado::select('id_habilidade', 'nome_habilidade', 'sigla_habilidade')
                ->where(['id_habilidade' => $id])
                ->groupBy('id_habilidade', 'nome_habilidade', 'sigla_habilidade')->get();

            //Adiciona ao Cache continuamente
            Cache::forever('habilidade_'.strval($id), $habilidade_selecionada);    
        }
        
        return $habilidade_selecionada;
    }

    /**
     * Método que óbtem os dados da Disciplina Selecionada utilizando Cache
     */
    public static function getEscolaSelecionada($id, $ano_same){
        //Busca em Cache pelo identificador e Ano SAME
        if(Cache::has('esc_'.strval($id).strval($ano_same))){
            $escola_selecionada = Cache::get('esc_'.strval($id).strval($ano_same));
        } else {
            //Busca do BD pelo identificador e Ano SAME informado
            $escola_selecionada =Escola::where(['id' => $id])->where(['SAME' => $ano_same])->get();

            //Adiciona ao Cache continuamente
            Cache::forever('esc_'.strval($id).strval($ano_same), $escola_selecionada);    
        }
        
        return $escola_selecionada;
    }

       /**
     * Método para buscar as disciplinas utilizando Cache
     */
    public static function getQuestoes(){
        //Busca todas as questões da Cache caso existir, e caso não exista busca do BD e adiciona a Cache continuamente
        $questoesListadas = Cache::rememberForever('questoes_all', function () {
            return Questao::all();
        });

        return $questoesListadas;
    }

        /**
     * Método que óbtem os dados da Disciplina Selecionada utilizando Cache
     */
    public static function getTurmaSelecionada($id, $ano_same){
        //Busca da Cache pelo identificador e Ano SAME
        if(Cache::has('turma_sel_'.strval($id).strval($ano_same))){
            $turma_selecionada = Cache::get('turma_sel_'.strval($id).strval($ano_same));
        } else {
            //Busca do BD pelo identificador e Ano SAME
            $turma_selecionada = Turma::where(['id' => $id])->where(['SAME' => $ano_same])->get();

            //Adiciona ao Cache continuamente
            Cache::forever('turma_sel_'.strval($id).strval($ano_same), $turma_selecionada);    
        }
        
        return $turma_selecionada;
    }

    /**
     * Método para buscar os critérios utilizando Cache
     */
    public static function getCriterios(){
        //Busca os critérios em geral da Cache, e caso não exista busca no BD e adiciona a Cache continuamente
        $criterios = Cache::rememberForever('criterio_total', function () {
            return CriterioQuestao::all();
        });

        return $criterios;
    }

    /**
     * Método que lista as sugestões pela data de criação, da mais recente para a mais antiga
     */
    public static function getSugestoes(){

        return Sugestao::where('mensagem','<>','')->orderBy('updated_at', 'desc')->paginate(2);
    }

    /**
     * Método que busca as Solicitações de Registro de Acordo com o Perfil
     */
    public static function getSolicitacaoRegistro(){

        //Caso seja Gestor, busca as Solicitações do seu Munícipio
        if(MethodsGerais::getPrevilegio()[0]->funcaos_id == 6){
            return Solicitacao::where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => MethodsGerais::getPrevilegio()[0]->municipios_id])->get();
        } else {
            //Caso contrário busca todas as Solicitações
            return Solicitacao::where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
        }

    }

    /**
     * Método que busca as Solicitações de Registro de Acordo com o Perfil
     */
    public static function getSolicitacaoAltCadastral(){

        //Caso seja Gestor, busca as Solicitações do seu Munícipio
        if(MethodsGerais::getPrevilegio()[0]->funcaos_id == 6){
            return Solicitacao::where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => MethodsGerais::getPrevilegio()[0]->municipios_id])->get();
        } else {
            //Caso contrário busca todas as Solicitações
            return Solicitacao::where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
        }

    }

    /**
     * Método que busca as Solicitações de Registro de Acordo com o Perfil
     */
    public static function getSolicitacaoTurma(){

        //Caso seja Gestor, busca as Solicitações do seu Munícipio
        if(MethodsGerais::getPrevilegio()[0]->funcaos_id == 6){
            return Solicitacao::where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => MethodsGerais::getPrevilegio()[0]->municipios_id])->get();
        } else {
            //Caso contrário busca todas as Solicitações
            return Solicitacao::where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
        }

    }

    /**
     * Método que lista as sugestões pela data de criação, da mais recente para a mais antiga
     */
    public static function getDestaques(){

        return DestaqueModel::where('titulo','<>','')->orderBy('updated_at', 'desc')->get();
    }

    /**
     * Método que recebe dados de habilidade questão, e atualiza as informações de nome e observação de critério
     */
    public static function getHabilidadesCriterios($dados_base_habilidade_questao){
        
        // Nova definição das Habilidades com os Critérios
        for ($j = 0; $j < sizeof($dados_base_habilidade_questao); $j++) {
            //Caso tipo de Questão seja diferente de Objetiva
            if ($dados_base_habilidade_questao[$j]->tipo_questao != 'Objetivas'){

                //Busca da Cache os Critérios Gerais de Questões
                $criterios_questaoAll = MethodsGerais::getCriterios();

                //Baseado nos dados informados e critários buscados, atualiza Nome e Observação por critério
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

}



