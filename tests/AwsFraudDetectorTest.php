<?php

namespace Roomies\Fraudable\Tests;

use Aws\FraudDetector\FraudDetectorClient;
use Aws\Result;
use Mockery;
use Roomies\Fraudable\AwsFraudDetector;
use Roomies\Fraudable\Label;
use Roomies\Fraudable\Models\FraudEvent;
use Roomies\Fraudable\Prediction;

class AwsFraudDetectorTest extends TestCase
{
    protected $mockClient;

    protected $fraudDetector;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockClient = Mockery::mock(FraudDetectorClient::class);
        $this->fraudDetector = new AwsFraudDetector($this->mockClient);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function test_upload_event()
    {
        $event = new FraudEvent([
            'id' => 'test-id',
            'name' => 'test-event',
            'context' => ['key' => 'value'],
            'created_at' => now(),
        ]);

        $event->fraudable = new FraudableStub;

        $this->mockClient->shouldReceive('sendEvent')
            ->once()
            ->andReturn(new Result(['@metadata' => ['statusCode' => 200]]));

        $result = $this->fraudDetector->upload($event);

        $this->assertTrue($result);
    }

    public function test_predict_event()
    {
        $event = new FraudEvent([
            'id' => 'test-id',
            'name' => 'test-event',
            'context' => ['key' => 'value'],
            'created_at' => now(),
        ]);

        $event->fraudable = new FraudableStub;

        $this->mockClient->shouldReceive('getEventPrediction')
            ->once()
            ->andReturn(new Result([
                '@metadata' => ['statusCode' => 200],
                'modelScores' => [
                    ['modelId' => 'model1', 'score' => 0.5],
                ],
                'ruleResults' => [
                    ['ruleId' => 'rule1', 'outcome' => 'fraud'],
                ],
            ]));

        $prediction = $this->fraudDetector->predict($event, 'detector-id');

        $this->assertInstanceOf(Prediction::class, $prediction);
    }

    public function test_label_updates_event_label()
    {
        $event = new FraudEvent([
            'id' => 'test-id',
            'name' => 'test-event',
            'updated_at' => now(),
        ]);

        $this->mockClient->shouldReceive('updateEventLabel')
            ->once()
            ->andReturn(new Result([
                '@metadata' => ['statusCode' => 200],
            ]));

        $result = $this->fraudDetector->label($event, Label::Fraudulent);

        $this->assertTrue($result);
    }
}
