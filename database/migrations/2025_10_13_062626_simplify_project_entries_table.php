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
        Schema::table('project_entries', function (Blueprint $table) {
            // Remove the extra fields we don't want
            $table->dropColumn([
                'project_title',
                'objectives',
                'outcomes',
                'challenges',
                'learning_points'
            ]);
            
            // Rename description to activity to match log entries
            $table->renameColumn('description', 'activity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_entries', function (Blueprint $table) {
            // Add back the removed fields
            $table->string('project_title')->after('user_id');
            $table->text('objectives')->nullable()->after('activity');
            $table->text('outcomes')->nullable()->after('objectives');
            $table->text('challenges')->nullable()->after('outcomes');
            $table->text('learning_points')->nullable()->after('challenges');
            
            // Rename activity back to description
            $table->renameColumn('activity', 'description');
        });
    }
};
