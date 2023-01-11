<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitacao extends Model
{
    use HasFactory;

    public function relTiposSolicitacao()
    {
        return $this->hasOne('App\Models\TipoSolicitacao', 'id', 'id_tipo_solicitacao');
    }
    public function relFuncoes()
    {
        return $this->hasOne('App\Models\Funcao', 'id', 'id_funcao');
    }
    public function relMunicipios()
    {
        return $this->hasOne('App\Models\Municipio', 'id', 'id_municipio');
    }
    public function relEscolas()
    {
        return $this->hasOne('App\Models\Escola', 'id', 'id_escola');
    }

    public function relTurmas()
    {
        return $this->hasOne('App\Models\Turma', 'id', 'id_turma');
    }

    protected $table = 'solicitacaos';

    protected $fillable = [
        'id', 'descricao', 'id_tipo_solicitacao', 'id_funcao', 'id_municipio', 'id_escola', 'id_turma', 'name',
        'email', 'password', 'perfil', 'aberto','SAME'
    ];
}
