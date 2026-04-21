<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BoardgameSeeder::class,
            TypeSeeder::class,
            ZassessionSeeder::class,
            GameSeeder::class,
            User_zassessionSeeder::class,
            Game_userSeeder::class,
            boardgame_typeSeeder::class,
        ]);
    }
}
