<?php

namespace App\Http\Controllers\cadastros\criterioquestao;

use App\Http\Requests\CriterioQuestaoRequest;
use App\Models\CriterioQuestao;
use App\Models\Disciplina;
use App\Models\TipoQuestao;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Throwable;

class CriterioQuestaoController extends Controller
{
    private $objDisciplina;
    private $objCriterioQuestao;
    private $objTipoQuestao;
    
    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objDisciplina = new Disciplina();
        $this->objCriterioQuestao = new CriterioQuestao();
        $this->objTipoQuestao = new TipoQuestao();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the application dashboard.
     * Método que realiza a listagem dos critérios de questão ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de critérios de questão
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        if(Cache::has('Filtros_Consulta_CriterioQuestao_'.strval(auth()->user()->id))){
            $query = CriterioQuestao::query();
            $parametros = Cache::get('Filtros_Consulta_CriterioQuestao_'.strval(auth()->user()->id));
            foreach($parametros as $nome => $valor){
                if($valor){
                    $query->where($nome,$valor);
                }
            }
            Cache::put('Filtros_Consulta_CriterioQuestao_'.strval(auth()->user()->id), $parametros, now()->addMinutes(5));
            $criterios_questao = $query->orderBy('updated_at', 'desc')->paginate(7);
        } else {
            $criterios_questao = $this->objCriterioQuestao->orderBy('updated_at', 'desc')->paginate(7);
        }

        $disciplinas = $this->objDisciplina->all();
        $tipoquestaos = $this->objTipoQuestao->all();
        return view('cadastro/criterios_questao/list_criterios_questao', compact('criterios_questao','disciplinas','tipoquestaos'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $disciplinas = $this->objDisciplina->all();
        $tipoquestaos = $this->objTipoQuestao->all();

        return view('cadastro/criterios_questao/create_criterios_questao', compact('disciplinas', 'tipoquestaos'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CriterioQuestaoRequest $request)
    {
        try {

            $data = [
                'nome' => trim($request->nome),
                'descricao' => trim($request->descricao),
                'id_disciplina' => intval($request->id_disciplina),
                'id_tipo_questao' => intval($request->id_tipo_questao),
                'ano' => intval($request->ano),
                'obs' => trim($request->obs)
            ];

            //Valida existência do Registro
            if($this->objCriterioQuestao->where([['nome', '=', $request->nome],['id_disciplina', '=', $request->id_disciplina],
            ['id_tipo_questao','=', $request->id_tipo_questao],['ano','=', $request->ano]])->get()->isNotEmpty()){
                $mensagem = 'O Critério de Questão '.$request->nome.' já encontra-se registrado para a Disciplina e Tipo de Questão no Ano '.$request->ano.' !';
                $status = 'error';
            } else {
                 //Realiza a inserção do Registro
                if($this->objCriterioQuestao->create($data)){
                    $mensagem = 'O Critério de Questão '.$request->nome.' do Ano '.$request->ano.' foi incluído com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_criterios_questao')->with(['mensagem' => $mensagem,'status' => $status]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * Método que carrega os dados do registro selecionado para edição e disponibiliza a página de cadastro em modo de edição
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $disciplinas = $this->objDisciplina->all();
        $tipoquestaos = $this->objTipoQuestao->all();
        $criterio_questao = $this->objCriterioQuestao->find($id);

        return view('cadastro/criterios_questao/create_criterios_questao', compact('criterio_questao', 'disciplinas', 'tipoquestaos'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CriterioQuestaoRequest $request, $id)
    {
        try {

            $data = [
                'nome' => trim($request->nome),
                'descricao' => trim($request->descricao),
                'id_disciplina' => intval($request->id_disciplina),
                'id_tipo_questao' => intval($request->id_tipo_questao),
                'ano' => intval($request->ano),
                'obs' => trim($request->obs)
            ];

            //Valida existência do Registro
            if($this->objCriterioQuestao->where([['nome', '=', $request->nome],['id_disciplina', '=', $request->id_disciplina],
            ['id_tipo_questao','=', $request->id_tipo_questao],['ano','=', $request->ano],['id','<>',$id]])->get()->isNotEmpty()){
                $mensagem = 'O Critério de Questão '.$request->nome.' já encontra-se registrado para a Disciplina e Tipo de Questão no Ano '.$request->ano.' !';
                $status = 'error';
            } else {
                 //Realiza a alteração do Registro
                if($this->objCriterioQuestao->where(['id' => $id])->update($data)){
                    $mensagem = 'O Critério de Questão '.$request->nome.' do Ano '.$request->ano.' foi alterado com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_criterios_questao')->with(['mensagem' => $mensagem,'status' => $status]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
