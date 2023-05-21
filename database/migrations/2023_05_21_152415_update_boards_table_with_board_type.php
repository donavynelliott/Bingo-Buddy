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
        Schema::table('bingo_boards', function (Blueprint $table) {
            // Add an enum called type where the values are 'blackout' and 'normal'
            $table->enum('type', ['blackout', 'classic'])->default('classic');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bingo_boards', function (Blueprint $table) {
            // Drop the type column
            $table->dropColumn('type');
        });
    }
};
