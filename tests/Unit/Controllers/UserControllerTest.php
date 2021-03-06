<?php

namespace Tests\Unit\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;


class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    //指定したユーザー情報を返す
    public function testShow(){
        $email = 'test@example.com';
        $user = User::factory()->for(Role::factory())->create([
            'email' => $email
        ]);

        $response = $this->getJson('/api/users/'.$user->id);
        $response->assertOk()
        ->assertJsonStructure([
            'id', 'name', 'email', 'created_at', 'updated_at','provider_id', 'provider_name', 'nickname', 'role_id', 'status'
        ])
        ->assertJson([
            'email' => $email
        ]);
    }

    //管理者が更新可能なユーザー情報を更新
    public function testUpdate(){
        $user_role = Role::factory()->create([
            'name' => '一般ユーザー'
        ]);
        $admin_role = Role::factory()->create([
            'name' => '管理者'
        ]);

        //roleとstatusが変更可能(selectBoxから選択)
        $user = User::factory()->for($user_role)
        ->create([
            'status' => 'normal'
        ]);

        $params = [
            'role_id' => $admin_role->id,
            'status' => $user->status = 'premium',
        ];

        $response = $this->putJson('/api/users/'.$user->id, $params);

        //DBの更新確認
        $response->assertOk();
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'premium',
            'role_id' => $admin_role->id,
        ]);
    }

    /**
     * @dataProvider dataProvider
     * @param パラメータ
     * @param コード
     * @param カラム名
     * @param エラーメッセージ
     * @param パスワードの有無
     */

    //ユーザーが更新可能なユーザー情報を更新
    public function testUpdateFromUser($request, $code, $column, $message, $password = null){
        //変更値
        $new_name = str_repeat('a', 255);
        $new_email = 'test123456789@example.com';

        //uniqueバリデーション確認用ユーザー
        User::factory()->for(Role::factory())
        ->create([
            'name' => 'unique',
            'email' => 'unique@example.com'
        ]);

        $user = User::factory()->for(Role::factory()->state([
            'name' => '一般ユーザー',
        ]))
        ->create();
        
        Auth::login($user);
        $old_password = $user->password;
        $params = $request;

        $response = $this->putJson('/api/users/update_from_user/'.$user->id, $params);
        if($code === 422){
            $response->assertJsonValidationErrors([$column => $message])
            ->assertStatus($code);
        }
        elseif($code === 200){
            $response->assertStatus($code);
            $this->assertDatabaseHas('users',[
                'name' => $new_name, 
                'email' => $new_email,
            ]);
            
            //パスワード変更時
            if($password) {
                $this->assertNotEquals($old_password, User::find($user->id)->password);
            }
        }
    }

    public function dataProvider(){
        return [
            'pass' => [[
                'name' => str_repeat('a', 255),
                'email' => 'test123456789@example.com',
                'password' => 'aaaaaaaa',
                'password_confirmation' => 'aaaaaaaa',
            ], 200, null, null, "password"],

            'pass_password_null' => [[
                'name' => str_repeat('a', 255),
                'email' => 'test123456789@example.com',
            ], 200, null, null],

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

    public function testDestroy(){
        $user = User::factory()->for(Role::factory())->create();
        $response = $this->deleteJson('/api/users/'.$user->id);
        $this->assertDeleted($user);
        $response->assertOk();
    }

    /**
     * @dataProvider dataProviderSearch
     * @param 検索カラム
     * @param 検索値
     * @param ソートカラム
     * @param ソート値
     * @param ケース
     * @param 表示件数フラグ
     */

    public function testSearch($search_param, $search_value, $sort_param, $sort_value, $case, $per_page = false){
        //roleを実際の値(user:1, admin:2)にする為
        if($case === 3 || $case === 4){
            $this->artisan('migrate:fresh');
        }

        $user_role = Role::factory()->create([
            'name' => '一般ユーザー'
        ]);
        $admin_role = Role::factory()->create([
            'name' => '管理者'
        ]);

        User::factory()->for($user_role)
        ->create([
            'name' => 'test1',
            'email' => 'test1@example.com',
            'created_at' => Carbon::now(),
            'status' => 'normal'
        ]);
        
        User::factory()->for($admin_role)
        ->create([
            'name' => 'test2',
            'email' => 'test2@example.com',
            'created_at' => Carbon::now()->subDay(1),
            'status' => 'premium'
        ]);
        
        $search = [
            'name' => null,
            'email' => null,
            'created_at_start' => null,
            'created_at_end' => null,
            'status' => '',
            'next_update_start' => null,
            'next_update_end' => null,
            'role' => '',
        ];

        $sort = [
            'id' => null,
            'created_at' => null,
            'status' => null,
            'role' => null,
            'per_page' => 10
        ];

        if($search_param){
            $search[$search_param] = $search_value;
        }
        if($sort_param){
            $sort[$sort_param] = $sort_value;
        }

        $current_page = 1;
        //ユーザー表示件数を変更しない場合
        if(!$per_page){
            $response = $this->postJson('/api/users/search?page='.$current_page, [
                'search' => $search,
                'sort' => $sort,
            ]);
            $response->assertOK();
        }
        else{
            //ユーザー表示数を確認する為、ユーザーを100人追加
            $count = 100;
            User::factory($count)->for($user_role)->create();
            $response = $this->postJson('/api/users/search?page='.$current_page, [
                'search' => $search,
                'sort' => $sort,
            ]);
            $response->assertJson(['last_page' => ceil(($count + 2) / $sort_value)])
            ->assertOK();
        }

        switch($case){
            case 0:
                $response->assertJsonCount(0, 'data');
                break;
            case 1:
                $response->assertJsonCount(1, 'data');
                break;
            case 2:
                $response->assertJsonCount(2, 'data');
                break;
            case 3:
                $response->assertJsonCount(1, 'data')
                ->assertJsonFragment(['role_id' => $user_role->id])
                ->assertJsonMissing(['role_id' => $admin_role->id]);
                break;
            case 4:
                $response->assertJsonCount(1, 'data')
                ->assertJsonFragment(['role_id' => $admin_role->id])
                ->assertJsonMissing(['role_id' => $user_role->id]);
                $this->artisan('migrate:fresh'); //RefreshDatabaseトレイトが作動せず、userが重複する為
                break;
            case 5:
                $response->assertJsonCount(1, 'data')
                ->assertJsonFragment(['status' => 'normal'])
                ->assertJsonMissing(['status' => 'premium']);
                break;
            case 6:
                $response->assertJsonCount(1, 'data')
                ->assertJsonFragment(['status' => 'premium'])
                ->assertJsonMissing(['status' => 'normal']);
                break;
            case 7:
                $this->assertGreaterThan($response['data'][0]['id'], $response['data'][1]['id']);
                break;
            case 8:
                $this->assertGreaterThan($response['data'][1]['id'], $response['data'][0]['id']);
                break;
        }
    }

    public function dataProviderSearch(){
        return [
            'search_non_expect_2' => [null, null, null, null, 2], 
            'search_name_expect_2' => ['name','test', null, null, 2],
            'search_name_expect_1' => ['name','test1', null, null, 1],
            'search_name_expect_0' => ['name','test12', null, null, 0],
            'search_email_expect_2' => ['email','test', null, null, 2],
            'search_email_expect_1' => ['email','test1', null, null, 1],
            'search_email_expect_0' => ['email','test12', null, null, 0],
            'search_created_at_start_expect_2' => ['created_at_start',Carbon::now()->subDay(1), null, null, 2],
            'search_created_at_start_expect_1' => ['created_at_start',Carbon::now(), null, null, 1],
            'search_created_at_start_expect_0' => ['created_at_start',Carbon::now()->addDay(1), null, null, 0], 
            'search_created_at_end_expect_2' => ['created_at_end',Carbon::now(), null, null, 2],
            'search_created_at_end_expect_1' => ['created_at_end',Carbon::now()->subDay(1), null, null, 1],
            'search_created_at_end_expect_0' => ['created_at_end',Carbon::now()->subDay(2), null, null, 0], 
            'search_role_expect_user' => ['role', 1, null, null, 3], 
            'search_role_expect_admin' => ['role', 2, null, null, 4], 
            'search_status_expect_normal' => ['status', 'normal', null, null, 5], 
            'search_status_expect_premium' => ['status', 'premium', null, null, 6], 
            'sort_id_expect_asc' => [null, null, 'id', 'asc', 7], 
            'sort_id_expect_desc' => [null, null, 'id', 'desc', 8], 
            'sort_created_at_expect_asc' => [null, null, 'created_at', 'asc', 8], 
            'sort_created_at_expect_desc' => [null, null, 'created_at', 'desc', 7], 
            'sort_status_expect_asc' => [null, null, 'status', 'asc', 7], 
            'sort_status_expect_desc' => [null, null, 'status', 'desc', 8], 
            'sort_role_expect_asc' => [null, null, 'role', 'asc', 7], 
            'sort_role_expect_desc' => [null, null, 'role', 'desc', 8], 
            'sort_per_page_expect_11' => [null, null, 'per_page', 10, 9, true], 
            'sort_per_page_expect_6' => [null, null, 'per_page', 20, 9, true], 
            'sort_per_page_expect_3' => [null, null, 'per_page', 50, 9, true], 
            'sort_per_page_expect_2' => [null, null, 'per_page', 100, 9, true], 
        ];
    }

    public function testExist(){
        //ダミー
        User::factory()->count(10)->for(Role::factory())->create();

        //正常チェック
        $user = User::factory()->for(Role::factory())->create();
        $response = $this->postJson('/api/users/exist', [
            'id' => $user->id,
        ]);
        $response->assertOk()
        ->assertJsonStructure([
            'id', 'name', 'email', 'created_at', 'updated_at','provider_id', 'provider_name', 'nickname', 'role_id', 'status'
        ])
        ->assertJson([
            'id' => $user->id,
        ]);

        //異常チェック
        $response = $this->postJson('/api/users/exist', [
            'id' => 10000,
        ]);
        $response->assertNotFound();
    }
}
