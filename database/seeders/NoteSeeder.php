<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class NoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        DB::table('notes')->insert([
            [
                'user_id' => 1,
                'title' => 'Nákupný zoznam',
                'body' => 'Mlieko, chlieb, vajcia',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 2,
                'title' => 'Škola',
                'body' => 'Pripraviť sa na cvičenie z Laravela',
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 3,
                'title' => 'Práca',
                'body' => 'Odoslať mesačný report do piatku',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'title' => 'Nápady',
                'body' => 'Navrhnúť novú mobilnú aplikáciu',
                'status' => 'archived',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 5,
                'title' => 'Cvičenie',
                'body' => 'Beh 5km v parku',
                'status' => 'published',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
