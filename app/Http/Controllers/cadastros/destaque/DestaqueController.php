<?php

namespace App\Http\Controllers\cadastros\destaque;

use App\Http\Requests\DestaqueRequest;
use App\Models\DestaqueModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        $data = [
            'titulo' => $request->titulo,
            'conteudo' => $request->conteudo,
            'descricao' => $request->descricao,
            'fonte' => $request->fonte
        ];

        $cad = $this->objDestaque->create($data);


        if ($cad) {
            return redirect()->route('lista_destaque');
        }
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
        $data = [
            'titulo' => $request->titulo,
            'conteudo' => $request->conteudo,
            'descricao' => $request->descricao,
            'fonte' => $request->fonte
        ];

        $this->objDestaque->where(['id' => $id])->update($data);
        return redirect()->route('lista_destaque');
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
