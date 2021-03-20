<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('herois')->insert([
            [
                'nome' => 'Guerreiro',
                'pdv' => 12,
                'forca' => 4,
                'defesa' => 3,
                'agilidade' => 3,
                'fdd' => '2d4'
            ],
            [
                'nome' => 'Bárbaro',
                'pdv' => 13,
                'forca' => 6,
                'defesa' => 1,
                'agilidade' => 3,
                'fdd' => '2d6'
            ],
            [
                'nome' => 'Paladino',
                'pdv' => 15,
                'forca' => 2,
                'defesa' => 5,
                'agilidade' => 1,
                'fdd' => '2d4'
            ]
        ]);

        DB::table('monstros')->insert([
            [
                'nome' => 'Morto-Vivo',
                'pdv' => 25,
                'forca' => 4,
                'defesa' => 0,
                'agilidade' => 1,
                'fdd' => '2d4'
            ],
            [
                'nome' => 'Orc',
                'pdv' => 20,
                'forca' => 6,
                'defesa' => 2,
                'agilidade' => 2,
                'fdd' => '1d8'
            ],
            [
                'nome' => 'Kobold',
                'pdv' => 20,
                'forca' => 4,
                'defesa' => 2,
                'agilidade' => 4,
                'fdd' => '3d2'
            ]
        ]);
    }
}
