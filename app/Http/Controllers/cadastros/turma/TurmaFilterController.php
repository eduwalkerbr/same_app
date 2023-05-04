<?php

namespace App\Http\Controllers\cadastros\turma;

use App\Models\AnoSame;
use App\Models\Turma;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class TurmaFilterController extends Controller
{
    private $objAnoSame;

     /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->objAnoSame = new AnoSame();
    }
    /**
     * Método que monta a listagem de Turma pelo filtro
     */
    public function filtrar(Request $request)
    {
        $query = Turma::query();

        //Limpa os Filtros de Consulta
        Cache::forget('Filtros_Consulta_Turma_'.strval(auth()->user()->id));

        //Adiciona os Filtros em Cache
        Cache::put('Filtros_Consulta_Turma_'.strval(auth()->user()->id), $request->only('TURMA','SAME','escolas_id'), now()->addMinutes(5));
        
        //Óbtem os parâmetros de Filtro da Cache
        $parametros = Cache::get('Filtros_Consulta_Turma_'.strval(auth()->user()->id));

        $parametros = $request->only('TURMA','SAME','escolas_id');
        foreach($parametros as $nome => $valor){
            if($valor){
                $query->where('turmas.'.$nome,$valor);
            }
        }

        $turmas = $query->join('municipios', ['turmas.escolas_municipios_id' => 'municipios.id', 'turmas.SAME' => 'municipios.SAME'])
                        ->join('escolas', ['turmas.escolas_id' => 'escolas.id', 'turmas.SAME' => 'escolas.SAME']) 
                        ->select('turmas.*', 'municipios.nome as nome_municipio','escolas.nome as nome_escola')
                        ->orderBy('updated_at', 'desc')->paginate(7);
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();

        return view('cadastro/turma/list_turma', compact('turmas','anossame'));    
    }
}
