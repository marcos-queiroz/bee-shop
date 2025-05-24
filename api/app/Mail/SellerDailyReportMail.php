<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class SellerDailyReportMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public $sales,
        public float $total,
        public float $commission,
        public $date
    ) {
        $this->date = Carbon::parse($date)->format('d/m/Y');
    }

    public function build(): self
    {
        return $this->subject("Relatório de vendas - {$this->date}")
            ->markdown('emails.seller.daily');
    }
}
