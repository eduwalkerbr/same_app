<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DestaqueModel extends Model
{
    use HasFactory;

    protected $table = 'destaque_models';

    protected $fillable = ['titulo', 'conteudo', 'descricao', 'fonte'];
}
