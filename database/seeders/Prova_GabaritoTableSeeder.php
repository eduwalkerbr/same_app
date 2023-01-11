<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class Prova_GabaritoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('prova_gabaritos')->insert(['id'=>	37620	,'ID_DISC_PROVA'=>	37620	,'DESCR_PROVA'=>'2º Ano - Matemática','gabarito'=>'BACCDCADBACDBDABADAA', 'ano'=>	2	, 'qtd'=>	20	, 'disciplinas_id'=>	1	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37596	,'ID_DISC_PROVA'=>	37596	,'DESCR_PROVA'=>'2º Ano - Português','gabarito'=>'CACBDABDBADDBACAA', 'ano'=>	2	, 'qtd'=>	17	, 'disciplinas_id'=>	2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37734	,'ID_DISC_PROVA'=>	37734	,'DESCR_PROVA'=>'3º Ano - Matemática','gabarito'=>'DBADACBABDBCBCADCDAA', 'ano'=>	3	, 'qtd'=>	20	, 'disciplinas_id'=>	1	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37776	,'ID_DISC_PROVA'=>	37776	,'DESCR_PROVA'=>'3º Ano - Português','gabarito'=>'ACDBADCABDCDBBAAA', 'ano'=>	3	, 'qtd'=>	17	, 'disciplinas_id'=>	2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37740	,'ID_DISC_PROVA'=>	37740	,'DESCR_PROVA'=>'4º Ano - Matemática','gabarito'=>'CABDDBCDACBCDBADACBDABAA', 'ano'=>	4	, 'qtd'=>	24	, 'disciplinas_id'=>	1	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37782	,'ID_DISC_PROVA'=>	37782	,'DESCR_PROVA'=>'4º Ano - Português','gabarito'=>'BACBDCADCDBADBCADABCADAA', 'ano'=>	4	, 'qtd'=>	24	, 'disciplinas_id'=>	2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37746	,'ID_DISC_PROVA'=>	37746	,'DESCR_PROVA'=>'5º Ano - Matemática','gabarito'=>'ACABDCBADABDBDCBACBDACAA', 'ano'=>	5	, 'qtd'=>	24	, 'disciplinas_id'=>	1	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37788	,'ID_DISC_PROVA'=>	37788	,'DESCR_PROVA'=>'5º Ano - Português','gabarito'=>'DCBACABDCDBCADBDADCBCAA', 'ano'=>	5	, 'qtd'=>	23	, 'disciplinas_id'=>	2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37752	,'ID_DISC_PROVA'=>	37752	,'DESCR_PROVA'=>'6º Ano - Matemática','gabarito'=>'BDADADCBCBCADCDBDCBABAAA', 'ano'=>	6	, 'qtd'=>	24	, 'disciplinas_id'=>	1	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37794	,'ID_DISC_PROVA'=>	37794	,'DESCR_PROVA'=>'6º Ano - Português','gabarito'=>'CBABCDBDACDABDADCDBACBA', 'ano'=>	6	, 'qtd'=>	23	, 'disciplinas_id'=>	2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37758	,'ID_DISC_PROVA'=>	37758	,'DESCR_PROVA'=>'7º Ano - Matemática','gabarito'=>'CABDDCABACBDACDABDBDCACBDCAA', 'ano'=>	7	, 'qtd'=>	28	, 'disciplinas_id'=>	1	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37800	,'ID_DISC_PROVA'=>	37800	,'DESCR_PROVA'=>'7º Ano - Português','gabarito'=>'BDACADCDBCABDCDBACABACBDABA', 'ano'=>	7	, 'qtd'=>	27	, 'disciplinas_id'=>	2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37764	,'ID_DISC_PROVA'=>	37764	,'DESCR_PROVA'=>'8º Ano - Matemática','gabarito'=>'DBCDBACACABCDACBDABBDCACBDAA', 'ano'=>	8	, 'qtd'=>	28	, 'disciplinas_id'=>	1	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37806	,'ID_DISC_PROVA'=>	37806	,'DESCR_PROVA'=>'8º Ano - Português','gabarito'=>'ABDCBACBCADABDACDBCABDBCDAA', 'ano'=>	8	, 'qtd'=>	27	, 'disciplinas_id'=>	2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37770	,'ID_DISC_PROVA'=>	37770	,'DESCR_PROVA'=>'9º Ano - Matemática','gabarito'=>'BABDACABCBDACACDBCDACDCDABAA', 'ano'=>	9	, 'qtd'=>	28	, 'disciplinas_id'=>	1	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('prova_gabaritos')->insert(['id'=>	37812	,'ID_DISC_PROVA'=>	37812	,'DESCR_PROVA'=>'9º Ano - Português','gabarito'=>'CADBBACDCABADBDADCABADCBDCA', 'ano'=>	9	, 'qtd'=>	27	, 'disciplinas_id'=>	2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
    }
}
