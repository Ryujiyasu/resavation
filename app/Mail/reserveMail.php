<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class reserveMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct($data)
     {
         //
         $this->data=$data;
     }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->view('mails.noticeMail')
          ->text('mails.noticeMail')
          ->subject('ご予約承りました。')
          ->with([
              'text' => $this->data,
              'name' => $this->data->name,
              'date' => $this->data->schedule_date,
              ]);
    }
}
