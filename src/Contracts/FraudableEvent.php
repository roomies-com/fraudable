<?php

declare(strict_types=1);

namespace Roomies\Fraudable\Contracts;

use Illuminate\Http\Request;
use Roomies\Fraudable\PendingEvent;

interface FraudableEvent
{
    public function toFraudEvent(Request $request): PendingEvent;
}
