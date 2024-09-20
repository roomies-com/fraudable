<?php

namespace Roomies\Fraudable\Contracts;

use Roomies\Fraudable\Label;
use Roomies\Fraudable\Models\FraudEvent;
use Roomies\Fraudable\Prediction;

interface FraudDetectionStore
{
    /**
     * Upload the fraud event to the fraud detector.
     */
    public function upload(FraudEvent $event): bool;

    /**
     * Make a prediction for the fraud event using the given detector.
     */
    public function predict(FraudEvent $event, string $detectorId): ?Prediction;

    /**
     * Update the label of the fraud event.
     */
    public function update(FraudEvent $event, Label $label): bool;
}
