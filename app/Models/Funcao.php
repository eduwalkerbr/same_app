<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcao extends Model
{
    use HasFactory;
    protected $table = 'funcaos';

    protected $fillable = ['id', 'desc', 'previlegio'];

    public function relSolicitacao()
    {
        return $this->hasOne('App\Models\Solicitacao', 'id_funcao');
    }

    public function relPrevilegio()
    {
        return $this->hasOne('App\Models\Previlegio', 'funcaos_id');
    }
}
