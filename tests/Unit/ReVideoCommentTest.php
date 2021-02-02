<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\VideoComment;
use App\Models\ReVideoComment;
use Tests\TestCase;

class ReVideoCommentTest extends TestCase
{
    public function setUp() :void {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
    }

    //VideoCommentモデルとのリレーション確認
    public function testReVideoCommentBelongsToVideoComment(){
        $video_comment = VideoComment::factory()->create();
        $re_video_comment = ReVideoComment::factory()->for($video_comment)->create();
        $this->assertEquals($video_comment->id, $re_video_comment->videoComment->id);
    }

    //Userモデルとのリレーション確認
    public function testReVideoCommentBelongsToUser(){
        $user = User::factory()->create();
        $re_video_comment = ReVideoComment::factory()->for($user)->create();
        $this->assertEquals($user->id, $re_video_comment->user->id);
    }
}
