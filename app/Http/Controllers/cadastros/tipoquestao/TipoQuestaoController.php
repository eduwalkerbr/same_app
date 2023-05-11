<?php

namespace App\Http\Controllers\cadastros\tipoquestao;

use App\Http\Requests\TipoQuestaoRequest;
use App\Models\TipoQuestao;
use App\Http\Controllers\Controller;

class TipoQuestaoController extends Controller
{
    private $objTipoQuestao;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
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
     * Método que realiza a listagem dos tipos de questão ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de tipos de questão
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $tipoquestaos = $this->objTipoQuestao->orderBy('updated_at', 'desc')->paginate(7);
        return view('cadastro/tipoquestao/list_tipoquestao', compact('tipoquestaos'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cadastro/tipoquestao/create_tipoquestao');
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoQuestaoRequest $request)
    {
        $data = [
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
        ];

        $tipoquestao = $this->objTipoQuestao->where([['titulo', '=', $request->titulo]])->get();
        if ($tipoquestao && sizeof($tipoquestao) > 0) {
            return redirect()->route('lista_tipoquestao')->with('status', 'O Tipo de Questão '.$request->titulo.' já encontra-se registrado!');
        }

        $cad = $this->objTipoQuestao->create($data);


        if ($cad) {
            return redirect()->route('lista_tipoquestao');
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
        $tipoquestao = $this->objTipoQuestao->find($id);
        return view('cadastro/tipoquestao/create_tipoquestao', compact('tipoquestao'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipoQuestaoRequest $request, $id)
    {
        $data = [
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
        ];

        $tipoquestao = $this->objTipoQuestao->where([['titulo', '=', $request->titulo],['id','<>',$id]])->get();
        if ($tipoquestao && sizeof($tipoquestao) > 0) {
            return redirect()->route('lista_tipoquestao')->with('status', 'O Tipo de Questão '.$request->titulo.' já encontra-se registrado!');
        }

        $this->objTipoQuestao->where(['id' => $id])->update($data);
        return redirect()->route('lista_tipoquestao');
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
