<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoVideoCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i < 50; $i++){
            $video_id = random_int(1, 10);
            $video_category_id = random_int(1, 9);
            $video_video_category = DB::table('video_video_category')->where([
                ['video_id', $video_id],
                ['video_category_id', $video_category_id]
            ])->get();

            if($video_video_category->isEmpty()){
                DB::table('video_video_category')->insert([
                    'video_id' => $video_id,
                    'video_category_id' => $video_category_id
                ]);
            }
        }
    }
}
