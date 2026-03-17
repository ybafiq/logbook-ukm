<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stbc4996_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->text('activity');
            $table->text('comment')->nullable();
            $table->string('image_path')->nullable();
            $table->boolean('supervisor_approved')->default(false);
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->string('supervisor_signature')->nullable();
            $table->text('supervisor_comment')->nullable();
            $table->text('weekly_reflection_content')->nullable();
            $table->date('reflection_week_start')->nullable();
            $table->boolean('reflection_supervisor_signed')->default(false);
            $table->foreignId('reflection_signed_by')->nullable()->constrained('users');
            $table->timestamp('reflection_signed_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stbc4996_entries');
    }
};
