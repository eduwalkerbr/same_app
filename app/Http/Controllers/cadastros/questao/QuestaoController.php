<?php

namespace App\Http\Controllers\cadastros\questao;

use App\Http\Requests\QuestaoRequest;
use App\Models\AnoSame;
use App\Models\Disciplina;
use App\Models\Habilidade;
use App\Models\Prova_gabarito;
use App\Models\Questao;
use App\Models\Tema;
use App\Models\TipoQuestao;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class QuestaoController extends Controller
{
    private $objDisciplina;
    private $objTema;
    private $objHabilidade;
    private $objProvaGabarito;
    private $objQuestao;
    private $objTipoQuestao;
    private $objAnoSame;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objDisciplina = new Disciplina();
        $this->objTema = new Tema();
        $this->objHabilidade = new Habilidade();
        $this->objProvaGabarito = new Prova_gabarito();
        $this->objQuestao = new Questao();
        $this->objTipoQuestao = new TipoQuestao();
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
     * Método que realiza a listagem das questões ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de questões
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();
        $disciplinas = $this->objDisciplina->all();
        $temas = $this->objTema->all();
        $habilidades = $this->objHabilidade->all();
        $tipoquestaos = $this->objTipoQuestao->all();
        $provas_gabaritos = $this->objProvaGabarito->where(['status' => 1])->get();
        if(Cache::has('Filtros_Consulta_Questao_'.strval(auth()->user()->id))){
            $query = Questao::query();
            $parametros = Cache::get('Filtros_Consulta_Questao_'.strval(auth()->user()->id));
            foreach($parametros as $nome => $valor){
                if($valor){
                    $query->where('questaos.'.$nome,$valor);
                }
            }
            Cache::put('Filtros_Consulta_Questao_'.strval(auth()->user()->id), $parametros, now()->addMinutes(5));
            $questaos = $query->join('habilidades', ['questaos.habilidades_id' => 'habilidades.id'])
                ->join('temas', ['questaos.temas_id' => 'temas.id'])
                ->select('questaos.*', 'habilidades.desc as nome_habilidade','temas.desc as nome_tema')
                ->orderBy('modelo', 'asc')->orderBy('disciplinas_id', 'asc')->orderBy('ano', 'asc')->orderBy('desc', 'asc')->paginate(7);
        } else {
            $questaos = $this->objQuestao->join('habilidades', ['questaos.habilidades_id' => 'habilidades.id'])
                ->join('temas', ['questaos.temas_id' => 'temas.id'])
                ->select('questaos.*', 'habilidades.desc as nome_habilidade','temas.desc as nome_tema')
                ->orderBy('modelo', 'asc')->orderBy('disciplinas_id', 'asc')->orderBy('ano', 'asc')->orderBy('desc', 'asc')->paginate(7);
        }
        return view('cadastro/questao/list_questao', compact('questaos','anossame','disciplinas','temas','habilidades','tipoquestaos','provas_gabaritos'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provas_gabaritos = $this->objProvaGabarito->where(['status' => 1])->get();
        $disciplinas = $this->objDisciplina->all();
        $temas = $this->objTema->all();
        $habilidades = $this->objHabilidade->all();
        $tipoquestaos = $this->objTipoQuestao->all();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();
        return view('cadastro/questao/create_questao', compact('provas_gabaritos', 'disciplinas', 'temas', 'habilidades', 'tipoquestaos','anosativos'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestaoRequest $request)
    {
        $data = [
            'num_questao' => $request->num_questao,
            'desc' => $request->desc,
            'correta' => $request->correta,
            'obs' => $request->obs,
            'modelo' => $request->modelo,
            'ano' => $request->ano,
            'tipo' => $request->tipo,
            'disciplinas_id' => $request->disciplinas_id,
            'temas_id' => $request->temas_id,
            'habilidades_id' => $request->habilidades_id,
            'prova_gabaritos_id' => $request->prova_gabaritos_id,
            'SAME' => $request->SAME
        ];

        //Carrega os dados da Disciplina
        $disciplina = $this->objDisciplina->find($request->disciplinas_id);

        //Verifica se já existe Questão registrada pelo Número, Modelo, Ano, Disciplina, Prova Gabarito e Same
        $questao = $this->objQuestao->where([['num_questao', '=', $request->num_questao],['modelo', '=', $request->modelo],['ano', '=', $request->ano],['disciplinas_id', '=', $request->disciplinas_id],['prova_gabaritos_id', '=', $request->prova_gabaritos_id],['SAME','=',$request->SAME]])->get();
        //Caso exista já questão
        if ($questao && sizeof($questao) > 0) {
            //Carrega os dados da Prova Gabarito pelo id e Ano SAME
            $prova_gabaritos = $this->objProvaGabarito->where([['id','=',$request->prova_gabaritos_id],['SAME','=',$request->SAME]])->get();
            $prova_gabarito = $prova_gabaritos[0];
            //Exibe a página de listagem de Questões, com mensagem informativa ao usuário
            return redirect()->route('lista_questao')->with('status', 'A Questão Número '.$request->num_questao.' da Prova '.$prova_gabarito->DESCR_PROVA.' do Ano '.$request->ano.' na Disciplina de '.$disciplina->desc.' já encontra-se Cadastrado no SAME '.$request->SAME.'!');
        }

        //Realiza a inserção da imagem
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('questao/'.$request->SAME.'/Prova_'.$request->modelo.'_'.$request->ano.'_Ano/'.$disciplina->desc);
            $data['imagem'] = $imagePath;
        }

        $cad = $this->objQuestao->create($data);

        if ($cad) {
            return redirect()->route('lista_questao');
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
    public function edit($id, $SAME)
    {
        //Obtém dados para popular filtros
        $provas_gabaritos = $this->objProvaGabarito->where(['status' => 1])->get();
        $disciplinas = $this->objDisciplina->all();
        $temas = $this->objTema->all();
        $habilidades = $this->objHabilidade->all();
        $tipoquestaos = $this->objTipoQuestao->all();

        //Obtém as questões pelo id e Ano SAME, carregando informações de prova gabarito
        $questaos = $this->objQuestao->join('prova_gabaritos', ['questaos.prova_gabaritos_id' => 'prova_gabaritos.id'])
                                     ->select('questaos.*', 'prova_gabaritos.id as id_prova_gabarito','prova_gabaritos.DESCR_PROVA as nome_prova_gabarito')
                                     ->where(['questaos.id' => $id])->where(['questaos.SAME' => $SAME])->get();
        $questao = $questaos[0];
        $anosame = $this->objAnoSame->where(['descricao' => $questao->SAME])->get();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();
        
        return view('cadastro/questao/create_questao', compact('questao', 'provas_gabaritos', 'disciplinas', 'temas', 'habilidades', 'tipoquestaos','anosame','anosativos'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuestaoRequest $request, $id)
    {
        $data = [
            'num_questao' => $request->num_questao,
            'desc' => $request->desc,
            'correta' => $request->correta,
            'obs' => $request->obs,
            'modelo' => $request->modelo,
            'tipo' => $request->tipo,
            'ano' => $request->ano,
            'disciplinas_id' => $request->disciplinas_id,
            'temas_id' => $request->temas_id,
            'habilidades_id' => $request->habilidades_id,
            'prova_gabaritos_id' => $request->prova_gabaritos_id,
            'SAME' => $request->SAME
        ];

        //Carrega dados da Disciplina
        $disciplina = $this->objDisciplina->find($request->disciplinas_id);

        //Verifica se já existe Questão registrada pelo Número, Modelo, Ano, Disciplina, Prova Gabarito e Same, que tenha id deiferente do que se está alterando
        $questao = $this->objQuestao->where([['num_questao', '=', $request->num_questao],['modelo', '=', $request->modelo],['ano', '=', $request->ano],['disciplinas_id', '=', $request->disciplinas_id],['prova_gabaritos_id', '=', $request->prova_gabaritos_id],['SAME','=',$request->SAME],['id','<>',$id]])->get();
        if ($questao && sizeof($questao) > 0) {
            //Carrega os dados da Prova Gabarito
            $prova_gabaritos = $this->objProvaGabarito->where([['id','=',$request->prova_gabaritos_id],['SAME','=',$request->SAME]])->get();
            $prova_gabarito = $prova_gabaritos[0];
            //Exibe página de listagem de Questões, com mensagem adicional de aviso ao usuário
            return redirect()->route('lista_questao')->with('status', 'A Questão Número '.$request->num_questao.' da Prova '.$prova_gabarito->DESCR_PROVA.' do Ano '.$request->ano.' na Disciplina de '.$disciplina->desc.' já encontra-se Cadastrado no SAME '.$request->SAME.'!');
        }

        //Alteração da imagem da questão
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imagePath = $request->file('image')->store('questao/'.$request->SAME.'/Prova_'.$request->modelo.'_'.$request->ano.'_Ano/'.$disciplina->desc);
            $data['imagem'] = $imagePath;
        }

        //Atualiza a Questão em BD pelo id e Ano SAME
        $this->objQuestao->where(['id' => $id])->where(['SAME' => $request->SAME])->update($data);

        //Exibe página de Listagem das Questões
        return redirect()->route('lista_questao');
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
