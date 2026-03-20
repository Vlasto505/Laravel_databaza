<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category; // TENTO RIADOK CHÝBAL – importuješ model Category
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void {
        Category::factory()->count(10)->create();

        $this->call([
            UserSeeder::class,
            //CategorySeeder::class,
            NoteSeeder::class,
            NoteCategorySeeder::class,
        ]);
    }
}
