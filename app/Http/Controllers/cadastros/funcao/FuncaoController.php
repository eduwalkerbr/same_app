<?php

namespace App\Http\Controllers\cadastros\funcao;

use App\Http\Requests\FuncaoRequest;
use App\Models\Funcao;
use App\Http\Controllers\Controller;
use Throwable;

class FuncaoController extends Controller
{
    private $objFuncao;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objFuncao = new Funcao();
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
     * Método que realiza a listagem das funções ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de funções
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $funcaos = $this->objFuncao->orderBy('updated_at', 'desc')->paginate(8);
        return view('cadastro/funcao/list_funcao', compact('funcaos'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cadastro/funcao/create_funcao');
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FuncaoRequest $request)
    {
        try {

            $data = [
                'desc' => trim($request->desc),
                'previlegio' => intval($request->previlegio),
            ];

            //Valida existência do Registro
            if($this->objFuncao->where(['desc' => $request->desc])->get()->isNotEmpty()){
                $mensagem = 'A Função '.$request->desc.' já foi cadastrada!';
                $status = 'error';
            } else {
                 //Realiza a inclusão do Registro
                if($this->objFuncao->create($data)){
                    $mensagem = 'A Função '.$request->desc.' foi Cadastrada com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_funcao')->with(['mensagem' => $mensagem,'status' => $status]);
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
        $funcao = $this->objFuncao->find($id);
        return view('cadastro/funcao/create_funcao', compact('funcao'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FuncaoRequest $request, $id)
    {
        try {

            $data = [
                'desc' => trim($request->desc),
                'previlegio' => intval($request->previlegio),
            ];

            //Valida existência do Registro
            if($this->objFuncao->where(['desc' => $request->desc])->where('id','<>',$id)->get()->isNotEmpty()){
                $mensagem = 'A Função '.$request->desc.' já foi cadastrada!';
                $status = 'error';
            } else {
                 //Realiza a alteração do Registro
                if($this->objFuncao->where(['id' => $id])->update($data)){
                    $mensagem = 'A Função '.$request->desc.' foi alterada com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_funcao')->with(['mensagem' => $mensagem,'status' => $status]);
    }

    /**
     * Remove the specified resource from storage.
     * Método para exclusão do registro de função
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if($this->objFuncao->destroy($id)){
                $mensagem = 'Exclusão realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
        return redirect()->route('lista_funcao')->with(['mensagem' => $mensagem,'status' => $status]);
    }
}
