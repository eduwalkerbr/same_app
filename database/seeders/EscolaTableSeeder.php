<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EscolaTableSeeder extends Seeder
{
    /**
     * Run theDatabase seeds.
     *
     * @return void
     */
    public function run()
    { 
        DB::table('escolas')->insert(['id'=>7194,'nome'=>'E.M.F. PEDRO COSTA BEBER',                 'municipios_id'=>'1', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>1170,'nome'=>'E.M.E.F. Miguel Burnier',                  'municipios_id'=>'2', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>7176,'nome'=>'E.M.F.DEOLINDA BARUFALDI',                 'municipios_id'=>'3', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>7170,'nome'=>'E.M.F.DONA LEOPOLDINA',                    'municipios_id'=>'3', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>7140,'nome'=>'E.M.F.DR RUY RAMOS',                       'municipios_id'=>'3', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>7146,'nome'=>'E.M.F. ESTADODO AMAZONAS',                 'municipios_id'=>'3', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>7182,'nome'=>'E.M.F. JOAO GOULART',                      'municipios_id'=>'3', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>7152,'nome'=>'E.M.F. JOAQUIM NABUCO',                    'municipios_id'=>'3', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>7188,'nome'=>'E.M.F. JOAQUIM PORTO VILLANOVA',           'municipios_id'=>'3', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>7164,'nome'=>'E.M.F. SOARESDE BARROS',                   'municipios_id'=>'3', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>7158,'nome'=>'E.M F TOMEDE SOUZA',                       'municipios_id'=>'3', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>7110,'nome'=>'E.M.E.T.I. Eugênio Ernesto Storch',        'municipios_id'=>'3', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>7122,'nome'=>'E.M.F. 15De Novembro',                     'municipios_id'=>'3', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>7128,'nome'=>'E.M.F. Anita Garibaldi',                   'municipios_id'=>'3', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>7134,'nome'=>'E.M.F.Davi Canabarro',                     'municipios_id'=>'3', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>3246,'nome'=>'Inst. Mun.De Ensino Assis Brasil - IMEAB', 'municipios_id'=>'3', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('escolas')->insert(['id'=>3594,'nome'=>'E.M.E.F.DOM PEDRO I',                      'municipios_id'=>'4', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);

    }
}
