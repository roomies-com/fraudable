<?php

declare(strict_types=1);

namespace Roomies\Fraudable\Concerns;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;
use Roomies\Fraudable\FraudDetector;
use Roomies\Fraudable\Label;
use Roomies\Fraudable\Models\FraudEvent;

trait Fraudable
{
    /**
     * Return the Fraud Detector entity ID.
     */
    public function entityId(): string
    {
        return (string) $this->getKey() ?: 'UNKNOWN';
    }

    /**
     * Return the Fraud Detector entity name.
     */
    public function entityType(): string
    {
        return Str::of($this::class)->replace('\\', '')->snake()->toString();
    }

    public function ingest(mixed $event): FraudEvent
    {
        return app(FraudDetector::class)->ingest($this, $event);
    }

    public function relabel(Label $label): void
    {
        $this->fraudEvents()
            ->where('label', '!==', $label->toString())
            ->each(fn (FraudEvent $fraudEvent) => $fraudEvent->relabel($label));
    }

    /**
     * Return the fraud events relationship.
     */
    public function fraudEvents(): MorphMany
    {
        return $this->morphMany(FraudEvent::class, 'fraudable');
    }
}
