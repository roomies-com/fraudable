<?php

declare(strict_types=1);

namespace Roomies\Fraudable;

enum Label
{
    case Legitimate;
    case Fraudulent;

    /**
     * Get the string representation from the config.
     */
    public function toString(): string
    {
        return match ($this) {
            self::Legitimate => config('fraudable.legitimate_label'),
            self::Fraudulent => config('fraudable.fraudulent_label'),
        };
    }
}
