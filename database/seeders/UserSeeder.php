<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //テストユーザー
        DB::table('users')->insert([
            'name' => 'test1111',
            'email' => 'test1@test.com',
            'password' => Hash::make(11111111),
            'created_at' => now(),
            'updated_at' => now(),
            'role_id' => 2,
            'status' => 'normal'
        ]);

        DB::table('users')->insert([
            'name' => 'test2222',
            'email' => 'test2@test.com',
            'password' => Hash::make(22222222),
            'created_at' => now(),
            'updated_at' => now(),
            'role_id' => 1,
            'status' => 'normal'
        ]);

        //ランダム生成
        User::factory()->times(100)->create();
    }
}
