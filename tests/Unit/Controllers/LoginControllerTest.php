<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @dataProvider registerDataProvider
     * @param パラメータ
     * @param コード
     * @param カラム名
     * @param エラーメッセージ
     * @param パスワードの有無
     */

    public function testLogin($request, $code, $column, $message){
        $user = User::factory()->for(Role::factory())
        ->create([
            'email' => 'test1@example.com',
            'password' => Hash::make('11111111'),
        ]);
        $session = session()->getId();
        $response = $this->postJson('/api/login', $request);
        if($code === 422){
            $response->assertJsonValidationErrors([$column => $message])
            ->assertStatus($code);
        }
        elseif($code === 200){
            $this->assertEquals(Auth::id(), $user->id);
            $this->assertNotEquals($session, session()->getId());
        }
    }

    public function registerDataProvider(){
        return [
            'pass' => [[
                'email' => 'test1@example.com',
                'password' => '11111111',
            ], 200, null, null],
            'email_error' => [[
                'email' => 'test11@example.com',
                'password' => '11111111',
            ], 422, 'not_found', 'メールアドレスかパスワードが間違っています'],
            'password_error' => [[
                'email' => 'test1@example.com',
                'password' => '1111111',
            ], 422, 'not_found', 'メールアドレスかパスワードが間違っています'],
            'email_password_error' => [[
                'name' => 'test11@example.com',
                'password' => '1111111',
            ], 422, 'not_found', 'メールアドレスかパスワードが間違っています'],
        ];
    }
}
