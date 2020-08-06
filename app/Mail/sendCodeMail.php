<?php

namespace App\Mail;

use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class sendCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $code, $email;

    public function __construct($code, $email)
    {
        $this->code = $code;
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('admin@tourrobot.ru')
            ->markdown('emails.sendCodeMail')
            ->with([
                'code' => $this->code,
                'url'=>route('checkCode').'?'.http_build_query(['email'=>$this->email,'code'=>$this->code])
            ]);

    }
}
