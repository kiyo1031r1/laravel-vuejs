<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(VideoCategorySeeder::class);
        $this->call(VideoSeeder::class);
        $this->call(VideoCommentSeeder::class);
        $this->call(ReVideoCommentSeeder::class);
        $this->call(VideoVideoCategorySeeder::class);
    }
}
