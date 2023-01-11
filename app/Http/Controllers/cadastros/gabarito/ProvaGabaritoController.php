<?php

namespace App\Http\Controllers\cadastros\gabarito;

use App\Http\Requests\ProvaGabaritoRequest;
use App\Models\AnoSame;
use App\Models\Disciplina;
use App\Models\Prova_gabarito;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ProvaGabaritoController extends Controller
{
    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objDisciplina = new Disciplina();
        $this->objProvaGabarito = new Prova_gabarito();
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
     * Método que realiza a listagem das provas gabaritos ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de provas gabaritos
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        if(Cache::has('Filtros_Consulta_ProvaGabarito_'.strval(auth()->user()->id))){
            $query = Prova_gabarito::query();
            $parametros = Cache::get('Filtros_Consulta_ProvaGabarito_'.strval(auth()->user()->id));
            foreach($parametros as $nome => $valor){
                if($valor){
                    $query->where($nome,$valor);
                }
            }
            Cache::put('Filtros_Consulta_ProvaGabarito_'.strval(auth()->user()->id), $parametros, now()->addMinutes(5));
            $prova_gabaritos = $query->orderBy('updated_at', 'desc')->paginate(7);
        } else {
            $prova_gabaritos = $this->objProvaGabarito->orderBy('updated_at', 'desc')->paginate(7);
        }
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();
        $disciplinas = $this->objDisciplina->all();
       // $prova_gabaritos = $this->objProvaGabarito->orderBy('updated_at', 'desc')->paginate(7);
        return view('cadastro/prova_gabarito/list_prova_gabarito', compact('prova_gabaritos','anossame','disciplinas'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $disciplinas = $this->objDisciplina->all();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();
        return view('cadastro/prova_gabarito/create_prova_gabarito', compact('disciplinas','anosativos'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            'DESCR_PROVA' => $request->DESCR_PROVA,
            'gabarito' => $request->gabarito,
            'ano' => $request->ano,
            'qtd' => $request->qtd,
            'disciplinas_id' => $request->disciplinas_id,
            'status' => $request->status,
            'SAME' => $request->SAME
        ];

        $prova_gabarito = $this->objProvaGabarito->where([['DESCR_PROVA', '=', $request->DESCR_PROVA],['ano', '=', $request->ano],['disciplinas_id','=',$request->disciplinas_id],['SAME','=',$request->SAME]])->get();
        if ($prova_gabarito && sizeof($prova_gabarito) > 0) {
            return redirect()->route('lista_prova_gabarito')->with('status', 'O Gabarito da Prova '.$request->DESCR_PROVA.' já encontra-se Cadastrado no SAME '.$request->SAME.'!');
        }

        $cad = $this->objProvaGabarito->create($data);


        if ($cad) {
            return redirect()->route('lista_prova_gabarito');
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
     * @param  \App\Models\Previlegio  $previlegio
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $disciplinas = $this->objDisciplina->all();
        $prova_gabarito = $this->objProvaGabarito->find($id);
        $anosame = $this->objAnoSame->where(['descricao' => $prova_gabarito->SAME])->orderBy('descricao', 'asc')->get();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();

        return view('cadastro/prova_gabarito/create_prova_gabarito', compact('disciplinas', 'prova_gabarito','anosame','anosativos'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProvaGabaritoRequest $request, $id)
    {
        $data = [
            'DESCR_PROVA' => $request->DESCR_PROVA,
            'gabarito' => $request->gabarito,
            'ano' => $request->ano,
            'qtd' => $request->qtd,
            'disciplinas_id' => $request->disciplinas_id,
            'status' => $request->status,
            'SAME' => $request->SAME
        ];

        $prova_gabarito = $this->objProvaGabarito->where([['DESCR_PROVA', '=', $request->DESCR_PROVA],['ano', '=', $request->ano],['disciplinas_id','=',$request->disciplinas_id],['SAME','=',$request->SAME],['id','<>',$id]])->get();
        if ($prova_gabarito && sizeof($prova_gabarito) > 0) {
            return redirect()->route('lista_prova_gabarito')->with('status', 'O Gabarito da Prova '.$request->DESCR_PROVA.' já encontra-se Cadastrado no SAME '.$request->SAME.'!');
        }

        $this->objProvaGabarito->where(['id' => $id])->update($data);
        return redirect()->route('lista_prova_gabarito');
    }

    /**
     * Update the specified resource in storage.
     * Método para inativar um registro de prova gabarito
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inativar($id)
    {
        $prova_gabarito = $this->objProvaGabarito->find($id);
        $prova_gabarito = [
            'status' => 0,
        ];

        $this->objProvaGabarito->where(['id' => $id])->update($prova_gabarito);
        return redirect()->route('lista_prova_gabarito');
    }

    /**
     * Update the specified resource in storage.
     * Método para arivar um registro de prova gabarito
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ativar($id)
    {
        $prova_gabarito = $this->objProvaGabarito->find($id);
        $prova_gabarito = [
            'status' => 1,
        ];

        $this->objProvaGabarito->where(['id' => $id])->update($prova_gabarito);
        return redirect()->route('lista_prova_gabarito');
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
