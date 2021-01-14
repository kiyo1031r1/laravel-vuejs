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
        $video_num = 100;
        $category_num = 9;
        $video_video_category_num = 200;
        $user_num = 100;
        $video_comment_num = 500;

        //ビデオに最低１カテゴリーをつける
        for($i = 1; $i < $video_num; $i++){
            $video_category_id = random_int(1, $category_num);
            DB::table('video_video_category')->insert([
                'video_id' => $i,
                'video_category_id' => $video_category_id
            ]);
        }

        //2つ目以降のカテゴリーをつける
        for($j = 0; $j < $video_video_category_num - $video_num; $j++){
            $video_id = random_int(1, $video_num);
            $video_category_id = random_int(1, $category_num);

            //同じカテゴリーがなければ追加
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
