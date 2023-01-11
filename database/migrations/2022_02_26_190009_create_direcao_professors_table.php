<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirecaoProfessorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direcao_professors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_previlegio')->constrained('previlegios')->onUpdate('cascade')->nullOnDelete();
            $table->foreignId('id_escola')->constrained('escolas')->onUpdate('cascade')->nullOnDelete();
            $table->foreignId('id_turma')->constrained('turmas')->onUpdate('cascade')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('direcao_professors');
    }
}
