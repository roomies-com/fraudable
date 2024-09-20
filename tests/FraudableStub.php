<?php

namespace Roomies\Fraudable\Tests;

use Illuminate\Database\Eloquent\Model;
use Roomies\Fraudable\Concerns\Fraudable;

class FraudableStub extends Model
{
    use Fraudable;

    public function entityType(): string
    {
        return 'fraudable';
    }
}
