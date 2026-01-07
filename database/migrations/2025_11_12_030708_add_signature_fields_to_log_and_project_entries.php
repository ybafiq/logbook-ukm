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
            $table->string('supervisor_signature')->nullable()->after('approved_at');
            $table->text('supervisor_comment')->nullable()->after('supervisor_signature');
        });
        
        Schema::table('project_entries', function (Blueprint $table) {
            $table->string('supervisor_signature')->nullable()->after('approved_at');
            $table->text('supervisor_comment')->nullable()->after('supervisor_signature');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_entries', function (Blueprint $table) {
            $table->dropColumn(['supervisor_signature', 'supervisor_comment']);
        });
        
        Schema::table('project_entries', function (Blueprint $table) {
            $table->dropColumn(['supervisor_signature', 'supervisor_comment']);
        });
    }
};
