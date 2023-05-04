<?php

namespace App\Http\Controllers\cadastros\gabarito;

use App\Models\AnoSame;
use App\Models\Disciplina;
use App\Models\Prova_gabarito;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ProvaGabaritoFilterController extends Controller
{
    private $objAnoSame;
    private $objDisciplina;

     /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->objAnoSame = new AnoSame();
        $this->objDisciplina = new Disciplina();
    }
    /**
     * Método que monta a listagem de Município pelo filtro
     */
    public function filtrar(Request $request)
    {
        $query = Prova_gabarito::query();

        //Limpa os Filtros de Consulta
        Cache::forget('Filtros_Consulta_ProvaGabarito_'.strval(auth()->user()->id));

        //Adiciona os Filtros em Cache
        Cache::put('Filtros_Consulta_ProvaGabarito_'.strval(auth()->user()->id), $request->only('DESCR_PROVA','ano','disciplinas_id','SAME'), now()->addMinutes(5));
        
        //Óbtem os parâmetros de Filtro da Cache
        $parametros = Cache::get('Filtros_Consulta_ProvaGabarito_'.strval(auth()->user()->id));

        foreach($parametros as $nome => $valor){
            if($valor){
                $query->where($nome,$valor);
            }
        }

        $prova_gabaritos = $query->orderBy('updated_at', 'desc')->paginate(7);
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();
        $disciplinas = $this->objDisciplina->all();
        return view('cadastro/prova_gabarito/list_prova_gabarito', compact('prova_gabaritos','anossame','disciplinas'));    
    }
}
