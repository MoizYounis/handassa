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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('experience')->length(2)->nullable()->after('type');
            $table->integer('total_project')->nullable()->after('experience');
            $table->integer('project_done_by_app')->nullable()->after('total_project');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('experience');
            $table->dropColumn('total_project');
            $table->dropColumn('project_done_by_app');
        });
    }
};
