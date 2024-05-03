<?php

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shared_tasks', function (Blueprint $table) {
            $table->id();
            $table->comment("Table to record shared tasks");
            $table->foreignIdFor(Task::class, 'id_task')->constrained('tasks', 'id')->comment("Shared task");
            $table->foreignIdFor(User::class, 'id_user')->constrained('users', 'id')->comment("User shared tasks");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shared_tasks');
    }
};
