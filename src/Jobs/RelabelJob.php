<?php

namespace Roomies\Fraudable\Jobs;

use Roomies\Fraudable\Enums\Label;
use Roomies\Fraudable\Fraud;
use Roomies\Fraudable\Models\FraudEvent;

class RelabelJob extends Job
{
    /*
     * Create a new job instance.
     */
    public function __construct(public FraudEvent $fraudEvent, public Label $label)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->fraudEvent->forceFill([
            'label' => $this->label->toString(),
        ])->save();

        Fraud::label($this->fraudEvent, $this->label);
    }
}
