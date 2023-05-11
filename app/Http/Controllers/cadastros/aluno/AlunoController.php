<?php

namespace App\Http\Controllers\cadastros\aluno;

use App\Http\Requests\AlunoRequest;
use App\Models\Aluno;
use App\Models\Escola;
use App\Models\Municipio;
use App\Models\Turma;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Models\AnoSame;

class AlunoController extends Controller
{
    private $objAluno;
    private $objMunicipio;
    private $objAnoSame;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objAluno = new Aluno();
        $this->objMunicipio = new Municipio();
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
     * Método que realiza a listagem dos alunos ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de alunos
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        if(Cache::has('Filtros_Consulta_Aluno_'.strval(auth()->user()->id))){
            $query = Aluno::query();
            $parametros = Cache::get('Filtros_Consulta_Aluno_'.strval(auth()->user()->id));
            foreach($parametros as $nome => $valor){
                if($valor){
                    $query->where('alunos.'.$nome,$valor);
                }
            }
            Cache::put('Filtros_Consulta_Aluno_'.strval(auth()->user()->id), $parametros, now()->addMinutes(5));
            $alunos = $query->join('municipios', ['alunos.turmas_escolas_municipios_id' => 'municipios.id', 'alunos.SAME' => 'municipios.SAME'])
                            ->join('escolas', ['alunos.turmas_escolas_id' => 'escolas.id', 'alunos.SAME' => 'escolas.SAME'])
                            ->join('turmas', ['alunos.turmas_id' => 'turmas.id', 'alunos.SAME' => 'turmas.SAME'])
                            ->select('alunos.*', 'municipios.nome as nome_municipio','escolas.nome as nome_escola','turmas.TURMA as nome_turma')
                            ->orderBy('updated_at', 'desc')->paginate(7);
        } else {
            $alunos = $this->objAluno->join('municipios', ['alunos.turmas_escolas_municipios_id' => 'municipios.id', 'alunos.SAME' => 'municipios.SAME'])
                                    ->join('escolas', ['alunos.turmas_escolas_id' => 'escolas.id', 'alunos.SAME' => 'escolas.SAME'])
                                    ->join('turmas', ['alunos.turmas_id' => 'turmas.id', 'alunos.SAME' => 'turmas.SAME'])
                                    ->select('alunos.*', 'municipios.nome as nome_municipio','escolas.nome as nome_escola','turmas.TURMA as nome_turma')
                                    ->orderBy('updated_at', 'desc')->paginate(7);
        }

        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();
        
