<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Zassession;

class ZassessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Zassession::create([
            'name' => 'Zassession 1',
            'event_name' => 'Cartas Event',
            'date' => '2026-10-10',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'max_users' => 10,
            'direction' => 'Cartas Direction',
            'latitude' => 40.7128,
            'longitude' => -74.0060,
            'created_at' => now(),
        ]);

        Zassession::create([
            'name' => 'Zassession 2',
            'event_name' => 'Ameritrash Event',
            'date' => '2026-10-11',
            'start_time' => '14:00:00',
            'end_time' => '16:00:00',
            'max_users' => 15,
            'direction' => 'Ameritrash Direction',
            'latitude' => 34.0522,
            'longitude' => -118.2437,
            'created_at' => now(),
        ]);
    }
}

