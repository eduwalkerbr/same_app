<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aluno extends Model
{
    use HasFactory;

    protected $table = 'alunos';

    public function relTurmas()
    {
        return $this->hasOne('App\Models\Turma', 'id', 'turmas_id');
    }

    public function relEscolas()
    {
        return $this->hasOne('App\Models\Escola', 'id', 'turmas_escolas_id');
    }

    public function relMunicipios()
    {
        return $this->hasOne('App\Models\Municipio', 'id', 'turmas_escolas_municipios_id');
    }

    protected $fillable = ['id', 'nome', 'created_at', 'updated_at', 'turmas_id', 'turmas_escolas_id', 'turmas_escolas_municipios_id','SAME'];
}
