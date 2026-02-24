<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->morphs('photoable'); // Creates photoable_id (bigInt) and photoable_type (string)
            $table->timestamps();
        });

        // Migrate existing data
        $properties = DB::table('properties')->whereNotNull('photo_path')->get();
        foreach ($properties as $property) {
            DB::table('photos')->insert([
                'path' => $property->photo_path,
                'photoable_id' => $property->id,
                'photoable_type' => 'App\Models\Property',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Drop the old column
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn('photo_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('photo_path')->nullable();
        });

        // Restore data
        $photos = DB::table('photos')->where('photoable_type', 'App\Models\Property')->get();
        foreach ($photos as $photo) {
            $exists = DB::table('properties')
                ->where('id', $photo->photoable_id)
                ->whereNull('photo_path')
                ->exists();

            if ($exists) {
                DB::table('properties')
                    ->where('id', $photo->photoable_id)
                    ->update(['photo_path' => $photo->path]);
            }
        }

        Schema::dropIfExists('photos');
    }
};
