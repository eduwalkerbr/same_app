<?php

namespace App\Http\Controllers\cadastros\tiposolicitacao;

use App\Http\Requests\TipoSolicitacaoRequest;
use App\Http\Controllers\Controller;
use App\Models\TipoSolicitacao;
use Illuminate\Http\Request;

class TipoSolicitacaoController extends Controller
{
    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    private $objTipoSolicitacao;

    public function __construct()
    {
        $this->objTipoSolicitacao = new TipoSolicitacao();
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
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cadastro/tipo_solicitacao/create_tipo_solicitacao');
    }

    /**
     * Show the application dashboard.
     * Método que realiza a listagem dos tipos de solicitações ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de alunos
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $tipos_solicitacao = $this->objTipoSolicitacao->orderBy('updated_at', 'desc')->paginate(7);
        return view('cadastro/tipo_solicitacao/list_tipo_solicitacao', compact('tipos_solicitacao'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoSolicitacaoRequest $request)
    {
        $data = [
            'nome' => $request->nome
        ];

        $tipo_solicitacao = $this->objTipoSolicitacao->where(['nome' => $request->nome])->get();
        if ($tipo_solicitacao && sizeof($tipo_solicitacao) > 0) {
            return redirect()->route('lista_tipo_solicitacao')->with('status', 'O Tipo de Solicitação '.$request->nome.' já foi cadastrado!');
        }

        $cad = $this->objTipoSolicitacao->create($data);

        if ($cad) {
            return redirect()->route('lista_tipo_solicitacao');
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
        $tipo_solicitacao = $this->objTipoSolicitacao->find($id);
        return view('cadastro/tipo_solicitacao/create_tipo_solicitacao', compact('tipo_solicitacao'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TipoSolicitacaoRequest $request, $id)
    {
        $data = [
            'nome' => $request->nome,
        ];

        $tipo_solicitacao = $this->objTipoSolicitacao->where(['nome' => $request->nome])->where('id','<>',$id)->get();
        if ($tipo_solicitacao && sizeof($tipo_solicitacao) > 0) {
            return redirect()->route('lista_tipo_solicitacao')->with('status', 'O Tipo de Solicitação '.$request->nome.' já foi cadastrado!');
        }

        $this->objTipoSolicitacao->where(['id' => $id])->update($data);
        return redirect()->route('lista_tipo_solicitacao');
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
