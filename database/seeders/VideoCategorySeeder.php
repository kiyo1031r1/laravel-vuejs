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
            'file_name' => 'php',
        ]);
        DB::table('video_categories')->insert([
            'name' => 'Ruby',
            'file_name' => 'ruby',
        ]);
        DB::table('video_categories')->insert([
            'name' => 'JavaScript',
            'file_name' => 'javascript',
        ]);
        DB::table('video_categories')->insert([
            'name' => 'Python',
            'file_name' => 'python',
        ]);
        DB::table('video_categories')->insert([
            'name' => 'C#',
            'file_name' => 'csharp',
        ]);
        DB::table('video_categories')->insert([
            'name' => 'Laravel',
            'file_name' => 'laravel',
        ]);
        DB::table('video_categories')->insert([
            'name' => 'Rails',
            'file_name' => 'rails',
        ]);
        DB::table('video_categories')->insert([
            'name' => 'VueJs',
            'file_name' => 'vuejs',
        ]);
        DB::table('video_categories')->insert([
            'name' => 'django',
            'file_name' => 'django',
        ]);
    }
}
