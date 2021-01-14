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
            'role_id' => 2,
            'status' => 'normal'
        ]);

        DB::table('users')->insert([
            'name' => 'test3333',
            'email' => 'test3@test.com',
            'password' => Hash::make(33333333),
            'created_at' => now(),
            'updated_at' => now(),
            'role_id' => 2,
            'status' => 'normal'
        ]);

        DB::table('users')->insert([
            'name' => 'test4444',
            'email' => 'test4@test.com',
            'password' => Hash::make(44444444),
            'created_at' => now(),
            'updated_at' => now(),
            'role_id' => 2,
            'status' => 'normal'
        ]);

        DB::table('users')->insert([
            'name' => 'test5555',
            'email' => 'test5@test.com',
            'password' => Hash::make(55555555),
            'created_at' => now(),
            'updated_at' => now(),
            'role_id' => 1,
            'status' => 'normal'
        ]);

        DB::table('users')->insert([
            'name' => 'test6666',
            'email' => 'test6@test.com',
            'password' => Hash::make(66666666),
            'created_at' => now(),
            'updated_at' => now(),
            'role_id' => 1,
            'status' => 'normal'
        ]);

        DB::table('users')->insert([
            'name' => 'test7777',
            'email' => 'test7@test.com',
            'password' => Hash::make(77777777),
            'created_at' => now(),
            'updated_at' => now(),
            'role_id' => 1,
            'status' => 'normal'
        ]);

        DB::table('users')->insert([
            'name' => 'test8888',
            'email' => 'test8@test.com',
            'password' => Hash::make(88888888),
            'created_at' => now(),
            'updated_at' => now(),
            'role_id' => 1,
            'status' => 'normal'
        ]);

        //ランダム生成
        User::factory()->times(100)->create();
    }
}
