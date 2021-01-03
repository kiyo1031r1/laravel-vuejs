<?php

namespace Database\Seeders;

use App\Models\ReVideoComment;
use Illuminate\Database\Seeder;

class ReVideoCommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ReVideoComment::factory()->times(100)->create();
    }
}
