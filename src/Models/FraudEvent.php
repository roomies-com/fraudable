<?php

declare(strict_types=1);

namespace Roomies\Fraudable\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Roomies\Fraudable\Fraud;
use Roomies\Fraudable\Label;
use Roomies\Fraudable\Prediction;

class FraudEvent extends Model
{
    use HasUuids;

    /**
     * Indicates if all mass assignment is enabled.
     *
     * @var bool
     */
    protected static $unguarded = true;

    public function casts(): array
    {
        return [
            'variables' => 'array',
        ];
    }

    /**
     * Label the event is legitimate.
     */
    public function legitimate(): void
    {
        $this->relabel(Label::Legitimate);
    }

    /**
     * Upload the fraud event to the fraud detector.
     */
    public function upload(): void
    {
        Fraud::upload($this);
    }

    /**
     * Predict the outcome of the event using the given detector.
     */
    public function predict(string $detectorId): Prediction
    {
        return Fraud::predict($this, $detectorId);
    }

    /**
     * Label the event as fraudulent.
     */
    public function fraudulent(): void
    {
        $this->relabel(Label::Fraudulent);
    }

    /**
     * Relabel the event with the given label.
     */
    public function relabel(Label $label): bool
    {
        Fraud::label($this, $label);

        return $this->forceFill([
            'label' => $label->toString(),
        ])->save();
    }

    /**
     * Get the associated model.
     */
    public function fraudable(): MorphTo
    {
        return $this->morphTo();
    }
}
