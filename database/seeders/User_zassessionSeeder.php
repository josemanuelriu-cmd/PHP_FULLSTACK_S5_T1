<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class user_zassessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('user_zassession')->insert([
            [
                'user_id' => 1, 
                'zassession_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),                
            ],
            [
                'user_id' => 1, 
                'zassession_id' => 2, 
                'created_at' => now(),
                'updated_at' => now(),                
            ],           
            [
                'user_id' => 2, 
                'zassession_id' => 1, 
                'created_at' => now(),
                'updated_at' => now(),                
            ],
            [
                'user_id' => 2, 
                'zassession_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),                
            ]            
        ]);
    }
}

