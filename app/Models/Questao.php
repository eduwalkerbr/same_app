<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questao extends Model
{
    use HasFactory;

    protected $table = 'questaos';

    public function relTemas()
    {
        return $this->hasOne('App\Models\Tema', 'id', 'temas_id');
    }

    public function relDisciplinas()
    {
        return $this->hasOne('App\Models\Disciplina', 'id', 'disciplinas_id');
    }

    public function relHabilidades()
    {
        return $this->hasOne('App\Models\Habilidade', 'id', 'habilidades_id');
    }

    public function relProvasGabaritos()
    {
        return $this->hasOne('App\Models\Prova_gabarito', 'id', 'prova_gabaritos_id');
    }

    public function relProvas()
    {
        return $this->hasOne('App\Models\Prova', 'id', 'id_prova');
    }

    public function relTipoQuestaos()
    {
        return $this->hasOne('App\Models\TipoQuestao', 'id', 'tipo');
    }

    protected $fillable = ['id', 'num_questao', 'desc', 'modelo', 'ano', 'tipo', 'temas_id', 'obs', 'imagem', 'correta', 'disciplinas_id', 'habilidades_id', 'prova_gabaritos_id', 'id_prova', 'SAME', 'created_at', 'updated_at'];
}
