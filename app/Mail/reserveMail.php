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
          ->subject('【●●接骨院】ご予約承りました')
          ->with([
              'text' => $this->data,
              'name' => $this->data->name,
              'tel' => $this->data->tel,
              'date' => $this->data->schedule_date,
              'time' => $this ->data->Time()->first()->name,
              'cource' => $this->data->Cource()->first()->name,
              'staff' => $this->data->Staff()->first()->name,
              ]);
    }
}
