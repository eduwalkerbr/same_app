<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DisciplinaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('disciplinas')->insert(['desc'=>'Matemática', 'obs'=>'', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);
        DB::table('disciplinas')->insert(['desc'=>'Português', 'obs'=>'', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]); 
        DB::table('disciplinas')->insert(['desc'=>'Questionário', 'obs'=>'', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now()]);     
    }
}
