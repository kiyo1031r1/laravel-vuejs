<?php

namespace Database\Seeders;

use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Video::factory()->times(10)->has(VideoCategory::factory()->count(2))->create();
        Video::factory()->times(10)->create();
    }
}
