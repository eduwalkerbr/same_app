<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoQuestao extends Model
{
    use HasFactory;

    protected $table = 'tipo_questaos';

    protected $fillable = ['id', 'titulo', 'descricao', 'created_at', 'updated_at'];

    public function relCriterios()
    {
        return $this->hasOne('App\Models\CriterioQuestao', 'id_tipo_criterio');
    }
}
