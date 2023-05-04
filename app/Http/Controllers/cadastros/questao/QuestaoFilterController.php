<?php

namespace App\Http\Controllers\cadastros\questao;

use App\Models\AnoSame;
use App\Models\Disciplina;
use App\Models\Habilidade;
use App\Models\Prova_gabarito;
use App\Models\Questao;
use App\Models\Tema;
use App\Models\TipoQuestao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class QuestaoFilterController extends Controller
{
    private $objAnoSame;
    private $objDisciplina;
    private $objTema;
    private $objHabilidade;
    private $objTipoQuestao;
    private $objProvaGabarito;


     /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->objAnoSame = new AnoSame();
        $this->objDisciplina = new Disciplina();
        $this->objTema = new Tema();
        $this->objHabilidade = new Habilidade();
        $this->objTipoQuestao = new TipoQuestao();
        $this->objProvaGabarito = new Prova_gabarito();
    }
    /**
     * Método que monta a listagem de Município pelo filtro
     */
    public function filtrar(Request $request)
    {
        $query = Questao::query();

        //Limpa os Filtros de Consulta
        Cache::forget('Filtros_Consulta_Questao_'.strval(auth()->user()->id));

        //Adiciona os Filtros em Cache
        Cache::put('Filtros_Consulta_Questao_'.strval(auth()->user()->id), $request->only('desc','num_questao','modelo','tipo','disciplinas_id','temas_id','habilidades_id','prova_gabaritos_id','ano','SAME'), now()->addMinutes(5));
        
        //Óbtem os parâmetros de Filtro da Cache
        $parametros = Cache::get('Filtros_Consulta_Questao_'.strval(auth()->user()->id));

        foreach($parametros as $nome => $valor){
            if($valor){
                $query->where('questaos.'.$nome,$valor);
            }
        }

        $questaos = $query->join('habilidades', ['questaos.habilidades_id' => 'habilidades.id'])
                          ->join('temas', ['questaos.temas_id' => 'temas.id'])
                          ->select('questaos.*', 'habilidades.desc as nome_habilidade','temas.desc as nome_tema')
                          ->orderBy('num_questao', 'asc')->paginate(7);
                          
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();
        $disciplinas = $this->objDisciplina->all();
        $temas = $this->objTema->all();
        $habilidades = $this->objHabilidade->all();
        $tipoquestaos = $this->objTipoQuestao->all();
        $provas_gabaritos = $this->objProvaGabarito->where(['status' => 1])->get();

        return view('cadastro/questao/list_questao', compact('questaos','anossame','disciplinas','temas','habilidades','tipoquestaos','provas_gabaritos'));    
    }
}
