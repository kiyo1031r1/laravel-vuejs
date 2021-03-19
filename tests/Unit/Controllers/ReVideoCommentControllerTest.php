<?php

namespace Tests\Unit\Controllers;

use App\Models\ReVideoComment;
use App\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Video;
use App\Models\User;
use App\Models\VideoComment;

class ReVideoCommentControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider storeDataProvider
     * @param パラメータ
     * @param コード
     * @param カラム名
     * @param エラーメッセージ
     */
    public function testStore($request, $code, $column, $message){
        $video = Video::factory()->create();
        $user = User::factory()->for(Role::factory())->create();
        $video_comment = VideoComment::factory()->for($video)->for($user)->create();

        $response = $this->postJson('/api/re_video_comments', [
            're_video_comment' => $request,
            'video_comment_id'=> $video_comment->id,
            'user_id'=> $user->id,
        ]);
        if($code === 422){
            $response->assertJsonValidationErrors([$column => $message])
            ->assertStatus($code);
        }
        elseif($code === 200){
            $response->assertStatus($code);
            $this->assertDatabaseHas('re_video_comments', [
                're_video_comment' => $request,
                'video_comment_id'=> $video_comment->id,
                'user_id'=> $user->id,
            ]);
        }
    }

    public function storeDataProvider(){
        return[
            'pass' => [str_repeat('a', 255), 200, null, null],
            're_video_comment_required' => [' ', 422, 're_video_comment', 'コメントは必ず入力してください。'],
            're_video_comment_string' => [11111111, 422, 're_video_comment', 'コメントには文字列を指定してください。'],
            're_video_comment_max' => [str_repeat('a', 256), 422, 're_video_comment', 'コメントには255文字以下の文字列を指定してください。'],
        ];
    }

    /**
     * @dataProvider updateDataProvider
     * @param パラメータ
     * @param コード
     * @param カラム名
     * @param エラーメッセージ
     */
    public function testUpdate($request, $code, $column, $message){
        $video = Video::factory()->create();
        $user = User::factory()->for(Role::factory())->create();
        $video_comment = VideoComment::factory()->for($video)->for($user)->create();
        $re_video_comment = ReVideoComment::factory()->for($video_comment)->for($user)->create();

        $response = $this->putJson('/api/re_video_comments/'.$re_video_comment->id, [
            'edit_re_video_comment' => $request,
        ]);

        if($code === 422){
            $response->assertJsonValidationErrors([$column => $message])
            ->assertStatus($code);
        }
        elseif($code === 200){
            $response->assertStatus($code);
            $this->assertDatabaseHas('re_video_comments', [
                're_video_comment' => $request,
            ]);
        }
    }

    public function updateDataProvider(){
        return[
            'pass' => [str_repeat('a', 255), 200, null, null],
            'edit_re_video_comment_required' => [' ', 422, 'edit_re_video_comment', 'コメントは必ず入力してください。'],
            'edit_re_video_comment_string' => [11111111, 422, 'edit_re_video_comment', 'コメントには文字列を指定してください。'],
            'edit_re_video_comment_max' => [str_repeat('a', 256), 422, 'edit_re_video_comment', 'コメントには255文字以下の文字列を指定してください。'],
        ];
    }

    public function testDestroy(){
        $user = User::factory()->for(Role::factory())->create();
        $video = Video::factory()->create();
        $video_comment = VideoComment::factory()->for($user)->for($video)->create();
        $re_video_comment = ReVideoComment::factory()->for($user)->for($video_comment)->create();
        $response = $this->deleteJson('/api/re_video_comments/'.$re_video_comment->id);
        $response->assertOk();
        $this->assertDeleted($re_video_comment);
    }
}
