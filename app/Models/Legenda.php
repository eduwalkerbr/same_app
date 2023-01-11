<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Legenda extends Model
{
    use HasFactory;

    protected $table = 'legendas';

    protected $fillable = ['titulo', 'descricao', 'cor_fundo', 'cor_letra', 'exibicao', 'valor_inicial', 'valor_final'];
}
