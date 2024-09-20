<?php

namespace Roomies\Fraudable\Tests;

use Roomies\Fraudable\Fraud;
use Roomies\Fraudable\FraudDetectorFake;

class FraudTest extends TestCase
{
    public function test_it_returns_instance_of_fake()
    {
        $result = Fraud::fake();

        $this->assertInstanceOf(FraudDetectorFake::class, $result);
    }
}
