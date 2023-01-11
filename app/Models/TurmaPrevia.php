<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurmaPrevia extends Model
{
    use HasFactory;

    protected $table = 'turma_previas';

    public function relEscolas()
    {
        return $this->hasOne('App\Models\Escola', 'id', 'id_escola');
    }

    public function relTurmas()
    {
        return $this->hasOne('App\Models\Turma', 'id', 'id_turma');
    }

    protected $fillable = [
        'id', 'email', 'id_escola', 'id_turma', 'ativo'
    ];
}
