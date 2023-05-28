<?php

namespace App\Http\Controllers\user;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Throwable;

class UserController extends Controller
{
    private $objUser;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objUser = new User();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
            'name' => trim($request->name),
            'email' => trim($request->email),
            'perfil' => $request->perfil,
            'password' => bcrypt($request->password),
        ];

        try {
            //Valida existência do Registro
            if($this->objUser->where(['email' => $request->email])->get()->isNotEmpty()){
                $mensagem = 'O E-mail '.$request->email.' já encontra-se em uso para outro Usuário!';
                $status = 'error';
            } else {
                 //Realiza a inclusão do Registro
                if($this->objUser->create($data)){
                    $mensagem = 'Usuário cadastrado com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('user.list')->with(['mensagem' => $mensagem,'status' => $status]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
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
            'name' => trim($request->name),
            'email' => trim($request->email),
            'perfil' => $request->perfil,
            'password' => bcrypt($request->password),
        ];

        try {
            //Valida existência do Registro
            if($this->objUser->where(['email' => $request->email])->where('id','<>',$id)->get()->isNotEmpty()){
                $mensagem = 'O E-mail '.$request->email.' já encontra-se em uso para outro Usuário!';
                $status = 'error';
            } else {
                 //Realiza a inclusão do Registro
                if($this->objUser->where(['id' => $id])->update($data)){
                    $mensagem = 'Usuário alterado com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('user.list')->with(['mensagem' => $mensagem,'status' => $status]);
    }

    /**
     * Remove the specified resource from storage.
     * Método para exlusão do registro de usuário
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if($this->objUser->destroy($id)){
                $mensagem = 'Exclusão realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
        return redirect()->route('user.list')->with(['mensagem' => $mensagem,'status' => $status]);
    }
}
