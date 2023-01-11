<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDadoUnificadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dado_unificados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_aluno')->constrained('alunos')->onUpdate('cascade')->nullOnDelete();
            $table->string('nome_aluno');
            $table->foreignId('id_turma')->constrained('turmas')->onUpdate('cascade')->nullOnDelete();
            $table->string('nome_turma');
            $table->string('turma_resumo');
            $table->foreignId('id_escola')->constrained('escolas')->onUpdate('cascade')->nullOnDelete();
            $table->string('nome_escola');
            $table->foreignId('id_municipio')->constrained('municipios')->onUpdate('cascade')->nullOnDelete();
            $table->string('nome_municipio');
            $table->foreignId('id_prova_gabarito')->constrained('prova_gabaritos')->onUpdate('cascade')->nullOnDelete();
            $table->string('nome_prova');
            $table->integer('ano');
            $table->string('gabarito_prova');
            $table->foreignId('id_disciplina')->constrained('disciplinas')->onUpdate('cascade')->nullOnDelete();
            $table->string('nome_disciplina');
            $table->foreignId('id_prova')->constrained('provas')->onUpdate('cascade')->nullOnDelete();
            $table->string('respostaDoAluno');
            $table->integer('pontuacao');
            $table->integer('presenca');
            $table->foreignId('id_questao')->constrained('questaos')->onUpdate('cascade')->nullOnDelete();
            $table->integer('numero_questao');
            $table->string('desc_questao');
            $table->string('tipo_questao');
            $table->string('imagem_questao')->nullable();
            $table->foreignId('id_habilidade')->constrained('habilidades')->onUpdate('cascade')->nullOnDelete();
            $table->text('nome_habilidade');
            $table->string('sigla_habilidade');
            $table->foreignId('id_tema')->constrained('temas')->onUpdate('cascade')->nullOnDelete();
            $table->string('nome_tema');
            $table->integer('acerto');
            $table->string('resposta');
            $table->string('correta');
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
        Schema::dropIfExists('dado_unificados');
    }
}
