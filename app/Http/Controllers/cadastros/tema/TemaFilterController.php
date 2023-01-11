<?php

namespace App\Http\Controllers\cadastros\tema;

use App\Models\Disciplina;
use App\Models\Tema;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class TemaFilterController extends Controller
{
     /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->objTema = new Tema();
        $this->objDisciplina = new Disciplina();
    }
    /**
     * Método que monta a listagem de Temas pelo filtro
     */
    public function filtrar(Request $request)
    {
        $query = Tema::query();

        //Limpa os Filtros de Consulta
        Cache::forget('Filtros_Consulta_Tema_'.strval(auth()->user()->id));

        //Adiciona os Filtros em Cache
        Cache::put('Filtros_Consulta_Tema_'.strval(auth()->user()->id), $request->only('desc','disciplinas_id'), now()->addMinutes(5));
        
        //Óbtem os parâmetros de Filtro da Cache
        $parametros = Cache::get('Filtros_Consulta_Tema_'.strval(auth()->user()->id));

        //$parametros = $request->only('desc','disciplinas_id');
        foreach($parametros as $nome => $valor){
            if($valor){
                $query->where($nome,$valor);
            }
        }

        $temas = $query->orderBy('updated_at', 'desc')->paginate(7);
        $disciplinas = $this->objDisciplina->all();

        return view('cadastro/tema/list_tema', compact('temas','disciplinas'));
    }
}
