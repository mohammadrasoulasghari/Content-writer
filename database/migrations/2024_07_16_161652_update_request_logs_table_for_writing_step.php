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
        Schema::table('request_logs', function (Blueprint $table) {
            $table->dropForeign(['prompt_id']);
            $table->dropColumn('prompt_id');
            $table->foreignId('writing_step_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_logs', function (Blueprint $table) {
            $table->dropForeign(['writing_step_id']);
            $table->dropColumn('writing_step_id');
            $table->foreignId('prompt_id')->constrained()->onDelete('cascade');
        });
    }
};
