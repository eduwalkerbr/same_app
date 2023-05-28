<?php

namespace App\Http\Controllers\sobre;

use App\Models\Previlegio;
use App\Models\Solicitacao;
use App\Models\Sugestao;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Throwable;

class SobreController extends Controller
{
    private $objPrevilegio;
    private $objSolicitacao;
    private $objSugestao;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /**
     * Método construtor que inicializa as classes a serem utilizadas para ações de comunicação com o banco de dados
     */
    public function __construct()
    {
        $this->objPrevilegio = new Previlegio();
        $this->objSolicitacao = new Solicitacao();
        $this->objSugestao = new Sugestao();
    }

    /**
     * Show the application dashboard.
     * Método que realiza o carregamento das informações necessárias do banco e exibe a página sobre ao usuário
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            //Obtém listagem de Sugestões
            $sugestoes  = $this->objSugestao->orderBy('updated_at', 'desc')->paginate(2);
            //Se usuário autenticado
            if (Auth::check()) {
                //Obtém os previlégios do usuário
                $previlegio = $this->objPrevilegio->where(['users_id' => auth()->user()->id])->get();
                //Caso seja administrados tem acesso a todas as solicitações em aberto
                if (auth()->user()->perfil == 'Administrador') {
                    //Listagem completa das Solicitações
                    $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1])->get();
                    $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2])->get();
                    $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3])->get();
                    //Caso seja gestor, tem acesso a todas as solicitações do município que esta vinculado
                } else if (isset($previlegio[0]->funcaos_id) && $previlegio[0]->funcaos_id == 6) {
                    //Listagem de Solicitações pelo município do usuário logado
                    $solRegistro = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 1, 'id_municipio' => $previlegio[0]->municipios_id])->get();
                    $solAltCadastral = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 2, 'id_municipio' => $previlegio[0]->municipios_id])->get();
                    $solAddTurma = $this->objSolicitacao->where(['aberto' => '1'])->where(['id_tipo_solicitacao' => 3, 'id_municipio' => $previlegio[0]->municipios_id])->get();
                }
            } else {
                //Zera as solicitações  
                $solRegistro = null;    
                $solAltCadastral = null; 
                $solAddTurma = null; 
            }
        } catch (Throwable $e) {
            report($e); 
        }

        return view('sobre/sobre', compact('solRegistro', 'solAltCadastral', 'solAddTurma', 'sugestoes'));
    }

    
}
