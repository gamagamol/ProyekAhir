<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\BillPaymentController;


class Testmail extends Mailable
{
    use Queueable, SerializesModels;

   public $detail;
   public $file_tagihan;
    public function __construct($detail,$file_tagihan)
    {
        set_time_limit(300);
        $this->detail=$detail;
        $this->file_tagihan= $file_tagihan;
        $this->bc=new BillPaymentController;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        

        return $this->subject('PT.Ibaraki Kogyo Hanan Indonesia')
        ->view('mail.mail',$this->detail);
    }
}
