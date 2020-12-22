<?php

namespace Database\Seeders;

use App\Models\VideoComment;
use Illuminate\Database\Seeder;

class VideoCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        VideoComment::factory()->times(500)->create();
    }
}
