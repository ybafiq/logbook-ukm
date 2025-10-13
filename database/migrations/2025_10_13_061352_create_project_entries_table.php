<?php

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
        Schema::create('project_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // student
            $table->string('project_title'); // Title of the project
            $table->date('date');
            $table->text('description'); // Project description/what was done
            $table->text('objectives')->nullable(); // Project objectives
            $table->text('outcomes')->nullable(); // Project outcomes/results
            $table->text('challenges')->nullable(); // Challenges faced
            $table->text('learning_points')->nullable(); // Key learning points
            $table->text('comment')->nullable(); // Additional comments
            $table->boolean('supervisor_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users'); // supervisor
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_entries');
    }
};
