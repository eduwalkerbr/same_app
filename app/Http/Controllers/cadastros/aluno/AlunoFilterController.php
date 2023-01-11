<?php

namespace App\Http\Controllers\cadastros\aluno;

use App\Models\Aluno;
use App\Models\Escola;
use App\Models\Turma;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\AnoSame;

class AlunoFilterController extends Controller
{
     /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->objTurma = new Turma();
        $this->objEscola = new Escola();
        $this->objAluno = new Aluno();
        $this->objAnoSame = new AnoSame();
    }
    /**
     * Método que monta a listagem de Aluno pelo filtro
     */
    public function filtrar(Request $request)
    {
        $query = Aluno::query();

        //Limpa os Filtros de Consulta
        Cache::forget('Filtros_Consulta_Aluno_'.strval(auth()->user()->id));

        //Adiciona os Filtros em Cache
        Cache::put('Filtros_Consulta_Aluno_'.strval(auth()->user()->id), $request->only('turmas_escolas_id','turmas_id','nome','SAME'), now()->addMinutes(5));
        
        //Óbtem os parâmetros de Filtro da Cache
        $parametros = Cache::get('Filtros_Consulta_Aluno_'.strval(auth()->user()->id));
        
        foreach($parametros as $nome => $valor){
            if($valor){
                $query->where('alunos.'.$nome,$valor);
            }
        }

        $alunos = $query->join('municipios', ['alunos.turmas_escolas_municipios_id' => 'municipios.id', 'alunos.SAME' => 'municipios.SAME'])
                        ->join('escolas', ['alunos.turmas_escolas_id' => 'escolas.id', 'alunos.SAME' => 'escolas.SAME'])
                        ->join('turmas', ['alunos.turmas_id' => 'turmas.id', 'alunos.SAME' => 'turmas.SAME']) 
                        ->select('alunos.*', 'municipios.nome as nome_municipio','escolas.nome as nome_escola','turmas.TURMA as nome_turma')
                        ->orderBy('updated_at', 'desc')->paginate(7);
        //$escolas = $this->objEscola->all();
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();
        //$turmas = $this->objTurma->all();

        return view('cadastro/aluno/list_aluno', compact('alunos','anossame'));
    }

}
