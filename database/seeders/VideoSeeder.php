<?php

namespace Database\Seeders;

use App\Models\Video;
use App\Models\VideoCategory;
use Illuminate\Database\Seeder;
use Database\Seeders\VideoCategorySeeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 100; $i++){
            $categories = [];
            $categories_id = range(1, 9);

            for($j = 0; $j < random_int(1, 3); $j++){
                $category_id = array_rand($categories_id, 1);
                $categories[] = VideoCategory::find($categories_id[$category_id]);
                unset($categories_id[$category_id]);
            }

            Video::factory()->hasAttached($categories)->create();
            $categories = [];
        }
    }
}
