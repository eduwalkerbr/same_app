<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use HasFactory;
    protected $table = 'municipios';

    protected $fillable = ['nome', 'uf', 'status','SAME'];

    public function relEscolas()
    {
        return $this->hasOne('App\Models\Escola', 'municipios_id');
    }

    public function relTurmas()
    {
        return $this->hasOne('App\Models\Turma', 'escolas_municipios_id');
    }

    public function relAlunos()
    {
        return $this->hasOne('App\Models\Aluno', 'turmas_escolas_municipios_id');
    }

    public function relSolicitacao()
    {
        return $this->hasOne('App\Models\Solicitacao', 'id_municipio');
    }

    public function relPrevilegio()
    {
        return $this->hasOne('App\Models\Previlegio', 'municipios_id');
    }
}
