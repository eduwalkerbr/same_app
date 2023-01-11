<?php

namespace App\Http\Controllers\cadastros\tema;

use App\Http\Requests\TemaRequest;
use App\Models\Disciplina;
use App\Models\Tema;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class TemaController extends Controller
{
    private $objTema;
    private $objDisciplina;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->objTema = new Tema();
        $this->objDisciplina = new Disciplina();
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
     * Método que realiza a listagem dos temas ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de temas
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        if(Cache::has('Filtros_Consulta_Tema_'.strval(auth()->user()->id))){
            $query = Tema::query();
            $parametros = Cache::get('Filtros_Consulta_Tema_'.strval(auth()->user()->id));
            foreach($parametros as $nome => $valor){
                if($valor){
                    $query->where($nome,$valor);
                }
            }
            Cache::put('Filtros_Consulta_Tema_'.strval(auth()->user()->id), $parametros, now()->addMinutes(5));
            $temas = $query->orderBy('updated_at', 'desc')->paginate(7);
        } else {
            $temas = $this->objTema->orderBy('updated_at', 'desc')->paginate(7);
        }
        $disciplinas = $this->objDisciplina->all();
        return view('cadastro/tema/list_tema', compact('temas','disciplinas'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $disciplinas = $this->objDisciplina->all();
        return view('cadastro/tema/create_tema', compact('disciplinas'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TemaRequest $request)
    {
        $data = [
            'desc' => $request->desc,
            'obs' => $request->obs,
            'disciplinas_id' => $request->disciplinas_id
        ];

        $tema = $this->objTema->where([['desc', '=', $request->desc],['disciplinas_id', '=', $request->disciplinas_id]])->get();
        
        if ($tema && sizeof($tema) > 0) {
            return redirect()->route('lista_tema')->with('status', 'O Tema '.$request->desc.' já encontra-se Cadastrado!');
        }

        $cad = $this->objTema->create($data);


        if ($cad) {
            return redirect()->route('lista_tema');
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
    public function edit($id)
    {
        $disciplinas = $this->objDisciplina->all();
        $tema = $this->objTema->find($id);

        return view('cadastro/tema/create_tema', compact('tema', 'disciplinas'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TemaRequest $request, $id)
    {
        $data = [
            'desc' => $request->desc,
            'disciplinas_id' => $request->disciplinas_id,
            'obs' => $request->obs,
        ];

        $tema = $this->objTema->where([['desc', '=', $request->desc],['disciplinas_id', '=', $request->disciplinas_id],['id','<>',$id]])->get();
        
        if ($tema && sizeof($tema) > 0) {
            return redirect()->route('lista_tema')->with('status', 'O Tema '.$request->desc.' já encontra-se Cadastrado!');
        }

        $this->objTema->where(['id' => $id])->update($data);
        return redirect()->route('lista_tema');
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
