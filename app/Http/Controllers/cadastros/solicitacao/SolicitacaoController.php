<?php

namespace App\Http\Controllers\cadastros\solicitacao;

use App\Http\Requests\SolicitacaoRequest;
use App\Models\DirecaoProfessor;
use App\Models\Escola;
use App\Models\Funcao;
use App\Models\Municipio;
use App\Models\Previlegio;
use App\Models\Solicitacao;
use App\Models\Turma;
use App\Models\TurmaPrevia;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SolicitacaoController extends Controller
{
    private $objSolicitacao;
    private $objUser;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->objSolicitacao = new Solicitacao();
        $this->objUser = new User();
        $this->objFuncao = new Funcao();
        $this->objMunicipio = new Municipio();
        $this->objEscola = new Escola();
        $this->objTurma = new Turma();
        $this->objPrevilegio = new Previlegio();
        $this->objDirecaoProfessor = new DirecaoProfessor();
        $this->objTurmaPrevia = new TurmaPrevia();
    }

    /**
     * 
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
     * Método para listagem de solicitações de registros de usuários
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function listar_registros_usuario()
    {
        $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
        $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
        $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();

        $solicitacoes = $this->objSolicitacao->where(['id_tipo_solicitacao' => 1])->where(['aberto' => '1'])->paginate(2);


        return view('cadastro/solicitacao/list_solicitacoes', compact('solicitacoes', 'solRegistro', 'solAltCadastral', 'solAddTurma'));
    }

    /**
     * Show the application dashboard.
     * Método para listagem das solicitações de turma
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function listar_solicitacao_turma()
    {
        $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
        $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
        $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();

        $solicitacoes = $this->objSolicitacao->where(['id_tipo_solicitacao' => 3])->where(['aberto' => '1'])->paginate(2);
        return view('cadastro/solicitacao/list_solicitacoes_turma', compact('solicitacoes', 'solRegistro', 'solAltCadastral', 'solAddTurma'));
    }


    /**
     * Show the application dashboard.
     * Método para exibição das solicitações tanto de turma como de registro de usuário para sua autorização
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function show($id)
    {
        $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
        $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
        $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();

        //Exibe solicitação de registro realizada
        $solicitacao = $this->objSolicitacao->find($id);
        if ($solicitacao->id_tipo_solicitacao == 1) {
            $funcao = $this->objFuncao->find($solicitacao->id_funcao);
            $municipio = null;
            $municipios = $this->objMunicipio->where(['id' => $solicitacao->id_municipio])->where(['SAME' => $solicitacao->SAME])->get();
            if($municipios){
                $municipio = $municipios[0];
            }

            $escola = null;
            $escolas = $this->objEscola->where(['id' => $solicitacao->id_escola])->where(['SAME' => $solicitacao->SAME])->get();
            if($escolas && sizeof($escolas) > 0){
                $escola = $escolas[0];
            }
            $turma = $this->objTurma->find($solicitacao->id_turma);
            $usuarios = $this->objUser->where(['email' => $solicitacao->email])->get();
            $turmasprevias = $this->objTurmaPrevia->where(['email' => $solicitacao->email])->whereNotIn('id_turma', [$solicitacao->id_turma])->get();
            return view('cadastro/solicitacao/autoriza_registro', compact('solicitacao', 'funcao', 'municipio', 'escola', 'turma', 'usuarios', 'solRegistro', 'solAltCadastral', 'solAddTurma', 'turmasprevias'));
        }
        //Exibe a solicitação de turma realizada
        if ($solicitacao->id_tipo_solicitacao == 3) {
            $turma = $this->objTurma->find($solicitacao->id_turma);
            $escolass = $this->objEscola->where([['id','=',$turma->escolas_id],['SAME','=',$turma->SAME]])->get();
            $escolas = $escolass[0];
            $usuarios = $this->objUser->where(['email' => $solicitacao->email])->get();
            return view('cadastro/solicitacao/autoriza_solicitacao_turma', compact('solicitacao', 'escolas', 'turma', 'usuarios', 'solRegistro', 'solAltCadastral', 'solAddTurma'));
        }
    }

    /**
     * Show the application dashboard.
     * Método que nega uma solicitação realizada, alterando a mesma para fechado, para não ser mais exibida ao gestor
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function negar($id)
    {

        $solicitacao = $this->objSolicitacao->find($id);
        $solicitacao = [
            'aberto' => false,
        ];

        $this->objSolicitacao->where(['id' => $id])->update($solicitacao);

        return redirect()->route('home.index');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(SolicitacaoRequest $request)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
