<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriterioQuestao extends Model
{
    use HasFactory;

    public function relDisciplinas()
    {
        return $this->hasOne('App\Models\Disciplina', 'id', 'id_disciplina');
    }

    public function relTipoQuestaos()
    {
        return $this->hasOne('App\Models\TipoQuestao', 'id', 'id_tipo_questao');
    }

    protected $table = 'criterio_questaos';

    protected $fillable = ['id_disciplina', 'nome', 'descricao', 'id_tipo_questao', 'ano', 'obs', 'created_at', 'updated_at'];
}
