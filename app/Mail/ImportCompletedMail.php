<?php

namespace App\Mail;

use App\Models\Import;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ImportCompletedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Import $import) {}

    public function build()
    {
        return $this
            ->subject('Product Import Completed')
            ->view('emails.import-completed');
    }
}
