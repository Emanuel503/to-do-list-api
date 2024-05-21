<?php

namespace App\Console\Commands;

use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TasksDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete daily the tasks you delete that are more than 30 days old';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dateThreshold = Carbon::now()->subDays(30);

        $tasks = Task::where('deleted_at', '<=', $dateThreshold)->get();

        $countTask = $tasks->count();

        foreach ($tasks as $task) {
            $task->shared()->delete();
            $task->forceDelete();
        }

        $this->info("Número de tareas eliminadas hace más de 30 días: $countTask");
    }
}
