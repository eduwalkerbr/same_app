<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespostaTeoricasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resposta_teoricas', function (Blueprint $table) {
            $table->id();
            $table->char('resp_q01',1)->NULLABLE()->CHANGE();
            $table->char('resp_q02',1)->NULLABLE()->CHANGE();
            $table->char('resp_q03',1)->NULLABLE()->CHANGE();
            $table->char('resp_q04',1)->NULLABLE()->CHANGE();
            $table->char('resp_q05',1)->NULLABLE()->CHANGE();
            $table->char('resp_q06',1)->NULLABLE()->CHANGE();
            $table->char('resp_q07',1)->NULLABLE()->CHANGE();
            $table->char('resp_q08',1)->NULLABLE()->CHANGE();
            $table->char('resp_q09',1)->NULLABLE()->CHANGE();
            $table->char('resp_q10',1)->NULLABLE()->CHANGE();
            $table->char('resp_q11',1)->NULLABLE()->CHANGE();
            $table->char('resp_q12',1)->NULLABLE()->CHANGE();
            $table->char('resp_q13',1)->NULLABLE()->CHANGE();
            $table->char('resp_q14',1)->NULLABLE()->CHANGE();
            $table->char('resp_q15',1)->NULLABLE()->CHANGE();
            $table->char('resp_q16',1)->NULLABLE()->CHANGE();
            $table->char('resp_q17',1)->NULLABLE()->CHANGE();
            $table->char('resp_q18',1)->NULLABLE()->CHANGE();
            $table->char('resp_q19',1)->NULLABLE()->CHANGE();
            $table->char('resp_q20',1)->NULLABLE()->CHANGE();
            $table->char('resp_q21',1)->NULLABLE()->CHANGE();
            $table->char('resp_q22',1)->NULLABLE()->CHANGE();
            $table->char('resp_q23',1)->NULLABLE()->CHANGE();
            $table->char('resp_q24',1)->NULLABLE()->CHANGE();
            $table->char('resp_q25',1)->NULLABLE()->CHANGE();
            $table->char('resp_q26',1)->NULLABLE()->CHANGE();
            $table->char('resp_q27',1)->NULLABLE()->CHANGE();
            $table->char('resp_q28',1)->NULLABLE()->CHANGE();
            $table->char('resp_q29',1)->NULLABLE()->CHANGE();
            $table->char('resp_q30',1)->NULLABLE()->CHANGE();
            $table->char('resp_q31',1)->NULLABLE()->CHANGE();
            $table->char('resp_q32',1)->NULLABLE()->CHANGE();
            $table->char('resp_q33',1)->NULLABLE()->CHANGE();
            $table->char('resp_q34',1)->NULLABLE()->CHANGE();
            $table->char('resp_q35',1)->NULLABLE()->CHANGE();
            $table->char('resp_q36',1)->NULLABLE()->CHANGE();
            $table->char('resp_q37',1)->NULLABLE()->CHANGE();
            $table->char('resp_q38',1)->NULLABLE()->CHANGE();
            $table->char('resp_q39',1)->NULLABLE()->CHANGE();
            $table->char('resp_q40',1)->NULLABLE()->CHANGE();
     
            $table->char('acerto_q01',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q02',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q03',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q04',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q05',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q06',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q07',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q08',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q09',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q10',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q11',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q12',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q13',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q14',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q15',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q16',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q17',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q18',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q19',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q20',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q21',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q22',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q23',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q24',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q25',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q26',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q27',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q28',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q29',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q30',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q31',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q32',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q33',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q34',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q35',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q36',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q37',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q38',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q39',1)->NULLABLE()->CHANGE();
            $table->char('acerto_q40',1)->NULLABLE()->CHANGE();

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
        Schema::dropIfExists('resposta_teoricas');
    }
}
