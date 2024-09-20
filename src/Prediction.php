<?php

declare(strict_types=1);

namespace Roomies\Fraudable;

class Prediction
{
    public function __construct(
        public array $modelScores,
        public array $ruleResults
    ) {
        //
    }
}
