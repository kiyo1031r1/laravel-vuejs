<?php

namespace Tests\Unit\Controllers;

use App\Models\Video;
use App\Models\VideoCategory;
use Carbon\Carbon;
use Database\Seeders\VideoCategorySeeder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class VideoControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider storeDataProvider
     * @param パラメータ
     * @param コード
     * @param カラム名
     * @param エラーメッセージ
     * @param ケース
     */
    public function testStore($request, $code, $column, $message, $case = 0){
        //正常チェックでは、videoCategoriesとのリレーションが必要な為
        if($case > 0){
            $this->artisan('migrate:fresh');
            $this->seed(VideoCategorySeeder::class);
        }

        $param = $request;
        $response = $this->postJson('/api/videos', $param);

        if($code === 422){
            $response->assertJsonValidationErrors([$column => $message])
            ->assertStatus($code);
        }
        elseif($code == 200){
            $response->assertStatus($code);

            //サムネイルと動画の保存確認
            Storage::disk('public')->assertExists('thumbnails/'.$param['thumbnail']->hashName());
            Storage::disk('public')->assertExists('videos/'.$param['video']->hashName());
            
            //サムネイルのリサイズ確認
            $image_size = getimagesize(env('APP_URL').'/storage/thumbnails/'.$param['thumbnail']->hashName());
            $this->assertEquals($image_size[0], 800);
            $this->assertEquals($image_size[1], 500);

            //DBデータ確認
            $this->assertDatabaseHas('videos',[
                'title' => str_repeat('a', 255),
                'about' => 'test',
                'status' => 'normal',
                'thumbnail' => env('APP_URL').'/storage/thumbnails/'.$param['thumbnail']->hashName(),
                'thumbnail_name' => str_repeat('a', 251).'.jpg',
                'video' => env('APP_URL').'/storage/videos/'.$param['video']->hashName(),
                'video_name' => str_repeat('a', 251).'.jpg',
                'video_time' => gmdate("H:i:s", (int)$param['video_time']),
            ]);

            //リレーションのDBデータ確認
            foreach($request['category'] as $category){
                $this->assertDatabaseHas('video_video_category',[
                    'video_id' => 1,
                    'video_category_id' => $category,
                ]);
            }
        }
    }

    // public function storeDataProvider(){
    //     return [
    //         'pass' => [[
    //             'title' => str_repeat('a', 255),
    //             'about' => 'test',
    //             'status' => 'normal',
    //             'thumbnail' => UploadedFile::fake()->image(str_repeat('a', 251).'.jpg'),
    //             'thumbnail_name' => str_repeat('a', 251).'.jpg',
    //             'video' => UploadedFile::fake()->image(str_repeat('a', 251).'.jpg'),
    //             'video_name' => str_repeat('a', 251).'.jpg',
    //             'video_time' => '86399',
    //             'category' => ['1','2'],
    //         ], 200, null, null, true],
    //         'title_required' => [['title' => ' '], 422, 'title', 'タイトルは必ず入力してください。'],
    //         'title_max' => [['title' => str_repeat('a', 256)], 422, 'title', 'タイトルには255文字以下の文字列を指定してください。'],
    //         'about_required' => [['about' => ' '], 422, 'about', '概要は必ず入力してください。'],
    //         'status_required' => [['status' => null], 422, 'status', 'ステータスは必ず入力してください。'],
    //         'thumbnail_name_max' => [['thumbnail_name' => str_repeat('a', 252).'.jpg'], 422, 'thumbnail_name', 'サムネイルには255文字以下の文字列を指定してください。'],
    //         'video_required' => [['video' => null], 422, 'video', '動画は必ず入力してください。'],
    //         'video_max' => [['video' => UploadedFile::fake()->image('test.jpg')->size(2049)], 422, 'video', '動画には2048 KB以下のファイルを指定してください。'],
    //         'video_name_required' => [['video_name' => ' '], 422, 'video_name', '動画は必ず入力してください。'],
    //         'video_time_required' => [['video_time' => null], 422, 'video_time', '再生時間は必ず入力してください。'],
    //         'video_time_max' => [['video_time' => "86400"], 422, 'video_time', '再生時間には23時間59分59秒以下を指定してください。'],
    //         'category' => [['category' => null], 422, 'category', 'カテゴリーは必ず入力してください。'],
    //         'category' => [['category' => [1,2,3,4]], 422, 'category', 'カテゴリーは3個まで登録することができます。'],
    //     ];
    // }

    // /**
    //  * @dataProvider updateDataProvider
    //  * @param 更新パラメーター
    //  * @param コード
    //  * @param エラーメッセージ
    //  * @param ケース
    //  * @param ダミーデータフラグ
    //  */
    // public function testUpdate($request, $code, $column, $message, $case = 0, $dummy = true){
    //     $this->artisan('migrate:fresh');
    //     $video_category = VideoCategory::factory()->create();

    //     //更新対象ビデオ
    //     if($dummy){
    //         $video = Video::factory()->hasAttached($video_category)->create();
    //     }
    //     //サムネイルと動画が通常データの場合
    //     else{
    //         $param = [
    //             'title' => 'test',
    //             'about' => 'test',
    //             'status' => 'normal',
    //             'thumbnail' => $thumbnail_cache = UploadedFile::fake()->image('test.jpg'), //ファイル確認で使用
    //             'thumbnail_name' => 'test',
    //             'video' => $video_cache = UploadedFile::fake()->image('test.jpg'),  //ファイル確認で使用
    //             'video_time' => "86399",
    //             'video_name' => 'test',
    //             'category' => [$video_category->id],
    //         ];
    //         $this->postJson('/api/videos', $param);
    //         $video = Video::find(1);
    //     }

    //     //カテゴリー取得
    //     foreach($video->videoCategories as $category){
    //         $categories[] = $category->id;
    //     }
    
    //     //データ読み込み(thumbnailとvideoはpreview側に読み込む為、未更新の場合は''になる)
    //     $param = [
    //         'title' => $video->title,
    //         'about' => $video->about,
    //         'status' => $video->status,
    //         'thumbnail' => '',
    //         'thumbnail_name' => $video->thumbnail_name,
    //         'video' => '',
    //         'video_time' => "86399", //DBからではなく、DBのvideoから取得する為
    //         'video_name' => $video->video_name,
    //         'category' => $categories,
    //     ];

    //     //データ更新
    //     if($request){
    //         foreach($request as $req_column => $req_value){
    //             $param[$req_column] = $req_value;
    //         }
    //     }

    //     $response = $this->putJson('/api/videos/'.$video->id, $param);

    //     if($code === 422){
    //         $response->assertJsonValidationErrors([$column => $message])
    //         ->assertStatus($code);
    //     }
    //     elseif($code == 200){
    //         $response->assertStatus($code);
    //         switch($case){
    //             case 1:
    //                 //ダミーデータが削除されていないか確認
    //                 $this->assertFileExists('./public/images/dummyData/dummy_thumbnail.jpeg');
    //                 $this->assertFileExists('./public/images/dummyData/dummy_video.qt');
                    
    //                 //DBデータ確認
    //                 $this->assertDatabaseHas('videos',[
    //                     'title' => $param['title'],
    //                     'about' => $param['about'],
    //                     'status' => $param['status'],
    //                     'thumbnail' => $video->thumbnail,
    //                     'thumbnail_name' => $param['thumbnail_name'],
    //                     'video' => $video->video,
    //                     'video_name' => $param['video_name'],
    //                     'video_time' => gmdate("H:i:s", (int)$param['video_time']),
    //                 ]);

    //                 //リレーションのDBデータ確認
    //                 foreach($param['category'] as $category){
    //                     $this->assertDatabaseHas('video_video_category',[
    //                         'video_id' => $video->id,
    //                         'video_category_id' => $category,
    //                     ]);
    //                 }
    //                 break;
    //             case 2:
    //                 //データが削除されていないか確認
    //                 Storage::disk('public')->assertExists('thumbnails/'.$thumbnail_cache->hashName());
    //                 Storage::disk('public')->assertExists('videos/'.$video_cache->hashName());
    //                 break;
    //             case 3:
    //                 $this->assertFileExists('./public/images/dummyData/dummy_thumbnail.jpeg');
    //                 Storage::disk('public')->assertExists('thumbnails/'.$param['thumbnail']->hashName());

    //                 //サムネイルのリサイズ確認
    //                 $image_size = getimagesize(env('APP_URL').'/storage/thumbnails/'.$param['thumbnail']->hashName());
    //                 $this->assertEquals($image_size[0], 800);
    //                 $this->assertEquals($image_size[1], 500);

    //                 //DB更新確認
    //                 $this->assertDatabaseHas('videos',[
    //                     'thumbnail' => env('APP_URL').'/storage/thumbnails/'.$param['thumbnail']->hashName(),
    //                     'thumbnail_name' => $param['thumbnail_name'],
    //                 ]);
    //                 break;
    //             case 4:
    //                 //サムネイルの更新確認
    //                 Storage::disk('public')->assertMissing('thumbnails/'.$thumbnail_cache->hashName());
    //                 Storage::disk('public')->assertExists('thumbnails/'.$param['thumbnail']->hashName());

    //                 $image_size = getimagesize(env('APP_URL').'/storage/thumbnails/'.$param['thumbnail']->hashName());
    //                 $this->assertEquals($image_size[0], 800);
    //                 $this->assertEquals($image_size[1], 500);
                    
    //                 //DB更新確認
    //                 $this->assertDatabaseHas('videos',[
    //                     'thumbnail' => env('APP_URL').'/storage/thumbnails/'.$param['thumbnail']->hashName(),
    //                     'thumbnail_name' => $param['thumbnail_name'],
    //                 ]);
    //                 break;
    //             case 5:
    //                 $this->assertFileExists('./public/images/dummyData/dummy_thumbnail.jpeg');
    //                 $this->assertDatabaseHas('videos',[
    //                     'thumbnail' => null,
    //                     'thumbnail_name' => null,
    //                 ]);
    //                 break;
    //             case 6:
    //                 Storage::disk('public')->assertMissing('thumbnails/'.$thumbnail_cache->hashName());

    //                 $this->assertDatabaseHas('videos',[
    //                     'thumbnail' => null,
    //                     'thumbnail_name' => null,
    //                 ]);
    //                 break;
    //             case 7:
    //                 $this->assertFileExists('./public/images/dummyData/dummy_video.qt');
    //                 Storage::disk('public')->assertExists('videos/'.$param['video']->hashName());

    //                 $this->assertDatabaseHas('videos',[
    //                     'video' => env('APP_URL').'/storage/videos/'.$param['video']->hashName(),
    //                     'video_name' => $param['video_name'],
    //                     'video_time' => gmdate("H:i:s", (int)$param['video_time']),
    //                 ]);
    //                 break;
    //             case 8:
    //                 Storage::disk('public')->assertMissing('videos/'.$video_cache->hashName());
    //                 Storage::disk('public')->assertExists('videos/'.$param['video']->hashName());

    //                 $this->assertDatabaseHas('videos',[
    //                     'video' => env('APP_URL').'/storage/videos/'.$param['video']->hashName(),
    //                     'video_name' => $param['video_name'],
    //                     'video_time' => gmdate("H:i:s", (int)$param['video_time']),
    //                 ]);
    //                 break;
    //         }
    //     }
    // }

    // public function updateDataProvider(){
    //     return [
    //         'pass_no_change' => [null, 200, null, null, 1],
    //         'pass_no_change_not_dummy' => [null, 200, null, null, 2, false],
    //         'pass_thumbnail_change' => [[
    //             'thumbnail' => UploadedFile::fake()->image('test.jpg')->size(2048),
    //             'thumbnail_name' => 'test.jpg',
    //         ], 200, null, null, 3],
    //         'pass_thumbnail_change_not_dummy' => [[
    //             'thumbnail' => UploadedFile::fake()->image('test.jpg')->size(2048),
    //             'thumbnail_name' => 'test.jpg',
    //         ], 200, null, null, 4, false],
    //         'pass_thumbnail_delete' => [[
    //             'thumbnail' => '',
    //             'thumbnail_name' => '',
    //         ], 200, null, null, 5],
    //         'pass_thumbnail_delete_not_dummy' => [[
    //             'thumbnail' => '',
    //             'thumbnail_name' => '',
    //         ], 200, null, null, 6, false],
    //         'pass_video_change' => [[
    //             'video' => UploadedFile::fake()->image('test.jpg')->size(2048),
    //             'video_name' => 'test.jpg',
    //             'video_time' => '86398',
    //         ], 200, null, null, 7],
    //         'pass_video_change_not_dummy' => [[
    //             'video' => UploadedFile::fake()->image('test.jpg')->size(2048),
    //             'video_name' => 'test.jpg',
    //             'video_time' => '86398',
    //         ], 200, null, null, 8, false],
    //         'title_required' => [['title' => ' '], 422, 'title', 'タイトルは必ず入力してください。'],
    //         'title_max' => [['title' => str_repeat('a', 256)], 422, 'title', 'タイトルには255文字以下の文字列を指定してください。'],
    //         'about_required' => [['about' => ' '], 422, 'about', '概要は必ず入力してください。'],
    //         'status_required' => [['status' => null], 422, 'status', 'ステータスは必ず入力してください。'],
    //         'thumbnail_name_max' => [['thumbnail_name' => str_repeat('a', 252).'.jpg'], 422, 'thumbnail_name', 'サムネイルには255文字以下の文字列を指定してください。'],
    //         'video_max' => [['video' => UploadedFile::fake()->image('test.jpg')->size(2049)], 422, 'video', '動画には2048 KB以下のファイルを指定してください。'],
    //         'video_name_required' => [['video_name' => ' '], 422, 'video_name', '動画は必ず入力してください。'],
    //         'video_time_required' => [['video_time' => null], 422, 'video_time', '再生時間は必ず入力してください。'],
    //         'video_time_max' => [['video_time' => "86400"], 422, 'video_time', '再生時間には23時間59分59秒以下を指定してください。'],
    //         'category' => [['category' => null], 422, 'category', 'カテゴリーは必ず入力してください。'],
    //         'category' => [['category' => [1,2,3,4]], 422, 'category', 'カテゴリーは3個まで登録することができます。'],
    //     ];
    // }

    // public function testShow(){
    //     $category = VideoCategory::factory()->count(2)->create();
    //     $video = Video::factory()->hasAttached($category)->create();
    //     $response = $this->getJson('/api/videos/'.$video->id);
    //     foreach($video->videoCategories as $video_category){
    //         $video_categories[] = [
    //             'id' => $video_category->id,
    //             'pivot' => $video_category->pivot->toArray(),
    //         ];
    //     }

    //     $response->assertJson([
    //         'title' => $video->title,
    //         'about' => $video->about,
    //         'status' => $video->status,
    //         'thumbnail' => $video->thumbnail,
    //         'thumbnail_name' => $video->thumbnail_name,
    //         'video' => $video->video,
    //         'video_name' => $video->video_name,
    //         'video_categories' => $video_categories,
    //     ])->assertOk();
    // }

    // public function testDestroy(){
    //     $video = Video::factory()->create();
    //     $this->deleteJson('/api/videos/'.$video->id);

    //     //ファイル削除処理は、updateアクションで確認済みの為省略
    //     $this->assertDeleted($video);
    // }

    // /**
    //  * @dataProvider dataProviderSearch
    //  * @param 検索カラム
    //  * @param 検索値
    //  * @param ソートカラム
    //  * @param ソート値
    //  * @param ケース
    //  * @param 表示件数フラグ
    //  */
    // public function testSearch($search_param, $search_value, $sort_param, $sort_value, $case, $per_page = false){
    //     $this->artisan('migrate:fresh');
    //     VideoCategory::factory()->count(3)->create();
    //     Video::factory()->hasAttached(VideoCategory::find(1))->create([
    //         'title' => 'test1',
    //         'created_at' => Carbon::now(),
    //     ]);
    //     Video::factory()->hasAttached([VideoCategory::find(1), VideoCategory::find(2)])->create([
    //         'title' => 'test2',
    //         'created_at' => Carbon::now()->subDay(1),
    //     ]);

    //     $search = [
    //         'title' => '',
    //         'created_at_start' => '',
    //         'created_at_end' => '',
    //         'categories' => [],
    //     ];
    //     $sort = [
    //         'created_at' => 'desc',
    //         'per_page' => 10,
    //     ];

    //     if($search_param){
    //         //dateProvider読み込み時にはモデルが作成されていないのでこちらで作成
    //         if($search_param === 'categories'){
    //             foreach($search_value as $value){
    //                 $search['categories'][] = VideoCategory::find($value);
    //             }
    //         }
    //         else{
    //             $search[$search_param] = $search_value;
    //         }
            
    //     }
    //     if($sort_param){
    //         $sort[$sort_param] = $sort_value;
    //     }

    //     $current_page = 1;
    //     //ユーザー表示件数を変更しない場合
    //     if(!$per_page){
    //         $response = $this->postJson('/api/videos/search?page='.$current_page, [
    //             'search' => $search,
    //             'sort' => $sort,
    //         ]);
    //     }

    //     switch($case){
    //         case 0:
    //             $response->assertJsonCount(0, 'data')
    //             ->assertOK();
    //             break;
    //         case 1:
    //             $response->assertJsonCount(1, 'data')
    //             ->assertOK();
    //             break;
    //         case 2:
    //             $response->assertJsonCount(2, 'data')
    //             ->assertOK();
    //             break;
    //         case 3:
    //             //descなら、0つめがid1(今日)で、1つめがid2(昨日)
    //             $this->assertGreaterThan($response['data'][0]['id'], $response['data'][1]['id']);
    //             $response->assertOK();
    //             break;
    //         case 4:
    //             //ascなら、0つめがid2(昨日)で、1つめがid1(今日)
    //             $this->assertGreaterThan($response['data'][1]['id'], $response['data'][0]['id']);
    //             $response->assertOK();
    //             break;
    //         case 5:
    //             //video表示数を確認する為、videoを100個追加
    //             $count = 100;
    //             Video::factory($count)->create();
    //             $response = $this->postJson('/api/videos/search?page='.$current_page, [
    //                 'search' => $search,
    //                 'sort' => $sort,
    //             ]);
    //             $response->assertJsonFragment(['last_page' => ceil(($count + 2) / $sort_value)])
    //             ->assertOK();
    //             break;
    //     }
    // }

    // public function dataProviderSearch(){
    //     return[
    //         'search_title_expect_2' => ['title', 'test', null, null, 2],
    //         'search_title_expect_1' => ['title', 'test 1', null, null, 1],
    //         'search_title_expect_0' => ['title', 'test12', null, null, 0],
    //         'search_categories_expect_2' => ['categories', [1], null, null, 2],
    //         'search_categories_expect_1' => ['categories', [1, 2], null, null, 1],
    //         'search_categories_expect_0' => ['categories', [3], null, null, 0],
    //         'search_created_at_start_expect_2' => ['created_at_start',Carbon::now()->subDay(1), null, null, 2],
    //         'search_created_at_start_expect_1' => ['created_at_start',Carbon::now(), null, null, 1],
    //         'search_created_at_start_expect_0' => ['created_at_start',Carbon::now()->addDay(1), null, null, 0], 
    //         'search_created_at_end_expect_2' => ['created_at_end',Carbon::now(), null, null, 2],
    //         'search_created_at_end_expect_1' => ['created_at_end',Carbon::now()->subDay(1), null, null, 1],
    //         'search_created_at_end_expect_0' => ['created_at_end',Carbon::now()->subDay(2), null, null, 0], 
    //         'sort_created_at_expect_desc' => [null, null, 'created_at', 'desc', 3], 
    //         'sort_created_at_expect_asc' => [null, null, 'created_at', 'asc', 4], 
    //         'sort_per_page_expect_11' => [null, null, 'per_page', 10, 5, true], 
    //         'sort_per_page_expect_6' => [null, null, 'per_page', 20, 5, true], 
    //         'sort_per_page_expect_3' => [null, null, 'per_page', 50, 5, true], 
    //         'sort_per_page_expect_2' => [null, null, 'per_page', 100, 5, true], 
    //     ];
    // }

    // /**
    //  * @dataProvider dataProviderWatch
    //  * @param カテゴリー登録フラグ
    //  * @param ビデオ登録多数フラグ
    //  * @param ケース
    //  */
    // public function testWatch($register, $rec_many, $case){
    //     $this->artisan('migrate:fresh');
    //     VideoCategory::factory()->count(5)->create();

    //     //$video->created_atとすると、結果がyyyy-mm hh-mm-ssになる
    //     //setTimezoneをして、フォーマットするとyyyy-mmThh-mm-ss+0900のようになる為、直接指定
    //     $created_at = "2021-02-11T09:00:00.000000Z";
    //     $created_at_expect = "2021-02-11T00:00:00.000000Z";

    //     //対象ビデオ
    //     if($register){
    //         $video = Video::factory()->hasAttached([
    //             VideoCategory::find(1),
    //             VideoCategory::find(2),
    //             VideoCategory::find(3),
    //         ])->create([
    //             'created_at' => $created_at,
    //         ]);
    //         $video_categories_id = [1,2,3];
    //     }
    //     else{
    //         $video = Video::factory()->create([
    //             'created_at' => $created_at,
    //         ]);
    //     }

    //     //レコメンド用ビデオ(7つ作成)
    //     // 3/3
    //     Video::factory()->hasAttached([
    //         VideoCategory::find(1),
    //         VideoCategory::find(2),
    //         VideoCategory::find(3),
    //     ])->create();

    //     // 2/3
    //     Video::factory()->hasAttached([
    //         VideoCategory::find(1),
    //         VideoCategory::find(2),
    //         VideoCategory::find(4),
    //     ])->create();

    //     // 1/3
    //     Video::factory()->hasAttached([
    //         VideoCategory::find(1),
    //         VideoCategory::find(4),
    //         VideoCategory::find(5),
    //     ])->create();

    //     // 2/2
    //     Video::factory()->hasAttached([
    //         VideoCategory::find(1),
    //         VideoCategory::find(2),
    //     ])->create();

    //     // 1/2
    //     Video::factory()->hasAttached([
    //         VideoCategory::find(3),
    //         VideoCategory::find(4),
    //     ])->create();

    //     // 1/1
    //     Video::factory()->hasAttached([
    //         VideoCategory::find(1),
    //     ])->create();

    //     // 0/1
    //     Video::factory()->hasAttached([
    //         VideoCategory::find(4),
    //     ])->create();

    //     //10以上のチェック(共通カテゴリー0を追加)
    //     if($rec_many){
    //         Video::factory()->hasAttached([
    //             VideoCategory::find(5),
    //         ])->count(10)->create();
    //     }

    //     $response = $this->postJson('/api/videos/watch/'.$video->id);
    //     $response->assertJson(['video' => [
    //         'id' => $video->id,
    //         'title' => $video->title,
    //         'about' => $video->about,
    //         'status' => $video->status,
    //         'video' => $video->video,
    //         'video_name' => $video->video_name,
    //         'created_at' => $created_at_expect,
    //     ]])->assertOk();

    //     if($register){
    //         $before_same_num = 3; //最大数
    //         //同カテゴリー数を比較
    //         foreach($response['recommends'] as $recommend){
    //             $r_video_categories_id = array_column($recommend['video_categories'],'id');
    //             $same_num = count(array_intersect($video_categories_id, $r_video_categories_id));
    //             $this->assertGreaterThanOrEqual($same_num, $before_same_num);
    //             $before_same_num = $same_num;
    //         }
    //     }

    //     switch($case){
    //         case 1:
    //             $response->assertJsonCount(10, 'recommends');
    //             break;
    //         case 2:
    //             $response->assertJsonCount(7, 'recommends');
    //             break;
    //         case 3:
    //             $this->assertNull($response['recommends']);
    //             break;
    //     }
    // }

    // public function dataProviderWatch(){
    //     return[
    //         'register_rec_video_many' => [true, true, 1], 
    //         'register_rec_video_little' => [true, false, 2], 
    //         'not_register' => [false, null, 3],
    //     ];
    // }

    // public function testExist(){
    //     //ダミー
    //     Video::factory()->hasAttached(VideoCategory::factory())->count(10)->create();

    //     //正常チェック
    //     $video = Video::factory()->hasAttached(VideoCategory::factory())->create();
    //     $response = $this->postJson('/api/videos/exist', [
    //         'id' => $video->id,
    //     ]);
    //     $response->assertOk()
    //     ->assertJsonStructure([
    //         'id', 'title', 'about', 'status', 'thumbnail', 'thumbnail_name','video', 'video_name', 'video_time', 'created_at', 'updated_at'
    //     ])
    //     ->assertJson([
    //         'id' => $video->id
    //     ]);

    //     //異常チェック
    //     $response = $this->postJson('/api/videos/exist', [
    //         'id' => 10000,
    //     ]);
    //     $response->assertNotFound();
    // }

    // public function testDownLoad(){
    //     $file_name = 'sample_video.mov';
    //     $response = $this->postJson('/api/videos/download', 
    //         ['file_name' => $file_name]
    //     );

    //     $this->assertFileExists(storage_path('app/public/'.$file_name));
    //     $response->assertOk();
    // }

}
