<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Termo extends Model
{
    use HasFactory;

    protected $table = 'termos';

    protected $fillable = ['id', 'descricao', 'arquivo', 'created_at', 'updated_at'];
}
