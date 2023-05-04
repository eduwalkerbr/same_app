<?php

namespace App\Http\Controllers\cadastros\direcaoprofessor;

use App\Models\AnoSame;
use App\Models\DirecaoProfessor;
use App\Models\Escola;
use App\Models\Turma;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class DirecaoProfessorFilterController extends Controller
{
    private $objUser;
    private $objAnoSame;

     /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {   
        $this->objUser = new User();
        $this->objAnoSame = new AnoSame();
    }
    /**
     * Método que monta a listagem de Município pelo filtro
     */
    public function filtrar(Request $request)
    {
        $query = DirecaoProfessor::query();

        //Limpa os Filtros de Consulta
        Cache::forget('Filtros_Consulta_DirecaoProfessor_'.strval(auth()->user()->id));

        //Adiciona os Filtros em Cache
        Cache::put('Filtros_Consulta_DirecaoProfessor_'.strval(auth()->user()->id), $request->only('id_previlegio','id_escolas','id_turma','SAME','users_id'), now()->addMinutes(5));
        
        //Óbtem os parâmetros de Filtro da Cache
        $parametros = Cache::get('Filtros_Consulta_DirecaoProfessor_'.strval(auth()->user()->id));

        $query->leftjoin('escolas', ['direcao_professors.id_escola' => 'escolas.id','direcao_professors.SAME' => 'escolas.SAME'])
              ->leftjoin('turmas', ['direcao_professors.id_turma' => 'turmas.id','direcao_professors.SAME' => 'turmas.SAME']);
        $query->select('direcao_professors.id', 'direcao_professors.id_previlegio','direcao_professors.id_escola','direcao_professors.id_turma',
              'direcao_professors.created_at','direcao_professors.updated_at','direcao_professors.SAME','escolas.nome as nome_escola','turmas.DESCR_TURMA as nome_turma');
        foreach($parametros as $nome => $valor){
            if($nome == 'users_id' && $valor){
                $query->join('previlegios', 'direcao_professors.id_previlegio', '=', 'previlegios.id');
                $query->where('previlegios.users_id',$valor);
            } else if($valor){
                $query->where('direcao_professors.'.$nome,$valor);
            }
        }

        $direcao_professores = $query->orderBy('direcao_professors.updated_at', 'desc')->paginate(7);
        $anossame = $this->objAnoSame->orderBy('descricao','asc')->get();
        $usuarios = $this->objUser->orderBy('name','asc')->get();
        return view('cadastro/direcao_professores/list_direcao_professor', compact('direcao_professores','anossame','usuarios'));    
    }

        /**
     * Método ajax que monta o select de turmas pela escola selecionada na página de cadastro de turma prévia
     */
    public function get_by_escola(Request $request)
    {

        if (!$request->id_escola) {
            $html = '<option value="">' . '' . '</option>';
        } else {
            $html = '<option value=""></option>';
            $turmas = Turma::where('escolas_id', $request->id_escola)->get();
            foreach ($turmas as $turma) {
                $html .= '<option value="' . $turma->id . '">' . $turma->DESCR_TURMA . ' ('.$turma->SAME.')'. '</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}
