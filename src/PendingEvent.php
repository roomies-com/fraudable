<?php

declare(strict_types=1);

namespace Roomies\Fraudable;

class PendingEvent
{
    /**
     * Create a new event instance.
     */
    public function __construct(
        public string $name,
        public array $variables
    ) {
        //
    }
}
