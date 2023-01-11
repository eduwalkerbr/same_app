<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirecaoProfessor extends Model
{
    use HasFactory;

    protected $table = 'direcao_professors';

    

    public function relPrevilegios()
    {
        return $this->hasOne('App\Models\Previlegio', 'id', 'id_previlegio');
    }

    public function relEscolas()
    {
        return $this->hasOne('App\Models\Escola', 'id', 'id_escola');
    }

    public function relTurmas()
    {
        return $this->hasOne('App\Models\Turma', 'id', 'id_turma');
    }

    protected $fillable = ['id_previlegio', 'id_escola', 'id_turma','SAME'];
}
