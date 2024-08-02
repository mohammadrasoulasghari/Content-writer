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
            $table->decimal('temperature', 2, 1)->default(0.7)->after('max_tokens'); // مقدار پیش‌فرض 0.7
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('writing_steps', function (Blueprint $table) {
            $table->dropColumn('temperature');
        });
    }
};
