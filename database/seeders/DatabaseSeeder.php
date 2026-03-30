<?php

namespace Database\Seeders;

use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
//use Tests\Feature\ZasssessionTest;

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
            user_zassessionSeeder::class,
        ]);
    }
}
