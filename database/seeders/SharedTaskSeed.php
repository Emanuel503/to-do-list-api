<?php

namespace Database\Seeders;

use App\Models\SharedTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SharedTaskSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SharedTask::factory(40)->create();
    }
}
