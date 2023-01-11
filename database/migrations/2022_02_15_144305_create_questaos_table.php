<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questaos', function (Blueprint $table) {
            $table->id();
            $table->integer('num_questao');
            $table->text('desc');
            $table->text('obs');
            $table->char('modelo');
            $table->integer('ano');
            $table->text('tipo');
            $table->text('alternativa01');
            $table->text('alternativa02');
            $table->text('alternativa03');
            $table->text('alternativa04');
            $table->text('alternativa05');
            $table->text('alternativa06');
            $table->text('alternativa07');
            $table->text('alternativa08');
            $table->text('alternativa09');
            $table->text('alternativa10');
            $table->text('alternativa11');
            $table->text('alternativa12');
            $table->char('correta');
            $table->integer('peso');
            $table->string('imagem', 200);
            $table->unsignedBigInteger('temas_id');
            $table->unsignedBigInteger('habilidades_id');
            $table->foreignId('disciplinas_id')->nullable()->constrained('disciplinas')->onUpdate('cascade')->nullOnDelete();
            $table->foreignId('prova_gabaritos_id')->nullable()->constrained('prova_gabaritos')->onUpdate('cascade')->nullOnDelete();
            $table->foreignId('id_prova')->nullable()->constrained('provas')->onUpdate('cascade')->nullOnDelete();
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
        Schema::dropIfExists('questaos');
    }
}
