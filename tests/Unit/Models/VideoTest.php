<?php

namespace Tests\Models\Unit;

use Tests\TestCase;
use App\Models\Video;
use App\Models\VideoCategory;
use App\Models\VideoComment;

class VideoTest extends TestCase
{
    public function setUp() :void {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
    }

    //VideoCategoryモデルとのリレーション確認
    public function testVideoBelongsToManyVideoCategory(){
        $count = 5;
        $video = Video::factory()
                ->has(VideoCategory::factory()->count($count))->create();
        //$this->assertEquals($count, count($video->videoCategory));
        $this->assertEquals($count, count($video->videoCategories));
    }

    //VideoCommentモデルとのリレーション確認
    public function testVideoHasManyVideoComments(){
        $count = 5;
        $video = Video::factory()
                ->has(VideoComment::factory()->count($count))->create();
        $this->assertEquals($count, count($video->videoComments));
    }

    //サムネイル保存時のミューテーター確認
    public function testSetThumbnailAttribute(){
        //ローカルのダミーデータ使用時は、値を変更しない
        $value = '/images/test.jpg';
        $video = Video::factory()->create([
            'thumbnail' => $value
        ]);
        $this->assertEquals($value, $video->thumbnail);

        //nullの場合は、値を変更しない
        $value = null;
        $video = Video::factory()->create([
            'thumbnail' => $value
        ]);
        $this->assertEquals($value, $video->thumbnail);

        //上記以外は、値の先頭に「アプリURL/storage/」を付け加える
        $value = 'test.jpg';
        $video = Video::factory()->create([
            'thumbnail' => $value
        ]);
        $expected = asset('storage/'. $value);
        $this->assertEquals($expected, $video->thumbnail);
    }

    //サムネイル保存時のミューテーター確認
    public function testSetVideoAttribute(){
        //ローカルのダミーデータ使用時は、値を変更しない
        $value = '/images/test.mov';
        $video = Video::factory()->create([
            'video' => $value
        ]);
        $this->assertEquals($value, $video->video);

        //上記以外は、値の先頭に「アプリURL/storage/」を付け加える
        $value = 'test.mov';
        $video = Video::factory()->create([
            'video' => $value
        ]);
        $expected = asset('storage/'. $value);
        $this->assertEquals($expected, $video->video);
    }

    public function testSetVideoTimeAttribute(){
        //動画尺は分単位、正の整数、最大86400(24時間)を想定
        $h = 60 * 60;
        $m = 60;

        //動画尺が1時間以上の場合は、HH:MM:SSに変更
        $value = (1 * $h) + (0 * $m) + 0;
        $video = Video::factory()->create([
            'video_time' => $value
        ]);
        $expected = gmdate("H:i:s", $value);
        $this->assertEquals($expected, $video->video_time);

        //動画尺が10分以上、1時間未満の場合は、MM:SSに変更
        $value = (0 * $h) + (59 * $m) + 59;
        $video = Video::factory()->create([
            'video_time' => $value
        ]);
        $expected = gmdate("i:s", $value);
        $this->assertEquals($expected, $video->video_time);

        $value = (0 * $h) + (10 * $m) + 0;
        $video = Video::factory()->create([
            'video_time' => $value
        ]);
        $expected = gmdate("i:s", $value);
        $this->assertEquals($expected, $video->video_time);

        //動画尺が10分未満の場合は、M:SSに変更する
        $value = (0 * $h) + (9 * $m) + 59;
        $video = Video::factory()->create([
            'video_time' => $value
        ]);
        $expected = substr(gmdate("i:s", $value), 1);
        $this->assertEquals($expected, $video->video_time);
    }

}
