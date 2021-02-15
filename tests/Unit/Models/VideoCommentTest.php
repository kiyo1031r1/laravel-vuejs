<?php

namespace Tests\Models\Unit;

use App\Models\Video;
use App\Models\User;
use App\Models\ReVideoComment;
use App\Models\VideoComment;
use Tests\TestCase;

class VideoCommentTest extends TestCase
{
    public function setUp() :void {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
    }

    //Videoモデルとのリレーション確認
    public function testVideoCommentBelongsToVideo(){
        $video = Video::factory()->create();
        $video_comment = VideoComment::factory()->for($video)->create();
        $this->assertNotEmpty($video_comment->video);
        $this->assertEquals($video->id, $video_comment->video->id);
    }

    //Userモデルとのリレーション確認
    public function testVideoCommentBelongsToUser(){
        $user = User::factory()->create();
        $video_comment = VideoComment::factory()->for($user)->create();
        $this->assertNotEmpty($video_comment->user);
        $this->assertEquals($user->id, $video_comment->user->id);
    }

    //ReVideoCommentモデルとのリレーション確認
    public function testVideoCommentHasManyReVideoComments(){
        $count = 5;
        $video_comment = VideoComment::factory()
                ->has(ReVideoComment::factory()->count($count))->create();
        $this->assertEquals($count, count($video_comment->reVideoComments));
    }
}
