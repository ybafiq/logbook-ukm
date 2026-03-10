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
        Schema::rename('log_entries', 'stbc4866_entries');
        Schema::rename('project_entries', 'stbc4966_entries');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('stbc4866_entries', 'log_entries');
        Schema::rename('stbc4966_entries', 'project_entries');
    }
};
