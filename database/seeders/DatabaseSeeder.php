<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'role' => 3,
            'school' => 'Test School',
            'password' => Hash::make('password')
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Test Teacher',
            'email' => 'teacher@test.com',
            'role' => 2,
            'school' => 'Test School',
            'password' => Hash::make('password')
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'role' => 1,
            'school' => 'Test School',
            'password' => Hash::make('password')
        ]);

        // create subjects
        \App\Models\Subject::factory()->create([
            'title' => 'Math',
            'slug' => 'math'
        ]);

        \App\Models\Subject::factory()->create([
            'title' => 'Science',
            'slug' => 'science'
        ]);

        \App\Models\Subject::factory()->create([
            'title' => 'Geography',
            'slug' => 'geography'
        ]);

        // create categories
        \App\Models\Category::factory()->create([
            'title' => 'Algebra',
            'slug' => 'algebra'
        ]);

        \App\Models\Category::factory()->create([
            'title' => 'Statistics',
            'slug' => 'statistics'
        ]);

        \App\Models\Category::factory()->create([
            'title' => 'Arithmetic',
            'slug' => 'arithmetic'
        ]);

        \App\Models\Category::factory()->create([
            'title' => 'Probability',
            'slug' => 'probability'
        ]);
    }
}
