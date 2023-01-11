<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurmaPreviasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turma_previas', function (Blueprint $table) {
            $table->id();
            $table->string('email');
            $table->foreignId('id_escola')->nullable()->constrained('escolas')->onUpdate('cascade')->nullOnDelete();
            $table->foreignId('id_turma')->nullable()->constrained('turmas')->onUpdate('cascade')->nullOnDelete();
            $table->boolean('ativo');
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
        Schema::dropIfExists('turma_previas');
    }
}
