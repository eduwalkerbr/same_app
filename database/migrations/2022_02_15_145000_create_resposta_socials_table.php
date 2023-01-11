<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRespostaSocialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resposta_socials', function (Blueprint $table) {
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
            $table->char('resp_q41',1)->NULLABLE()->CHANGE();
            $table->char('resp_q42',1)->NULLABLE()->CHANGE();
            $table->char('resp_q43',1)->NULLABLE()->CHANGE();
            $table->char('resp_q44',1)->NULLABLE()->CHANGE();
            $table->char('resp_q45',1)->NULLABLE()->CHANGE();
            $table->char('resp_q46',1)->NULLABLE()->CHANGE();
            $table->char('resp_q47',1)->NULLABLE()->CHANGE();
            $table->char('resp_q48',1)->NULLABLE()->CHANGE();
            $table->char('resp_q49',1)->NULLABLE()->CHANGE();
            $table->char('resp_q50',1)->NULLABLE()->CHANGE();
            $table->char('resp_q51',1)->NULLABLE()->CHANGE();
            $table->char('resp_q52',1)->NULLABLE()->CHANGE();
            $table->char('resp_q53',1)->NULLABLE()->CHANGE();
            $table->char('resp_q54',1)->NULLABLE()->CHANGE();
            $table->char('resp_q55',1)->NULLABLE()->CHANGE();
            $table->char('resp_q56',1)->NULLABLE()->CHANGE();
            $table->char('resp_q57',1)->NULLABLE()->CHANGE();
            $table->char('resp_q58',1)->NULLABLE()->CHANGE();
            $table->char('resp_q59',1)->NULLABLE()->CHANGE();
            $table->char('resp_q60',1)->NULLABLE()->CHANGE();
            $table->char('resp_q61',1)->NULLABLE()->CHANGE();
            $table->char('resp_q62',1)->NULLABLE()->CHANGE();
            $table->char('resp_q63',1)->NULLABLE()->CHANGE();
            $table->char('resp_q64',1)->NULLABLE()->CHANGE();
            $table->char('resp_q65',1)->NULLABLE()->CHANGE();
            $table->char('resp_q66',1)->NULLABLE()->CHANGE();
            $table->char('resp_q67',1)->NULLABLE()->CHANGE();
            $table->char('resp_q68',1)->NULLABLE()->CHANGE();
            $table->char('resp_q69',1)->NULLABLE()->CHANGE();
            $table->char('resp_q70',1)->NULLABLE()->CHANGE();
            $table->char('resp_q71',1)->NULLABLE()->CHANGE();
            $table->char('resp_q72',1)->NULLABLE()->CHANGE();
            $table->char('resp_q73',1)->NULLABLE()->CHANGE();
            $table->char('resp_q74',1)->NULLABLE()->CHANGE();
            $table->char('resp_q75',1)->NULLABLE()->CHANGE();
            $table->char('resp_q76',1)->NULLABLE()->CHANGE();
            $table->char('resp_q77',1)->NULLABLE()->CHANGE();
            $table->char('resp_q78',1)->NULLABLE()->CHANGE();
            $table->char('resp_q79',1)->NULLABLE()->CHANGE();
            $table->char('resp_q80',1)->NULLABLE()->CHANGE();
            $table->char('resp_q81',1)->NULLABLE()->CHANGE();
            $table->char('resp_q82',1)->NULLABLE()->CHANGE();
            $table->char('resp_q83',1)->NULLABLE()->CHANGE();
            $table->char('resp_q84',1)->NULLABLE()->CHANGE();
            $table->char('resp_q85',1)->NULLABLE()->CHANGE();
            $table->char('resp_q86',1)->NULLABLE()->CHANGE();
            $table->char('resp_q87',1)->NULLABLE()->CHANGE();
            $table->char('resp_q88',1)->NULLABLE()->CHANGE();
            $table->char('resp_q89',1)->NULLABLE()->CHANGE();
            $table->char('resp_q90',1)->NULLABLE()->CHANGE();   
           
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
        Schema::dropIfExists('resp_qosta_socials');
    }
}
