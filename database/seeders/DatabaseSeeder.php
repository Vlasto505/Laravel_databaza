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
        $this->call([
            UserSeeder::class,
        ]);
        $categories = Category::factory(10)->create();
        $users = User::all();
        foreach ($users as $user) {
            $user->notes()->createMany(
                \App\Models\Note::factory(5)->make()->toArray()
            );
        }
        $notes = \App\Models\Note::all();
        foreach ($notes as $note) {
            $note->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->all()
            );
            $note->tasks()->createMany(
                \App\Models\Task::factory(rand(2, 6))->make()->toArray()
            );
        }
    }
}
