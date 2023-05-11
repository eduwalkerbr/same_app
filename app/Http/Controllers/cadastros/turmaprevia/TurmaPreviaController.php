<?php

namespace App\Http\Controllers\cadastros\turmaprevia;

use App\Http\Requests\TurmaPreviaRequest;
use App\Models\Escola;
use App\Models\Turma;
use App\Models\TurmaPrevia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TurmaPreviaController extends Controller
{
    private $objEscola;
    private $objTurmaPrevia;
    private $objTurma;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objEscola = new Escola();
        $this->objTurma = new Turma();
        $this->objTurmaPrevia = new TurmaPrevia();
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
     * Método que realiza a listagem dos turmas prévias ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de turmas prévias
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $escolas = $this->objEscola->where(['status' => 'Ativo'])->get();
        $turmas = $this->objTurma->where(['status' => 'Ativo'])->get();
        $turmasprevias = $this->objTurmaPrevia->orderBy('updated_at', 'desc')->paginate(7);
        return view('cadastro/turma_previa/list_turma_previa', compact('turmasprevias','escolas','turmas'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $escolas = $this->objEscola->where(['status' => 'Ativo'])->get();
        return view('cadastro/turma_previa/create_turma_previa', compact('escolas'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TurmaPreviaRequest $request)
    {
        $data = [
            'email' => $request->email,
            'id_escola' => $request->id_escola,
            'id_turma' => $request->id_turma,
            'ativo' => $request->ativo,
        ];

        $turmaprevia = $this->objTurmaPrevia->where([['id_escola', '=', $request->id_escola],['id_turma', '=', $request->id_turma],
            ['email','=', $request->email],['ativo','=', $request->ativo]])->get();
        
        if ($turmaprevia && sizeof($turmaprevia) > 0) {
            return redirect()->route('lista_turma_previa')->with('status', 'A turma selecionada já foi adicionada ao respectivo Usuário!');
        }

        $cad = $this->objTurmaPrevia->create($data);


        if ($cad) {
            return redirect()->route('lista_turma_previa');
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
        $escolas = $this->objEscola->where(['status' => 'Ativo'])->get();
        $turmaprevia = $this->objTurmaPrevia->find($id);

        return view('cadastro/turma_previa/create_turma_previa', compact('turmaprevia', 'escolas'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(TurmaPreviaRequest $request, $id)
    {
        $data = [
            'email' => $request->email,
            'id_escola' => $request->id_escola,
            'id_turma' => $request->id_turma,
            'ativo' => $request->ativo,
        ];

        $turmaprevia = $this->objTurmaPrevia->where([['id_escola', '=', $request->id_escola],['id_turma', '=', $request->id_turma],
            ['email','=', $request->email],['ativo','=', $request->ativo],['id','<>',$id]])->get();
        
        if ($turmaprevia && sizeof($turmaprevia) > 0) {
            return redirect()->route('lista_turma_previa')->with('status', 'A turma selecionada já foi adicionada ao respectivo Usuário!');
        }

        $this->objTurmaPrevia->where(['id' => $id])->update($data);
        return redirect()->route('lista_turma_previa');
    }

    /**
     * Update the specified resource in storage.
     * Método para inativar o registro de turma prévia
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inativar($id)
    {
        $turmaprevia = $this->objTurmaPrevia->find($id);
        $turmaprevia = [
            'ativo' => false,
        ];

        $this->objTurmaPrevia->where(['id' => $id])->update($turmaprevia);
        return redirect()->route('lista_turma_previa');
    }

    /**
     * Update the specified resource in storage.
     * Método para ativar o registro de turma previa
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ativar($id)
    {
        $turmaprevia = $this->objTurmaPrevia->find($id);
        $turmaprevia = [
            'ativo' => true,
        ];

        $this->objTurmaPrevia->where(['id' => $id])->update($turmaprevia);
        return redirect()->route('lista_turma_previa');
    }

    /**
     * Remove the specified resource from storage.
     * Método para exclusão do registro de turma prévia
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $del = $this->objTurmaPrevia->destroy($id);
        return ($del) ? "sim" : "não";
    }

    /**
     * Método ajax que monta o select de turmas pela escola selecionada na página de cadastro de turma prévia
     */
    public function get_by_escola(Request $request)
    {

        if (!$request->id_escola) {
            $html = '<option value="">' . '' . '</option>';
        } else {
            $html = '<option value=""></option>';
            $turmas = Turma::where('escolas_id', $request->id_escola)->get();
            foreach ($turmas as $turma) {
                $html .= '<option value="' . $turma->id . '">' . $turma->DESCR_TURMA . ' ('.$turma->SAME.')'. '</option>';
            }
        }

        return response()->json(['html' => $html]);
    }
}
