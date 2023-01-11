<?php

namespace App\Http\Controllers\cadastros\escola;

use App\Models\AnoSame;
use App\Models\Escola;
use App\Models\Municipio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class EscolaFilterController extends Controller
{
     /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->objAnoSame = new AnoSame();
        $this->objMunicipio = new Municipio();
    }
    /**
     * Método que monta a listagem de Escola pelo filtro
     */
    public function filtrar(Request $request)
    {
        $query = Escola::query();

        //Limpa os Filtros de Consulta
        Cache::forget('Filtros_Consulta_Escola_'.strval(auth()->user()->id));

        //Adiciona os Filtros em Cache
        Cache::put('Filtros_Consulta_Escola_'.strval(auth()->user()->id), $request->only('nome','SAME','municipios_id'), now()->addMinutes(5));
        
        //Óbtem os parâmetros de Filtro da Cache
        $parametros = Cache::get('Filtros_Consulta_Escola_'.strval(auth()->user()->id));

        //$parametros = $request->only('nome','SAME','municipios_id');
        foreach($parametros as $nome => $valor){
            if($valor){
                $query->where('escolas.'.$nome,$valor);
            }
        }

        $escolas = $query->join('municipios', ['escolas.municipios_id' => 'municipios.id', 'escolas.SAME' => 'municipios.SAME'])->select('escolas.*', 'municipios.nome as nome_municipio')->orderBy('updated_at', 'desc')->paginate(7);
        //$municipios = $this->objMunicipio->all();
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();

        return view('cadastro/escola/list_escola', compact('escolas','anossame'));    
    }
}
