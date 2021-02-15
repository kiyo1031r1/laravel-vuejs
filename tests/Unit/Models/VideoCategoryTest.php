<?php

namespace Tests\Models\Unit;

use App\Models\Video;
use App\Models\VideoCategory;
use Tests\TestCase;

class VideoCategoryTest extends TestCase
{
    public function setUp() :void {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
    }

    //Videoモデルとのリレーション確認
    public function testVideoCategoryBelongsToManyVideo(){
        $count = 5;
        $video_category = VideoCategory::factory()
                ->has(Video::factory()->count($count))->create();
        $this->assertEquals($count, count($video_category->videos));
    }
}
