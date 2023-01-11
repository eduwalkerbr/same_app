<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escola extends Model
{
    use HasFactory;

    public function relMunicipios()
    {
        return $this->hasOne('App\Models\Municipio', 'id', 'municipios_id');
    }

    protected $table = 'escolas';

    protected $fillable = ['id', 'nome', 'municipios_id','SAME'];

    public function relTurmas()
    {
        return $this->hasOne('App\Models\Turma', 'escolas_id');
    }

    public function relAlunos()
    {
        return $this->hasOne('App\Models\Aluno', 'turmas_escolas_id');
    }

    public function relSolicitacao()
    {
        return $this->hasOne('App\Models\Solicitacao', 'id_escola');
    }

    public function relDirecaoProfessores()
    {
        return $this->hasOne('App\Models\DirecaoProfessor', 'id_escola');
    }
}
