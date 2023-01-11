<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FuncaoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('funcaos')->insert(['desc'=>'Diretor(a)',    'previlegio'=>'30', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now() ]);
        DB::table('funcaos')->insert(['desc'=>'Gestor(a)',     'previlegio'=>'10', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now() ]);
        DB::table('funcaos')->insert(['desc'=>'Professor(a)',  'previlegio'=>'20', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now() ]);
        DB::table('funcaos')->insert(['desc'=>'Secretario(a)', 'previlegio'=>'50', 'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now() ]);
       
    }
}
