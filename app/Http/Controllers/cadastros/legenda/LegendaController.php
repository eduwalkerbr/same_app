<?php

namespace App\Http\Controllers\cadastros\legenda;

use App\Http\Requests\LegendaRequest;
use App\Models\Legenda;
use App\Http\Controllers\Controller;

class LegendaController extends Controller
{
    private $objLegenda;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objLegenda = new Legenda();
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
     * Método que realiza a listagem das legendas ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de legendas
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $legendas = $this->objLegenda->orderBy('updated_at', 'desc')->paginate(7);
        return view('cadastro/legenda/list_legenda', compact('legendas'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cadastro/legenda/create_legenda');
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LegendaRequest $request)
    {
        $data = [
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'exibicao' => $request->exibicao,
            'cor_fundo' => $request->cor_fundo,
            'cor_letra' => $request->cor_letra,
            'valor_inicial' => $request->valor_inicial,
            'valor_final' => $request->valor_final,
        ];

        $legenda = $this->objLegenda->where(['titulo' => $request->titulo])->get();
        if ($legenda && sizeof($legenda) > 0) {
            return redirect()->route('lista_legenda')->with('status', 'A Legenda '.$request->titulo.' já foi cadastrada!');
        }

        $cad = $this->objLegenda->create($data);


        if ($cad) {
            return redirect()->route('lista_legenda');
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
        $legenda = $this->objLegenda->find($id);
        return view('cadastro/legenda/create_legenda', compact('legenda'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LegendaRequest $request, $id)
    {
        $data = [
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'exibicao' => $request->exibicao,
            'cor_fundo' => $request->cor_fundo,
            'cor_letra' => $request->cor_letra,
            'valor_inicial' => $request->valor_inicial,
            'valor_final' => $request->valor_final,
        ];

        $legenda = $this->objLegenda->where(['titulo' => $request->titulo])->where('id','<>',$id)->get();
        if ($legenda && sizeof($legenda) > 0) {
            return redirect()->route('lista_legenda')->with('status', 'A Legenda '.$request->titulo.' já foi cadastrada!');
        }

        $this->objLegenda->where(['id' => $id])->update($data);
        return redirect()->route('lista_legenda');
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
