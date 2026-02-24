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
        Schema::create('unit_utility_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('utility_category_id')->constrained()->cascadeOnDelete();
            $table->decimal('price_per_unit', 10, 4)->nullable(); // e.g. 0.50 € per unit
            $table->string('calculation_method')->nullable(); // Optional: description or formula identifier
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_utility_configs');
    }
};
