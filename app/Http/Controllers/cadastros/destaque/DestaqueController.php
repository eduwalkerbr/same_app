<?php

namespace App\Http\Controllers\cadastros\destaque;

use App\Http\Requests\DestaqueRequest;
use App\Models\DestaqueModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Symfony\Component\Console\Helper\ProgressBar;
use Throwable;

class DestaqueController extends Controller
{
    private $objDestaque;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objDestaque = new DestaqueModel();
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
     * Método que realiza a listagem dos destaques ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de destaques
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $destaques = $this->objDestaque->orderBy('updated_at', 'desc')->paginate(7);
        return view('cadastro/destaque/list_destaque', compact('destaques'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cadastro/destaque/create_destaque');
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DestaqueRequest $request)
    {
        try {

            $data = [
                'titulo' => trim($request->titulo),
                'conteudo' => trim($request->conteudo),
                'descricao' => trim($request->descricao),
                'fonte' => trim($request->fonte)
            ];

            //Realiza a inclusão do Registro
            if($this->objDestaque->create($data)){
                $mensagem = 'O Destaque '.$request->titulo.' foi cadastrado com Sucesso!';
                $status = 'success';
            }   
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_destaque')->with(['mensagem' => $mensagem,'status' => $status]);
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
        $destaque = $this->objDestaque->find($id);

        return view('cadastro/destaque/create_destaque', compact('destaque'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DestaqueRequest $request, $id)
    {
        try {

            $data = [
                'titulo' => trim($request->titulo),
                'conteudo' => trim($request->conteudo),
                'descricao' => trim($request->descricao),
                'fonte' => trim($request->fonte)
            ];

            //Realiza a inclusão do Registro
            if($this->objDestaque->where(['id' => $id])->update($data)){
                $mensagem = 'O Destaque '.$request->titulo.' foi alterado com Sucesso!';
                $status = 'success';
            }   
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_destaque')->with(['mensagem' => $mensagem,'status' => $status]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            if($this->objDestaque->destroy($request->id)){
                $mensagem = 'Exclusão realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            report($e);
        }
    }
}
