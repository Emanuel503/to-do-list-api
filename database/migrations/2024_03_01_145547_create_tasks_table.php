<?php

use App\Models\TasksStatus;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->comment("Table to record user tasks");
            $table->foreignIdFor(User::class, 'user_id_register')->constrained('users', 'id')->comment("User who registers the task");
            $table->foreignIdFor(TasksStatus::class, 'id_task_status')->constrained('tasks_statuses', 'id')->comment("Status task");
            $table->text("title")->comment("Task title")->nullable();
            $table->text("description")->comment("Task description");
            $table->text("category")->comment("Task category")->nullable();
            $table->string("color", 6)->comment("Task color")->nullable();
            $table->date("start_date")->comment("Task start date")->nullable();
            $table->date("end_date")->comment("Task end date")->nullable();
            $table->date("deleted_at")->comment("Date the task was deleted")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
