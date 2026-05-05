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
        Schema::table('project_contents', function (Blueprint $table) {
            $table->string('year_range')->nullable()->after('type'); // e.g., "2024", "2021-2025"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_contents', function (Blueprint $table) {
            $table->dropColumn('year_range');
        });
    }
};
