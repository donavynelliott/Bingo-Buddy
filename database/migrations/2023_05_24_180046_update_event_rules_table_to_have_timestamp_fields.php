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

        Schema::table('event_rules', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
        });

        Schema::table('event_rules', function (Blueprint $table) {
            $table->dateTime('start_date')->default(now()->addDays(7));
            $table->dateTime('end_date')->default(now()->addDays(38));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_rules', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->date('start_date')->after('event_id');
            $table->date('end_date')->after('start_date');
        });
    }
};
