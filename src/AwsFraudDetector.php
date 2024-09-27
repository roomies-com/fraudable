<?php

declare(strict_types=1);

namespace Roomies\Fraudable;

use Aws\FraudDetector\FraudDetectorClient;
use Illuminate\Support\Arr;
use Roomies\Fraudable\Contracts\FraudDetectionStore;
use Roomies\Fraudable\Models\FraudEvent;

class AwsFraudDetector implements FraudDetectionStore
{
    /**
     * Create a new fraud detector instance.
     */
    public function __construct(protected FraudDetectorClient $client)
    {
        //
    }

    /**
     * Upload the fraud event to the fraud detector.
     */
    public function upload(FraudEvent $fraudEvent): bool
    {
        $payload = $this->buildEventPayload($fraudEvent);

        $result = $this->client->sendEvent($payload);

        return Arr::get($result->toArray(), '@metadata.statusCode') === 200;
    }

    /**
     * Make a prediction for the fraud event using the given detector.
     */
    public function predict(FraudEvent $fraudEvent, string $detectorId): ?Prediction
    {
        $payload = array_merge($this->buildEventPayload($fraudEvent), [
            'detectorId' => $detectorId,
        ]);

        $result = $this->client->getEventPrediction($payload);

        if (Arr::get($result->toArray(), '@metadata.statusCode') !== 200) {
            return null;
        }

        return new Prediction(
            modelScores: $result->get('modelScores'),
            ruleResults: $result->get('ruleResults')
        );
    }

    /**
     * Update the label of the fraud event.
     */
    public function label(FraudEvent $fraudEvent, Label $label): bool
    {
        $result = $this->client->updateEventLabel([
            'eventId' => $fraudEvent->id,
            'eventTypeName' => $fraudEvent->name,
            'labelTimestamp' => now()->toISOString(),
            'assignedLabel' => $label->toString(),
        ]);

        return Arr::get($result->toArray(), '@metadata.statusCode') === 200;
    }

    /**
     * Create the required event representation from a fraud event record.
     */
    protected function buildEventPayload(FraudEvent $fraudEvent): array
    {
        return [
            'eventId' => $fraudEvent->id,
            'eventTypeName' => $fraudEvent->name,
            'eventTimestamp' => $fraudEvent->created_at->toISOString(),
            'entities' => [
                [
                    'entityType' => $fraudEvent->fraudable->entityType(),
                    'entityId' => $fraudEvent->fraudable->entityId(),
                ],
            ],
            'eventVariables' => $fraudEvent->variables,
        ];
    }
}
