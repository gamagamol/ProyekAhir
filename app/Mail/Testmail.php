<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Http\Controllers\BillPaymentController;
use Barryvdh\DomPDF\Facade as PDF;

class Testmail extends Mailable
{
    use Queueable, SerializesModels;

   public $detail;
   public $no_tagihan;
    public function __construct($detail,$no_tagihan)
    {
        set_time_limit(300);
        $this->detail=$detail;
        $this->no_tagihan= $no_tagihan;
        $this->bc=new BillPaymentController;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // CETAK PDF
        $data = $this->bc->bill_email($this->no_tagihan);
        $pdf = PDF::loadview('bill/print', $data);
        $test=$pdf->download('test');
        
        return $this->subject('PT.Ibaraki Kogyo Hanan Indonesia')
        ->view('mail.mail',$this->detail)
        ->attachData($test,'test.pdf', ['mime' => 'application/pdf']);
    }
}
