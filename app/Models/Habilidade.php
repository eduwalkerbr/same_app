<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Habilidade extends Model
{
  use HasFactory;
  //protected $fillable = ['id', 'desc', 'obs', 'disciplinas_id',' created_at', 'updated_at'];

  public function relDisciplinas()
  {
    return $this->hasOne('App\Models\Disciplina', 'id', 'disciplinas_id');
  }

  protected $table = 'habilidades';

  protected $fillable = ['desc', 'obs', 'disciplinas_id', 'created_at', 'updated_at'];
}
