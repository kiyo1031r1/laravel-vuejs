<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Database\Seeders\RoleSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminLoginControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @dataProvider registerDataProvider
     * @param パラメータ
     * @param コード
     * @param カラム名
     * @param エラーメッセージ
     */

    public function testLogin($request, $code, $column, $message){
        $this->seed(RoleSeeder::class);

        //admin
        User::factory()->for(Role::where('name', '管理者')->first())
        ->create([
            'email' => 'test1@example.com',
            'password' => Hash::make('11111111'),
        ]);

        //user
        User::factory()->for(Role::where('name', '一般ユーザー')->first())
        ->create([
            'email' => 'test2@example.com',
            'password' => Hash::make('22222222'),
        ]);

        $response = $this->postJson('/api/admin/login', $request);
        if($code === 422){
            $response->assertJsonValidationErrors([$column => $message])
            ->assertStatus($code);
        }
        elseif($code === 200){
            $this->assertAuthenticated();
        }
    }

    public function registerDataProvider(){
        return [
            'admin_pass' => [[
                'email' => 'test1@example.com',
                'password' => '11111111',
            ], 200, null, null],
            'admin_email_error' => [[
                'email' => 'test11@example.com',
                'password' => '11111111',
            ], 422, 'not_found', 'メールアドレスかパスワードが間違っています。'],
            'admin_password_error' => [[
                'email' => 'test1@example.com',
                'password' => '1111111',
            ], 422, 'not_found', 'メールアドレスかパスワードが間違っています。'],
            'admin_email_password_error' => [[
                'name' => 'test11@example.com',
                'password' => '1111111',
            ], 422, 'not_found', 'メールアドレスかパスワードが間違っています。'],
            'user_fail' => [[
                'email' => 'test2@example.com',
                'password' => '22222222',
            ], 422, 'not_found', 'メールアドレスかパスワードが間違っています。'],
            'user_email_error' => [[
                'email' => 'test22@example.com',
                'password' => '22222222',
            ], 422, 'not_found', 'メールアドレスかパスワードが間違っています。'],
            'user_password_error' => [[
                'email' => 'test2@example.com',
                'password' => '2222222',
            ], 422, 'not_found', 'メールアドレスかパスワードが間違っています。'],
            'user_email_password_error' => [[
                'name' => 'test22@example.com',
                'password' => '22222222',
            ], 422, 'not_found', 'メールアドレスかパスワードが間違っています。'],
        ];
    }

    public function testLogout(){
        $this->seed(RoleSeeder::class);
        $user = User::factory()->for(Role::where('name', '管理者')->first())->create();
        Auth::login($user);
        $response = $this->postJson('/api/admin/logout');
        $response->assertOk();

        //ログアウト確認
        $this->assertGuest();
    }
}
