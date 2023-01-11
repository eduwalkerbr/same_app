<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnoSame extends Model
{
    use HasFactory;

    protected $table = 'ano_sames';

    protected $fillable = ['id', 'descricao', 'status', 'created_at', 'updated_at'];
}
