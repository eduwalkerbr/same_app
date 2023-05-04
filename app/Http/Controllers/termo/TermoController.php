<?php

namespace App\Http\Controllers\termo;

use App\Http\Requests\TermoRequest;
use App\Models\Termo;
use App\Http\Controllers\Controller;

class TermoController extends Controller
{
    private $objTermo;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->objTermo = new Termo();
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
     * Método que realiza a listagem dos termos ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de termos
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $termos = $this->objTermo->orderBy('updated_at', 'desc')->paginate(7);
        return view('cadastro/termo/list_termo', compact('termos'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cadastro/termo/create_termo');
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TermoRequest $request)
    {
        $data = [
            'descricao' => $request->descricao,
        ];

        //Realiza a inclusão da imagem
        if ($request->hasFile('image') && $request->image->isValid()) {
            $imagePath = $request->image->store('termo');
            $data['arquivo'] = $imagePath;
        }

        $cad = $this->objTermo->create($data);

        if ($cad) {
            return redirect()->route('lista_termo');
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
        $termo = $this->objTermo->find($id);
        return view('cadastro/termo/create_termo', compact('termo'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TermoRequest $request, $id)
    {
        $data = [
            'descricao' => $request->descricao,
        ];

        //Alteração da Imagem
        if ($request->hasFile('image') && $request->image->isValid()) {
            $imagePath = $request->image->store('termo');
            $data['arquivo'] = $imagePath;
        }

        $this->objTermo->where(['id' => $id])->update($data);
        return redirect()->route('lista_termo');
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
