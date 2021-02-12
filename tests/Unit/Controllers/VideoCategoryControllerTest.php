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
}
