<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoSolicitacao extends Model
{
    use HasFactory;

    protected $table = 'tipo_solicitacaos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
    ];
    public function relSolicitacao()
    {
        return $this->hasOne('App\Models\Solicitacao', 'id_tipo_solicitacao');
    }
}
