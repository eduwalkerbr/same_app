<?php

namespace App\Http\Controllers\cadastros\solicitacao;

use App\Models\DirecaoProfessor;
use App\Models\Escola;
use App\Models\Funcao;
use App\Models\Municipio;
use App\Models\Previlegio;
use App\Models\Solicitacao;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AnoSame;

class SolicitacaoRegistroController extends Controller
{
    /**
     * Método construtor que inicializa as classes e seus objetos que serão utilizadas para comunicação com o banco de dados
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * Método para registro das solicitações de registro após sua aceitação por parte do gestor
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Obtem o Ano Same Atual
        //$anosame = $this->objAnoSame->where(['descricao' => strval(date("Y"))])->get();
        $municipios = $this->objMunicipio->where(['id' => $request->id_municipio])->where(['SAME' => $request->SAME])->get();
        $municipio = $municipios[0];

        //Realiza inicialmente o registro dos previlégio do usuário
        $dataPrevilegio = [
            'status' => 1,
            'users_id' => $request->id_user,
            'autorizou_users_id' => $request->autorizou_users_id,
            'municipios_id' => $request->id_municipio,
            'funcaos_id' => $request->id_funcao,
            'SAME' => $municipio->SAME
        ];

        //Verifica se já existe um registro igual para o mesmo usuário, ocultando a solicitação e informando o usuário
        $previlegio = $this->objPrevilegio->where([['users_id', '=', $request->users_id],['funcaos_id', '=', $request->funcaos_id],['municipios_id', '=', $request->municipios_id], ['SAME', '=', $request->SAME]])->get();
        if ($previlegio && sizeof($previlegio) > 0) {
            $usuario = $this->objUser->find($request->users_id);
            $funcao = $this->objFuncao->find($request->funcaos_id);

            //Altera o status para a solicitação não ser mais exibida
            $solicitacao = $this->objSolicitacao->find($request->id_solicitacao);
            $solicitacao = [
                'aberto' => false,
            ];
            $this->objSolicitacao->where(['id' => $request->id_solicitacao])->update($solicitacao);

            return redirect()->route('home.index')->with('status', 'O usuário '.$usuario->name.' já possuí a Função de '.$funcao->desc.' no Município de '.$municipio->nome.' no Ano de '.$request->SAME.'!');
        }

        $cadPrevilegio = $this->objPrevilegio->create($dataPrevilegio);

        //Após, verifica se o mesmo tem escola selecionada, pois nestes casos se encaixa em diretor ou professor
        if ($request->id_escola != null) {
            //Adiciona Escola e Turma da Solicitação realizada pelo usuário
            $dataDirecaoProfessor = [
                'id_previlegio' => $cadPrevilegio->id,
                'id_turma' => $request->id_turma,
                'id_escola' => $request->id_escola,
                'SAME' => $municipio->SAME
            ];

            $direcao_professor = $this->objDirecaoProfessor->where([['id_previlegio', '=', $cadPrevilegio->id],['id_escola', '=', $request->id_escola],['id_turma','=', $request->id_turma],['SAME','=',$municipio->SAME]])->get();    
            if (!$direcao_professor || sizeof($direcao_professor) <= 0) {
                $cadDirecaoProfessor = $this->objDirecaoProfessor->create($dataDirecaoProfessor);
            }

            /*Verifica se existem turmas prévias registradas para o usuário, que tenham sido aprovadas pelo gestor, 
            com a seleção dos checkbox da tabela por parte do gestor, procedendo a inclusão das escolas e turmas selecionadas
            */
            if (isset($request->selected_values)) {
                foreach ($request->selected_values as $id_turma) {
                    $turma = $this->objTurma->find($id_turma);
                    $escola = $this->objEscola->find($turma->escolas_id);

                    $dataDirecaoProfessor = [
                        'id_previlegio' => $cadPrevilegio->id,
                        'id_turma' => $turma->id,
                        'id_escola' => $escola->id,
                        'SAME' => $municipio->SAME
                    ];

                    $direcao_professor = $this->objDirecaoProfessor->where([['id_previlegio', '=', $cadPrevilegio->id],['id_escola', '=', $request->id_escola],['id_turma','=', $request->id_turma],['SAME','=',$municipio->SAME]])->get();    
                    if (!$direcao_professor || sizeof($direcao_professor) <= 0) {
                        $cadDirecaoProfessor = $this->objDirecaoProfessor->create($dataDirecaoProfessor);
                    }
                }
            }
        }

        /**
         * Por fim realiza a alteração da solicitação para que a mesma não fique mais em aberto, não sendo mais exibida ao gestor
         */
        $solicitacao = $this->objSolicitacao->find($request->id_solicitacao);
        $solicitacao = [
            'aberto' => false,
        ];

        $this->objSolicitacao->where(['id' => $request->id_solicitacao])->update($solicitacao);
        return redirect()->route('home.index');
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
