<?php

namespace Tests\Models\Unit;

use App\Models\PasswordReset;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;


    //DBにデータ保存した際に、自動でcreated_atだけを追加して保存できること(updated_atは保存しない)
    public function testBoot(){
        $password_reset = new PasswordReset([
            'email' => 'test@test.com',
            'token' => 'testToken'
        ]);
        $password_reset->save();
        $data = PasswordReset::where('email', $password_reset->email)->first();

        $expected = [
            'email', 'token', 'created_at'
        ];
        
        $this->assertDatabaseHas('password_resets',[
            'email' => $password_reset->email
        ]);
        $this->assertSame($expected, array_keys($data->toArray()));
    }
}
