<?php

namespace App\Http\Controllers\cadastros\anosame;

use App\Models\AnoSame;
use Illuminate\Http\Request;
use App\Http\Requests\AnoSAMERequest;
use App\Http\Controllers\Controller;

class AnoSAMEController extends Controller
{

    private $objAnoSame;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objAnoSame = new AnoSame();
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
     * Método que realiza a listagem das legendas ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de AnosSame
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $anosSame = $this->objAnoSame->orderBy('descricao', 'asc')->paginate(7);
        return view('cadastro/anosame/list_anosame', compact('anosSame'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cadastro/anosame/create_anosame',);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnoSAMERequest $request)
    {
        $data = [
            'descricao' => $request->descricao,
            'status' => $request->status
        ];

        $anosame = $this->objAnoSame->where(['descricao' => $request->descricao])->get();
        if ($anosame && sizeof($anosame) > 0) {
            return redirect()->route('lista_anosame')->with('status', 'O Ano '.$request->descricao.' já foi cadastrado!');
        }

        $cad = $this->objAnoSame->create($data);


        if ($cad) {
            return redirect()->route('lista_anosame');
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $anosame = $this->objAnoSame->find($id);

        return view('cadastro/anosame/create_anosame', compact('anosame'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AnoSAMERequest $request, $id)
    {
        $data = [
            'descricao' => $request->descricao,
            'status' => $request->status
        ];

        $anosame = $this->objAnoSame->where(['descricao' => $request->descricao])->where('id','<>',$id)->get();
        if ($anosame && sizeof($anosame) > 0) {
            return redirect()->route('lista_anosame')->with('status', 'O Ano '.$request->descricao.' já foi cadastrado!');
        }

        $this->objAnoSame->where(['id' => $id])->update($data);
        return redirect()->route('lista_anosame');
    }

    /**
     * Update the specified resource in storage.
     * Método para inativar o registro de Turma
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inativar($id)
    {
        $anosame = $this->objAnoSame->find($id);
        $anosame = [
            'status' => 'Inativo',
        ];

        $this->objAnoSame->where(['id' => $id])->update($anosame);
        return redirect()->route('lista_anosame');
    }

    /**
     * Update the specified resource in storage.
     * Método para ativar o registro de turma
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ativar($id)
    {
        $anosame = $this->objAnoSame->find($id);
        $anosame = [
            'status' => 'Ativo',
        ];

        $this->objAnoSame->where(['id' => $id])->update($anosame);
        return redirect()->route('lista_anosame');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = $this->objAnoSame->destroy($id);
        return ($del) ? "sim" : "não";
    }
}
