<?php

namespace App\Jobs;

use App\Mail\ImportCompletedMail;
use App\Models\Import;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendImportCompletedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Import $import) {}

    public function handle(): void
    {
        $merchant = $this->import->merchant;

        if (!$merchant || !$merchant->email) {
            return;
        }

        Mail::to($merchant->email)
            ->send(new ImportCompletedMail($this->import));
    }
}
