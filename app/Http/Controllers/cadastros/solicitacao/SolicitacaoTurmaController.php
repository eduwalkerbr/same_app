<?php

namespace App\Http\Controllers\cadastros\solicitacao;

use App\Models\DirecaoProfessor;
use App\Models\Escola;
use App\Models\Previlegio;
use App\Models\Solicitacao;
use App\Models\Turma;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SolicitacaoTurmaController extends Controller
{
    private $objTurma;
    private $objSolicitacao;
    private $objDirecaoProfessor;
    private $objPrevilegio;
    private $objEscola;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->objTurma = new Turma();
        $this->objSolicitacao = new Solicitacao();
        $this->objDirecaoProfessor = new DirecaoProfessor();
        $this->objPrevilegio = new Previlegio();
        $this->objEscola = new Escola();
    }

    /**
     * Display a listing of the resource.
     * Método que exibe a tela de solicitação de turma, para aprovação por parte do Gestor
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $escolas = DB::select('SELECT esc.* FROM escolas esc WHERE esc.status = \'Ativo\' 
                              AND esc.municipios_id = (
                                SELECT prev.municipios_id FROM previlegios prev
                                WHERE prev.users_id = :id_user
                            )
                           /* AND esc.id NOT IN (
                                SELECT dp.id_escola FROM direcao_professors dp
                                INNER JOIN previlegios pdp ON pdp.id = dp.id_previlegio
                                WHERE pdp.users_id = :id_user_prev
                            )*/
        ', ['id_user' => Auth()->user()->id]);

        $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
        $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
        $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();

        return view('cadastro/solicitacao/solicitacao_turma', compact('escolas', 'solRegistro', 'solAltCadastral', 'solAddTurma'));
    }

    /**
     * Método ajax para listar as turmas baseado na escola selecionada na página de solicitação de turma
     */
    public function get_by_escola(Request $request)
    {
        if (!$request->escola_id) {
            $html = '<option value="">' . trans('') . '</option>';
        } else {
            $params = explode('_',$request->escola_id);
            $html = '<option value=""></option>';
            $turmas = Turma::where([['escolas_id','=', $params[0]],['SAME','=',$params[1]]])->orderBy('TURMA', 'asc')->get();
            foreach ($turmas as $turma) {
                $html .= '<option value="' . $turma->id . '">' . $turma->DESCR_TURMA . ' ('.$turma->SAME.')'. '</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Método ajax para listar as escolas pelo munícipio selecionada na página de solicitação de turma 
     */
    public function get_by_municipio(Request $request)
    {
        if (!$request->municipio_id) {
            $html = '<option value="">' . trans('') . '</option>';
        } else {
            $params = explode('_',$request->municipio_id);
            $html = '<option value=""></option>';
            $escolas = Escola::where([['municipios_id','=', $params[0]],['SAME','=',$params[1]]])->get();
            foreach ($escolas as $escola) {
                $html .= '<option value="' . $escola->id.'_'.$escola->SAME . '">' . $escola->nome . ' ('.$escola->SAME.')'. '</option>';
            }
        }

        return response()->json(['html' => $html]);
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
     * Método que realiza o registro das turmas aprovadas pelo gestor
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Obtém dados da Escola pelo id e Ano SAME
        $escolas = $this->objEscola->where(['id' => $request->id_escola])->where(['SAME' => $request->SAME])->get();
        $escola = $escolas[0];

        //Realiza o registro das escola e turma aprovada pelo gestor
        $previlegio = $this->objPrevilegio->where(['users_id' => $request->id_user])->get();

        //Caso na requisição tenha escola
        if ($request->filled('id_escola')) {
            $dataDirecaoProfessor = [
                'id_previlegio' => $previlegio[0]->id,
                'id_turma' => $request->id_turma,
                'id_escola' => $escola->id,
                'SAME' => $escola->SAME
            ];

            //Verifica a existência de DirecaoProfessor pelo Previlegio, Escola, Turma e Ano SAME
            $direcao_professor = $this->objDirecaoProfessor->where([['id_previlegio', '=', $previlegio[0]->id],['id_escola', '=', $request->id_escola],['id_turma','=', $request->id_turma],['SAME','=',$escola->SAME]])->get();    
            //Caso já tenha direção professor registrada
            if ($direcao_professor && sizeof($direcao_professor) > 0) {

                //Altera o registro da Solicitação para fechado, de forma a não ser mais exibido para o gestor
                $solicitacao = $this->objSolicitacao->find($request->id_solicitacao);
                $solicitacao = [
                    'aberto' => false,
                ];

                $this->objSolicitacao->where(['id' => $request->id_solicitacao])->update($solicitacao);

                //Exibe a página Home com mensagem ao Usuário
                return redirect()->route('home.index')->with('status', 'A Turma solicitada já encontra-se Registrada para o Usuário!');
            }

            //Caso não tenha turma já cadastrada, faz o registro em BD
            $cadDirecaoProfessor = $this->objDirecaoProfessor->create($dataDirecaoProfessor);
        }

        //Altera o registro da Solicitação para fechado, de forma a não ser mais exibido para o gestor
        $solicitacao = $this->objSolicitacao->find($request->id_solicitacao);
        $solicitacao = [
            'aberto' => false,
        ];

        $this->objSolicitacao->where(['id' => $request->id_solicitacao])->update($solicitacao);

        //Exibe a página Inicial
        return redirect()->route('home.index');
    }

    /**
     * Display the specified resource.
     * Método que exibe a solicitação a solicitação de turma, com os dados cadastrados, ao usuário gestor, 
     * para que realiza a aprovação visualizando os dados
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Carrega as Solicitações
        $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
        $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
        $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();

        //Carrega os dados da Solicitação selecionada
        $solicitacao = $this->objSolicitacao->find($id);
        //Caso seja Solicitação de Turma
        if ($solicitacao->id_tipo_solicitacao == 3) {
            //Carrega os dados da Escola pelo id e Ano SAME
            $escolas = $this->objEscola->where(['id' => $solicitacao->id_escola])->where(['SAME' => $solicitacao->SAME])->get();
            $escola = $escolas[0];
            //Carrega os dados da Turma
            $turma = $this->objTurma->find($solicitacao->id_turma);

            //Exibe página para autorização de Solicitação de Turma
            return view('cadastro/solicitacao/autoriza_solicitacao_turma', compact('solicitacao', 'escola', 'turma', 'solRegistro', 'solAltCadastral', 'solAddTurma'));
        }
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
