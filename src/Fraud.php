<?php

declare(strict_types=1);

namespace Roomies\Fraudable;

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Testing\Fakes\Fake;

class Fraud extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return FraudDetector::class;
    }

    /**
     * Replace the bound instance with a fake.
     *
     * @return \Roomies\Fraudable\FraudDetectorFake
     */
    public static function fake()
    {
        return tap(new FraudDetectorFake, function ($fake) {
            static::swap($fake);
        });
    }
}
