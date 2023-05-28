<?php

namespace App\Http\Controllers\cadastros\habilidade;

use App\Http\Requests\HabilidadeRequest;
use App\Models\Disciplina;
use App\Models\Habilidade;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Throwable;

class HabilidadeController extends Controller
{
    private $objHabilidade;
    private $objDisciplina;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objHabilidade = new Habilidade();
        $this->objDisciplina = new Disciplina();
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
     * Método que realiza a listagem das habilidades ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de habilidades
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        if(Cache::has('Filtros_Consulta_Habilidade_'.strval(auth()->user()->id))){
            $query = Habilidade::query();
            $parametros = Cache::get('Filtros_Consulta_Habilidade_'.strval(auth()->user()->id));
            foreach($parametros as $nome => $valor){
                if($valor){
                    $query->where($nome,$valor);
                }
            }
            Cache::put('Filtros_Consulta_Habilidade_'.strval(auth()->user()->id), $parametros, now()->addMinutes(5));
            $habilidades = $query->orderBy('updated_at', 'desc')->paginate(6);
        } else {
            $habilidades = $this->objHabilidade->orderBy('updated_at', 'desc')->paginate(6);
        }

        $disciplinas = $this->objDisciplina->all();
        return view('cadastro/habilidade/list_habilidade', compact('habilidades','disciplinas'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $disciplinas = $this->objDisciplina->all();
        return view('cadastro/habilidade/create_habilidade', compact('disciplinas'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HabilidadeRequest $request)
    {
        try {

            $data = [
                'desc' => trim($request->desc),
                'obs' => trim($request->obs),
                'disciplinas_id' => intval($request->disciplinas_id)
            ];

            //Valida existência do Registro
            if($this->objHabilidade->where([['desc', '=', $request->desc],['disciplinas_id', '=', $request->disciplinas_id]])->get()->isNotEmpty()){
                $mensagem = 'A Habilidade '.$request->desc.' já encontra-se Cadastrada!';
                $status = 'error';
            } else {
                 //Realiza a inclusão do Registro
                if($this->objHabilidade->create($data)){
                    $mensagem = 'A Habilidade '.$request->desc.' foi cadastrada com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_habilidade')->with(['mensagem' => $mensagem,'status' => $status]);
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
        $habilidade = $this->objHabilidade->find($id);

        return view('cadastro/habilidade/create_habilidade', compact('habilidade', 'disciplinas'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(HabilidadeRequest $request, $id)
    {
        try {

            $data = [
                'desc' => trim($request->desc),
                'disciplinas_id' => intval($request->disciplinas_id),
                'obs' => trim($request->obs),
            ];

            //Valida existência do Registro
            if($this->objHabilidade->where([['desc', '=', $request->desc],['disciplinas_id', '=', $request->disciplinas_id],['id','<>',$id]])->get()->isNotEmpty()){
                $mensagem = 'A Habilidade '.$request->desc.' já encontra-se Cadastrada!';
                $status = 'error';
            } else {
                 //Realiza a alteração do Registro
                if($this->objHabilidade->where(['id' => $id])->update($data)){
                    $mensagem = 'A Habilidade '.$request->desc.' foi alterada com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_habilidade')->with(['mensagem' => $mensagem,'status' => $status]);
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
