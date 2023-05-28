<?php

namespace App\Http\Controllers\cadastros\previlegio;

use App\Http\Requests\PrevilegioRequest;
use App\Models\Previlegio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AnoSame;
use App\Models\Funcao;
use App\Models\Municipio;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Throwable;

class PrevilegioController extends Controller
{
    private $objUser;
    private $objMunicipio;
    private $objFuncao;
    private $objPrevilegio;
    private $objAnoSame;

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
    public function exibirLista()
    {
        $usuarios = $this->objUser->all();
        $funcaos = $this->objFuncao->all();

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
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();

        return view('cadastro/previlegio/list_previlegio', compact('previlegios','usuarios','funcaos','anossame'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $usuarios = $this->objUser->all();
        $funcaos = $this->objFuncao->all();
        $municipios = $this->objMunicipio->where(['status' => 'Ativo'])->get();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();
        return view('cadastro/previlegio/create_previlegio', compact('usuarios', 'municipios', 'funcaos','anosativos'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            //Verifica existência de Previlégio pelo usuário
            if($this->objPrevilegio->where(['users_id' => $request->users_id])->get()->isNotEmpty()){
                //Exibe página de listagem de previlégios com mensagem ao usuário
                $mensagem = 'Já existem previlégios atribuídos para este Usuário!';
                $status = 'error';
                return redirect()->route('lista_previlegio')->with(['mensagem' => $mensagem,'status' => $status]);
            }

            $id_municipio = explode('_',$request->municipios_id)[0];

            $data = [
                'users_id' => intval($request->users_id),
                'autorizou_users_id' => intval($request->autorizou_users_id),
                'status' => 1,
                'funcaos_id' => intval($request->funcaos_id),
                'municipios_id' => intval($id_municipio),
                'SAME' => trim($request->SAME)
            ];
            //Verifica existência previlégio pelo usuário, função e município
            if($this->objPrevilegio->where([['users_id', '=', $request->users_id],['funcaos_id', '=', $request->funcaos_id],['municipios_id', '=', $id_municipio]])->get()->isNotEmpty()){
                //Carrega os dados do Usuário
                $usuario = $this->objUser->find($request->users_id);
                //Carrega os dados da Função
                $funcao = $this->objFuncao->find($request->funcaos_id);
                //Carrega os dados do Município pelo id e Ano SAME
                $municipios = $this->objMunicipio->where([['id','=',$id_municipio],['SAME','=',$request->SAME]])->get();
                $municipio = $municipios[0];
                $mensagem = 'O usuário '.$usuario->name.' já possuí a Função de '.$funcao->desc.' no Município de '.$municipio->nome.'!';
                $status = 'error';
            } else {
                 //Realiza a alteração do Registro
                if($this->objPrevilegio->create($data)){
                    //Carrega os dados do Usuário
                    $usuario = $this->objUser->find($request->users_id);
                    $mensagem = 'O Previlégio do usuário '.$usuario->name.' foi incluído com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_previlegio')->with(['mensagem' => $mensagem,'status' => $status]);
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
        $municipios = $this->objMunicipio->where(['status' => 'Ativo'])->get();
        $previlegios = $this->objPrevilegio->join('municipios', ['previlegios.municipios_id' => 'municipios.id','previlegios.SAME' => 'municipios.SAME'])
                                          ->select('previlegios.*', 'municipios.id as id_municipio','municipios.nome as nome_municipio','municipios.SAME as SAME_municipio')
                                          ->where(['previlegios.id' => $id])->get();
        $previlegio = $previlegios[0];                                  

        $anosame = $this->objAnoSame->where(['descricao' => $previlegio->SAME])->orderBy('descricao', 'asc')->get();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();

        return view('cadastro/previlegio/create_previlegio', compact('previlegio', 'usuarios', 'municipios', 'funcaos','anosame','anosativos'));
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
        try {

            $id_municipio = explode('_',$request->municipios_id)[0];
        
            $data = [
                'users_id' => intval($request->users_id),
                'autorizou_users_id' => intval($request->autorizou_users_id),
                'status' => 1,
                'funcaos_id' => intval($request->funcaos_id),
                'municipios_id' => intval($id_municipio),
                'SAME' => trim($request->SAME)
            ];

            //Verifica existência previlégio pelo usuário, função e município
            if($this->objPrevilegio->where([['users_id', '=', $request->users_id],['funcaos_id', '=', $request->funcaos_id],['municipios_id', '=', $id_municipio],['id','<>',$id]])->get()->isNotEmpty()){
                //Carrega os dados do Usuário
                $usuario = $this->objUser->find($request->users_id);
                //Carrega os dados da Função
                $funcao = $this->objFuncao->find($request->funcaos_id);
                //Carrega os dados do Município pelo id e Ano SAME
                $municipios = $this->objMunicipio->where([['id','=',$id_municipio],['SAME','=',$request->SAME]])->get();
                $municipio = $municipios[0];
                $mensagem = 'O usuário '.$usuario->name.' já possuí a Função de '.$funcao->desc.' no Município de '.$municipio->nome.'!';
                $status = 'error';
            } else {
                 //Realiza a alteração do Registro
                if($this->objPrevilegio->where(['id' => $id])->update($data)){
                    //Carrega os dados do Usuário
                    $usuario = $this->objUser->find($request->users_id);
                    $mensagem = 'O Previlégio do usuário '.$usuario->name.' foi alterado com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_previlegio')->with(['mensagem' => $mensagem,'status' => $status]);
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
        try {

            $previlegio = $this->objPrevilegio->find($id);
            $previlegio = [
                'status' => 0,
            ];

            if($this->objPrevilegio->where(['id' => $id])->update($previlegio)){
                $mensagem = 'Inativação realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
        return redirect()->route('lista_previlegio')->with(['mensagem' => $mensagem,'status' => $status]);
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
        try {

            $previlegio = $this->objPrevilegio->find($id);
            $previlegio = [
                'status' => 1,
            ];

            if($this->objPrevilegio->where(['id' => $id])->update($previlegio)){
                $mensagem = 'Ativação realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
        return redirect()->route('lista_previlegio')->with(['mensagem' => $mensagem,'status' => $status]);
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
