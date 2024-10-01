<?php

namespace Roomies\Fraudable\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Roomies\Fraudable\Fraud;
use Roomies\Fraudable\FraudEvent;

class UploadJob extends Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /*
     * Create a new job instance.
     */
    public function __construct(public FraudEvent $fraudEvent)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Fraud::upload($this->fraudEvent);
    }
}
