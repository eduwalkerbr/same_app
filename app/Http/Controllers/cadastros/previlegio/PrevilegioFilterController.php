<?php

namespace App\Http\Controllers\cadastros\previlegio;

use App\Models\AnoSame;
use App\Models\Funcao;
use App\Models\Previlegio;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class PrevilegioFilterController extends Controller
{
    private $objUser;
    private $objFuncao;
    private $objAnoSame;

     /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objUser = new User();
        $this->objFuncao = new Funcao();
        $this->objAnoSame = new AnoSame();
    }
    /**
     * Método que monta a listagem de Município pelo filtro
     */
    public function filtrar(Request $request)
    {
        $query = Previlegio::query();

        //Limpa os Filtros de Consulta
        Cache::forget('Filtros_Consulta_Previlegio_'.strval(auth()->user()->id));

        //Adiciona os Filtros em Cache
        Cache::put('Filtros_Consulta_Previlegio_'.strval(auth()->user()->id), $request->only('users_id','municipios_id','funcaos_id','SAME'), now()->addMinutes(5));
        
        //Óbtem os parâmetros de Filtro da Cache
        $parametros = Cache::get('Filtros_Consulta_Previlegio_'.strval(auth()->user()->id));

        foreach($parametros as $nome => $valor){
            if($valor){
                $query->where('previlegios.'.$nome,$valor);
            }
        }

        $previlegios = $query->orderBy('updated_at', 'desc')->paginate(7);
        $usuarios = $this->objUser->all();
        $funcaos = $this->objFuncao->all();
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();
        return view('cadastro/previlegio/list_previlegio', compact('previlegios','usuarios','funcaos','anossame'));    
    }
}
