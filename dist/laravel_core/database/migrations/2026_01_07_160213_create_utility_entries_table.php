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
        Schema::create('utility_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('utility_category_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->decimal('value', 12, 4); // The meter reading or the cost value
            $table->decimal('cost', 10, 2)->nullable(); // Calculated cost
            $table->string('proof_image_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utility_entries');
    }
};
