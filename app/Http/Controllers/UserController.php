<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class UserController extends Controller
{
    private $objUser;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->objUser = new User();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the application dashboard.
     * Método que realiza a listagem dos usuários ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de usuários
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        if(Cache::has('Filtros_Consulta_User_'.strval(auth()->user()->id))){
            $query = User::query();
            $parametros = Cache::get('Filtros_Consulta_User_'.strval(auth()->user()->id));
            foreach($parametros as $nome => $valor){
                if($valor){
                    $query->where($nome,$valor);
                }
            }
            Cache::put('Filtros_Consulta_User_'.strval(auth()->user()->id), $parametros, now()->addMinutes(5));
            $users = $query->orderBy('updated_at', 'desc')->paginate(7);
        } else {
            $users = $this->objUser->orderBy('updated_at', 'desc')->paginate(7);
        }
        
        return view('cadastro/user/list_user', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cadastro/user/create_user');
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'perfil' => $request->perfil,
            'password' => bcrypt($request->password),
        ];

        $usuario = $this->objUser->where(['email' => $request->email])->get();
        if ($usuario && sizeof($usuario) > 0) {
            return redirect()->route('lista_user')->with('status', 'O E-mail '.$request->email.' já encontra-se em uso para o Usuário '.$usuario[0]->name.'!');
        }

        $cad = $this->objUser->create($data);


        if ($cad) {
            return redirect()->route('lista_user');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * Método que carrega os dados do registro selecionado para edição e disponibiliza a página de cadastro em modo de edição
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->objUser->find($id);
        return view('cadastro/user/create_user', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'perfil' => $request->perfil,
            'password' => bcrypt($request->password),
        ];

        $usuario = $this->objUser->where(['email' => $request->email])->where('id','<>',$id)->get();
        if ($usuario && sizeof($usuario) > 0) {
            return redirect()->route('lista_user')->with('status', 'O E-mail '.$request->email.' já encontra-se em uso para o Usuário '.$usuario[0]->name.'!');
        }

        $this->objUser->where(['id' => $id])->update($data);
        return redirect()->route('lista_user');
    }

    /**
     * Remove the specified resource from storage.
     * Método para exlusão do registro de usuário
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = $this->objUser->destroy($id);
        return ($del) ? "sim" : "não";
    }

    /**
     * Método que monta a listagem de Usuários pelo filtro
     */
    public function filtrar(Request $request)
    {
        if($request->name && $request->email){
            $users = $this->objUser->where([['name', '=', $request->name],['email', '=', $request->email]])->orderBy('updated_at', 'desc')->paginate(8);
        } else if($request->email){
            $users = $this->objUser->where([['email', '=', $request->email]])->orderBy('updated_at', 'desc')->paginate(8);
        } if($request->name){
            $users = $this->objUser->where([['name', '=', $request->name]])->orderBy('updated_at', 'desc')->paginate(8);
        } else{
            $users = $this->objUser->orderBy('updated_at', 'desc')->paginate(8); 
        }
        return redirect()->route('lista_user');
       // return view('cadastro/user/list_user', compact('users'));    
    }
}
