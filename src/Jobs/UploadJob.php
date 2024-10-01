<?php

namespace Roomies\Fraudable\Jobs;

use Roomies\Fraudable\Fraud;
use Roomies\Fraudable\Models\FraudEvent;

class UploadJob extends Job
{
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
