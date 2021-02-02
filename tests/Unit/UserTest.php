<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\VideoComment;
use App\Models\ReVideoComment;

class UserTest extends TestCase
{
    public function setUp() :void {
        parent::setUp();
        $this->artisan('migrate:fresh');
        $this->artisan('db:seed');
    }

    //Roleモデルとのリレーション確認
    public function testUserBelongsToRole(){
        $role = Role::create(['name' => 'testRole']);
        $user = User::factory()->for($role)->create();
        $this->assertNotEmpty($user->role);
        $this->assertEquals($role->id, $user->role->id);
    }

    //VideoCommentモデルとのリレーション確認
    public function testUserHasManyVideoComments(){
        $count = 5;
        $user = User::factory()
                ->has(VideoComment::factory()->count($count))->create();
        $this->assertEquals($count, count($user->videoComments));
    }

    //ReVideoCommentモデルとのリレーション確認
    public function testUserHasManyReVideoComments(){
        $count = 5;
        $user = User::factory()
                ->has(ReVideoComment::factory()->count($count))->create();
        $this->assertEquals($count, count($user->reVideoComments));
    }

}
