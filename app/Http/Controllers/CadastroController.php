<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CadastroController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->objUser = new User();
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     * Método que disponibiliza as páginas de cadastro, realizando validação, onde se o usuário for diferente de Administrado, 
     * o mesmo é redirecionado para a página home
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $perfil = auth()->user()->perfil;
        if ($perfil == 'Usuário') {
            return redirect('/');
        } else {
            $users = $this->objUser->orderBy('updated_at', 'desc')->limit(3)->get();
            return view('cadastro/exibir_cadastro', compact('users'));
        }
    }
}
