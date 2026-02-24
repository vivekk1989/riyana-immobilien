<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('unit_utility_configs', function (Blueprint $table) {
            $table->integer('year')->default(date('Y'))->after('utility_category_id');
            // We should enforce unique config per category per unit per year
            // But first we might need to drop existing unique constraint if any.
            // Assuming previous logic didn't enforce simple unique.
            // Let's add an index for faster lookups.
            $table->index(['unit_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('unit_utility_configs', function (Blueprint $table) {
            $table->dropColumn('year');
        });
    }
};
