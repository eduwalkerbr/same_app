<?php

namespace App\Http\Controllers\cadastros\direcaoprofessor;

use App\Http\Requests\DirecaoProfessorRequest;
use App\Models\AnoSame;
use App\Models\DirecaoProfessor;
use App\Models\Previlegio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Throwable;

class DirecaoProfessorController extends Controller
{
    private $objUser;
    private $objPrevilegio;
    private $objDirecaoProfessor;
    private $objAnoSame;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objUser = new User();
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
     * Show the application dashboard.
     * Método que realiza a listagem de direção professores ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de direção professores
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        if(Cache::has('Filtros_Consulta_DirecaoProfessor_'.strval(auth()->user()->id))){
            $query = DirecaoProfessor::query();
            $parametros = Cache::get('Filtros_Consulta_DirecaoProfessor_'.strval(auth()->user()->id));
            $query->leftjoin('escolas', ['direcao_professors.id_escola' => 'escolas.id','direcao_professors.SAME' => 'escolas.SAME'])
                  ->leftjoin('turmas', ['direcao_professors.id_turma' => 'turmas.id','direcao_professors.SAME' => 'turmas.SAME']);
            $query->select('direcao_professors.id', 'direcao_professors.id_previlegio','direcao_professors.id_escola','direcao_professors.id_turma',
                'direcao_professors.created_at','direcao_professors.updated_at','direcao_professors.SAME','escolas.nome as nome_escola','turmas.DESCR_TURMA as nome_turma');
            foreach($parametros as $nome => $valor){
                if($nome == 'users_id' && $valor){
                    $query->join('previlegios', 'direcao_professors.id_previlegio', '=', 'previlegios.id');
                    $query->where('previlegios.users_id',$valor);
                }  else if($nome == 'escolas_id' && $valor){ 
                    $query->where('direcao_professors.'.'id_escola',$valor);
                } else if($nome == 'turmas_id' && $valor){
                    $query->where('direcao_professors.'.'id_turma',$valor);
                } else if($valor){
                    $query->where('direcao_professors.'.$nome,$valor);
                }
            }
            Cache::put('Filtros_Consulta_DirecaoProfessor_'.strval(auth()->user()->id), $parametros, now()->addMinutes(5));
            $direcao_professores = $query->orderBy('updated_at', 'desc')->paginate(7);
        } else {
            $direcao_professores = $this->objDirecaoProfessor->orderBy('updated_at', 'desc')->paginate(7);
        }
        
        $anossame = $this->objAnoSame->orderBy('descricao','asc')->get();
        $usuarios = $this->objUser->orderBy('name','asc')->get();
        return view('cadastro/direcao_professores/list_direcao_professor', compact('direcao_professores','anossame','usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $previlegios = $this->objPrevilegio->where(['status' => 1])->get();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao','asc')->get();
        return view('cadastro/direcao_professores/create_direcao_professor', compact('previlegios','anosativos'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DirecaoProfessorRequest $request)
    { 
        try {

            $id_escola = null;
            if($request->filled('escolas_id')){
                $id_escola = explode('_',$request->escolas_id)[0];
            }

            $data = [
                'id_previlegio' => intval($request->id_previlegio),
                'id_escola' => $id_escola,
                'id_turma' => $request->turmas_id,
                'SAME' => trim($request->SAME)
            ];

            if($this->objDirecaoProfessor->where([['id_previlegio', '=', $request->id_previlegio],['id_escola', '=', $id_escola],['id_turma','=', $request->turmas_id],['SAME','=',$request->SAME]])->get()->isNotEmpty()){
                $mensagem = 'A turma selecionada já foi adicionada ao respectivo Usuário no SAME '.$request->SAME.'!'; 
                $status = 'error';
            } else {
                if($this->objDirecaoProfessor->create($data)){
                    $mensagem = 'O Registro da Direção Professor incluído com sucesso.'; 
                    $status = 'success';
                }
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
        return redirect()->route('lista_direcao_professor')->with(['mensagem' => $mensagem,'status' => $status]);
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

        $turmas = null;
        $previlegios = $this->objPrevilegio->where(['status' => 1])->get();

        $direcao_professors = $this->objDirecaoProfessor
                ->leftjoin('escolas', ['direcao_professors.id_escola' => 'escolas.id','direcao_professors.SAME' => 'escolas.SAME'])
                ->leftjoin('turmas', ['direcao_professors.id_turma' => 'turmas.id','direcao_professors.SAME' => 'turmas.SAME'])
                ->leftjoin('municipios', ['turmas.escolas_municipios_id' => 'municipios.id','direcao_professors.SAME' => 'municipios.SAME'])
                ->select('direcao_professors.*','turmas.id as id_turma','turmas.DESCR_TURMA as nome_turma','turmas.SAME as SAME_turma','escolas.id as id_escola', 
                'escolas.nome as nome_escola','escolas.SAME as SAME_escola','municipios.nome as nome_municipio','municipios.id as id_municipio')
                ->where(['direcao_professors.id' => $id])->get();
        $direcao_professor = $direcao_professors[0];
        $anosame = $this->objAnoSame->where(['descricao' => $direcao_professor->SAME])->orderBy('descricao','asc')->get();

        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao','asc')->get();
        return view('cadastro/direcao_professores/create_direcao_professor', compact('turmas', 'previlegios', 'direcao_professor','anosativos','anosame'));
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
        try {

            $id_escola = null;
            if($request->filled('escolas_id')){
                $id_escola = explode('_',$request->escolas_id)[0];
            }

            $data = [
                'id_previlegio' => intval($request->id_previlegio),
                'id_escola' => $id_escola,
                'id_turma' => $request->turmas_id,
                'SAME' => trim($request->SAME)
            ];

            if($this->objDirecaoProfessor->where([['id_previlegio', '=', $request->id_previlegio],['id_escola', '=', $id_escola],['id_turma','=', $request->turmas_id],['SAME','=',$request->SAME],['id','<>',$id]])->get()->isNotEmpty()){
                $mensagem = 'A turma selecionada já foi adicionada ao respectivo Usuário no SAME '.$request->SAME.'!'; 
                $status = 'error';
            } else {
                if($this->objDirecaoProfessor->where(['id' => $id])->update($data)){
                    $mensagem = 'O Registro de Direção Professor foi alterado com sucesso.'; 
                    $status = 'success';
                }
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
        return redirect()->route('lista_direcao_professor')->with(['mensagem' => $mensagem,'status' => $status]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            if($this->objDirecaoProfessor->destroy($request->id)){
                $mensagem = 'Exclusão realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
    }

}
