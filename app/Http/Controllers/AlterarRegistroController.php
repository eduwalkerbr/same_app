<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Legenda;
use App\Models\Previlegio;
use App\Models\Solicitacao;
use App\Models\Sugestao;
use App\Models\User;
use Illuminate\Http\Request;

class AlterarRegistroController extends Controller
{
    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objUser = new User();
        $this->objPrevilegio = new Previlegio();
        $this->objSolicitacao = new Solicitacao();
        $this->objLegenda = new Legenda();
        $this->objSugestao = new Sugestao();
    }

    /**
     * Show the application dashboard.
     * Método que realiza o carregamento de dados do banco e procede a abertura da página de alteração de registro
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $previlegio = $this->objPrevilegio->where(['users_id' => auth()->user()->id])->get();
        $sugestoes = $this->objSugestao->orderBy('updated_at', 'desc')->paginate(2);

        //Caso seja administrados tem acesso a todas as solicitações em aberto
        if (auth()->user()->perfil == 'Administrador') {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
            //Caso seja gestor, tem acesso a todas as solicitações do município que esta vinculado
        } else if (isset($previlegio[0]->funcaos_id) && $previlegio[0]->funcaos_id == 6) {
            $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
            $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
        }

        return view('cadastro/registro/alterar_registro', compact('solRegistro', 'solAltCadastral', 'solAddTurma', 'sugestoes'));
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Update the specified resource in storage.
     * Método que recebe os dados de request e id do registro para proceder a alteração no banco de dados
     * Ressaltar o campo de senha que faz uso de cryptografia
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'perfil' => $request->perfil,
            'password' => bcrypt($request->password),
        ];

        $this->objUser->where(['id' => $id])->update($data);

        return redirect()->route('home.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
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
