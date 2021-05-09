<?php

namespace Database\Seeders;

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Str;

class MediaDataMigrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Media::cursor()->each(
            fn (Media $media) => $media->update(['uuid' => Str::uuid(), 'conversions_disk' => $media->disk])
        );
    }
}
