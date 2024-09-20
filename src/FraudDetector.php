<?php

declare(strict_types=1);

namespace Roomies\Fraudable;

use Illuminate\Http\Request;
use Roomies\Fraudable\Contracts\FraudableEvent;
use Roomies\Fraudable\Events\Event;
use Roomies\Fraudable\Models\FraudEvent;

class FraudDetector
{
    /**
     * Create a new fraud detector instance.
     */
    public function __construct(
        protected AwsFraudDetector $client,
        protected Request $request
    ) {
        //
    }

    /**
     * Record the given event for training or prediction.
     */
    public function ingest(mixed $fraudable, FraudableEvent $event): FraudEvent
    {
        $pendingEvent = $event->toFraudEvent($this->request);

        return $fraudable->fraudEvents()->create([
            'name' => $pendingEvent->name,
            'variables' => $pendingEvent->variables,
        ]);

    }

    /**
     * Upload the given fraud event to the fraud detector.
     */
    public function upload(FraudEvent $fraudEvent): bool
    {
        return $this->client->upload($fraudEvent);

    }

    /**
     * Predict the outcome of the given event.
     */
    public function predict(FraudEvent $fraudEvent, string $detectorId): Prediction
    {
        return $this->client->predict($fraudEvent, $detectorId);
    }

    /**
     * Mark the given event as fraudulent.
     */
    public function label(FraudEvent $fraudEvent, Label $label): bool
    {
        $this->client->update($fraudEvent, $label);

        return $event->forceFill([
            'label' => $label->toString(),
        ])->save();
    }
}
