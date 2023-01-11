<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MunicipiosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {  
        DB::table('municipios')->insert(['nome'=>'Bozano','uf'  =>'RS'
                ,'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now() ]);
        DB::table('municipios')->insert(['nome'=>'Coronel Barros','uf'  =>'RS'
                ,'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now() ]);
        DB::table('municipios')->insert(['nome'=>'Ijuí','uf'  =>'RS'
                ,'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now() ]);
        DB::table('municipios')->insert(['nome'=>'Nova Ramada','uf'  =>'RS'
                ,'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now() ]);
        DB::table('municipios')->insert(['nome'=>'UNIJUI','uf'  =>'RS'
                ,'created_at'=>Carbon::now(), 'updated_at'=> Carbon::now() ]);     
     
    }
}
