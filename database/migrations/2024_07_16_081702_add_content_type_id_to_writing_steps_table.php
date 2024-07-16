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
        Schema::table('writing_steps', function (Blueprint $table) {
            $table->foreignId('content_type_id')->nullable()->constrained('content_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('writing_steps', function (Blueprint $table) {
            $table->dropForeign(['content_type_id']);
            $table->dropColumn('content_type_id');
        });
    }
};
