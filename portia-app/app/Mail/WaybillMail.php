<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WaybillMail extends Mailable
{
    use Queueable, SerializesModels;

    public $query;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($query)
    {
            $this->query = $query;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.waybill-created');
    }
}
