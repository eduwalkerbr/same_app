<?php

namespace App\Http\Controllers\gestaoescolar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DirecaoProfessorRequest;
use Illuminate\Support\Facades\Cache;
use App\Models\AnoSame;
use App\Models\DirecaoProfessor;
use App\Models\Escola;
use App\Models\Previlegio;
use App\Models\Solicitacao;
use App\Models\Turma;
use App\Models\User;

class GestaoEscDirProfessorController extends Controller
{
    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objUser = new User();
        $this->objPrevilegio = new Previlegio();
        $this->objEscola = new Escola();
        $this->objTurma = new Turma();
        $this->objDirecaoProfessor = new DirecaoProfessor();
        $this->objAnoSame = new AnoSame();
        $this->objSolicitacao = new Solicitacao();
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
     * Método que realiza a listagem de direção professores ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de direção professores
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function filtrar(Request $request)
    {
        $previlegio = $this->objPrevilegio->where(['users_id' => auth()->user()->id])->get();
        $direcao_professor = $this->objDirecaoProfessor->where(['id_previlegio' => $previlegio[0]->id])->get();
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

        $query = DirecaoProfessor::query();

        //Limpa os Filtros de Consulta
        Cache::forget('Filtros_Consulta_DirecaoProfessor_'.strval(auth()->user()->id));

        //Adiciona os Filtros em Cache
        Cache::put('Filtros_Consulta_DirecaoProfessor_'.strval(auth()->user()->id), $request->only('id_previlegio','id_escola','id_turma','SAME','users_id'), now()->addMinutes(5));
        
        //Óbtem os parâmetros de Filtro da Cache
        $parametros = Cache::get('Filtros_Consulta_DirecaoProfessor_'.strval(auth()->user()->id));

        //$parametros = $request->only('id_previlegio','id_escola','id_turma','SAME','users_id');
        $query->leftjoin('escolas', ['direcao_professors.id_escola' => 'escolas.id','direcao_professors.SAME' => 'escolas.SAME'])
              ->leftjoin('turmas', ['direcao_professors.id_turma' => 'turmas.id','direcao_professors.SAME' => 'turmas.SAME']);
        $query->join('previlegios', ['direcao_professors.id_previlegio' => 'previlegios.id']);
        $query->select('direcao_professors.id', 'direcao_professors.id_previlegio','direcao_professors.id_escola','direcao_professors.id_turma',
              'direcao_professors.created_at','direcao_professors.updated_at','direcao_professors.SAME','escolas.nome as nome_escola','turmas.DESCR_TURMA as nome_turma');
        foreach($parametros as $nome => $valor){
            if($nome == 'users_id' && $valor){
               // $query->join('previlegios', 'direcao_professors.id_previlegio', '=', 'previlegios.id');
                $query->where('previlegios.users_id',$valor);
            } else if($valor){
                $query->where('direcao_professors.'.$nome,$valor);
            }
        }

        //dump($query->toSql());
        if (isset($previlegio[0]->funcaos_id) && $previlegio[0]->funcaos_id == 6) {
            $query->where('direcao_professors.id_escola',$direcao_professor[0]->id_escola);    
        }

        $direcao_professores = $query->orderBy('direcao_professors.updated_at', 'desc')->paginate(7);
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();
        if(isset($direcao_professor) && sizeof($direcao_professor) > 0){
            $escolas = $this->objEscola->where(['status' => 'Ativo'])->where(['id' => $direcao_professor[0]->id_escola])->where(['SAME' => $direcao_professor[0]->SAME])->get();
        } else {
            $escolas = $this->objEscola->where(['status' => 'Ativo'])->get();
        }
        if(auth()->user()->perfil == 'Administrador'){
            $escolas = $this->objEscola->where(['status' => 'Ativo'])->get();
        }
        $turmas = null;
        //$turmas = $this->objTurma->where(['status' => 'Ativo'])->get();
        $usuarios = $this->objUser->orderBy('name','asc')->get();

        return view('gestaoescolar/direcaoprofessor/list_gest_direcao_professor', compact('direcao_professores','anossame','escolas','turmas','usuarios','solRegistro','solAltCadastral','solAddTurma'));
    }

