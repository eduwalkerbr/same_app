<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class UserFilter extends Controller
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
     * Método que monta a listagem de Usuários pelo filtro
     */
    public function filtrar(Request $request)
    {
        $query = User::query();

        //Limpa os Filtros de Consulta
        Cache::forget('Filtros_Consulta_User_'.strval(auth()->user()->id));

        //Adiciona os Filtros em Cache
        Cache::put('Filtros_Consulta_User_'.strval(auth()->user()->id), $request->only('name','email'), now()->addMinutes(5));
        
        //Óbtem os parâmetros de Filtro da Cache
        $parametros = Cache::get('Filtros_Consulta_User_'.strval(auth()->user()->id));

        //$parametros = $request->only('name','email');
        foreach($parametros as $nome => $valor){
            if($valor){
                $query->where($nome,$valor);
            }
        }

        $users = $query->orderBy('updated_at', 'desc')->paginate(7);

        return view('cadastro/user/list_user', compact('users'));    
    }
}
