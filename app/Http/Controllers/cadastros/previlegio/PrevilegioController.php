<?php

namespace App\Http\Controllers\cadastros\previlegio;

use App\Http\Requests\PrevilegioRequest;
use App\Models\Previlegio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\AnoSame;
use App\Models\Funcao;
use App\Models\Municipio;
use App\Models\Escola;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

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
        //Verifica existência de Previlégio pelo usuário
        $previlegios = $this->objPrevilegio->where(['users_id' => $request->users_id])->get();
        if ($previlegios && sizeof($previlegios) > 0) {
            //Exibe página de listagem de previlégios com mensagem ao usuário
            return redirect()->route('lista_previlegio')->with('status', 'Já existem previlégios atribuídos para este Usuário!');
        }

        $data = [
            'users_id' => $request->users_id,
            'autorizou_users_id' => $request->autorizou_users_id,
            'status' => 1,
            'funcaos_id' => $request->funcaos_id,
            'municipios_id' => $request->municipios_id,
            'SAME' => $request->SAME
        ];

        //Verifica existência previlégio pelo usuário, função e município
        $previlegio = $this->objPrevilegio->where([['users_id', '=', $request->users_id],['funcaos_id', '=', $request->funcaos_id],['municipios_id', '=', $request->municipios_id]])->get();
        //Caso exista previlégio cadastrado
        if ($previlegio && sizeof($previlegio) > 0) {
            //Carrega os dados do Usuário
            $usuario = $this->objUser->find($request->users_id);
            //Carrega os dados da Função
            $funcao = $this->objFuncao->find($request->funcaos_id);
            //Carrega os dados do Município pelo id e Ano SAME
            $municipios = $this->objMunicipio->where([['id','=',$request->municipios_id],['SAME','=',$request->SAME]])->get();
            $municipio = $municipios[0];
            //Exibe listagem de previlégio com mensagem ao usuário
            return redirect()->route('lista_previlegio')->with('status', 'O usuário '.$usuario->name.' já possuí a Função de '.$funcao->desc.' no Município de '.$municipio->nome.'!');
        }

        $cad = $this->objPrevilegio->create($data);

        if ($cad) {
            return redirect()->route('lista_previlegio');
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

        $data = [
            'users_id' => $request->users_id,
            'autorizou_users_id' => $request->autorizou_users_id,
            'status' => 1,
            'funcaos_id' => $request->funcaos_id,
            'municipios_id' => $request->municipios_id,
            'SAME' => $request->SAME
        ];

        //Verifica existência previlégio pelo usuário, função e município
        $previlegio = $this->objPrevilegio->where([['users_id', '=', $request->users_id],['funcaos_id', '=', $request->funcaos_id],['municipios_id', '=', $request->municipios_id],['id','<>',$id]])->get();
        //Caso exista um previlégio cadastrado
        if ($previlegio && sizeof($previlegio) > 0) {
            //Carrega os dados do Usuário
            $usuario = $this->objUser->find($request->users_id);
            //Carrega os dados da Função
            $funcao = $this->objFuncao->find($request->funcaos_id);
            //Carrega os dados do Município pelo id e Ano SAME
            $municipios = $this->objMunicipio->where([['id','=',$request->municipios_id],['SAME','=',$request->SAME]])->get();
            $municipio = $municipios[0];

            //Exibe página de listagem de previlégios com mensagem ao usuário
            return redirect()->route('lista_previlegio')->with('status', 'O usuário '.$usuario->name.' já possuí a Função de '.$funcao->desc.' no Município de '.$municipio->nome.'!');
        }

        $this->objPrevilegio->where(['id' => $id])->update($data);
        return redirect()->route('lista_previlegio');
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
        return redirect()->route('lista_previlegio');
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
        return redirect()->route('lista_previlegio');
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

    /**
     * Método ajax para listar as turmas baseado na escola selecionada na página de solicitação de turma
     */
    public function get_by_same_municipio(Request $request)
    {
        if (!$request->SAME) {
            $html = '<option value="">' . trans('') . '</option>';
        } else {
            $html = '<option value=""></option>';
            $municipios = Municipio::where(['SAME' => $request->SAME])->get();
            foreach ($municipios as $municipio) {
                $html .= '<option value="' . $municipio->id . '">' . $municipio->nome . ' ('.$municipio->SAME.')'. '</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}
