<?php

namespace Roomies\Fraudable;

use Aws\FraudDetector\FraudDetectorClient;
use Illuminate\Support\Manager as BaseManager;

class Manager extends BaseManager
{
    /**
     * Get the default driver name.
     */
    public function getDefaultDriver(): string
    {
        if (is_null($driver = $this->config['fraudable.default'])) {
            return 'null';
        }

        return $driver;
    }

    /**
     * Create an instance of the AWS driver.
     */
    public function createAwsDriver(): AwsFraudDetector
    {
        return new AwsFraudDetector(
            $this->getContainer()->make(FraudDetectorClient::class)
        );
    }

    /**
     * Create an instance of the Null driver.
     */
    public function createNullDriver(): NullFraudDetector
    {
        return new NullFraudDetector;
    }
}
