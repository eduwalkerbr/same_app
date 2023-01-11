<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TemaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('temas')->insert(['id'=>	1	,'desc'=>'ESPAÇO E FORMA','disciplinas_id'=>1	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	2	,'desc'=>'GEOMETRIA','disciplinas_id'=>1	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	3	,'desc'=>'GRANDEZAS E MEDIDAS','disciplinas_id'=>1	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	4	,'desc'=>'NUMÉRICO E ALGÉBRICO','disciplinas_id'=>1	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	5	,'desc'=>'NÚMEROS E OPERAÇÕES/ ÁLGEBRA E FUNÇÕES','disciplinas_id'=>1	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	6	,'desc'=>'TRATAMENTO DA INFORMAÇÃO','disciplinas_id'=>1	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	14	,'desc'=>'Leitura/ escuta/ BNCC','disciplinas_id'=>2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	15	,'desc'=>'Coerência e coesão no processamento do texto','disciplinas_id'=>2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	16	,'desc'=>'Escrita','disciplinas_id'=>2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	17	,'desc'=>'Implicações do suporte, do gênero e/ou do enunciador na compreensão do texto.','disciplinas_id'=>2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	18	,'desc'=>'Leitura','disciplinas_id'=>2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	19	,'desc'=>'Leitura/ ANA','disciplinas_id'=>2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	20	,'desc'=>'Leitura/ BNCC','disciplinas_id'=>2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	21	,'desc'=>'Procedimentos de leitura','disciplinas_id'=>2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	22	,'desc'=>'Relação entre textos','disciplinas_id'=>2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	23	,'desc'=>'Relações entre recursos expressivos e efeitos de sentido','disciplinas_id'=>2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	24	,'desc'=>'Relações entre textos','disciplinas_id'=>2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('temas')->insert(['id'=>	25	,'desc'=>'Variação linguística','disciplinas_id'=>2	, 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        
    }
}
