<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run()
    {
        // Add 5 sample tasks
        Task::create([
            'title' => 'Learn Laravel',
            'description' => 'Practice models and controllers',
            'is_completed' => false
        ]);

        Task::create([
            'title' => 'Build API',
            'description' => 'Use api.php routes',
            'is_completed' => false
        ]);
    }
}