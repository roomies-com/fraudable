<?php

namespace Roomies\Fraudable;

use Aws\Exception\AwsException;
use Illuminate\Support\Arr;

class FraudList
{
    public function __construct(protected AwsFraudDetector $client)
    {
        //
    }

    /**
     * Add the given item(s) to the list.
     */
    public function add(string $name, $elements = [])
    {
        $elementsToAdd = Arr::wrap($elements);

        try {
            return $this->client->updateList([
                'name' => $name,
                'elementsToAdd' => $elementsToAdd,
            ]);
        } catch (AwsException $e) {
            //
        }
    }

    /**
     * Get the items on the list.
     */
    public function get(string $name): array
    {
        try {
            $result = $this->client->getListElements([
                'name' => $name,
            ]);

            return Arr::get($result, 'elements');
        } catch (AwsException $e) {
            //
        }
    }

    /**
     * Remove the given item(s) from the list.
     */
    public function remove(string $name, $elements = [])
    {
        $elementsToDelete = Arr::wrap($elements);

        try {
            return $this->client->updateList([
                'name' => $name,
                'elementsToDelete' => $elementsToDelete,
            ]);
        } catch (AwsException $e) {
            //
        }
    }
}
