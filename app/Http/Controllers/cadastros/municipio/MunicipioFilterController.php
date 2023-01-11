<?php

namespace App\Http\Controllers\cadastros\municipio;

use App\Models\AnoSame;
use App\Models\Municipio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class MunicipioFilterController extends Controller
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
     * Método que monta a listagem de Município pelo filtro
     */
    public function filtrar(Request $request)
    {
        $query = Municipio::query();

        //Limpa os Filtros de Consulta
        Cache::forget('Filtros_Consulta_Municipio_'.strval(auth()->user()->id));

        //Adiciona os Filtros em Cache
        Cache::put('Filtros_Consulta_Municipio_'.strval(auth()->user()->id), $request->only('nome','SAME'), now()->addMinutes(5));
        
        //Óbtem os parâmetros de Filtro da Cache
        $parametros = Cache::get('Filtros_Consulta_Municipio_'.strval(auth()->user()->id));

        //$parametros = $request->only('nome','SAME');
        foreach($parametros as $nome => $valor){
            if($valor){
                $query->where('municipios.'.$nome,$valor);
            }
        }

        $municipios = $query->orderBy('updated_at', 'desc')->paginate(7);
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();

        return view('cadastro/municipio/list_municipio', compact('municipios','anossame'));    
    }
}
