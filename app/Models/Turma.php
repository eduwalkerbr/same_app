<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turma extends Model
{
    use HasFactory;

    public function relMunicipios()
    {
        return $this->hasOne('App\Models\Municipio', 'id', 'escolas_municipios_id');
    }

    public function relEscolas()
    {
        return $this->hasOne('App\Models\Escola', 'id', 'escolas_id');
    }

    protected $table = 'turmas';

    protected $fillable = ['id', 'TURMA', 'DESCR_TURMA', 'escolas_municipios_id', 'escolas_id', 'SAME', 'created_at', 'updated_at'];

    public function relAlunos()
    {
        return $this->hasOne('App\Models\Aluno', 'turmas_id');
    }

    public function relSolicitacao()
    {
        return $this->hasOne('App\Models\Solicitacao', 'id_turma');
    }

    public function relDirecaoProfessores()
    {
        return $this->hasOne('App\Models\DirecaoProfessor', 'id_turma');
    }
}