        return view('cadastro/aluno/list_aluno', compact('alunos','anossame'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro de alunos
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();
        $municipios = $this->objMunicipio->where(['status' => 'Ativo'])->get();
        return view('cadastro/aluno/create_aluno', compact('municipios','anosativos'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AlunoRequest $request)
    {
        
        $id_escola = explode('_',$request->turmas_escolas_id)[0];
        $id_municipio = explode('_',$request->turmas_escolas_municipios_id)[0];
        $data = [
            'nome' => $request->nome,
            'turmas_id' => $request->turmas_id,
            'turmas_escolas_id' => $id_escola,
            'turmas_escolas_municipios_id' => $id_municipio,
            'SAME' => $request->SAME
        ];

        $aluno = $this->objAluno->where([['nome', '=', $request->nome],['SAME','=',$request->SAME]])->get();
        
        if ($aluno && sizeof($aluno) > 0) {
            return redirect()->route('lista_aluno')->with('status', 'O Aluno '.$request->nome.' já encontra-se Cadastrado no SAME '.$request->SAME.'!');
        }

        $cad = $this->objAluno->create($data);


        if ($cad) {
            return redirect()->route('lista_aluno');
        }
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
    public function edit($id, $ano_same)
    {
        $municipios = $this->objMunicipio->where(['status' => 'Ativo'])->get();
        $alunos = $this->objAluno->join('municipios', ['alunos.turmas_escolas_municipios_id' => 'municipios.id', 'alunos.SAME' => 'municipios.SAME'])
                                ->join('escolas', ['alunos.turmas_escolas_id' => 'escolas.id', 'alunos.SAME' => 'escolas.SAME'])
                                ->join('turmas', ['alunos.turmas_id' => 'turmas.id', 'alunos.SAME' => 'turmas.SAME'])
                                ->select('alunos.*', 'municipios.id as id_municipio','municipios.nome as nome_municipio','municipios.SAME as SAME_municipio',
                                'escolas.id as id_escola','escolas.nome as nome_escola','escolas.SAME as SAME_escola','turmas.id as id_turma','turmas.DESCR_TURMA as nome_turma','turmas.SAME as SAME_turma')
                                ->where([['alunos.id','=', $id],['alunos.SAME','=',$ano_same]])->get();
                                
        $aluno = $alunos[0];     
        $anosame = $this->objAnoSame->where(['descricao' => $aluno->SAME])->get();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();                   

        return view('cadastro/aluno/create_aluno', compact('aluno', 'municipios','anosame','anosativos'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(AlunoRequest $request, $id)
    {
        $id_escola = explode('_',$request->turmas_escolas_id)[0];
        $id_municipio = explode('_',$request->turmas_escolas_municipios_id)[0];

        $data = [
            'nome' => $request->nome,
            'turmas_id' => $request->turmas_id,
            'turmas_escolas_id' => $id_escola,
            'turmas_escolas_municipios_id' => $id_municipio,
            'SAME' => $request->SAME
        ];

        $aluno = $this->objAluno->where([['nome', '=', $request->nome],['SAME','=',$request->SAME],['id','<>',$id]])->get();
        
        if ($aluno && sizeof($aluno) > 0) {
            return redirect()->route('lista_aluno')->with('status', 'O Aluno '.$request->nome.' já encontra-se Cadastrado no SAME '.$request->SAME.'!');
        }

        $this->objAluno->where([['id','=',$id],['SAME','=',$request->SAME]])->update($data);
        return redirect()->route('lista_aluno');
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

    /**
     * Método ajax para listar os Municípios baseado no Ano SAME
     */
    public function get_by_same(Request $request)
    {
        if (!$request->SAME) {
            $html = '<option value="">' . trans('') . '</option>';
        } else {
            $html = '<option value=""></option>';
            $municipios = Municipio::where(['SAME' => $request->SAME])->get();
            foreach ($municipios as $municipio) {
                $html .= '<option value="' . $municipio->id.'_'.$municipio->SAME . '">' . $municipio->nome . ' ('.$municipio->SAME.')'. '</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
    
    /**
     * Método ajax para listar as escolas pelo munícipio selecionada na página de solicitação de turma 
     */
    public function get_by_municipio(Request $request)
    {
        if (!$request->turmas_escolas_municipios_id) {
            $html = '<option value="">' . trans('') . '</option>';
        } else {
            $params = explode('_',$request->turmas_escolas_municipios_id);
            $html = '<option value=""></option>';
            $escolas = Escola::where([['municipios_id','=', $params[0]],['SAME','=',$params[1]]])->get();
            foreach ($escolas as $escola) {
                $html .= '<option value="' . $escola->id.'_'.$escola->SAME . '">' . $escola->nome . ' ('.$escola->SAME.')'. '</option>';
            }
        }

        return response()->json(['html' => $html]);
    }

    /**
     * Método ajax para listar as turmas baseado na escola selecionada na página de solicitação de turma
     */
    public function get_by_escola(Request $request)
    {
        if (!$request->turmas_escolas_id) {
            $html = '<option value="">' . trans('') . '</option>';
        } else {
            $params = explode('_',$request->turmas_escolas_id);
            $html = '<option value=""></option>';
            $turmas = Turma::where([['escolas_id','=', $params[0]],['SAME','=',$params[1]]])->get();
            foreach ($turmas as $turma) {
                $html .= '<option value="' . $turma->id . '">' . $turma->DESCR_TURMA . ' ('.$turma->SAME.')'. '</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}
