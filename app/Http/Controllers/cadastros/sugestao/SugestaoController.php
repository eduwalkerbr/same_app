<?php

namespace App\Http\Controllers\cadastros\sugestao;

use App\Http\Requests\SugestaoRequest;
use App\Models\Sugestao;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SugestaoController extends Controller
{
    private $objSugestao;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
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
        $sugestoes = $this->objSugestao->orderBy('updated_at', 'desc')->paginate(8);
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
        // return view('cadastro/topico_aberto/create_topico_aberto');
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SugestaoRequest $request)
    {
        $data = [
            'nome' => $request->nome,
            'email' => $request->email,
            'mensagem' => $request->mensagem
        ];

        $cad = $this->objSugestao->create($data);


        if ($cad) {
            return redirect()->route('home.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /*$topico_aberto = $this->objTopicoAberto->find($id);
        return view('cadastro/topico_aberto/create_topico_aberto',compact('topico_aberto'));*/
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
        $data = [
            'nome' => $request->nome,
            'email' => $request->email,
            'mensagem' => $request->mensagem
        ];
        return redirect()->route('home.index');
    }

    /**
     * Remove the specified resource from storage.
     * Método para exclusão dos registro de Sugestão
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = $this->objSugestao->destroy($id);
        return ($del) ? "sim" : "não";
    }
}
