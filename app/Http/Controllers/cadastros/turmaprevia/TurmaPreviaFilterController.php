<?php

namespace App\Http\Controllers\cadastros\turmaprevia;

use App\Models\Escola;
use App\Models\Turma;
use App\Models\TurmaPrevia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TurmaPreviaFilterController extends Controller
{
    private $objEscola;
    private $objTurma;


     /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objEscola = new Escola();
        $this->objTurma = new Turma();
    }
    /**
     * Método que monta a listagem de Turma P´revia pelo filtro
     */
    public function filtrar(Request $request)
    {
        $query = TurmaPrevia::query();

        $parametros = $request->only('id_escola','id_turma','email');
        foreach($parametros as $nome => $valor){
            if($valor){
                $query->where($nome,$valor);
            }
        }

        $turmasprevias = $query->orderBy('updated_at', 'desc')->paginate(7);
        $escolas = $this->objEscola->where(['status' => 'Ativo'])->get();
        $turmas = $this->objTurma->where(['status' => 'Ativo'])->get();

        return view('cadastro/turma_previa/list_turma_previa', compact('turmasprevias','escolas','turmas'));  
    }
}
