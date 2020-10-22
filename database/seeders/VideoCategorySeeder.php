<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VideoCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('video_categories')->insert([
            'name' => 'PHP',
        ]);
        DB::table('video_categories')->insert([
            'name' => 'JavaScript',
        ]);
        DB::table('video_categories')->insert([
            'name' => 'Ruby',
        ]);
        DB::table('video_categories')->insert([
            'name' => 'Python',
        ]);
        DB::table('video_categories')->insert([
            'name' => 'C#',
        ]);
        DB::table('video_categories')->insert([
            'name' => 'Laravel',
        ]);
        DB::table('video_categories')->insert([
            'name' => 'VueJs',
        ]);
    }
}
