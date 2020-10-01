<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'パスワードの再設定';

        $baseUrl = config('app.url');
        $token = $this->token;
        $url = "{$baseUrl}/reset-password/{$token}";
        $from = config('mail.from.address');
        
        return $this->from($from)->subject($subject)->view('reset_password_mail')->with('url', $url);
    }
}
