<?php

namespace App\Http\Controllers\cadastros\tema;

use App\Http\Requests\TemaRequest;
use App\Models\Disciplina;
use App\Models\Tema;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Throwable;

class TemaController extends Controller
{
    private $objTema;
    private $objDisciplina;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objTema = new Tema();
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
     * Método que realiza a listagem dos temas ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de temas
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        if(Cache::has('Filtros_Consulta_Tema_'.strval(auth()->user()->id))){
            $query = Tema::query();
            $parametros = Cache::get('Filtros_Consulta_Tema_'.strval(auth()->user()->id));
            foreach($parametros as $nome => $valor){
                if($valor){
                    $query->where($nome,$valor);
                }
            }
            Cache::put('Filtros_Consulta_Tema_'.strval(auth()->user()->id), $parametros, now()->addMinutes(5));
            $temas = $query->orderBy('updated_at', 'desc')->paginate(7);
        } else {
            $temas = $this->objTema->orderBy('updated_at', 'desc')->paginate(7);
        }
        $disciplinas = $this->objDisciplina->all();
        return view('cadastro/tema/list_tema', compact('temas','disciplinas'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $disciplinas = $this->objDisciplina->all();
        return view('cadastro/tema/create_tema', compact('disciplinas'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TemaRequest $request)
    {
        try {

            $data = [
                'desc' => trim($request->desc),
                'obs' => trim($request->obs),
                'disciplinas_id' => intval($request->disciplinas_id)
            ];

            //Valida existência do Registro
            if($this->objTema->where([['desc', '=', $request->desc],['disciplinas_id', '=', $request->disciplinas_id]])->get()->isNotEmpty()){
                $mensagem = 'O Tema '.$request->desc.' já encontra-se Cadastrado!';
                $status = 'error';
            } else {
                 //Realiza a inclusão do Registro
                if($this->objTema->create($data)){
                    $mensagem = 'O Tema '.$request->desc.' foi cadastrado com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_tema')->with(['mensagem' => $mensagem,'status' => $status]);
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
        $tema = $this->objTema->find($id);

        return view('cadastro/tema/create_tema', compact('tema', 'disciplinas'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TemaRequest $request, $id)
    {
        try {

            $data = [
                'desc' => trim($request->desc),
                'disciplinas_id' => intval($request->disciplinas_id),
                'obs' => trim($request->obs),
            ];

            //Valida existência do Registro
            if($this->objTema->where([['desc', '=', $request->desc],['disciplinas_id', '=', $request->disciplinas_id],['id','<>',$id]])->get()->isNotEmpty()){
                $mensagem = 'O Tema '.$request->desc.' já encontra-se Cadastrado!';
                $status = 'error';
            } else {
                 //Realiza a alteração do Registro
                if($this->objTema->where(['id' => $id])->update($data)){
                    $mensagem = 'O Tema '.$request->desc.' foi atualizado com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_tema')->with(['mensagem' => $mensagem,'status' => $status]);
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
