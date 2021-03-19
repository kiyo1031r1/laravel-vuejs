<?php

namespace Tests\Unit\Controllers;

use App\Models\ReVideoComment;
use App\Models\Role;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Video;
use App\Models\User;
use App\Models\VideoComment;
use Carbon\Carbon;

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
            'video_comment' => $request,
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
                'video_comment' => $request,
                'video_id'=> $video->id,
                'user_id'=> $user->id,
            ]);
        }
    }

    public function storeDataProvider(){
        return[
            'pass' => [str_repeat('a', 255), 200, null, null],
            'video_comment_required' => [' ', 422, 'video_comment', 'コメントは必ず入力してください。'],
            'video_comment_string' => [11111111, 422, 'video_comment', 'コメントには文字列を指定してください。'],
            'video_comment_max' => [str_repeat('a', 256), 422, 'video_comment', 'コメントには255文字以下の文字列を指定してください。'],
        ];
    }

    public function testGetComment(){
        $user = User::factory()->for(Role::factory())->create();

        $video1 = Video::factory()->create();
        $comment_new = VideoComment::factory()->for($user)->for($video1)
        ->create([
            'created_at' => Carbon::now()
        ]);

        $comment_old = VideoComment::factory()->for($user)->for($video1)
        ->create([
            'created_at' => Carbon::now()->subDay(1)
        ]);
        ReVideoComment::factory()->for($user)->for($comment_new)->create();
        
        //ダミーデータ
        $video2 = Video::factory()->create();
        $comment2 = VideoComment::factory()->for($user)->for($video2)->create();
        ReVideoComment::factory()->for($user)->for($comment2)->create();

        $response = $this->postJson('/api/video_comments/get_comment'.'?page='.'1', [
            'video_id' => $video1->id,
            'per_page' =>  '20',
        ]);

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id', 
                    'video_comment', 
                    'video_id', 
                    'user_id', 
                    'created_at', 
                    'updated_at',
                    'user' => [
                        'id', 'name'
                    ],
                    're_video_comments' => [
                        '*' => [
                            'id', 
                            're_video_comment', 
                            'video_comment_id', 
                            'user_id', 
                            'created_at', 
                            'updated_at',
                            'user' => [
                                'id', 'name'
                            ]
                        ]
                    ]
                ]
            ]
        ])
        ->assertJsonCount(2, 'data')
        ->assertOk();
        $this->assertGreaterThan($response['data'][0]['id'], $response['data'][1]['id']);
    }

    public function testDestroy(){
        $user = User::factory()->for(Role::factory())->create();
        $video = Video::factory()->create();
        $comment = VideoComment::factory()->for($user)->for($video)->create();
        $response = $this->deleteJson('/api/video_comments/'.$comment->id);
        $response->assertOk();
        $this->assertDeleted($comment);
    }
}
