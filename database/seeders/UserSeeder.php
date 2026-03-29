<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::table('users')->insert([
            [
                'first_name' => 'Vlasto',
                'last_name' => 'Srnka',
                'email' => 'vlastislav.srnka@student.ukf.sk',
                'password' => Hash::make('456'),
                'role' => 'user',
                'premium_until' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now(),
            ], // Chýbala čiarka medzi poliami
            [
                'first_name' => 'Milan',
                'last_name' => 'Jokovic',
                'email' => 'milan.jokovic@student.ukf.sk',
                'password' => Hash::make('456'),
                'role' => 'user',
                'premium_until' => now()->addDays(30),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Anna',
                'last_name' => 'Veselá',
                'email' => 'anna.vesela@student.ukf.sk',
                'password' => Hash::make('456'),
                'role' => 'admin',
                'premium_until' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Peter',
                'last_name' => 'Múdry',
                'email' => 'peter.mudry@student.ukf.sk',
                'password' => Hash::make('456'),
                'role' => 'user',
                'premium_until' => now()->addDays(15),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'first_name' => 'Jana',
                'last_name' => 'Nováková',
                'email' => 'jana.novakova@student.ukf.sk',
                'password' => Hash::make('456'),
                'role' => 'user',
                'premium_until' => now()->addDays(365),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
