<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvaGabaritosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prova_gabaritos', function (Blueprint $table) {
            $table->id();
            $table->integer('ID_DISC_PROVA')->nullable();
            $table->text('DESCR_PROVA');
            $table->string('gabarito', 100);
            $table->string('ano', 40);
            $table->integer('qtd');
            $table->integer('status');
            $table->foreignId('disciplinas_id')->nullable()->constrained('disciplinas')->onUpdate('cascade')->nullOnDelete();

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
        Schema::dropIfExists('prova_gabaritos');
    }
}
