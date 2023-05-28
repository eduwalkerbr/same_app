<?php

namespace App\Http\Controllers\cadastros\turma;

use App\Http\Requests\TurmaRequest;
use App\Models\AnoSame;
use App\Models\Escola;
use App\Models\Turma;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Throwable;

class TurmaController extends Controller
{
    private $objEscola;
    private $objTurma;
    private $objAnoSame;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objEscola = new Escola();
        $this->objTurma = new Turma();
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
     * Método que realiza a listagem das turmas ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem das turmas
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();

        if(Cache::has('Filtros_Consulta_Turma_'.strval(auth()->user()->id))){
            $query = Turma::query();
            $parametros = Cache::get('Filtros_Consulta_Turma_'.strval(auth()->user()->id));
            foreach($parametros as $nome => $valor){
                if($valor){
                    $query->where('turmas.'.$nome,$valor);
                }
            }
            Cache::put('Filtros_Consulta_Turma_'.strval(auth()->user()->id), $parametros, now()->addMinutes(5));
            $turmas = $query->join('municipios', ['turmas.escolas_municipios_id' => 'municipios.id', 'turmas.SAME' => 'municipios.SAME'])
                            ->join('escolas', ['turmas.escolas_id' => 'escolas.id', 'turmas.SAME' => 'escolas.SAME']) 
                            ->select('turmas.*', 'municipios.nome as nome_municipio','escolas.nome as nome_escola')
                            ->orderBy('updated_at', 'desc')->paginate(7);
        } else {
            $turmas = $this->objTurma->join('municipios', ['turmas.escolas_municipios_id' => 'municipios.id', 'turmas.SAME' => 'municipios.SAME'])
                                    ->join('escolas', ['turmas.escolas_id' => 'escolas.id', 'turmas.SAME' => 'escolas.SAME']) 
                                    ->select('turmas.*', 'municipios.nome as nome_municipio','escolas.nome as nome_escola')
                                    ->orderBy('updated_at', 'desc')->paginate(7);
        }
        
        return view('cadastro/turma/list_turma', compact('turmas','anossame'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $escolas = $this->objEscola->where(['status' => 'Ativo'])->get();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();
        return view('cadastro/turma/create_turma', compact('escolas','anosativos'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TurmaRequest $request)
    {
        try {

            $params = explode('_',$request->municipios_id);

            $data = [
                'TURMA' => trim($request->TURMA),
                'DESCR_TURMA' => trim($request->DESCR_TURMA),
                'status' => 'Ativo',
                'escolas_municipios_id' => intval($params[0]),
                'escolas_id' => intval($request->escolas_id),
                'SAME' => trim($request->SAME)
            ];

            //Valida existência do Registro
            if($this->objTurma->where([['SAME', '=', $request->SAME],['DESCR_TURMA', '=', $request->DESCR_TURMA],['escolas_municipios_id','=',$params[0]]])->get()->isNotEmpty()){
                $mensagem = 'A Turma '.$request->DESCR_TURMA.' já encontra-se cadastrada no SAME '.$request->SAME.'!';
                $status = 'error';
            } else {
                 //Realiza a inclusão do Registro
                if($this->objTurma->create($data)){
                    $mensagem = 'A Turma '.$request->DESCR_TURMA.' foi incluída com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_turma')->with(['mensagem' => $mensagem,'status' => $status]);
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
     * Método que carrega os dados do registro selecionado para edição e disponibiliza a página de cadastro em modo de edição
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $escolas = $this->objEscola->where(['status' => 'Ativo'])->get();
        $turmas = $this->objTurma->join('municipios', ['turmas.escolas_municipios_id' => 'municipios.id', 'turmas.SAME' => 'municipios.SAME'])
                                ->join('escolas', ['turmas.escolas_id' => 'escolas.id', 'turmas.SAME' => 'escolas.SAME']) 
                                ->select('turmas.*', 'municipios.id as id_municipio','municipios.nome as nome_municipio','municipios.SAME as SAME_municipio',
                                'escolas.id as id_escola','escolas.nome as nome_escola','escolas.SAME as SAME_escola')->where(['turmas.id' => $id])->get();
        $turma = $turmas[0];                        
        $anosame = $this->objAnoSame->where(['descricao' => $turma->SAME])->get();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();
        return view('cadastro/turma/create_turma', compact('turma', 'escolas','anosame','anosativos'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TurmaRequest $request, $id)
    {
        try {

            $params = explode('_',$request->municipios_id);

            $data = [
                'TURMA' => trim($request->TURMA),
                'DESCR_TURMA' => trim($request->DESCR_TURMA),
                'status' => trim($request->status),
                'escolas_municipios_id' => intval($params[0]),
                'escolas_id' => intval($request->escolas_id),
                'SAME' => trim($request->SAME)
            ];

            //Valida existência do Registro
            if($this->objTurma->where([['SAME', '=', $request->SAME],['DESCR_TURMA', '=', $request->DESCR_TURMA],['escolas_id','=',$request->escolas_id],['id','<>',$id]])->get()->isNotEmpty()){
                $mensagem = 'A Turma '.$request->DESCR_TURMA.' já encontra-se cadastrada no SAME '.$request->SAME.'!';
                $status = 'error';
            } else {
                 //Realiza a alteração do Registro
                if($this->objTurma->where(['id' => $id])->update($data)){
                    $mensagem = 'A Turma '.$request->DESCR_TURMA.' foi alterada com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_turma')->with(['mensagem' => $mensagem,'status' => $status]);
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
            $turma = $this->objTurma->find($id);
            $turma = [
                'status' => 'Inativo',
            ];
            if($this->objTurma->where(['id' => $id])->update($turma)){
                $mensagem = 'Inativação realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
        return redirect()->route('lista_turma')->with(['mensagem' => $mensagem,'status' => $status]);
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
            $turma = $this->objTurma->find($id);
            $turma = [
                'status' => 'Ativo',
            ];
            if($this->objTurma->where(['id' => $id])->update($turma)){
                $mensagem = 'Ativação realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
        return redirect()->route('lista_turma')->with(['mensagem' => $mensagem,'status' => $status]);
    }

    /**
     * Remove the specified resource from storage.
     * Método para exclusão do registro de turma
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if($this->objTurma->destroy($id)){
                $mensagem = 'Exclusão realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
        return redirect()->route('lista_turma')->with(['mensagem' => $mensagem,'status' => $status]);
    }

    /**
     * Método ajax para listar as turmas baseado na escola selecionada na página de solicitação de turma
     */
    public function get_by_same_escola(Request $request)
    {
        if (!$request->escolas_id) {
            $html = '<option value="">' . trans('') . '</option>';
        } else {
            $params = explode('_',$request->escolas_id);
            $html = '<option value=""></option>';
            $turmas = Turma::where([['escolas_id','=', $params[0]],['SAME','=',$params[1]]])->get();
            foreach ($turmas as $turma) {
                $html .= '<option value="' . $turma->id . '">' . $turma->DESCR_TURMA . ' ('.$turma->SAME.')'. '</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}
