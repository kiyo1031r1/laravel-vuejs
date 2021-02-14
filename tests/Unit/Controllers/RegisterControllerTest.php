<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterControllerTest extends TestCase
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
    public function testRegister($request, $code, $column, $message, $password = null){
        //uniqueバリデーション確認用ユーザー
        User::factory()->for(Role::factory())
        ->create([
            'name' => 'unique',
            'email' => 'unique@example.com'
        ]);

        $response = $this->postJson('/api/register', $request);
        if($code === 422){
            $response->assertJsonValidationErrors([$column => $message])
            ->assertStatus($code);
        }
        elseif($code === 200){
            $response->assertStatus($code);
            //DB確認
            $this->assertDatabaseHas('users',[
                'name' => $request['name'], 
                'email' => $request['email'],
                'role_id' => Role::where('name', '一般ユーザー')->first()->id,
                'status' => 'normal'
            ]);
            $user = User::where('email', $request['email'])->first();
            $this->assertTrue(password_verify($request['password'], $user->password));

            //ログイン確認
            $this->assertEquals(Auth::id(), $user->id);
        }
    }

    public function registerDataProvider(){
        return [
            'pass' => [[
                'name' => str_repeat('a', 255),
                'email' => 'test123456789@example.com',
                'password' => 'aaaaaaaa',
                'password_confirmation' => 'aaaaaaaa',
            ], 200, null, null, "password"],
            'name_required' => [['name' => ' '], 422, 'name', '名前は必ず入力してください。'],
            'name_string' => [['name' => 11111111], 422, 'name', '名前には文字列を指定してください。'],
            'name_max' => [['name' => str_repeat('a', 256)], 422, 'name', '名前には255文字以下の文字列を指定してください。'],
            'name_unique' => [['name' => 'unique'], 422, 'name', 'その名前はすでに使われています。'],
            'email_required' => [['email' => ' '], 422, 'email', 'メールアドレスは必ず入力してください。'],
            'email_string' => [['email' => 11111111], 422, 'email', 'メールアドレスには文字列を指定してください。'],
            'email_email' => [['email' => '11111111'], 422, 'email', 'メールアドレスには正しい形式のメールアドレスを指定してください。'],
            'email_max' => [['email' => str_repeat('a', 245).'example.com'], 422, 'email', 'メールアドレスには255文字以下の文字列を指定してください。'],
            'email_unique' => [['email' => 'unique@example.com'], 422, 'email', 'そのメールアドレスはすでに使われています。'],
            'password_required' => [['password' => ' '], 422, 'password', 'パスワードは必ず入力してください。'],
            'password_string' => [['password' => 11111111], 422, 'password', 'パスワードには文字列を指定してください。'],
            'password_min' => [['password' => str_repeat('a', 7)], 422, 'password', 'パスワードには8文字以上の文字列を指定してください。'],
            'password_confirmation' => [['password' => str_repeat('a', 8), 'password_confirmation' => str_repeat('b', 8)], 422, 'password', 'パスワードが確認用の値と一致しません。'],
        ];
    }
}
