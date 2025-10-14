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
        Schema::table('log_entries', function (Blueprint $table) {
            $table->text('weekly_reflection_content')->nullable();
            $table->date('reflection_week_start')->nullable();
            $table->boolean('reflection_supervisor_signed')->default(false);
            $table->foreignId('reflection_signed_by')->nullable()->constrained('users');
            $table->timestamp('reflection_signed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_entries', function (Blueprint $table) {
            $table->dropForeign(['reflection_signed_by']);
            $table->dropColumn([
                'weekly_reflection_content',
                'reflection_week_start',
                'reflection_supervisor_signed',
                'reflection_signed_by',
                'reflection_signed_at'
            ]);
        });
    }
};
