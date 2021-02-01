<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    //Userモデルとのリレーション確認
    public function testRoleHasManyUsers(){
        $count = 5;
        $role = Role::create(['name' => 'testRole']);
        $users = User::factory()->count($count)->create([
            'role_id' => $role->id,
        ]);
        $this->assertEquals($count, count($role->users));
    }
}