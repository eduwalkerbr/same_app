<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prova_gabarito extends Model
{
    use HasFactory;

    public function relDisciplinas()
    {
        return $this->hasOne('App\Models\Disciplina', 'id', 'disciplinas_id');
    }

    protected $table = 'prova_gabaritos';

    protected $fillable = ['id', 'ID_DISC_PROVA', 'DESCR_PROVA', 'gabarito', 'ano', 'qtd', 'disciplinas_id', 'status', 'SAME', 'created_at', 'updated_at'];
}
