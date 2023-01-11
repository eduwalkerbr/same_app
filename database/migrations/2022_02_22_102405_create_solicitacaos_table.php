<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitacaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitacaos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->foreignId('id_tipo_solicitacao')->nullable()->constrained('tipo_solicitacaos')->onUpdate('cascade')->nullOnDelete();
            $table->foreignId('id_funcao')->nullable()->constrained('funcaos')->onUpdate('cascade')->nullOnDelete();
            $table->foreignId('id_municipio')->nullable()->constrained('municipios')->onUpdate('cascade')->nullOnDelete();
            $table->foreignId('id_escola')->nullable()->constrained('escolas')->onUpdate('cascade')->nullOnDelete();
            $table->foreignId('id_turma')->nullable()->constrained('turmas')->onUpdate('cascade')->nullOnDelete();
            $table->string('name')->nullable();
            $table->string('email');
            $table->text('perfil')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('aberto');
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
        Schema::dropIfExists('solicitacaos');
    }
}
