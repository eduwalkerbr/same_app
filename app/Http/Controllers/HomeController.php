<?php

namespace App\Http\Controllers;

use App\Models\DestaqueModel;
use App\Models\Escola;
use App\Models\Legenda;
use App\Models\Municipio;
use App\Models\Previlegio;
use App\Models\Prova;
use App\Models\Prova_gabarito;
use App\Models\Questao;
use App\Models\Solicitacao;
use App\Models\Sugestao;
use App\Models\Turma;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     * Método construtor que realiza a inicilização dos objetos e classes que serão utilizados na conexão
     * de dados com o banco, além de realizar a validação se o usuário está autenticado
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objUser = new User();
        $this->objPrevilegio = new Previlegio();
        $this->objSolicitacao = new Solicitacao();
        $this->objLegenda = new Legenda();
        $this->objSugestao = new Sugestao();
    }

    /**
     * Show the application dashboard.
     * Método que disponibiliza a página inicial do site
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $previlegio = $this->objPrevilegio->where(['users_id' => auth()->user()->id])->get();
        $legendas = $this->objLegenda->all();
        $sugestoes = $this->objSugestao->orderBy('updated_at', 'desc')->paginate(2);

        //Caso seja administrados tem acesso a todas as solicitações em aberto
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

        //Caso não tenha previlégio adicionados ao usuário, acessa apenas a página inicial de boas vinda
        if (!isset($previlegio[0]->funcaos_id)) {
            return view('bem-vindo', compact('legendas', 'sugestoes'));
            //Caso seja gestor acessa apenas a página de boas vindos, com as solicitações disponíveis
        } else if ((isset($previlegio[0]->funcaos_id) && $previlegio[0]->funcaos_id == 6)) {
            return view('bem-vindo', compact('solRegistro', 'solAltCadastral', 'solAddTurma', 'legendas', 'sugestoes'));
            //Caso seja Administrador, acessa todo conteúdo do site, iniciando pelça página do secretario
        } else if (auth()->user()->perfil == 'Administrador') {
            return redirect()->route('secretario.index');
            //Caso seja secretario ou pesquisador do munícipio unijuí, inicia pela página de secretario
        } else if ($previlegio[0]->funcaos_id == 8 || (($previlegio[0]->funcaos_id == 13 || $previlegio[0]->funcaos_id == 14) && $previlegio[0]->municipios_id == 5)) {
            return redirect()->route('secretario.index');
            //Caso seja diretor, inicia pela página do diretor
        } else if ($previlegio[0]->funcaos_id == 5) {
            return redirect()->route('diretor.index');
            //Caso seja professor, inicia pela página de professor
        } else if ($previlegio[0]->funcaos_id == 7) {
            return redirect()->route('professor.index');
        }
    }
}
