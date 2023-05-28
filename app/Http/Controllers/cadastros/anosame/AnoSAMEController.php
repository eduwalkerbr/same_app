<?php

namespace App\Http\Controllers\cadastros\anosame;

use App\Models\AnoSame;
use App\Http\Requests\AnoSAMERequest;
use App\Http\Controllers\Controller;
use Throwable;

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
        try {

            $data = [
                'descricao' => trim($request->descricao),
                'status' => trim($request->status)
            ];

            //Valida existência do Registro
            if($this->objAnoSame->where(['descricao' => $request->descricao])->get()->isNotEmpty()){
                $mensagem = 'O Ano SAME '.$request->descricao.' já foi cadastrado!';
                $status = 'error';
            } else {
                 //Realiza a inclusão do Registro
                if($this->objAnoSame->create($data)){
                    $mensagem = 'O Ano SAME '.$request->descricao.' foi cadastrada com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_anosame')->with(['mensagem' => $mensagem,'status' => $status]);
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
        try {

            $data = [
                'descricao' => trim($request->descricao),
                'status' => trim($request->status)
            ];

            //Valida existência do Registro
            if($this->objAnoSame->where(['descricao' => $request->descricao])->where('id','<>',$id)->get()->isNotEmpty()){
                $mensagem = 'O Ano SAME '.$request->descricao.' já foi cadastrado!';
                $status = 'error';
            } else {
                 //Realiza a alteração do Registro
                if($this->objAnoSame->where(['id' => $id])->update($data)){
                    $mensagem = 'O Ano SAME '.$request->descricao.' foi alterado com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_anosame')->with(['mensagem' => $mensagem,'status' => $status]);
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
        try {

            $anosame = $this->objAnoSame->find($id);
            $anosame = [
                'status' => 'Inativo',
            ];

            if($this->objAnoSame->where(['id' => $id])->update($anosame)){
                $mensagem = 'Inativação realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_anosame')->with(['mensagem' => $mensagem,'status' => $status]);
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
        try {

            $anosame = $this->objAnoSame->find($id);
            $anosame = [
                'status' => 'Ativo',
            ];

            if($this->objAnoSame->where(['id' => $id])->update($anosame)){
                $mensagem = 'Ativação realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_anosame')->with(['mensagem' => $mensagem,'status' => $status]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if($this->objAnoSame->destroy($id)){
                $mensagem = 'Exclusão realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
        return redirect()->route('lista_destaque')->with(['mensagem' => $mensagem,'status' => $status]);
    }
}
