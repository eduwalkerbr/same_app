<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CadastroController extends Controller
{
    private $objUser;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->objUser = new User();
    }

    /**
     * Show the application dashboard.
     * Método que disponibiliza as páginas de cadastro, realizando validação, onde se o usuário for diferente de Administrado, 
     * o mesmo é redirecionado para a página home
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if (Auth::user()->perfil == 'Usuário') {
            return redirect('/');

        } else {

            $users = $this->objUser->orderBy('updated_at', 'desc')->limit(3)->get();
            return view('cadastro/exibir_cadastro', compact('users'));
            
        }
    }
}
