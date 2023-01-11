<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prova extends Model
{
    use HasFactory;

    public function relProvaGabaritos()
    {
        return $this->hasOne('App\Models\Prova_gabarito', 'id', 'prova_gabaritos_id');
    }

    protected $table = 'provas';

    protected $fillable = ['id', 'status', 'respostaDoAluno', 'pontuacao', 'prova_gabaritos_id', 'prova_gabaritos_disciplinas_id', 'alunos_id', 'alunos_turmas_id', 'alunos_turmas_escolas_id', 'alunos_turmas_escolas_municipios_id', 'created_at', 'updated_at'];
}
