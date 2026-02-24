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
        Schema::create('nebenkosten_periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->constrained()->cascadeOnDelete();
            $table->integer('year');
            $table->enum('status', ['OPEN', 'LOCKED', 'PUBLISHED'])->default('OPEN');
            $table->string('pdf_path')->nullable();
            $table->timestamps();

            $table->unique(['unit_id', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nebenkosten_periods');
    }
};
