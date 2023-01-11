<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeslogarController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     * Método que desloga do sistema e redireciona para a rota raiz
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        Auth::logout();
        return redirect('/');
    }
}
