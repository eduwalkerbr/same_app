<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // $this->call(MunicipiosTableSeeder::class);
        // $this->call(FuncaoTableSeeder::class);
        // $this->call(EscolaTableSeeder::class);
        // $this->call(TurmasTableSeeder::class);
        // $this->call(AlunosTableSeeder::class);
        // $this->call(DisciplinaTableSeeder::class);
        // $this->call(TemaTableSeeder::class);
        // $this->call(HabilidadeTableSeeder::class);
        // $this->call(QuestaoTableSeeder::class);
        // $this->call(Prova_GabaritoTableSeeder::class);
        // $this->call(ProvaTableSeeder::class);   
           $this->call(Resposta_teoricasTableSeeder::class);   
           
    }
}
