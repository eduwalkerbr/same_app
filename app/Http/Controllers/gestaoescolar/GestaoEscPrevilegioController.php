<?php

namespace App\Http\Controllers\gestaoescolar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\PrevilegioRequest;
use App\Models\Funcao;
use App\Models\Municipio;
use App\Models\Previlegio;
use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Models\AnoSame;

class GestaoEscPrevilegioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objUser = new User();
        $this->objMunicipio = new Municipio();
        $this->objFuncao = new Funcao();
        $this->objPrevilegio = new Previlegio();
        $this->objSolicitacao = new Solicitacao();
        $this->objAnoSame = new AnoSame();
    }

    public function index()
    {
    }

    /**
     * Show the application dashboard.
     * Método que realiza a listagem dos previlégios ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de previlégios
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function filtrar(Request $request)
    {
        $previlegio = $this->objPrevilegio->where(['users_id' => auth()->user()->id])->get();
        if (auth()->user()->perfil == 'Administrador') {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
            //Caso seja gestor, tem acesso a todas as solicitações do município que esta vinculado
        } else if (isset($previlegio[0]->funcaos_id) && $previlegio[0]->funcaos_id == 6) {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
        }

        $query = Previlegio::query();

        //Limpa os Filtros de Consulta
        Cache::forget('Filtros_Consulta_Previlegio_'.strval(auth()->user()->id));

        //Adiciona os Filtros em Cache
        Cache::put('Filtros_Consulta_Previlegio_'.strval(auth()->user()->id), $request->only('users_id','municipios_id','funcaos_id','SAME'), now()->addMinutes(5));
        
        //Óbtem os parâmetros de Filtro da Cache
        $parametros = Cache::get('Filtros_Consulta_Previlegio_'.strval(auth()->user()->id));

        //$parametros = $request->only('users_id','municipios_id','funcaos_id');
        foreach($parametros as $nome => $valor){
            if($valor){
                $query->where('previlegios.'.$nome,$valor);
            }
        }

        $previlegios = $query->orderBy('updated_at', 'desc')->paginate(7);
        $usuarios = $this->objUser->all();
        $funcaos = $this->objFuncao->all();
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();
        $municipios = $this->objMunicipio->where(['status' => 'Ativo'])->where(['id' => $previlegio[0]->municipios_id])->get();
        return view('gestaoescolar/previlegio/gest_list_previlegio', compact('previlegios','usuarios','funcaos','municipios','solRegistro','solAltCadastral','solAddTurma','anossame'));  

    }

    /**
     * Show the application dashboard.
     * Método que realiza a listagem dos previlégios ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de previlégios
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $previlegio = $this->objPrevilegio->where(['users_id' => auth()->user()->id])->get();
        if (auth()->user()->perfil == 'Administrador') {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
            //Caso seja gestor, tem acesso a todas as solicitações do município que esta vinculado
        } else if (isset($previlegio[0]->funcaos_id) && $previlegio[0]->funcaos_id == 6) {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
        }

        if(Cache::has('Filtros_Consulta_Previlegio_'.strval(auth()->user()->id))){
            $query = Previlegio::query();
            $parametros = Cache::get('Filtros_Consulta_Previlegio_'.strval(auth()->user()->id));
            foreach($parametros as $nome => $valor){
                if($valor){
                    $query->where('previlegios.'.$nome,$valor);
                }
            }
            Cache::put('Filtros_Consulta_Previlegio_'.strval(auth()->user()->id), $parametros, now()->addMinutes(5));
            $previlegios = $query->orderBy('updated_at', 'desc')->paginate(7);
        } else {
            $previlegios = $this->objPrevilegio->orderBy('updated_at', 'desc')->paginate(7);
        }

        $usuarios = $this->objUser->all();
        $funcaos = $this->objFuncao->all();
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();
        $municipios = $this->objMunicipio->where(['status' => 'Ativo'])->where(['id' => $previlegio[0]->municipios_id])->get();
        return view('gestaoescolar/previlegio/gest_list_previlegio', compact('previlegios','usuarios','funcaos','municipios','solRegistro','solAltCadastral','solAddTurma','anossame'));  

    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $previlegio = $this->objPrevilegio->where(['users_id' => auth()->user()->id])->get();
        $usuarios = $this->objUser->all();
        $funcaos = $this->objFuncao->all();
        $municipios = $this->objMunicipio->where(['status' => 'Ativo'])->where(['id' => $previlegio[0]->municipios_id])->get();
        return view('gestaoescolar/previlegio/gest_previlegio', compact('usuarios', 'municipios', 'funcaos'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $previlegios = $this->objPrevilegio->where(['users_id' => $request->users_id])->get();
        if ($previlegios && sizeof($previlegios) > 0) {
            return redirect()->route('gest_previlegio.filtrar')->with('status', 'Já existem previlégios atribuídos para este Usuário!');
        }
        $data = [
            'users_id' => $request->users_id,
            'autorizou_users_id' => $request->autorizou_users_id,
            'status' => 1,
            'funcaos_id' => $request->funcaos_id,
            'municipios_id' => $request->municipios_id,
            'SAME' => $request->SAME
        ];

        $previlegio = $this->objPrevilegio->where([['users_id', '=', $request->users_id],['funcaos_id', '=', $request->funcaos_id],['municipios_id', '=', $request->municipios_id]])->get();
        
        if ($previlegio && sizeof($previlegio) > 0) {
            $usuario = $this->objUser->find($request->users_id);
            $funcao = $this->objFuncao->find($request->funcaos_id);
            $municipios = $this->objMunicipio->where([['id','=',$request->municipios_id],['SAME','=',$request->SAME]])->get();
            $municipio = $municipios[0];
            return redirect()->route('gest_previlegio.listar')->with('status', 'O usuário '.$usuario->name.' já possuí a Função de '.$funcao->desc.' no Município de '.$municipio->nome.'!');
        }

        $cad = $this->objPrevilegio->create($data);


        if ($cad) {
            return redirect()->route('gest_previlegio.listar');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Previlegio  $previlegio
     * @return \Illuminate\Http\Response
     */
    public function show(Previlegio $previlegio)
    {
    }

    /**
     * Show the form for editing the specified resource.
     * Método que carrega os dados do registro selecionado para edição e disponibiliza a página de cadastro em modo de edição
     * @param  \App\Models\Previlegio  $previlegio
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuarios = $this->objUser->all();
        $funcaos = $this->objFuncao->all();
        $previlegios = $this->objPrevilegio->join('municipios', ['previlegios.municipios_id' => 'municipios.id','previlegios.SAME' => 'municipios.SAME'])
                                          ->select('previlegios.*', 'municipios.id as id_municipio','municipios.nome as nome_municipio','municipios.SAME as SAME_municipio')
                                          ->where(['previlegios.id' => $id])->get();
        $previlegio = $previlegios[0]; 

        $anosame = $this->objAnoSame->where(['descricao' => $previlegio->SAME])->orderBy('descricao', 'asc')->get();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();

        $municipios = $this->objMunicipio->where(['status' => 'Ativo'])->where(['id' => $previlegio->municipios_id])->get();

        return view('gestaoescolar/previlegio/gest_previlegio', compact('previlegio', 'usuarios', 'municipios', 'funcaos','anosame','anosativos'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Previlegio  $previlegio
     * @return \Illuminate\Http\Response
     */
    public function update(PrevilegioRequest $request, $id)
    {

        $data = [
            'users_id' => $request->users_id,
            'autorizou_users_id' => $request->autorizou_users_id,
            'status' => 1,
            'funcaos_id' => $request->funcaos_id,
            'municipios_id' => $request->municipios_id,
            'SAME' => $request->SAME
        ];

        $previlegio = $this->objPrevilegio->where([['users_id', '=', $request->users_id],['funcaos_id', '=', $request->funcaos_id],['municipios_id', '=', $request->municipios_id],['id','<>',$id]])->get();
        
        if ($previlegio && sizeof($previlegio) > 0) {
            $usuario = $this->objUser->find($request->users_id);
            $funcao = $this->objFuncao->find($request->funcaos_id);
            $municipios = $this->objMunicipio->where([['id','=',$request->municipios_id],['SAME','=',$request->SAME]])->get();
            $municipio = $municipios[0];
            return redirect()->route('gest_previlegio.listar')->with('status', 'O usuário '.$usuario->name.' já possuí a Função de '.$funcao->desc.' no Município de '.$municipio->nome.'!');
        }

        $this->objPrevilegio->where(['id' => $id])->update($data);
        return redirect()->route('gest_previlegio.listar');
    }

    /**
     * Update the specified resource in storage.
     * Método para inativar um registro de previlégio
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inativar($id)
    {
        $previlegio = $this->objPrevilegio->find($id);
        $previlegio = [
            'status' => 0,
        ];

        $this->objPrevilegio->where(['id' => $id])->update($previlegio);
        return redirect()->route('gest_previlegio.listar');
    }

    /**
     * Update the specified resource in storage.
     * Método para ativar um registro de previlégio
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ativar($id)
    {
        $previlegio = $this->objPrevilegio->find($id);
        $previlegio = [
            'status' => 1,
        ];

        $this->objPrevilegio->where(['id' => $id])->update($previlegio);
        return redirect()->route('gest_previlegio.listar');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Previlegio  $previlegio
     * @return \Illuminate\Http\Response
     */
    public function destroy(Previlegio $previlegio)
    {
    }
}
