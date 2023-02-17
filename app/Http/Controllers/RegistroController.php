<?php

namespace App\Http\Controllers;

use App\Http\Requests\SolicitacaoRequest;
use App\Models\AnoSame;
use App\Models\DirecaoProfessor;
use App\Models\Escola;
use App\Models\Funcao;
use App\Models\Municipio;
use App\Models\Previlegio;
use App\Models\Solicitacao;
use App\Models\Termo;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegistroController extends Controller
{
    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     *
     * @return void
     */
    public function __construct()
    {
        $this->objFuncao = new Funcao();
        $this->objMunicipio = new Municipio();
        $this->objEscola = new Escola();
        $this->objTurma = new Turma();
        $this->objSolicitacao = new Solicitacao();
        $this->objUser = new User();
        $this->objTermo = new Termo();
        $this->objAnoSame = new AnoSame();
        $this->objDirecaoProfessor = new DirecaoProfessor();
        $this->objPrevilegio = new Previlegio();
    }

    /**
     * Display a listing of the resource.
     * Método para disponibilizar a página de registro base
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $previlegio = $this->objPrevilegio->where(['users_id' => auth()->user()->id])->get();

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

        } else {
          $solRegistro = null;    
          $solAltCadastral = null; 
          $solAddTurma = null; 
        }

        $funcoes = $this->objFuncao->all();
        $termo = $this->objTermo->orderBy('updated_at', 'desc')->limit(1)->get();
        return view('cadastro/registro/registro_base', compact('funcoes', 'solRegistro', 'solAltCadastral', 'solAddTurma', 'termo'));
    }

    /**
     * Store a newly created resource in storage.
     * Método para disponibilizar a página de registro complementar, e de acordo com os dados preenchido, salvar em banco o registro de usuário realizado
     * Foi utilizada a mesma rota para as duas finalidade pois o sistema do laravel aceita apenas um rota store post por Controller
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::check()) {
            $previlegio = $this->objPrevilegio->where(['users_id' => auth()->user()->id])->get();

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

        } else {
          $solRegistro = null;    
          $solAltCadastral = null; 
          $solAddTurma = null; 
        }

        //Obtem o Ano Same Atual
        //$anosame = $this->objAnoSame->where(['descricao' => strval(date("Y"))])->get();
        $previlegio = $this->objPrevilegio->where(['users_id' => $request->id_user])->get();

        //Validação para Registro de Usuário
        if($request->id_tipo_solicitacao == 1){
            $usuarios = $this->objUser->where(['email' => $request->email])->get();
            if ($usuarios && sizeof($usuarios) > 0) {
                return redirect()->route('registro_base.index')->with('status', 'Já existe um Usuário registrado no Sistema com este E-mail!');
            }
        }
        
        //Validação para Registro de Turma
        if($request->id_tipo_solicitacao == 3){
            $anosame = explode('_',$request->id_escola)[1];
            $direcao_professor = $this->objDirecaoProfessor->where([['id_previlegio', '=', $previlegio[0]->id],['id_escola', '=', $request->id_escola],['id_turma','=', $request->id_turma],['SAME','=',$anosame]])->get();    
            if ($direcao_professor && sizeof($direcao_professor) > 0) {
                return redirect()->route('solicitacao_turma.index')->with('status', 'Turma já Registrada para o Presente Usuário!');
            }
        }

        if ($request->id_municipio == null) {
            $municipios = $this->objMunicipio->all();
            $escolas = $this->objEscola->all();
            $turmas = $this->objTurma->all();
            $funcao = $this->objFuncao->find($request->id_funcao);

            $name = $request->name;
            $email = $request->email;
            $password = $request->password;
            $data_base = [
                'name' => $request->name,
                'email' => $request->email,
                'senha' => $request->password,
            ];

            return view('cadastro/registro/registro_complementar', compact('funcao', 'data_base', 'municipios', 'escolas', 'turmas', 'solRegistro', 'solAltCadastral', 'solAddTurma'));
        } else {

            //Gera Parâmetros de id do Munícipio e SAME
            $params = explode('_',$request->id_municipio);

            //Obtém id da escola
            $id_escola = null;
            if($request->id_escola){
                $id_escola = explode('_',$request->id_escola)[0];
                $params[1] = explode('_',$request->id_escola)[1];
            }

            $data = [
                'descricao' => $request->descricao,
                'id_tipo_solicitacao' => $request->id_tipo_solicitacao,
                'id_funcao' => $request->id_funcao,
                'id_municipio' => $params[0],
                'id_escola' => $id_escola,
                'id_turma' => $request->id_turma,
                'name' => $request->name,
                'email' => $request->email,
                'perfil' => 'Usuário',
                'password' => bcrypt($request->password),
                'aberto' => true,
                'SAME' => $params[1]
            ];
            $cad = $this->objSolicitacao->create($data);
            $cadUser = null;
            if ($request->id_tipo_solicitacao == 1) {
                $dataUser = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'perfil' => 'Usuário',
                    'password' => bcrypt($request->password),
                ];

                $usuarios = $this->objUser->where(['email' => $request->email])->get();
                if (!$usuarios || sizeof($usuarios) <= 0) {
                    $cadUser = $this->objUser->create($dataUser);
                    //return redirect()->route('registro_base.index')->with('status', 'Já existe um Usuário registrado no Sistema com este E-mail!');
                }

                //$cadUser = $this->objUser->create($dataUser);
            }
            if ($cad && $cadUser) {
                return view('solicitacao/mensagem_registro_usuario', compact('solRegistro', 'solAltCadastral', 'solAddTurma'));
            }
            if ($cad) {
                return redirect()->route('home.index')->with('status', 'Solicitação realizada com Sucesso!');
            }
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
