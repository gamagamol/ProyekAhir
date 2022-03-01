<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Testmail extends Mailable
{
    use Queueable, SerializesModels;

   public $detail;
    public function __construct($detail)
    {
        $this->detail=$detail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('PT.Ibaraki Kogyo Hanan Indonesia')->view('mail.mail',$this->detail);
    }
}
