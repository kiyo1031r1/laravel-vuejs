<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Video;
use App\Models\VideoCategory;


class VideoCategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex(){
        $count = 10;
        VideoCategory::factory()->count($count)->create();
        $response = $this->getJson('/api/video_categories');
        $response->assertJsonStructure([
            '*' => [
                'id', 'name', 'file_name'
            ]
        ])
        ->assertJsonCount(10)
        ->assertOk();
    }

    /**
     * @dataProvider storeDataProvider
     * @param パラメータ
     * @param コード
     * @param カラム名
     * @param エラーメッセージ
     */
    public function testStore($request, $code, $column, $message){
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

    /**
     * @dataProvider destroyDataProvider
     * @param パラメータ
     * @param コード
     * @param カラム名
     * @param エラーメッセージ
     */
    public function testDestroy($request, $code, $column, $message){
        $category = VideoCategory::factory()->create($request);
        $response = $this->deleteJson('/api/video_categories/'. $category->id);

        if($code === 422){
            $response->assertJsonValidationErrors([$column => $message])
            ->assertStatus(422);
        }
        elseif($code == 200){
            $this->assertDeleted($category);
            $response->assertOk();
        }
    }

    public function destroyDataProvider(){
        return[
            'php' => [['file_name' => 'php'], 422, 'delete_category', 'そのカテゴリーは削除できません。'],
            'ruby' => [['file_name' => 'ruby'], 422, 'delete_category', 'そのカテゴリーは削除できません。'],
            'javascript' => [['file_name' => 'javascript'], 422, 'delete_category', 'そのカテゴリーは削除できません。'],
            'python' => [['file_name' => 'python'], 422, 'delete_category', 'そのカテゴリーは削除できません。'],
            'csharp' => [['file_name' => 'csharp'], 422, 'delete_category', 'そのカテゴリーは削除できません。'],
            'laravel' => [['file_name' => 'laravel'], 422, 'delete_category', 'そのカテゴリーは削除できません。'],
            'rails' => [['file_name' => 'rails'], 422, 'delete_category', 'そのカテゴリーは削除できません。'],
            'vuejs' => [['file_name' => 'vuejs'], 422, 'delete_category', 'そのカテゴリーは削除できません。'],
            'django' => [['file_name' => 'django'], 422, 'delete_category', 'そのカテゴリーは削除できません。'],
            'testCategory' => [['file_name' => 'testCategory'], 200, null, null],
        ];
    }

    public function testExist(){
        //ダミー
        VideoCategory::factory()->count(10)->create();

        //正常チェック
        $category = VideoCategory::factory()->create();
        $response = $this->postJson('/api/video_categories/exist', [
            'file_name' => $category->file_name,
        ]);
        $response->assertOk()
        ->assertJsonStructure([
            'id', 'name', 'file_name', 'created_at', 'updated_at'
        ])
        ->assertJson([
            'file_name' => $category->file_name
        ]);

        //異常チェック
        $response = $this->postJson('/api/video_categories/exist', [
            'file_name' => 'not_exist',
        ]);
        $response->assertNotFound();
    }
}
