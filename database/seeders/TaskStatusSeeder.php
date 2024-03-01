<?php

namespace Database\Seeders;

use App\Models\TasksStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TasksStatus::create([
            'status' => 'Active', 
            'description' => 'The task is active and is shown to the user'
        ]);

        TasksStatus::create([
            'status' => 'Hidden', 
            'description' => 'The task is active but is not shown to the user'
        ]);

        TasksStatus::create([
            'status' => 'Deleted', 
            'description' => 'The task was deleted'
        ]);
    }
}
