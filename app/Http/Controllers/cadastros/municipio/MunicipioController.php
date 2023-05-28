<?php

namespace App\Http\Controllers\cadastros\municipio;

use App\Http\Requests\MunicipioRequest;
use App\Models\AnoSame;
use App\Models\Municipio;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;
use Throwable;

class MunicipioController extends Controller
{
    private $objMunicipio;
    private $objAnoSame;

    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->middleware('auth');
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
     * Método que realiza a listagem dos municípios ordenando para data de atualização do mesmos, e realiza a abertura da página de listagem de municípios
     * páginada de 7 em 7 registros
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function exibirLista()
    {
        if(Cache::has('Filtros_Consulta_Municipio_'.strval(auth()->user()->id))){
            $query = Municipio::query();
            $parametros = Cache::get('Filtros_Consulta_Municipio_'.strval(auth()->user()->id));
            foreach($parametros as $nome => $valor){
                if($valor){
                    $query->where('municipios.'.$nome,$valor);
                }
            }
            Cache::put('Filtros_Consulta_Municipio_'.strval(auth()->user()->id), $parametros, now()->addMinutes(5));
            $municipios = $query->orderBy('updated_at', 'desc')->paginate(7);
        } else {
            $municipios = $this->objMunicipio->orderBy('updated_at', 'desc')->paginate(7);
        }
        
        $anossame = $this->objAnoSame->orderBy('descricao', 'asc')->get();
        return view('cadastro/municipio/list_municipio', compact('municipios','anossame'));
    }

    /**
     * Show the form for creating a new resource.
     * Método que carrega os dados e disponibiliza a página para cadastro
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();
        return view('cadastro/municipio/create_municipio',compact('anosativos'));
    }

    /**
     * Store a newly created resource in storage.
     * Método que recebe os dados registrados na página de cadastro por meio do request e procede o no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MunicipioRequest $request)
    {
        try {

            $data = [
                'nome' => trim($request->nome),
                'uf' => trim($request->uf),
                'SAME' => trim($request->SAME),
                'status' => 'Ativo',
            ];

            //Valida existência do Registro
            if($this->objMunicipio->where([['nome', '=', $request->nome],['uf', '=', $request->uf],['SAME','=',$request->SAME]])->get()->isNotEmpty()){
                $mensagem = 'O Munícipio '.$request->nome.'/'.$request->uf.' já encontra-se Cadastrado no SAME '.$request->SAME.'!';
                $status = 'error';
            } else {
                 //Realiza a inclusão do Registro
                if($this->objMunicipio->create($data)){
                    $mensagem = 'O Município '.$request->nome.'/'.$request->uf.' do Ano SAME '.$request->SAME.' foi cadastrado com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_municipio')->with(['mensagem' => $mensagem,'status' => $status]);
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
        $municipios = $this->objMunicipio->where(['id' => $id])->where(['SAME' => $anosame])->get();
        $municipio = $municipios[0];
        $anosame = $this->objAnoSame->where(['descricao' => $municipio->SAME])->get();
        $anosativos = $this->objAnoSame->where(['status' => 'Ativo'])->orderBy('descricao', 'asc')->get();
        return view('cadastro/municipio/create_municipio', compact('municipio','anosame','anosativos'));
    }

    /**
     * Update the specified resource in storage.
     * Método que realiza atualização dos registros, baseado nas informação preenchidas pelo usuário, 
     * encaminhadas na request, além do id do registro, para identificar o registro sobre o qual ocorrerá o update no banco de dados
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MunicipioRequest $request, $id)
    {
        try {

            $data = [
                'nome' => trim($request->nome),
                'uf' => trim($request->uf),
                'SAME' => trim($request->SAME),
                'status' => trim($request->status),
            ];

            //Valida existência do Registro
            if($this->objMunicipio->where([['nome', '=', $request->nome],['uf', '=', $request->uf],['SAME','=',$request->SAME],['id','<>',$id]])->get()->isNotEmpty()){
                $mensagem = 'O Munícipio '.$request->nome.'/'.$request->uf.' já encontra-se Cadastrado no SAME '.$request->SAME.'!';
                $status = 'error';
            } else {
                 //Realiza a alteração do Registro
                if($this->objMunicipio->where(['id' => $id])->where(['SAME' => $request->SAME])->update($data)){
                    $mensagem = 'O Município '.$request->nome.'/'.$request->uf.' do Ano SAME '.$request->SAME.' foi alterado com Sucesso!';
                    $status = 'success';
                }   
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }

        return redirect()->route('lista_municipio')->with(['mensagem' => $mensagem,'status' => $status]);
    }

    /**
     * Update the specified resource in storage.
     * Método para inativar um registro de município
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inativar($id, $anosame)
    {
        try {
            $municipios = $this->objMunicipio->where(['id' => $id])->where(['SAME' => $anosame])->get();
            $municipio = $municipios[0];
            $municipio = [
                'status' => 'Inativo',
            ];
            if($this->objMunicipio->where(['id' => $id])->where(['SAME' => $anosame])->update($municipio)){
                $mensagem = 'Inativação realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
        return redirect()->route('lista_municipio')->with(['mensagem' => $mensagem,'status' => $status]);
    }

    /**
     * Update the specified resource in storage.
     * Método para ativar um registro de município
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function ativar($id, $anosame)
    {
        try {
            $municipios = $this->objMunicipio->where(['id' => $id])->where(['SAME' => $anosame])->get();
            $municipio = $municipios[0];
            $municipio = [
                'status' => 'Ativo',
            ];
            if($this->objMunicipio->where(['id' => $id])->where(['SAME' => $anosame])->update($municipio)){
                $mensagem = 'Ativação realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
        return redirect()->route('lista_municipio')->with(['mensagem' => $mensagem,'status' => $status]);
    }

    /**
     * Remove the specified resource from storage.
     * Método para exclusão de um registro de município
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if($this->objMunicipio->destroy($id)){
                $mensagem = 'Exclusão realizada com Sucesso.'; 
                $status = 'success';
            }
        } catch (Throwable $e) {
            $mensagem = 'Erro: '.$e; 
            $status = 'error';
        }
        
        return redirect()->route('lista_municipio')->with(['mensagem' => $mensagem,'status' => $status]);
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
}
