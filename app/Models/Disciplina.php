<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;
    protected $table = 'disciplinas';

    protected $fillable = ['desc', 'obs'];

    public function relHabilidades()
    {
        return $this->hasOne('App\Models\Habilidade', 'disciplinas_id');
    }

    public function relTemas()
    {
        return $this->hasOne('App\Models\Tema', 'disciplinas_id');
    }

    public function relProvaGabaritos()
    {
        return $this->hasOne('App\Models\Prova_gabarito', 'disciplinas_id');
    }
}
