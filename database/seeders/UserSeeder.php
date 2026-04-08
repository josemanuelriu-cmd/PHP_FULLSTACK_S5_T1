<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //DB::table('users')->insert([
        User::create([
            'num_partner' => 1,
            'nickname' => 'testuser',
            'name' => 'Test User',
            'password' => bcrypt('password'),
            'type' => 'admin',
            'registration_date' => now(),
            'email' => 'test@example.com',
            'telephone' => '1234567890',
            'age' => 30,
            'language' => 'es'
        ]);

        User::create([
            'num_partner' => 2,
            'nickname' => 'testuser2',
            'name' => 'Test User2',
            'password' => bcrypt('password2'),
            'type' => 'admin',
            'registration_date' => now(),
            'email' => 'test2@example.com',
            'telephone' => '123456789',
            'age' => 32,
            'language' => 'es'
        ]);

        User::create([
            'num_partner' => 3,
            'nickname' => 'testscribe',
            'name' => 'Test scribe',
            'password' => bcrypt('password'),
            'type' => 'admin',
            'registration_date' => now(),
            'email' => 'test@scribe.com',
            'telephone' => '987654321',
            'age' => 66,
            'language' => 'es'
        ]);
    }
}
