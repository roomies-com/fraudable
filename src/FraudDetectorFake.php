<?php

declare(strict_types=1);

namespace Roomies\Fraudable;

use Illuminate\Support\Testing\Fakes\Fake;
use Roomies\Fraudable\Models\FraudEvent;

class FraudDetectorFake extends FraudDetector implements Fake
{
    public function upload(FraudEvent $fraudEvent): bool
    {
        return true;
    }

    /**
     * Make a prediction for the fraud event using the given detector.
     */
    public function predict(FraudEvent $fraudEvent, string $detectorId): Prediction
    {
        return new Prediction(modelScores: [], ruleResults: []);
    }

    /**
     * Update the label of the fraud event.
     */
    public function label(FraudEvent $fraudEvent, Label $label): bool
    {
        return true;
    }
}
