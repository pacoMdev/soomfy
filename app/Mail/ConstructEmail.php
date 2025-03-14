<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConstructEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->from($this->data['from_email'], $this->data['from_name'])
                ->view($this->data['view'])
                ->subject($this->data['subject'])
                ->with(
                    [
                        'from_name' => $this->data['from_name'],
                        'from_email' => $this->data['from_email'],
                        'to_name' => $this->data['to_name'],
                        'to_email' => $this->data['to_email'],
                        'subject' => $this->data['subject'],
                        'color_primary' => $this->data['color_primary'] ?? null,
                        'color_secondary' => $this->data['color_secondary'] ?? null,
                        'logo' => $this->data['logo'] ?? null,
                        'finalPrice' => $this->data['finalPrice'] ?? null,
                        'userSeller' => $this->data['userSeller'] ?? null,
                        'userBuyer' => $this->data['userBuyer'] ?? null,
                        'product' => $this->data['product'] ?? null,
                        'saleDate' => $this->data['saleDate'] ?? null,
                        'url' => $this->data['productUrl'] ?? null,
                    ]
                );
        return $email;
    }
}
