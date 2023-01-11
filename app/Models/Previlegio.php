<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Previlegio extends Model
{
    use HasFactory;

    public function relMunicipios()
    {
        return $this->hasOne('App\Models\Municipio', 'id', 'municipios_id');
    }

    public function relUsuarios()
    {
        return $this->hasOne('App\Models\User', 'id', 'users_id');
    }

    public function relFuncaos()
    {
        return $this->hasOne('App\Models\Funcao', 'id', 'funcaos_id');
    }

    protected $table = 'previlegios';

    protected $fillable = ['status', 'funcaos_id', 'municipios_id', 'users_id', 'autorizou_users_id','SAME'];

    public function relDirecaoProfessores()
    {
        return $this->hasOne('App\Models\DirecaoProfessor', 'id_previlegio');
    }
}
