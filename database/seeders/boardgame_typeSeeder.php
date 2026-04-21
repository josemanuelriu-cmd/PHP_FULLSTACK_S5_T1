<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class boardgame_typeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('boardgame_type')->insert([
            [
                'boardgame_id' => 1, 
                'type_id' => 8, 
            ],
            [
                'boardgame_id' => 1, 
                'type_id' => 10, 
            ],
            [
                'boardgame_id' => 1, 
                'type_id' => 12, 
            ],
            [
                'boardgame_id' => 2, 
                'type_id' => 5, 
            ],
            [
                'boardgame_id' => 2, 
                'type_id' => 10, 
            ],
            [
                'boardgame_id' => 2, 
                'type_id' => 11, 
            ],
            [
                'boardgame_id' => 3, 
                'type_id' => 1, 
            ],
            [
                'boardgame_id' => 3, 
                'type_id' => 3, 
            ],
            [
                'boardgame_id' => 3, 
                'type_id' => 10, 
            ],
            [
                'boardgame_id' => 3, 
                'type_id' => 19, 
            ],
        ]);
    }
}
