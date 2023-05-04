<?php

namespace App\Http\Controllers\cadastros\habilidade;

use App\Models\Disciplina;
use App\Models\Habilidade;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class HabilidadeFilterController extends Controller
{
    private $objDisciplina;

     /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->objDisciplina = new Disciplina();
    }
    /**
     * Método que monta a listagem de Habilidade pelo filtro
     */
    public function filtrar(Request $request)
    {
        $query = Habilidade::query();

        //Limpa os Filtros de Consulta
        Cache::forget('Filtros_Consulta_Habilidade_'.strval(auth()->user()->id));

        //Adiciona os Filtros em Cache
        Cache::put('Filtros_Consulta_Habilidade_'.strval(auth()->user()->id), $request->only('desc','disciplinas_id'), now()->addMinutes(5));
        
        //Óbtem os parâmetros de Filtro da Cache
        $parametros = Cache::get('Filtros_Consulta_Habilidade_'.strval(auth()->user()->id));

        foreach($parametros as $nome => $valor){
            if($valor){
                $query->where($nome,$valor);
            }
        }

        $habilidades = $query->orderBy('updated_at', 'desc')->paginate(6);
        $disciplinas = $this->objDisciplina->all();

        return view('cadastro/habilidade/list_habilidade', compact('habilidades','disciplinas'));
    }
}
