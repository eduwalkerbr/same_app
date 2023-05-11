<?php

namespace App\Http\Controllers\cadastros\direcaoprofessor;

use App\Models\AnoSame;
use App\Models\DirecaoProfessor;
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
        $this->middleware('auth');
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
        Cache::put('Filtros_Consulta_DirecaoProfessor_'.strval(auth()->user()->id), $request->only('id_previlegio','escolas_id','turmas_id','SAME','users_id'), now()->addMinutes(5));
        
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
            } else if($nome == 'escolas_id' && $valor){ 
                $query->where('direcao_professors.'.'id_escola',$valor);
            } else if($nome == 'turmas_id' && $valor){
                $query->where('direcao_professors.'.'id_turma',$valor);
            } else if($valor){
                $query->where('direcao_professors.'.$nome,$valor);
            }
        }

        $direcao_professores = $query->orderBy('direcao_professors.updated_at', 'desc')->paginate(7);
        $anossame = $this->objAnoSame->orderBy('descricao','asc')->get();
        $usuarios = $this->objUser->orderBy('name','asc')->get();
        return view('cadastro/direcao_professores/list_direcao_professor', compact('direcao_professores','anossame','usuarios'));    
    }
}
