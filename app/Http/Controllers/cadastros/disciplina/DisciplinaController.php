<?php

namespace App\Http\Controllers\cadastros\disciplina;

use App\Http\Requests\DisciplinaRequest;
use App\Models\Disciplina;
use App\Http\Controllers\Controller;
use Throwable;

class DisciplinaController extends Controller
{
    private $objDisciplina;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
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
     * Método que realiza a listagem das disciplinas ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de disciplinas
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $disciplinas = $this->objDisciplina->orderBy('updated_at', 'desc')->paginate(7);
        return view('cadastro/disciplina/list_disciplina', compact('disciplinas'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cadastro/disciplina/create_disciplina');
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DisciplinaRequest $request)
    {
        try {

            $data = [
                'desc' => trim($request->desc),
                'obs' => trim($request->obs),
            ];

            //Valida existência do Registro
            if($this->objDisciplina->where([['desc', '=', $request->desc]])->get()->isNotEmpty()){
                $mensagem = 'A Disciplina '.$request->desc.' já encontra-se Cadastrada!';
                $status = 'error';
            } else {
                 //Realiza a inclusão do Registro
                if($this->objDisciplina->create($data)){
                    $mensagem = 'A Disciplina '.$request->desc.' foi cadastrada com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_disciplina')->with(['mensagem' => $mensagem,'status' => $status]);
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
        $disciplina = $this->objDisciplina->find($id);
        return view('cadastro/disciplina/create_disciplina', compact('disciplina'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DisciplinaRequest $request, $id)
    {
        try {

            $data = [
                'desc' => trim($request->desc),
                'obs' => trim($request->obs),
            ];

            //Valida existência do Registro
            if($this->objDisciplina->where([['desc', '=', $request->desc],['id','<>',$id]])->get()->isNotEmpty()){
                $mensagem = 'A Disciplina '.$request->desc.' já encontra-se Cadastrada!';
                $status = 'error';
            } else {
                 //Realiza a alteração do Registro
                if($this->objDisciplina->where(['id' => $id])->update($data)){
                    $mensagem = 'A Disciplina '.$request->desc.' foi alterada com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_disciplina')->with(['mensagem' => $mensagem,'status' => $status]);
    }

    /**
     * Remove the specified resource from storage.
     * Método para exclusão de disciplinas
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if($this->objDisciplina->destroy($id)){
                $mensagem = 'Exclusão realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
        return redirect()->route('lista_disciplina')->with(['mensagem' => $mensagem,'status' => $status]);
    }
}
