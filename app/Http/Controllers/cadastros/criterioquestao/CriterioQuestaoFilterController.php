<?php

namespace App\Http\Controllers\cadastros\criterioquestao;

use App\Models\CriterioQuestao;
use App\Models\Disciplina;
use App\Models\TipoQuestao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class CriterioQuestaoFilterController extends Controller
{
    private $objDisciplina;
    private $objTipoQuestao;

     /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objDisciplina = new Disciplina();
        $this->objTipoQuestao = new TipoQuestao();
    }
    /**
     * Método que monta a listagem de Critérios Questão pelo filtro
     */
    public function filtrar(Request $request)
    {
        $query = CriterioQuestao::query();

        //Limpa os Filtros de Consulta
        Cache::forget('Filtros_Consulta_CriterioQuestao_'.strval(auth()->user()->id));

        //Adiciona os Filtros em Cache
        Cache::put('Filtros_Consulta_CriterioQuestao_'.strval(auth()->user()->id), $request->only('nome','id_disciplina','id_tipo_questao','ano'), now()->addMinutes(5));
        
        //Óbtem os parâmetros de Filtro da Cache
        $parametros = Cache::get('Filtros_Consulta_CriterioQuestao_'.strval(auth()->user()->id));

        //$parametros = $request->only('nome','id_disciplina','id_tipo_questao','ano');
        foreach($parametros as $nome => $valor){
            if($valor){
                $query->where($nome,$valor);
            }
        }

        $criterios_questao = $query->orderBy('updated_at', 'desc')->paginate(7);
        $disciplinas = $this->objDisciplina->all();
        $tipoquestaos = $this->objTipoQuestao->all();

        return view('cadastro/criterios_questao/list_criterios_questao', compact('criterios_questao','disciplinas','tipoquestaos'));  
    }
}
