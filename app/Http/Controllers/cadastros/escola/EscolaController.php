<?php

namespace App\Http\Controllers\cadastros\escola;

use App\Http\Requests\EscolaRequest;
use App\Models\AnoSame;
use App\Models\Escola;
use App\Models\Municipio;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class EscolaController extends Controller
{
    private $objEscola;
    private $objMunicipio;
    private $objAnoSame;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->objEscola = new Escola();
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
     * Método que realiza a listagem das escolas ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de escolas
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $anossame = $this->objAnoSame->orderBy('descricao','asc')->get();
        if(Cache::has('Filtros_Consulta_Escola_'.strval(auth()->user()->id))){
            $query = Escola::query();
            $parametros = Cache::get('Filtros_Consulta_Escola_'.strval(auth()->user()->id));
            foreach($parametros as $nome => $valor){
                if($valor){
                    $query->where('escolas.'.$nome,$valor);
                }
            }
            Cache::put('Filtros_Consulta_Escola_'.strval(auth()->user()->id), $parametros, now()->addMinutes(5));
            $escolas = $query->join('municipios', ['escolas.municipios_id' => 'municipios.id', 'escolas.SAME' => 'municipios.SAME'])->select('escolas.*', 'municipios.nome as nome_municipio')->orderBy('updated_at', 'desc')->paginate(7);
        } else {
            $escolas = $this->objEscola->join('municipios', ['escolas.municipios_id' => 'municipios.id', 'escolas.SAME' => 'municipios.SAME'])->select('escolas.*', 'municipios.nome as nome_municipio')->orderBy('updated_at', 'desc')->paginate(7);
        }
        
        return view('cadastro/escola/list_escola', compact('escolas','anossame'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $municipios = $this->objMunicipio->where(['status' => 'Ativo'])->get();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao','asc')->get();
        return view('cadastro/escola/create_escola', compact('municipios','anosativos'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EscolaRequest $request)
    {
        $data = [
            'nome' => $request->nome,
            'SAME' => $request->SAME,
            'status' => 'Ativo',
            'municipios_id' => $request->municipios_id
        ];

        $escola = $this->objEscola->where([['nome', '=', $request->nome],['municipios_id', '=', $request->municipios_id],['SAME','=',$request->SAME]])->get();
        
        if ($escola && sizeof($escola) > 0) {
            return redirect()->route('lista_escola')->with('status', 'A Escola '.$request->nome.' já encontra-se Cadastrada no SAME '.$request->SAME.'!');
        }

        $cad = $this->objEscola->create($data);

        if ($cad) {
            return redirect()->route('lista_escola');
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
    public function edit($id, $anosame)
    {
        $municipios = $this->objMunicipio->where(['status' => 'Ativo'])->get();
        $escolas = $this->objEscola->join('municipios', ['escolas.municipios_id' => 'municipios.id', 'escolas.SAME' => 'municipios.SAME'])
                                ->select('escolas.*', 'municipios.nome as nome_municipio','municipios.id as id_municipio','municipios.SAME as SAME_municipio')
                                ->where(['escolas.id' => $id])->where(['escolas.SAME' => $anosame])->get();
        $escola = $escolas[0];
        $anosame = $this->objAnoSame->where(['descricao' => $escola->SAME])->get();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao','asc')->get();
        return view('cadastro/escola/create_escola', compact('escola', 'municipios','anosame','anosativos'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EscolaRequest $request, $id)
    {
        $data = [
            'nome' => $request->nome,
            'municipios_id' => $request->municipios_id,
            'SAME' => $request->SAME,
            'status' => $request->status,
        ];

        $escola = $this->objEscola->where([['nome', '=', $request->nome],['municipios_id', '=', $request->municipios_id],['SAME','=',$request->SAME],['id','<>',$id]])->get();
        
        if ($escola && sizeof($escola) > 0) {
            return redirect()->route('lista_municipio')->with('status', 'A Escola '.$request->nome.' já encontra-se Cadastrada no SAME '.$request->SAME.'!');
        }

        $this->objEscola->where(['id' => $id])->where(['SAME' => $request->SAME])->update($data);
        return redirect()->route('lista_escola');
    }

    /**
     * Update the specified resource in storage.
     * Método para inativar um registro de escola
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inativar($id, $anosame)
    {
        $escolas = $this->objEscola->where(['id' => $id])->where(['SAME' => $anosame])->get();
        $escola = $escolas[0];
        $escola = [
            'status' => 'Inativo',
        ];

        $this->objEscola->where(['id' => $id])->where(['SAME' => $anosame])->update($escola);
        return redirect()->route('lista_escola');
    }

    /**
     * Update the specified resource in storage.
     * Método para ativar um registro de escola
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ativar($id, $anosame)
    {
        $escolas = $this->objEscola->where(['id' => $id])->where(['SAME' => $anosame])->get();
        $escola = $escolas[0];
        $escola = [
            'status' => 'Ativo',
        ];

        $this->objEscola->where(['id' => $id])->where(['SAME' => $anosame])->update($escola);
        return redirect()->route('lista_escola');
    }

    /**
     * Remove the specified resource from storage.
     * Método para exclusão do registro de escola
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = $this->objEscola->destroy($id);
        return ($del) ? "sim" : "não";
    }

    /**
     * Método ajax para listar as turmas baseado na escola selecionada na página de solicitação de turma
     */
    public function get_by_same_municipio(Request $request)
    {
        if (!$request->SAME) {
            $html = '<option value="">' . trans('') . '</option>';
        } else {
            $html = '<option value=""></option>';
            $municipios = Municipio::where(['SAME' => $request->SAME])->get();
            foreach ($municipios as $municipio) {
                $html .= '<option value="' . $municipio->id . '">' . $municipio->nome . ' ('.$municipio->SAME.')'. '</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}
