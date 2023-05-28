<?php

namespace App\Http\Controllers\cadastros\sugestao;

use App\Http\Requests\SugestaoRequest;
use App\Models\Sugestao;
use App\Http\Controllers\Controller;
use Throwable;

class SugestaoController extends Controller
{
    private $objSugestao;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objSugestao = new Sugestao();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
    }

    /**
     * Show the application dashboard.
     * Método que realiza a listagem das sugestões ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de sugestões
     * páginada de 8 em 8 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $sugestoes = $this->objSugestao->orderByDesc('status', 'desc')->orderBy('created_at', 'desc')->paginate(8);
        return view('cadastro/sugestao/list_sugestao', compact('sugestoes'));
    }

    /**
     * Função para listar todas as notícias cadastradas de forma páginada
     */
    public function listar()
    {
        /*$topicos_abertos = $this->objTopicoAberto->orderBy('updated_at', 'desc')->paginate(3);
        return view('topico_aberto/listagem_topico_aberto_completa',compact('topicos_abertos'));*/
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('cadastro/sugestao/create_sugestao');
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SugestaoRequest $request)
    {
        try {

            $data = [
                'nome' => trim($request->nome),
                'email' => trim($request->email),
                'mensagem' => trim($request->mensagem),
                'status' => 1
            ];

            //Realiza a alteração do Registro
            if($this->objSugestao->create($data)){
                $mensagem = 'Inclusão realizada com Sucesso.'; 
                $status = 'success';
            }   
        } catch (Throwable $e) {
            $mensagem = 'Erro: ' + $e; 
            $status = 'error';
        }

        return redirect()->route('lista_sugestoes')->with(['mensagem' => $mensagem,'status' => $status]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sugestao = $this->objSugestao->find($id);
        return view('cadastro/sugestao/create_sugestao',compact('sugestao'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SugestaoRequest $request, $id)
    {
        try {

            $data = [
                'status' => 0
            ];

            //Realiza a alteração do Registro
            if($this->objSugestao->where(['id' => $id])->update($data)){
                $mensagem = ''; 
                $status = 'success';
            }   
        } catch (Throwable $e) {
            $mensagem = 'Erro: ' + $e; 
            $status = 'error';
        }

        return redirect()->route('lista_sugestoes')->with(['mensagem' => $mensagem,'status' => $status]);;
    }

    /**
     * Remove the specified resource from storage.
     * Método para exclusão dos registro de Sugestão
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if($this->objSugestao->destroy($id)){
                $mensagem = 'Exclusão realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
    }
}
