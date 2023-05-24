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
        Schema::create('event_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('end_condition', ['end_date', 'all_boards_completed']);
            $table->integer('max_users');            
            $table->boolean('public');
            $table->timestamps();
        });

        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('visibility');
            $table->dropColumn('start_date');
            $table->dropColumn('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_rules');

        Schema::table('events', function (Blueprint $table) {
            $table->enum('visibility', ['public', 'private']);
            $table->date('start_date');
            $table->enum('type', ['bingo', 'raffle']);
        });
    }
};