    /**
     * Show the application dashboard.
     * Método que realiza a listagem de direção professores ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de direção professores
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $previlegio = $this->objPrevilegio->where(['users_id' => auth()->user()->id])->get();
        $direcao_professor = $this->objDirecaoProfessor->where(['id_previlegio' => $previlegio[0]->id])->get();
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

        if(Cache::has('Filtros_Consulta_DirecaoProfessor_'.strval(auth()->user()->id))){
            $query = DirecaoProfessor::query();
            $parametros = Cache::get('Filtros_Consulta_DirecaoProfessor_'.strval(auth()->user()->id));
            $query->leftjoin('escolas', ['direcao_professors.id_escola' => 'escolas.id','direcao_professors.SAME' => 'escolas.SAME'])
                  ->leftjoin('turmas', ['direcao_professors.id_turma' => 'turmas.id','direcao_professors.SAME' => 'turmas.SAME']);
            $query->join('previlegios', ['direcao_professors.id_previlegio' => 'previlegios.id']);
            $query->select('direcao_professors.id', 'direcao_professors.id_previlegio','direcao_professors.id_escola','direcao_professors.id_turma',
                'direcao_professors.created_at','direcao_professors.updated_at','direcao_professors.SAME','escolas.nome as nome_escola','turmas.DESCR_TURMA as nome_turma');
            foreach($parametros as $nome => $valor){
                if($nome == 'users_id' && $valor){
              //      $query->join('previlegios', 'direcao_professors.id_previlegio', '=', 'previlegios.id');
                    $query->where('previlegios.users_id',$valor);
                } else if($valor){
                    $query->where('direcao_professors.'.$nome,$valor);
                }
            }
            Cache::put('Filtros_Consulta_DirecaoProfessor_'.strval(auth()->user()->id), $parametros, now()->addMinutes(5));
            if (isset($previlegio[0]->funcaos_id) && $previlegio[0]->funcaos_id == 6) {
                $query->where('direcao_professors.id_escola',$direcao_professor[0]->id_escola);    
            }
            $direcao_professores = $query->orderBy('direcao_professors.updated_at', 'desc')->paginate(7);
        } else {
            if (isset($previlegio[0]->funcaos_id) && $previlegio[0]->funcaos_id == 6) {
                $query->where('direcao_professors.id_escola',$direcao_professor[0]->id_escola);    
            }
            $direcao_professores = $this->objDirecaoProfessor->orderBy('updated_at', 'desc')->paginate(7);
        }

        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();
        if(isset($direcao_professor) && sizeof($direcao_professor) > 0){
            $escolas = $this->objEscola->where(['status' => 'Ativo'])->where(['id' => $direcao_professor[0]->id_escola])->where(['SAME' => $direcao_professor[0]->SAME])->get();
        } else {
            $escolas = $this->objEscola->where(['status' => 'Ativo'])->get();
        }
        if(auth()->user()->perfil == 'Administrador'){
            $escolas = $this->objEscola->where(['status' => 'Ativo'])->get();
        }
        $turmas = null;
        $usuarios = $this->objUser->orderBy('name','asc')->get();

        return view('gestaoescolar/direcaoprofessor/list_gest_direcao_professor', compact('direcao_professores','anossame','escolas','turmas','usuarios','solRegistro','solAltCadastral','solAddTurma'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $previlegio = $this->objPrevilegio->where(['users_id' => auth()->user()->id])->get();
        $direcao_professor = $this->objDirecaoProfessor->where(['id_previlegio' => $previlegio[0]->id])->get();

        if(isset($direcao_professor) && sizeof($direcao_professor) > 0){
            $escolas = $this->objEscola->where(['status' => 'Ativo'])->where(['id' => $direcao_professor[0]->id_escola])->where(['SAME' => $direcao_professor[0]->SAME])->get();
        } else {
            $escolas = $this->objEscola->where(['status' => 'Ativo'])->get();
        }
        $turmas = $this->objTurma->where(['status' => 'Ativo'])->get();
        $previlegios = $this->objPrevilegio->where(['status' => 1])->get();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();
        return view('gestaoescolar/direcaoprofessor/gest_direcao_professor', compact('escolas', 'turmas', 'previlegios','anosativos'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DirecaoProfessorRequest $request)
    {
        $data = [
            'id_previlegio' => $request->id_previlegio,
            'id_escola' => $request->id_escola,
            'id_turma' => $request->id_turma,
            'SAME' => $request->SAME
        ];

        $direcao_professor = $this->objDirecaoProfessor->where([['id_previlegio', '=', $request->id_previlegio],['id_escola', '=', $request->id_escola],['id_turma','=', $request->id_turma],['SAME','=',$request->SAME]])->get();
        
        if ($direcao_professor && sizeof($direcao_professor) > 0) {
            return redirect()->route('gest_direcao_professor.listar')->with('status', 'A turma selecionada já foi adicionada ao respectivo Usuário no SAME '.$request->SAME.'!');
        }

        $cad = $this->objDirecaoProfessor->create($data);


        if ($cad) {
            return redirect()->route('gest_direcao_professor.listar');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Previlegio  $previlegio
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     * Método que carrega os dados do registro selecionado para edição e disponibiliza a página de cadastro em modo de edição
     * @param  \App\Models\Previlegio  $previlegio
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $previlegio = $this->objPrevilegio->where(['users_id' => auth()->user()->id])->get();
        $direcao_professor_gestor = $this->objDirecaoProfessor->where(['id_previlegio' => $previlegio[0]->id])->get();

        if(isset($direcao_professor) && sizeof($direcao_professor) > 0){
            $escolas = $this->objEscola->where(['status' => 'Ativo'])->where(['id' => $direcao_professor[0]->id_escola])->where(['SAME' => $direcao_professor[0]->SAME])->get();
        } else {
            $escolas = $this->objEscola->where(['status' => 'Ativo'])->get();
        }
        $turmas = null;
        //$turmas = $this->objTurma->where(['status' => 'Ativo'])->get();
        $previlegios = $this->objPrevilegio->where(['status' => 1])->get();

        $direcao_professors = $this->objDirecaoProfessor
                ->leftjoin('escolas', ['direcao_professors.id_escola' => 'escolas.id','direcao_professors.SAME' => 'escolas.SAME'])
                ->leftjoin('turmas', ['direcao_professors.id_turma' => 'turmas.id','direcao_professors.SAME' => 'turmas.SAME'])
                ->join('previlegios', ['direcao_professors.id_previlegio' => 'previlegios.id'])
                ->select('direcao_professors.*','turmas.id as id_turma','turmas.DESCR_TURMA as nome_turma','turmas.SAME as SAME_turma','escolas.id as id_escola', 
                'escolas.nome as nome_escola','escolas.SAME as SAME_escola')->where(['direcao_professors.id' => $id])->get();
        $direcao_professor = $direcao_professors[0];     

        $anosame = $this->objAnoSame->where(['descricao' => $direcao_professor->SAME])->get();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();

        return view('gestaoescolar/direcaoprofessor/gest_direcao_professor', compact('escolas', 'turmas', 'previlegios', 'direcao_professor','anosativos','anosame'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DirecaoProfessorRequest $request, $id)
    {
        $id_escola = null;
        if($request->id_escola){
            $id_escola = explode('_',$request->id_escola)[0];
        }
        $data = [
            'id_previlegio' => $request->id_previlegio,
            'id_escola' => $id_escola,
            'id_turma' => $request->id_turma,
            'SAME' => $request->SAME
        ];

        $direcao_professor = $this->objDirecaoProfessor->where([['id_previlegio', '=', $request->id_previlegio],['id_escola', '=', $request->id_escola],['id_turma','=', $request->id_turma],['SAME','=',$request->SAME],['id','<>',$id]])->get();
        
        if ($direcao_professor && sizeof($direcao_professor) > 0) {
            return redirect()->route('gest_direcao_professor.listar')->with('status', 'A turma selecionada já foi adicionada ao respectivo Usuário no SAME '.$request->SAME.'!');
        }

        $this->objDirecaoProfessor->where(['id' => $id])->update($data);
        return redirect()->route('gest_direcao_professor.listar');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = $this->objDirecaoProfessor->destroy($id);
        return ($del) ? "sim" : "não";
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
            $turmas = Turma::where([['escolas_id','=', $params[0]],['SAME','=',$params[1]]])->get();
            foreach ($turmas as $turma) {
                $html .= '<option value="' . $turma->id . '">' . $turma->DESCR_TURMA . ' ('.$turma->SAME.')'. '</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}
