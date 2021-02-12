<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Video;
use App\Models\VideoCategory;


class VideoCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    // public function testIndex(){
    //     $count = 10;
    //     VideoCategory::factory()->count($count)->create();
    //     $response = $this->getJson('/api/video_categories');
    //     $response->assertJsonStructure([
    //         '*' => [
    //             'id', 'name', 'file_name'
    //         ]
    //     ])
    //     ->assertJsonCount(10)
    //     ->assertOk();
    // }

    /**
     * @dataProvider storeDataProvider
     * @param パラメータ
     * @param コード
     * @param カラム名
     * @param エラーメッセージ
     * @param ケース
     */
    public function testStore($request, $code, $column, $message, $case = 0){
        //uniqueバリデーション確認用
        VideoCategory::factory()->create([
            'name' => 'unique',
            'file_name' => 'unique'
        ]);

        $param = $request;
        $response = $this->postJson('/api/video_categories', $param);
        if($code === 422){
            $response->assertJsonValidationErrors([$column => $message])
            ->assertStatus($code);
        }
        elseif($code === 200){
            $response->assertStatus($code);
            $this->assertDatabaseHas('video_categories', [
                'name' => str_repeat('a', 255),
                'file_name' => str_repeat('a', 255),
            ]);
        }
    }

    public function storeDataProvider(){
        return[
            'pass' => [[
                'name' => str_repeat('a', 255),
                'file_name' => str_repeat('a', 255),
            ], 200, null, null],
            'name_required' => [['name' => ' '], 422, 'name', '名前は必ず入力してください。'],
            'name_string' => [['name' => 11111111], 422, 'name', '名前には文字列を指定してください。'],
            'name_max' => [['name' => str_repeat('a', 256)], 422, 'name', '名前には255文字以下の文字列を指定してください。'],
            'name_unique' => [['name' => 'unique'], 422, 'name', 'その名前はすでに使われています。'],
            'file_name_required' => [['file_name' => ' '], 422, 'file_name', 'ファイル名は必ず入力してください。'],
            'file_name_string' => [['file_name' => 11111111], 422, 'file_name', 'ファイル名には文字列を指定してください。'],
            'file_name_max' => [['file_name' => str_repeat('a', 256)], 422, 'file_name', 'ファイル名には255文字以下の文字列を指定してください。'],
            'file_name_unique' => [['file_name' => 'unique'], 422, 'file_name', 'そのファイル名はすでに使われています。'],
        ];
    }
}
