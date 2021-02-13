<?php

namespace Tests\Unit\Controllers;

use App\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Video;
use App\Models\User;
use App\Models\VideoComment;

class VideoCommentControllerTest extends TestCase
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
        $response = $this->postJson('/api/video_comments', [
            'comment' => $request,
            'video_id'=> $video->id,
            'user_id'=> $user->id,
        ]);
        if($code === 422){
            $response->assertJsonValidationErrors([$column => $message])
            ->assertStatus($code);
        }
        elseif($code === 200){
            $response->assertStatus($code);
            $this->assertDatabaseHas('video_comments', [
                'comment' => $request,
                'video_id'=> $video->id,
                'user_id'=> $user->id,
            ]);
        }
    }

    public function storeDataProvider(){
        return[
            'pass' => [str_repeat('a', 255), 200, null, null],
            'comment_required' => [' ', 422, 'comment', 'コメントは必ず入力してください。'],
            'comment_string' => [11111111, 422, 'comment', 'コメントには文字列を指定してください。'],
            'comment_max' => [str_repeat('a', 256), 422, 'comment', 'コメントには255文字以下の文字列を指定してください。'],
        ];
    }
}
