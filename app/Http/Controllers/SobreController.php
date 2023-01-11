<?php

namespace App\Http\Controllers;

use App\Models\Legenda;
use App\Models\Previlegio;
use App\Models\Solicitacao;
use App\Models\Sugestao;
use App\Models\User;
use Illuminate\Http\Request;

class SobreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
       // $this->middleware('auth');
        $this->objUser = new User();
        $this->objPrevilegio = new Previlegio();
        $this->objSolicitacao = new Solicitacao();
        $this->objLegenda = new Legenda();
        $this->objSugestao = new Sugestao();
    }

    /**
     * Show the application dashboard.
     * Método que realiza o carregamento das informações necessárias do banco e exibe a página sobre ao usuário
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
      //  $previlegio = $this->objPrevilegio->where(['users_id' => auth()->user()->id])->get();
        $sugestoes  = $this->objSugestao->orderBy('updated_at', 'desc')->paginate(2);

        $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
        $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
        $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();

        return view('sobre/sobre', compact('solRegistro', 'solAltCadastral', 'solAddTurma', 'sugestoes'));
       // return view('sobre/sobre', compact('solRegistro', 'solAltCadastral', 'solAddTurma', 'sugestoes'));
    }

    
}
