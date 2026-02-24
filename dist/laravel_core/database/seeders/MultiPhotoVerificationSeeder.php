<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Photo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class MultiPhotoVerificationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Setup Property
        $property = Property::firstOrCreate(
            ['address' => 'Test Property Photos Setup by Seeder'],
            ['type' => 'Single Family Home']
        );

        // 2. Setup Unit
        $unit = Unit::firstOrCreate(
            [
                'property_id' => $property->id,
                'unit_number' => '101-Test'
            ],
            [
                'floor' => 1,
                'size' => 100,
                'price' => 1500,
                'status' => 'for_rent'
            ]
        );

        // 3. Prepare Storage
        Storage::disk('public')->makeDirectory('photos');
        $fixturesPath = base_path('tests/fixtures');

        $files = ['house1.jpg', 'house2.jpg', 'house3.jpg'];

        foreach ($files as $file) {
            $source = $fixturesPath . '/' . $file;
            $destName = 'photos/' . $file; // emulate 'photos/hash.jpg' structure

            if (File::exists($source)) {
                Storage::disk('public')->put($destName, File::get($source));
            }
        }

        // 4. Attach Photos to Property (house1, house2)
        // Clear existing to avoid dupes
        $property->photos()->delete();

        $property->photos()->create(['path' => 'photos/house1.jpg']);
        $property->photos()->create(['path' => 'photos/house2.jpg']);

        $this->command->info("Seeded 2 photos for Property ID: {$property->id}");

        // 5. Attach Photos to Unit (house1, house2, house3)
        $unit->photos()->delete();

        $unit->photos()->create(['path' => 'photos/house1.jpg']);
        $unit->photos()->create(['path' => 'photos/house2.jpg']);
        $unit->photos()->create(['path' => 'photos/house3.jpg']);

        $this->command->info("Seeded 3 photos for Unit ID: {$unit->id}");
    }
}
