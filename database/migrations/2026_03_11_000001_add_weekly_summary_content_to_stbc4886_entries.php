<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stbc4886_entries', function (Blueprint $table) {
            $table->text('weekly_summary_content')->nullable()->after('weekly_reflection_content');
        });
    }

    public function down(): void
    {
        Schema::table('stbc4886_entries', function (Blueprint $table) {
            $table->dropColumn('weekly_summary_content');
        });
    }
};
